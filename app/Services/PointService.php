<?php

namespace App\Services;

use App\Models\User;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * PointService - Quản lý hệ thống điểm tích lũy
 * 
 * QUY TẮC QUAN TRỌNG:
 * - 1 điểm = 1 VND (1đ)
 * - Áp dụng cho tất cả các trường hợp: tích điểm, sử dụng điểm, hoàn hàng đổi điểm
 * - Yêu cầu hoàn hàng và hoàn hàng đổi điểm đều tuân theo quy tắc này
 */
class PointService
{
    /**
     * Tích điểm cho user khi mua hàng
     */
    public function earnPointsFromOrder(User $user, Order $order): bool
    {
        try {
            DB::beginTransaction();

            // Kiểm tra xem đơn hàng đã được tích điểm chưa
            if ($order->points_earned > 0) {
                Log::info("Đơn hàng #{$order->code_order} đã được tích điểm trước đó (points_earned: {$order->points_earned})");
                DB::rollBack();
                return false;
            }

            // Kiểm tra xem đã có giao dịch điểm cho đơn hàng này chưa
            $existingTransaction = PointTransaction::where('user_id', $user->id)
                ->where('reference_type', 'order')
                ->where('reference_id', $order->id)
                ->where('type', 'earn')
                ->first();

            if ($existingTransaction) {
                Log::info("Đã có giao dịch tích điểm cho đơn hàng #{$order->code_order}");
                DB::rollBack();
                return false;
            }

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
            $updateResult = $order->update(['points_earned' => $pointsToEarn]);
            Log::info("Cập nhật points_earned cho đơn hàng #{$order->code_order}: {$pointsToEarn} điểm, kết quả: " . ($updateResult ? 'true' : 'false'));

            DB::commit();
            
            // Refresh order để đảm bảo dữ liệu được cập nhật
            $order->refresh();
            Log::info("Đã tích điểm thành công cho đơn hàng #{$order->code_order}: {$pointsToEarn} điểm, points_earned sau refresh: {$order->points_earned}");
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
        $orderTotal = $order->total_amount;
        
        // Tỷ lệ tích điểm theo % giá trị đơn hàng
        $baseRate = 0.01; // 1% giá trị đơn hàng
        
        // Tỷ lệ VIP (nếu có)
        $vipMultiplier = 1.0;
        $basePoints = 0; // Điểm cơ bản cho mỗi mức VIP
        
        if ($user->point) {
            $vipMultiplier = $user->point->point_rate; // Sử dụng point_rate từ model
            
            // Thêm điểm cơ bản cho mỗi mức VIP
            $vipLevel = $user->point->vip_level;
            $basePoints = match($vipLevel) {
                'Diamond' => 500000, // 500k điểm cơ bản
                'Platinum' => 400000, // 400k điểm cơ bản
                'Gold' => 300000, // 300k điểm cơ bản
                'Silver' => 200000, // 200k điểm cơ bản
                'Bronze' => 100000, // 100k điểm cơ bản
                default => 100000,
            };
        }
        
        // Tính điểm từ giá trị đơn hàng
        $orderPoints = floor($orderTotal * $baseRate * $vipMultiplier);
        
        // Tổng điểm = điểm từ đơn hàng + điểm cơ bản VIP
        $totalPoints = $orderPoints + $basePoints;
        
        return max(1, $totalPoints); // Ít nhất 1 điểm
    }

    /**
     * Đổi điểm lấy mã giảm giá
     */
    public function exchangePointsForCoupon(User $user, int $couponId): array
    {
        try {
            DB::beginTransaction();

            $coupon = Coupon::findOrFail($couponId);

            // Kiểm tra mã giảm giá có hoạt động không
            if ($coupon->status != 1 || $coupon->start_date > now() || $coupon->end_date < now()) {
                return [
                    'success' => false,
                    'message' => 'Mã giảm giá không còn hiệu lực'
                ];
            }

            // Kiểm tra user đã có mã giảm giá này chưa (chỉ kiểm tra mã chưa sử dụng)
            $existingUnusedCouponUser = CouponUser::where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->whereNull('used_at')
                ->first();

            if ($existingUnusedCouponUser) {
                return [
                    'success' => false,
                    'message' => 'Bạn đã có mã giảm giá này rồi'
                ];
            }

            // Kiểm tra xem user có đủ điểm để đổi không
            $userPoints = $user->point;
            $availablePoints = $userPoints ? $userPoints->total_points : 0;
            
            if ($availablePoints < $coupon->exchange_points) {
                return [
                    'success' => false,
                    'message' => 'Bạn không đủ điểm để đổi mã giảm giá này'
                ];
            }

            // Trừ điểm của user
            if ($userPoints) {
                $userPoints->update([
                    'total_points' => $userPoints->total_points - $coupon->exchange_points,
                    'used_points' => $userPoints->used_points + $coupon->exchange_points,
                ]);
            }

            // Tạo mã giảm giá cho user
            $couponUser = CouponUser::create([
                'user_id' => $user->id,
                'coupon_id' => $coupon->id,
                'used_at' => null,
                'order_id' => null,
            ]);

            // Tạo giao dịch điểm
            $expiryDate = Carbon::now()->addMonths(12);
            
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'exchange',
                'points' => -$coupon->exchange_points, // Số điểm âm vì đã trừ
                'description' => "Đổi {$coupon->exchange_points} điểm lấy mã giảm giá {$coupon->code}",
                'reference_type' => 'coupon',
                'reference_id' => $coupon->id,
                'expiry_date' => $expiryDate,
                'is_expired' => false,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Nhận mã giảm giá thành công',
                'coupon_code' => $coupon->code,
                'coupon' => $coupon
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error exchanging points for coupon: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi nhận mã giảm giá'
            ];
        }
    }

