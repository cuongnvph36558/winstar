<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use App\Models\Attendance;
use App\Mail\AttendanceThankYouMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use Illuminate\Support\Facades\Mail;

class AttendancePointController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Điểm danh hôm nay (mỗi ngày 1 lần, 100 điểm)
     */
    public function claimPoints(Request $request)
    {
        try {
            $user = Auth::user();
            $today = now()->toDateString();
            
            // Kiểm tra xem đã điểm danh hôm nay chưa
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->where('date', $today)
                ->where('points_claimed', true)
                ->first();
            
            if ($existingAttendance) {
                $result = [
                    'success' => false,
                    'message' => 'Bạn đã điểm danh hôm nay rồi!'
                ];
            } else {
                // Tạo điểm danh mới
                $result = $this->createCompleteAttendance($user);
            }

            if ($request->expectsJson() || $request->header('Content-Type') === 'application/json') {
                return response()->json($result);
            }

            if ($result['success']) {
                return redirect()->back()->with('success', $result['message']);
            } else {
                return redirect()->back()->with('error', $result['message']);
            }
            
        } catch (\Exception $e) {
            $errorResult = [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi điểm danh!'
            ];
            
            if ($request->expectsJson() || $request->header('Content-Type') === 'application/json') {
                return response()->json($errorResult);
            }
            
            return redirect()->back()->with('error', $errorResult['message']);
        }
    }

    /**
     * Tạo điểm danh hoàn chỉnh (mỗi ngày 1 lần, 100 điểm)
     */
    private function createCompleteAttendance(User $user): array
    {
        try {
            DB::beginTransaction();

            $today = now()->toDateString();
            $currentTime = now();

            // Tạo bản ghi điểm danh mới
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'check_in_time' => $currentTime->format('H:i:s'),
                'check_out_time' => $currentTime->format('H:i:s'),
                'status' => 'present',
                'points_earned' => 100,
                'points_claimed' => true
            ]);

            // Cộng điểm vào tài khoản user
            $userPoint = $user->point;
            if (!$userPoint) {
                $userPoint = $user->point()->create([
                    'total_points' => 0,
                    'earned_points' => 0,
                    'used_points' => 0,
                    'expired_points' => 0,
                    'vip_level' => 'Bronze'
                ]);
            }

            $userPoint->total_points += 100;
            $userPoint->earned_points += 100;
            $userPoint->save();

            // Tạo giao dịch điểm
            \App\Models\PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'earn',
                'points' => 100,
                'description' => "Điểm danh ngày " . $today,
                'reference_type' => 'attendance',
                'reference_id' => $attendance->id,
                'expiry_date' => now()->addYear(),
                'is_expired' => false
            ]);

            DB::commit();

            // Gửi email cảm ơn điểm danh
            try {
                Mail::to($user->email)->send(new AttendanceThankYouMail($user, $attendance, 'check_out'));
            } catch (\Exception $e) {
                // Email error không ảnh hưởng đến việc điểm danh
            }

            return [
                'success' => true,
                'message' => "Điểm danh thành công! Bạn nhận được 100 điểm.",
                'data' => $attendance
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi điểm danh!'
            ];
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


}
