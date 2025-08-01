<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        // Lấy thống kê điểm
        $pointStats = $this->pointService->getUserPointStats($user);

        // Lấy lịch sử giao dịch điểm
        $pointHistory = $this->pointService->getUserPointHistory($user, 20);

        // Lấy danh sách voucher có thể đổi
        $availableVouchers = \App\Models\PointVoucher::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('points_required', 'asc')
            ->get();

        return view('client.points.index', compact('pointStats', 'pointHistory', 'availableVouchers'));
    }

    /**
     * Đổi điểm lấy voucher
     */
    public function exchangeVoucher(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:point_vouchers,id'
        ]);

        $user = Auth::user();
        $voucherId = $request->voucher_id;

        $result = $this->pointService->exchangePointsForVoucher($user, $voucherId);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message'] . ' Mã voucher: ' . $result['voucher_code']);
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
     * Hiển thị voucher đã đổi
     */
    public function vouchers()
    {
        $user = Auth::user();
        $userVouchers = $user->userPointVouchers()
            ->with('pointVoucher')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.points.vouchers', compact('userVouchers'));
    }
}
