<?php

namespace App\Services;

use App\Models\User;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\PointVoucher;
use App\Models\UserPointVoucher;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PointService
{
    /**
     * Tích điểm cho user khi mua hàng
     */
    public function earnPointsFromOrder(User $user, Order $order): bool
    {
        try {
            DB::beginTransaction();

            // Lấy hoặc tạo point record cho user
            $point = $user->point ?? Point::create([
                'user_id' => $user->id,
                'total_points' => 0,
                'earned_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
            ]);

            // Tính điểm dựa trên giá trị đơn hàng và level VIP
            $pointsToEarn = $this->calculatePointsFromOrder($order, $user);

            if ($pointsToEarn <= 0) {
                DB::rollBack();
                return false;
            }

            // Cập nhật điểm
            $point->update([
                'total_points' => $point->total_points + $pointsToEarn,
                'earned_points' => $point->earned_points + $pointsToEarn,
            ]);

            // Tạo giao dịch tích điểm
            $expiryDate = Carbon::now()->addMonths(12); // Điểm hết hạn sau 12 tháng

            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'earn',
                'points' => $pointsToEarn,
                'description' => "Tích điểm từ đơn hàng #{$order->code_order}",
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'expiry_date' => $expiryDate,
                'is_expired' => false,
            ]);

            // Cập nhật đơn hàng
            $order->update(['points_earned' => $pointsToEarn]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error earning points: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tính điểm dựa trên đơn hàng và level VIP
     */
    private function calculatePointsFromOrder(Order $order, User $user): int
    {
        $orderAmount = $order->total_amount;
        $pointRate = $user->getVipLevel() === 'Bronze' ? 0.05 : $user->point->point_rate;

        return (int) ($orderAmount * $pointRate);
    }

    /**
     * Đổi điểm lấy voucher
     */
    public function exchangePointsForVoucher(User $user, int $voucherId): array
    {
        try {
            DB::beginTransaction();

            $voucher = PointVoucher::findOrFail($voucherId);

            // Kiểm tra voucher có hoạt động không
            if (!$voucher->isActive()) {
                return [
                    'success' => false,
                    'message' => 'Voucher không còn hiệu lực'
                ];
            }

            // Kiểm tra user có đủ điểm không
            if (!$user->hasEnoughPoints($voucher->points_required)) {
                return [
                    'success' => false,
                    'message' => 'Bạn không đủ điểm để đổi voucher này'
                ];
            }

            $point = $user->point;
            if (!$point) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin điểm của bạn'
                ];
            }

            // Trừ điểm
            $point->update([
                'total_points' => $point->total_points - $voucher->points_required,
                'used_points' => $point->used_points + $voucher->points_required,
            ]);

            // Tạo giao dịch sử dụng điểm
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'use',
                'points' => -$voucher->points_required,
                'description' => "Đổi điểm lấy voucher: {$voucher->name}",
                'reference_type' => 'voucher',
                'reference_id' => $voucher->id,
            ]);

            // Tạo voucher cho user
            $userVoucher = UserPointVoucher::create([
                'user_id' => $user->id,
                'point_voucher_id' => $voucher->id,
                'voucher_code' => $this->generateVoucherCode(),
                'status' => 'active',
                'expiry_date' => $voucher->end_date ?? Carbon::now()->addMonths(3),
            ]);

            // Tăng số lần sử dụng voucher
            $voucher->incrementUsage();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Đổi voucher thành công',
                'voucher_code' => $userVoucher->voucher_code,
                'voucher' => $userVoucher
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error exchanging points for voucher: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đổi voucher'
            ];
        }
    }

    /**
     * Sử dụng voucher trong đơn hàng
     */
    public function useVoucherInOrder(string $voucherCode, Order $order): array
    {
        try {
            $userVoucher = UserPointVoucher::where('voucher_code', $voucherCode)
                ->where('user_id', $order->user_id)
                ->first();

            if (!$userVoucher) {
                return [
                    'success' => false,
                    'message' => 'Voucher không tồn tại'
                ];
            }

            if (!$userVoucher->isActive()) {
                return [
                    'success' => false,
                    'message' => 'Voucher đã hết hạn hoặc đã được sử dụng'
                ];
            }

            $voucher = $userVoucher->pointVoucher;

            if (!$voucher->canBeUsedForOrder($order->total_amount)) {
                return [
                    'success' => false,
                    'message' => 'Đơn hàng không đủ điều kiện sử dụng voucher'
                ];
            }

            // Tính toán giảm giá
            $discount = $voucher->calculateDiscount($order->total_amount);
            $finalAmount = $order->total_amount - $discount;

            // Cập nhật đơn hàng
            $order->update([
                'total_amount' => $finalAmount,
                'point_voucher_code' => $voucherCode,
            ]);

            // Đánh dấu voucher đã sử dụng
            $userVoucher->markAsUsed($order->id);

            return [
                'success' => true,
                'message' => 'Áp dụng voucher thành công',
                'discount' => $discount,
                'final_amount' => $finalAmount
            ];

        } catch (\Exception $e) {
            Log::error('Error using voucher: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng voucher'
            ];
        }
    }

    /**
     * Tạo điểm thưởng cho user
     */
    public function giveBonusPoints(User $user, int $points, string $description): bool
    {
        try {
            DB::beginTransaction();

            $point = $user->point ?? Point::create([
                'user_id' => $user->id,
                'total_points' => 0,
                'earned_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
            ]);

            // Cập nhật điểm
            $point->update([
                'total_points' => $point->total_points + $points,
                'earned_points' => $point->earned_points + $points,
            ]);

            // Tạo giao dịch
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'bonus',
                'points' => $points,
                'description' => $description,
                'expiry_date' => Carbon::now()->addMonths(12),
                'is_expired' => false,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error giving bonus points: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xử lý điểm hết hạn
     */
    public function processExpiredPoints(): void
    {
        try {
            $expiredTransactions = PointTransaction::where('expiry_date', '<=', Carbon::now())
                ->where('is_expired', false)
                ->where('type', 'earn')
                ->get();

            foreach ($expiredTransactions as $transaction) {
                DB::beginTransaction();

                // Đánh dấu giao dịch đã hết hạn
                $transaction->update(['is_expired' => true]);

                // Cập nhật điểm của user
                $point = $transaction->user->point;
                if ($point) {
                    $point->update([
                        'total_points' => max(0, $point->total_points - $transaction->points),
                        'expired_points' => $point->expired_points + $transaction->points,
                    ]);
                }

                DB::commit();
            }
        } catch (\Exception $e) {
            Log::error('Error processing expired points: ' . $e->getMessage());
        }
    }

    /**
     * Tạo mã voucher ngẫu nhiên
     */
    private function generateVoucherCode(): string
    {
        do {
            $code = 'POINT' . strtoupper(Str::random(8));
        } while (UserPointVoucher::where('voucher_code', $code)->exists());

        return $code;
    }

    /**
     * Lấy thống kê điểm của user
     */
    public function getUserPointStats(User $user): array
    {
        $point = $user->point;

        if (!$point) {
            return [
                'total_points' => 0,
                'earned_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
                'vip_level' => 'Bronze',
                'point_rate' => 0.05,
            ];
        }

        return [
            'total_points' => $point->total_points,
            'earned_points' => $point->earned_points,
            'used_points' => $point->used_points,
            'expired_points' => $point->expired_points,
            'vip_level' => $point->vip_level,
            'point_rate' => $point->point_rate,
        ];
    }

    /**
     * Lấy lịch sử giao dịch điểm
     */
    public function getUserPointHistory(User $user, int $limit = 20): array
    {
        $transactions = $user->pointTransactions()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'type' => $transaction->type,
                'points' => $transaction->points,
                'description' => $transaction->description,
                'created_at' => $transaction->created_at->format('d/m/Y H:i'),
                'expiry_date' => $transaction->expiry_date?->format('d/m/Y'),
                'is_expired' => $transaction->is_expired,
                'reference' => $this->getReferenceInfo($transaction),
            ];
        })->toArray();
    }

    /**
     * Lấy thông tin đối tượng tham chiếu
     */
    private function getReferenceInfo(PointTransaction $transaction): ?array
    {
        if ($transaction->reference_type === 'order') {
            $order = $transaction->order;
            if ($order) {
                return [
                    'type' => 'order',
                    'id' => $order->id,
                    'code' => $order->code_order,
                    'amount' => $order->total_amount,
                ];
            }
        }

        if ($transaction->reference_type === 'voucher') {
            $voucher = $transaction->voucher;
            if ($voucher) {
                return [
                    'type' => 'voucher',
                    'id' => $voucher->id,
                    'name' => $voucher->name,
                ];
            }
        }

        return null;
    }
}
