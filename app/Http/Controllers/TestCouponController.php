<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CouponService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestCouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function createTestCoupon()
    {
        try {
            $coupon = Coupon::create([
                'code' => 'TEST50K',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_order_value' => 100000,
                'max_discount_value' => null,
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addMonths(1),
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Created test coupon: TEST50K',
                'coupon' => $coupon
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating coupon: ' . $e->getMessage()
            ]);
        }
    }

    public function testCouponValidation(Request $request)
    {
        $code = $request->input('code', 'TEST50K');
        $orderAmount = $request->input('amount', 500000);

        $result = $this->couponService->validateAndCalculateDiscount($code, $orderAmount);

        return response()->json([
            'code' => $code,
            'order_amount' => $orderAmount,
            'result' => $result
        ]);
    }

    public function listCoupons()
    {
        $coupons = Coupon::all(['id', 'code', 'discount_type', 'discount_value', 'status', 'start_date', 'end_date']);

        return response()->json([
            'success' => true,
            'coupons' => $coupons
        ]);
    }

    public function testApplyCoupon(Request $request)
    {
        $code = $request->input('coupon_code', 'TEST50K');
        $orderAmount = 500000; // Fixed amount for testing

        $result = $this->couponService->validateAndCalculateDiscount($code, $orderAmount);

        if ($result['valid']) {
            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công! Giảm ' . number_format($result['discount'], 0, ',', '.') . 'đ',
                'discount' => $result['discount'],
                'coupon_code' => $code
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }
} 