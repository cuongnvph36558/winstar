<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Hiển thị trang điểm danh
     */
    public function index()
    {
        $user = Auth::user();
        
        // Lấy trạng thái điểm danh hôm nay
        $todayStatus = $this->attendanceService->getTodayAttendanceStatus($user);
        
        // Lấy thống kê điểm danh tháng này
        $monthlyStats = $this->attendanceService->getUserAttendanceStats($user, now()->month, now()->year);
        
        // Lấy lịch sử điểm danh
        $attendanceHistory = $this->attendanceService->getUserAttendanceHistory($user, 30);

        return view('client.attendance.index', compact('todayStatus', 'monthlyStats', 'attendanceHistory'));
    }

    /**
     * Điểm danh vào
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $result = $this->attendanceService->checkIn($user);

        if ($request->expectsJson() || $request->header('Content-Type') === 'application/json') {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Điểm danh ra
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $result = $this->attendanceService->checkOut($user);

        if ($request->expectsJson() || $request->header('Content-Type') === 'application/json') {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * API: Lấy trạng thái điểm danh hôm nay
     */
    public function getTodayStatus()
    {
        $user = Auth::user();
        $status = $this->attendanceService->getTodayAttendanceStatus($user);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * API: Lấy thống kê điểm danh
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $stats = $this->attendanceService->getUserAttendanceStats($user, $month, $year);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

        /**
     * API: Lấy lịch sử điểm danh
     */
    public function getHistory(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 30);
        
        $history = $this->attendanceService->getUserAttendanceHistory($user, $limit);
        
        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Tích điểm từ điểm danh
     */
    public function claimPoints(Request $request)
    {
        $user = Auth::user();
        $result = $this->attendanceService->claimAttendancePoints($user);

        if ($request->expectsJson() || $request->header('Content-Type') === 'application/json') {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}