    /**
     * Sử dụng mã giảm giá trong đơn hàng
     */
    public function useCouponInOrder(string $couponCode, Order $order): array
    {
        try {
            $coupon = Coupon::where('code', $couponCode)->first();
            
            if (!$coupon) {
                return [
                    'success' => false,
                    'message' => 'Mã giảm giá không tồn tại'
                ];
            }

            // Kiểm tra mã giảm giá có hoạt động không
            if ($coupon->status != 1 || $coupon->start_date > now() || $coupon->end_date < now()) {
                return [
                    'success' => false,
                    'message' => 'Mã giảm giá không còn hiệu lực'
                ];
            }

            // Kiểm tra giá trị đơn hàng tối thiểu
            if ($order->total_amount < $coupon->min_order_value) {
                return [
                    'success' => false,
                    'message' => "Đơn hàng phải có giá trị tối thiểu " . number_format($coupon->min_order_value) . " VND"
                ];
            }

            // Kiểm tra user có mã giảm giá này không
            $couponUser = CouponUser::where('user_id', $order->user_id)
                ->where('coupon_id', $coupon->id)
                ->whereNull('used_at')
                ->first();

            if (!$couponUser) {
                return [
                    'success' => false,
                    'message' => 'Bạn chưa có mã giảm giá này'
                ];
            }

            // Tính toán giảm giá
            $discountAmount = 0;
            if ($coupon->discount_type === 'percentage') {
                $discountAmount = ($order->total_amount * $coupon->discount_value) / 100;
                if ($coupon->max_discount_value) {
                    $discountAmount = min($discountAmount, $coupon->max_discount_value);
                }
            } else {
                $discountAmount = $coupon->discount_value;
            }

            // Cập nhật đơn hàng
            $order->update([
                'coupon_id' => $coupon->id,
                'discount_amount' => $discountAmount,
                'total_amount' => $order->total_amount - $discountAmount
            ]);

            // Đánh dấu mã giảm giá đã sử dụng
            $couponUser->update([
                'used_at' => now(),
                'order_id' => $order->id
            ]);

            return [
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công',
                'discount_amount' => $discountAmount,
                'coupon' => $coupon
            ];

        } catch (\Exception $e) {
            Log::error('Error using coupon in order: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng mã giảm giá'
            ];
        }
    }

