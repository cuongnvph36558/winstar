<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\PointService;
use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PointController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Hiển thị trang điểm tích lũy
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Test đơn giản trước
            $pointStats = [
                'total_points' => 0,
                'earned_points' => 0,
                'used_points' => 0,
                'vip_level' => 0,
                'vip_name' => 'Bronze'
            ];

            $pointHistory = [];
            $availableCoupons = collect([]);
            $userCoupons = collect([]);

            return view('client.points.index', compact('pointStats', 'pointHistory', 'availableCoupons', 'userCoupons'));
        } catch (\Exception $e) {
            // Log lỗi
            Log::error('Error in ClientPointController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Trả về lỗi 500 với thông tin chi tiết
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Đổi điểm lấy mã giảm giá
     */
    public function exchangeCoupon(Request $request)
    {
        $request->validate([
            'coupon_id' => 'required|exists:coupons,id'
        ]);

        $user = Auth::user();
        $couponId = $request->coupon_id;

        $result = $this->pointService->exchangePointsForCoupon($user, $couponId);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message'] . ' Mã giảm giá: ' . $result['coupon_code']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Hiển thị lịch sử giao dịch điểm
     */
    public function history()
    {
        $user = Auth::user();
        $pointHistory = $this->pointService->getUserPointHistory($user, 50);

        return view('client.points.history', compact('pointHistory'));
    }

    /**
     * Hiển thị mã giảm giá đã đổi
     */
    public function coupons()
    {
        $user = Auth::user();
        
        // Lấy thống kê điểm
        $pointStats = $this->pointService->getUserPointStats($user);
        
        // Lấy danh sách mã giảm giá có thể đổi
        $availableCoupons = \App\Models\Coupon::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('discount_value', 'desc')
            ->get();
        
        $userCoupons = CouponUser::where('user_id', $user->id)
            ->with('coupon')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.points.coupons', compact('pointStats', 'availableCoupons', 'userCoupons'));
    }

    /**
     * API: Lấy thông tin điểm của user
     */
    public function getPointInfo()
    {
        $user = Auth::user();
        $pointStats = $this->pointService->getUserPointStats($user);

        return response()->json([
            'success' => true,
            'data' => [
                'total_points' => $pointStats['total_points'],
                'earned_points' => $pointStats['earned_points'],
                'used_points' => $pointStats['used_points'],
                'vip_level' => $pointStats['vip_level'],
                'vip_name' => $pointStats['vip_name'],
            ]
        ]);
    }

    /**
     * API: Lấy danh sách mã giảm giá có thể đổi
     */
    public function getAvailableCoupons()
    {
        $user = Auth::user();
        $pointStats = $this->pointService->getUserPointStats($user);

        $availableCoupons = \App\Models\Coupon::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('discount_value', 'desc')
            ->get()
            ->map(function ($coupon) use ($pointStats) {
                return [
                    'id' => $coupon->id,
                    'name' => $coupon->name,
                    'description' => $coupon->description,
                    'discount_type' => $coupon->discount_type,
                    'discount_value' => $coupon->discount_value,
                    'min_order_value' => $coupon->min_order_value,
                    'code' => $coupon->code,
                    'can_exchange' => true, // Mã giảm giá không cần điểm để đổi
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $availableCoupons
        ]);
    }

    /**
     * API: Lấy danh sách mã giảm giá của user
     */
    public function getUserCoupons()
    {
        $user = Auth::user();
        
        $userCoupons = CouponUser::where('user_id', $user->id)
            ->with('coupon')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($userCoupon) {
                return [
                    'id' => $userCoupon->id,
                    'coupon_code' => $userCoupon->coupon->code,
                    'coupon_name' => $userCoupon->coupon->name,
                    'discount_type' => $userCoupon->coupon->discount_type,
                    'discount_value' => $userCoupon->coupon->discount_value,
                    'min_order_value' => $userCoupon->coupon->min_order_value,
                    'status' => $userCoupon->status,
                    'expiry_date' => $userCoupon->expiry_date,
                    'created_at' => $userCoupon->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $userCoupons
        ]);
    }
}
