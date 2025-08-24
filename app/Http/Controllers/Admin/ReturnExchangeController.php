<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ReturnExchangeController - Quản lý yêu cầu đổi hoàn hàng
 * 
 * QUY TẮC QUAN TRỌNG:
 * - 1 điểm = 1 VND (1đ)
 * - Khi admin chấp thuận hoàn hàng đổi điểm, số điểm hoàn = số tiền hoàn
 * - Ví dụ: Hoàn 50,000đ = 50,000 điểm
 */
class ReturnExchangeController extends Controller
{
    public function index()
    {
        $returns = Order::where('return_status', '!=', 'none')
            ->with(['user', 'orderDetails.product'])
            ->orderBy('return_requested_at', 'desc')
            ->paginate(15);

        return view('admin.return-exchange.index', compact('returns'));
    }

    public function show(Order $order)
    {
        if ($order->return_status === 'none') {
            return redirect()->route('admin.return-exchange.index')
                ->with('error', 'Đơn hàng này không có yêu cầu đổi hoàn hàng!');
        }

        return view('admin.return-exchange.show', compact('order'));
    }

    public function approve(Request $request, Order $order)
    {
        // Debug: Log request data
        \Log::info('Approve return request', [
            'order_id' => $order->id,
            'return_status' => $order->return_status,
            'return_method' => $order->return_method,
            'request_data' => $request->all()
        ]);

        // Debug: Log before points check
        \Log::info('Before points check', [
            'return_method' => $order->return_method,
            'return_amount' => $request->return_amount,
            'should_add_points' => (($order->return_method === 'points' || $order->return_method === 'refund') && $request->return_amount > 0)
        ]);

        if ($order->return_status !== 'requested') {
            return redirect()->back()->with('error', 'Chỉ có thể chấp thuận yêu cầu đang ở trạng thái requested!');
        }

        // Xác định max amount dựa trên phương thức
        // Quy tắc: 1 điểm = 1đ, nên max amount cho điểm = total_amount
        $maxAmount = $order->total_amount;

        $request->validate([
            'admin_return_note' => 'nullable|string|max:1000',
            'return_amount' => 'nullable|numeric|min:0|max:' . $maxAmount
        ]);

        try {
            // Bắt đầu transaction
            \DB::beginTransaction();

            $order->update([
                'return_status' => 'approved',
                'admin_return_note' => $request->admin_return_note,
                'return_amount' => $request->return_amount,
                'return_processed_at' => now()
            ]);

            // Nếu là đổi điểm/refund và có số điểm hoàn
            if (($order->return_method === 'points' || $order->return_method === 'refund') && $request->return_amount > 0) {
                \Log::info('Adding points to user', [
                    'user_id' => $order->user->id,
                    'points' => $request->return_amount
                ]);
                $this->addPointsToUser($order->user, $request->return_amount, $order);
            } else {
                \Log::info('Not adding points', [
                    'return_method' => $order->return_method,
                    'return_amount' => $request->return_amount,
                    'condition_met' => (($order->return_method === 'points' || $order->return_method === 'refund') && $request->return_amount > 0)
                ]);
            }

            // Gửi thông báo cho user
            $this->sendNotificationToUser($order, 'approved');

            \DB::commit();

            \Log::info('Return request approved successfully', ['order_id' => $order->id]);

            return redirect()->route('admin.order.show', $order->id)
                ->with('success', 'Đã chấp thuận yêu cầu đổi hoàn hàng thành công!');
        } catch (\Exception $e) {
            \DB::rollback();
            
            \Log::error('Error approving return request', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi chấp thuận yêu cầu: ' . $e->getMessage());
        }
    }

    /**
     * Cộng điểm cho user
     */
    private function addPointsToUser($user, $points, $order)
    {
        try {
            // Tìm hoặc tạo point record cho user
            $pointRecord = \App\Models\Point::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'total_points' => 0, 
                    'earned_points' => 0,
                    'used_points' => 0,
                    'expired_points' => 0
                ]
            );

            // Cộng điểm
            $pointRecord->update([
                'total_points' => $pointRecord->total_points + $points,
                'earned_points' => $pointRecord->earned_points + $points,
            ]);

            // Tạo transaction log
            \App\Models\PointTransaction::create([
                'user_id' => $user->id,
                'points' => $points,
                'type' => 'earn',
                'description' => "Điểm hoàn từ yêu cầu đổi hoàn hàng đơn hàng #{$order->code_order}",
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'expiry_date' => \Carbon\Carbon::now()->addMonths(12), // Điểm hết hạn sau 12 tháng
                'is_expired' => false,
            ]);

            \Log::info('Points added to user from return/exchange', [
                'user_id' => $user->id,
                'points_added' => $points,
                'order_id' => $order->id,
                'order_code' => $order->code_order,
                'return_method' => $order->return_method,
                'return_amount' => $order->return_amount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding points to user from return/exchange', [
                'user_id' => $user->id,
                'points' => $points,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Gửi thông báo cho user
     */
    private function sendNotificationToUser($order, $action)
    {
        try {
            $user = $order->user;
            $message = '';
            $title = '';

            switch ($action) {
                case 'approved':
                    $title = 'Yêu cầu đổi hoàn hàng được chấp thuận';
                    if (($order->return_method === 'points' || $order->return_method === 'refund') && $order->return_amount > 0) {
                        $message = "Yêu cầu đổi hoàn hàng đơn hàng #{$order->id} đã được chấp thuận. Bạn đã nhận được {$order->return_amount} điểm vào tài khoản.";
                    } else {
                        $message = "Yêu cầu đổi hoàn hàng đơn hàng #{$order->id} đã được chấp thuận.";
                    }
                    break;
                case 'rejected':
                    $title = 'Yêu cầu đổi hoàn hàng bị từ chối';
                    $message = "Yêu cầu đổi hoàn hàng đơn hàng #{$order->id} đã bị từ chối.";
                    break;
                case 'completed':
                    $title = 'Yêu cầu đổi hoàn hàng hoàn thành';
                    $message = "Yêu cầu đổi hoàn hàng đơn hàng #{$order->id} đã được hoàn thành.";
                    break;
            }

            // Tạo notification record
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => 'return_exchange',
                'read_at' => null,
                'data' => json_encode([
                    'order_id' => $order->id,
                    'action' => $action,
                    'return_method' => $order->return_method,
                    'return_amount' => $order->return_amount
                ])
            ]);

            \Log::info('Notification sent to user', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'action' => $action
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending notification to user', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            // Không throw exception vì notification không quan trọng bằng việc approve
        }
    }

    public function reject(Request $request, Order $order)
    {
        if ($order->return_status !== 'requested') {
            return redirect()->back()->with('error', 'Chỉ có thể từ chối yêu cầu đang ở trạng thái requested!');
        }

        $request->validate([
            'admin_return_note' => 'required|string|max:1000'
        ]);

        try {
            \DB::beginTransaction();

            $order->update([
                'return_status' => 'rejected',
                'admin_return_note' => $request->admin_return_note,
                'return_processed_at' => now()
            ]);

            // Gửi thông báo cho user
            $this->sendNotificationToUser($order, 'rejected');

            \DB::commit();

            return redirect()->route('admin.order.show', $order->id)
                ->with('success', 'Đã từ chối yêu cầu đổi hoàn hàng!');
        } catch (\Exception $e) {
            \DB::rollback();
            
            \Log::error('Error rejecting return request', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi từ chối yêu cầu: ' . $e->getMessage());
        }
    }

    public function complete(Request $request, Order $order)
    {
        if ($order->return_status !== 'approved') {
            return redirect()->back()->with('error', 'Chỉ có thể hoàn thành yêu cầu đã được chấp thuận!');
        }

        $request->validate([
            'admin_return_note' => 'nullable|string|max:1000'
        ]);

        try {
            \DB::beginTransaction();

            $order->update([
                'return_status' => 'completed',
                'admin_return_note' => $request->admin_return_note ?: $order->admin_return_note,
                'return_processed_at' => now()
            ]);

            // Gửi thông báo cho user
            $this->sendNotificationToUser($order, 'completed');

            \DB::commit();

            return redirect()->route('admin.order.show', $order->id)
                ->with('success', 'Đã hoàn thành xử lý đổi hoàn hàng!');
        } catch (\Exception $e) {
            \DB::rollback();
            
            \Log::error('Error completing return request', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hoàn thành yêu cầu: ' . $e->getMessage());
        }
    }

    public function statistics()
    {
        $stats = [
            'total_requests' => Order::where('return_status', '!=', 'none')->count(),
            'pending_requests' => Order::where('return_status', 'requested')->count(),
            'approved_requests' => Order::where('return_status', 'approved')->count(),
            'rejected_requests' => Order::where('return_status', 'rejected')->count(),
            'completed_requests' => Order::where('return_status', 'completed')->count(),
            'total_refund_amount' => Order::where('return_status', 'completed')
                ->whereNotNull('return_amount')
                ->sum('return_amount')
        ];

        $recentReturns = Order::where('return_status', '!=', 'none')
            ->with(['user'])
            ->orderBy('return_requested_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.return-exchange.statistics', compact('stats', 'recentReturns'));
    }
}