    /**
     * Thêm điểm thưởng cho user
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

            // Tạo giao dịch điểm thưởng
            $expiryDate = Carbon::now()->addMonths(12);

            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'bonus',
                'points' => $points,
                'description' => $description,
                'reference_type' => 'bonus',
                'reference_id' => null,
                'expiry_date' => $expiryDate,
                'is_expired' => false,
            ]);

            DB::commit();
            Log::info("Đã thêm điểm thưởng cho user {$user->id}: {$points} điểm - {$description}");
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
            $expiredTransactions = PointTransaction::where('expiry_date', '<', now())
                ->where('is_expired', false)
                ->where('type', 'earn')
                ->get();

            foreach ($expiredTransactions as $transaction) {
                $point = Point::where('user_id', $transaction->user_id)->first();
                
                if ($point) {
                    $point->update([
                        'total_points' => max(0, $point->total_points - $transaction->points),
                        'expired_points' => $point->expired_points + $transaction->points,
                    ]);
                }

                $transaction->update(['is_expired' => true]);
            }

            Log::info("Đã xử lý " . $expiredTransactions->count() . " giao dịch điểm hết hạn");

        } catch (\Exception $e) {
            Log::error('Error processing expired points: ' . $e->getMessage());
        }
    }

    /**
     * Tạo mã giảm giá ngẫu nhiên
     */
    private function generateCouponCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Coupon::where('code', $code)->exists());

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
                'vip_level' => 0,
                'vip_name' => 'Bronze',
            ];
        }

        // Tính toán lại để đảm bảo tính nhất quán
        $totalEarned = PointTransaction::where('user_id', $user->id)
            ->where('type', 'earn')
            ->where('is_expired', false)
            ->sum('points');

        $totalUsed = PointTransaction::where('user_id', $user->id)
            ->where('type', 'use')
            ->sum(DB::raw('ABS(points)'));

        $totalExpired = PointTransaction::where('user_id', $user->id)
            ->where('type', 'earn')
            ->where('is_expired', true)
            ->sum('points');

        $totalPoints = $totalEarned - $totalUsed;

        // Cập nhật lại bảng points để đồng bộ
        $point->update([
            'total_points' => max(0, $totalPoints),
            'earned_points' => $totalEarned,
            'used_points' => $totalUsed,
            'expired_points' => $totalExpired,
        ]);

        // Tính level VIP dựa trên tổng điểm đã tích
        $vipLevel = $this->calculateVipLevel($totalEarned);

        return [
            'total_points' => max(0, $totalPoints),
            'earned_points' => $totalEarned,
            'used_points' => $totalUsed,
            'expired_points' => $totalExpired,
            'vip_level' => $vipLevel,
            'vip_name' => $vipLevel, // VIP level đã là string rồi
        ];
    }

    /**
     * Lấy lịch sử giao dịch điểm của user
     */
    public function getUserPointHistory(User $user, int $limit = 20): array
    {
        $transactions = PointTransaction::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($transaction) {
                $referenceInfo = $this->getReferenceInfo($transaction);
                
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'points' => $transaction->points,
                    'description' => $transaction->description,
                    'reference_info' => $referenceInfo,
                    'created_at' => $transaction->created_at,
                    'expiry_date' => $transaction->expiry_date,
                    'is_expired' => $transaction->is_expired,
                ];
            });

        return $transactions->toArray();
    }

    /**
     * Lấy thông tin tham chiếu của giao dịch
     */
    private function getReferenceInfo(PointTransaction $transaction): ?array
    {
        if (!$transaction->reference_type || !$transaction->reference_id) {
            return null;
        }

        switch ($transaction->reference_type) {
            case 'order':
                $order = Order::find($transaction->reference_id);
                return $order ? [
                    'type' => 'order',
                    'id' => $order->id,
                    'code' => $order->code_order,
                    'amount' => $order->total_amount,
                ] : null;

            case 'coupon':
                $coupon = Coupon::find($transaction->reference_id);
                return $coupon ? [
                    'type' => 'coupon',
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                ] : null;

            default:
                return null;
        }
    }

    /**
     * Tính level VIP dựa trên điểm đã tích
     */
    private function calculateVipLevel(int $earnedPoints): string
    {
        if ($earnedPoints >= 700000) return 'Diamond'; // Tăng từ 600k lên 700k (+100k)
        if ($earnedPoints >= 490000) return 'Platinum'; // Tăng từ 390k lên 490k (+100k)
        if ($earnedPoints >= 430000) return 'Gold'; // Tăng từ 330k lên 430k (+100k)
        if ($earnedPoints >= 340000) return 'Silver'; // Tăng từ 240k lên 340k (+100k)
        return 'Bronze';
    }

    /**
     * Tính giá trị tiền của điểm (1 điểm = 1 VND)
     */
    public function calculatePointsValue(int $points): int
    {
        return $points * 1; // 1 điểm = 1 VND
    }

    /**
     * Tính số điểm cần để đổi thành tiền
     */
    public function calculatePointsNeeded(int $moneyAmount): int
    {
        return ceil($moneyAmount / 1); // 1 VND = 1 điểm
    }

    /**
     * Kiểm tra và sử dụng điểm để giảm giá đơn hàng
     */
    public function usePointsForOrder(User $user, int $pointsToUse, float $orderTotal): array
    {
        try {
            DB::beginTransaction();

            $point = $user->point;
            
            if (!$point || $point->total_points < $pointsToUse) {
                return [
                    'success' => false,
                    'message' => 'Không đủ điểm để sử dụng'
                ];
            }

            // Tính giá trị tiền của điểm
            $pointsValue = $this->calculatePointsValue($pointsToUse);
            
            // Cho phép sử dụng điểm tối đa 100% giá trị đơn hàng
            $maxPointsValue = $orderTotal;
            if ($pointsValue > $maxPointsValue) {
                $maxPoints = $this->calculatePointsNeeded($maxPointsValue);
                return [
                    'success' => false,
                    'message' => "Chỉ được sử dụng tối đa {$maxPoints} điểm (100% giá trị đơn hàng)"
                ];
            }
            
            // Nếu điểm sử dụng >= tổng tiền, thì chỉ sử dụng đúng số điểm cần thiết
            if ($pointsValue >= $orderTotal) {
                $pointsToUse = $orderTotal; // Chỉ sử dụng đúng số điểm cần thiết
                $pointsValue = $orderTotal;
            }

            // Cập nhật điểm
            $point->update([
                'total_points' => $point->total_points - $pointsToUse,
                'used_points' => $point->used_points + $pointsToUse,
            ]);

            // Tạo giao dịch sử dụng điểm
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'use',
                'points' => -$pointsToUse, // Số âm để thể hiện sử dụng
                'description' => "Sử dụng {$pointsToUse} điểm để giảm giá đơn hàng",
                'reference_type' => 'order_points',
                'reference_id' => null,
                'expiry_date' => null,
                'is_expired' => false,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Đã sử dụng {$pointsToUse} điểm thành công",
                'points_used' => $pointsToUse,
                'points_value' => $pointsValue,
                'remaining_points' => $point->total_points
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error using points for order: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng điểm'
            ];
        }
    }

    /**
     * Hoàn trả điểm nếu đơn hàng bị hủy
     */
    public function refundPointsForOrder(User $user, int $pointsUsed, string $orderCode): bool
    {
        try {
            DB::beginTransaction();

            $point = $user->point;
            
            if (!$point) {
                DB::rollBack();
                return false;
            }

            // Cập nhật điểm
            $point->update([
                'total_points' => $point->total_points + $pointsUsed,
                'used_points' => $point->used_points - $pointsUsed,
            ]);

            // Tạo giao dịch hoàn trả điểm
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'bonus',
                'points' => $pointsUsed,
                'description' => "Hoàn trả {$pointsUsed} điểm từ đơn hàng #{$orderCode}",
                'reference_type' => 'order_refund',
                'reference_id' => null,
                'expiry_date' => Carbon::now()->addMonths(12),
                'is_expired' => false,
            ]);

            DB::commit();
            Log::info("Đã hoàn trả {$pointsUsed} điểm cho user {$user->id} từ đơn hàng #{$orderCode}");
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error refunding points: ' . $e->getMessage());
            return false;
        }
    }
}
