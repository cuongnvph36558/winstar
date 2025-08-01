<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\PointVoucher;
use App\Models\UserPointVoucher;
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

        // Voucher được sử dụng nhiều
        $topVouchers = PointVoucher::withCount('userVouchers')
            ->orderByDesc('user_vouchers_count')
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
            'topVouchers'
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
        $userVouchers = $user->userPointVouchers()->with('pointVoucher')->paginate(10);

        return view('admin.points.user-detail', compact('user', 'pointStats', 'pointHistory', 'userVouchers'));
    }

    /**
     * Hiển thị danh sách voucher
     */
    public function vouchers()
    {
        $vouchers = PointVoucher::withCount('userVouchers')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.points.vouchers', compact('vouchers'));
    }

    /**
     * Tạo voucher mới
     */
    public function createVoucher()
    {
        return view('admin.points.create-voucher');
    }

    /**
     * Lưu voucher mới
     */
    public function storeVoucher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_usage' => 'nullable|integer|min:1',
        ]);

        PointVoucher::create($request->all());

        return redirect()->route('admin.points.vouchers')
            ->with('success', 'Voucher đã được tạo thành công!');
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
     * Sửa voucher
     */
    public function editVoucher(PointVoucher $voucher)
    {
        return view('admin.points.edit-voucher', compact('voucher'));
    }

    /**
     * Cập nhật voucher
     */
    public function updateVoucher(Request $request, PointVoucher $voucher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_usage' => 'nullable|integer|min:1',
        ]);

        $voucher->update($request->all());

        return redirect()->route('admin.points.vouchers')
            ->with('success', 'Voucher đã được cập nhật thành công!');
    }

    /**
     * Xóa voucher
     */
    public function destroyVoucher(PointVoucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('admin.points.vouchers')
            ->with('success', 'Voucher đã được xóa thành công!');
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
