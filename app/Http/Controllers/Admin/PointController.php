<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Hiển thị trang quản lý điểm tích lũy
     */
    public function index()
    {
        // Thống kê tổng quan
        $totalUsers = User::count();
        $usersWithPoints = Point::count();
        $totalPoints = Point::sum('total_points');
        $totalEarnedPoints = Point::sum('earned_points');
        $totalUsedPoints = Point::sum('used_points');

        // Top users có nhiều điểm
        $topUsers = Point::with('user')
            ->orderByDesc('total_points')
            ->limit(10)
            ->get();

        // Thống kê theo level VIP
        $vipStats = Point::select('vip_level', DB::raw('count(*) as count'))
            ->groupBy('vip_level')
            ->get();

        // Giao dịch điểm gần đây
        $recentTransactions = PointTransaction::with(['user'])
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        // Mã giảm giá được sử dụng nhiều
        $topCoupons = Coupon::withCount('couponUsers')
            ->orderByDesc('coupon_users_count')
            ->limit(10)
            ->get();

        return view('admin.points.index', compact(
            'totalUsers',
            'usersWithPoints',
            'totalPoints',
            'totalEarnedPoints',
            'totalUsedPoints',
            'topUsers',
            'vipStats',
            'recentTransactions',
            'topCoupons'
        ));
    }

    /**
     * Hiển thị danh sách users và điểm
     */
    public function users()
    {
        $users = User::with('point')
            ->withCount('orders')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.points.users', compact('users'));
    }

    /**
     * Hiển thị chi tiết điểm của user
     */
    public function userDetail(User $user)
    {
        $pointStats = $this->pointService->getUserPointStats($user);
        $pointHistory = $this->pointService->getUserPointHistory($user, 50);
        $userCoupons = $user->couponUsers()->with('coupon')->paginate(10);

        return view('admin.points.user-detail', compact('user', 'pointStats', 'pointHistory', 'userCoupons'));
    }

    /**
     * Hiển thị danh sách mã giảm giá
     */
    public function coupons()
    {
        $coupons = Coupon::withCount('couponUsers')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.points.coupons', compact('coupons'));
    }

    /**
     * Tạo mã giảm giá mới
     */
    public function createCoupon()
    {
        return view('admin.points.create-coupon');
    }

    /**
     * Lưu mã giảm giá mới
     */
    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value' => 'required|numeric|min:0',
            'max_usage' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Handle empty exchange_points value
        if (empty($data['exchange_points']) || $data['exchange_points'] === '') {
            $data['exchange_points'] = 0;
        }
        
        Coupon::create($data);

        return redirect()->route('admin.points.coupons')
            ->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    /**
     * Hiển thị lịch sử giao dịch điểm
     */
    public function transactions()
    {
        $transactions = PointTransaction::with(['user'])
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('admin.points.transactions', compact('transactions'));
    }

    /**
     * Thêm điểm thưởng cho user
     */
    public function addBonusPoints(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        $result = $this->pointService->giveBonusPoints($user, $request->points, $request->description);

        if ($result) {
            return redirect()->back()->with('success', 'Đã thêm điểm thưởng thành công!');
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm điểm thưởng!');
        }
    }

    /**
     * Sửa mã giảm giá
     */
    public function editCoupon(Coupon $coupon)
    {
        return view('admin.points.edit-coupon', compact('coupon'));
    }

    /**
     * Cập nhật mã giảm giá
     */
    public function updateCoupon(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value' => 'required|numeric|min:0',
            'max_usage' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Handle empty exchange_points value
        if (empty($data['exchange_points']) || $data['exchange_points'] === '') {
            $data['exchange_points'] = 0;
        }
        
        $coupon->update($data);

        return redirect()->route('admin.points.coupons')
            ->with('success', 'Mã giảm giá đã được cập nhật thành công!');
    }

    /**
     * Xóa mã giảm giá
     */
    public function destroyCoupon(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.points.coupons')
            ->with('success', 'Mã giảm giá đã được xóa thành công!');
    }

    /**
     * Xử lý điểm hết hạn
     */
    public function processExpiredPoints()
    {
        $this->pointService->processExpiredPoints();

        return redirect()->back()->with('success', 'Đã xử lý điểm hết hạn thành công!');
    }
}
