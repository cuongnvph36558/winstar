<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CouponService
{
    /**
     * Validate và tính toán giá trị giảm giá của mã
     */
    public function validateAndCalculateDiscount(string $code, float $orderAmount, ?User $user = null): array
    {
        $coupon = Coupon::where('code', $code)
            ->where('status', 1)
            ->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn',
                'discount' => 0
            ];
        }

        // Kiểm tra thời gian hiệu lực
        $now = Carbon::now();
        if ($coupon->start_date && $now->lt($coupon->start_date)) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá chưa có hiệu lực',
                'discount' => 0
            ];
        }

        if ($coupon->end_date && $now->gt($coupon->end_date)) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã hết hạn',
                'discount' => 0
            ];
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($coupon->min_order_value && $orderAmount < $coupon->min_order_value) {
            return [
                'valid' => false,
                'message' => 'Giá trị đơn hàng chưa đạt mức tối thiểu',
                'discount' => 0
            ];
        }

        // Kiểm tra số lần sử dụng
        if ($coupon->usage_limit && $coupon->couponUsers()->count() >= $coupon->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng',
                'discount' => 0
            ];
        }

        // Kiểm tra số lần sử dụng của người dùng
        if ($user && $coupon->usage_limit_per_user) {
            $userUsage = $coupon->couponUsers()->where('user_id', $user->id)->count();
            if ($userUsage >= $coupon->usage_limit_per_user) {
                return [
                    'valid' => false,
                    'message' => 'Bạn đã sử dụng hết số lần cho phép của mã này',
                    'discount' => 0
                ];
            }
        }

        // Kiểm tra mã giảm giá có yêu cầu điểm để đổi không
        if ($coupon->exchange_points > 0) {
            // Kiểm tra xem user đã đổi mã này chưa
            if ($user) {
                $couponUser = CouponUser::where('user_id', $user->id)
                    ->where('coupon_id', $coupon->id)
                    ->whereNull('used_at') // Chỉ tìm record chưa sử dụng
                    ->first();
                
                if (!$couponUser) {
                    return [
                        'valid' => false,
                        'message' => 'Mã giảm giá này yêu cầu điểm để đổi, vui lòng vào trang Điểm tích lũy để đổi',
                        'discount' => 0
                    ];
                }
                
                // Kiểm tra mã đã đổi đã được sử dụng chưa (chỉ được sử dụng 1 lần)
                if ($couponUser->used_at !== null) {
                    return [
                        'valid' => false,
                        'message' => 'Mã giảm giá này đã được sử dụng',
                        'discount' => 0
                    ];
                }
            } else {
                return [
                    'valid' => false,
                    'message' => 'Mã giảm giá này yêu cầu điểm để đổi, vui lòng vào trang Điểm tích lũy để đổi',
                    'discount' => 0
                ];
            }
        }

        // Kiểm tra giá trị giảm giá có hợp lệ không
        if (is_null($coupon->discount_value) || $coupon->discount_value <= 0) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không hợp lệ (0đ)',
                'discount' => 0
            ];
        }

        // Tính toán giá trị giảm giá
        $discount = $this->calculateDiscount($coupon, $orderAmount);

        return [
            'valid' => true,
            'message' => 'Áp dụng mã giảm giá thành công',
            'discount' => $discount,
            'coupon' => $coupon
        ];
    }

    /**
     * Tính toán giá trị giảm giá
     */
    private function calculateDiscount(Coupon $coupon, float $orderAmount): float
    {
        $discount = 0;

        if ($coupon->discount_type === 'percentage') {
            $discount = $orderAmount * ($coupon->discount_value / 100);
        } else {
            $discount = $coupon->discount_value;
        }

        // Kiểm tra giới hạn giảm giá tối đa
        if ($coupon->max_discount_value && $discount > $coupon->max_discount_value) {
            $discount = $coupon->max_discount_value;
        }

        return $discount;
    }
}