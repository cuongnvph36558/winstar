<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReturnExchangeController extends Controller
{
    public function requestReturn(Request $request, Order $order)
    {
        // kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403, 'bạn không có quyền thực hiện hành động này!');
        }

        // kiểm tra điều kiện để yêu cầu đổi hoàn hàng
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'chỉ có thể yêu cầu đổi hoàn hàng khi đơn hàng đã hoàn thành!');
        }

        if ($order->return_status !== 'none') {
            return redirect()->back()->with('error', 'đơn hàng này đã có yêu cầu đổi hoàn hàng!');
        }

        // kiểm tra thời gian (cho phép đổi hoàn trong vòng 7 ngày sau khi hoàn thành đơn hàng)
        $completedAt = $order->updated_at;
        $deadline = $completedAt->addDays(7);

        if (now()->gt($deadline)) {
            return redirect()->back()->with('error', 'thời gian yêu cầu đổi hoàn hàng đã hết hạn (7 ngày sau khi hoàn thành đơn hàng)!');
        }

        $request->validate([
            'return_products' => 'required|array|min:1',
            'return_products.*' => 'exists:order_details,id',
            'return_quantities' => 'required|array',
            'return_quantities.*' => 'integer|min:1',
            'return_reason' => 'required|string|max:500',
            'return_description' => 'nullable|string|max:1000',
            'return_method' => 'required|in:points,exchange',
            'return_video' => 'required|file|mimes:mp4,avi,mov|max:51200', // 50MB
            'return_order_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB
            'return_product_image' => 'required|image|mimes:jpeg,png,jpg|max:5120' // 5MB
        ]);

        // Upload video
        $videoPath = null;
        if ($request->hasFile('return_video')) {
            $video = $request->file('return_video');
            $videoName = 'return_video_' . $order->id . '_' . time() . '.' . $video->getClientOriginalExtension();
            $videoPath = $video->storeAs('returns/videos', $videoName, 'public');
        }

        // Upload order image
        $orderImagePath = null;
        if ($request->hasFile('return_order_image')) {
            $orderImage = $request->file('return_order_image');
            $orderImageName = 'return_order_' . $order->id . '_' . time() . '.' . $orderImage->getClientOriginalExtension();
            $orderImagePath = $orderImage->storeAs('returns/images', $orderImageName, 'public');
        }

        // Upload product image
        $productImagePath = null;
        if ($request->hasFile('return_product_image')) {
            $productImage = $request->file('return_product_image');
            $productImageName = 'return_product_' . $order->id . '_' . time() . '.' . $productImage->getClientOriginalExtension();
            $productImagePath = $productImage->storeAs('returns/images', $productImageName, 'public');
        }

        // Cập nhật thông tin hoàn hàng cho từng sản phẩm được chọn
        $returnProducts = $request->return_products;
        $returnQuantities = $request->return_quantities;
        $totalReturnAmount = 0;

        foreach ($returnProducts as $orderDetailId) {
            $orderDetail = $order->orderDetails()->find($orderDetailId);
            if ($orderDetail && isset($returnQuantities[$orderDetailId])) {
                $quantity = min($returnQuantities[$orderDetailId], $orderDetail->quantity);
                $returnAmount = $quantity * $orderDetail->price;
                
                $orderDetail->update([
                    'is_returned' => true,
                    'return_quantity' => $quantity,
                    'return_reason' => $request->return_reason,
                    'return_amount' => $returnAmount,
                    'returned_at' => now()
                ]);
                
                $totalReturnAmount += $returnAmount;
            }
        }

        $order->update([
            'return_status' => 'requested',
            'return_reason' => $request->return_reason,
            'return_description' => $request->return_description,
            'return_method' => $request->return_method,
            'return_video' => $videoPath,
            'return_order_image' => $orderImagePath,
            'return_product_image' => $productImagePath,
            'return_requested_at' => now(),
            'return_amount' => $totalReturnAmount
        ]);

        // Gửi thông báo cho admin về yêu cầu hoàn hàng mới
        $this->sendNotificationToAdmin($order);

        return redirect()->route('client.order.show', $order->id)
            ->with('success', 'Yêu cầu đổi hoàn hàng đã được gửi thành công! Chúng tôi sẽ xem xét và phản hồi trong thời gian sớm nhất.');
    }

    public function cancelReturnRequest(Order $order)
    {
        // kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403, 'bạn không có quyền thực hiện hành động này!');
        }

        // chỉ cho phép hủy khi đang ở trạng thái requested
        if ($order->return_status !== 'requested') {
            return redirect()->back()->with('error', 'không thể hủy yêu cầu đổi hoàn hàng ở trạng thái hiện tại!');
        }

        // Reset thông tin hoàn hàng cho tất cả sản phẩm
        $order->orderDetails()->update([
            'is_returned' => false,
            'return_quantity' => 0,
            'return_reason' => null,
            'return_amount' => null,
            'returned_at' => null
        ]);

        $order->update([
            'return_status' => 'none',
            'return_reason' => null,
            'return_description' => null,
            'return_method' => null,
            'return_requested_at' => null,
            'return_amount' => null
        ]);

        return redirect()->route('client.order.show', $order->id)
            ->with('success', 'Đã hủy yêu cầu đổi hoàn hàng thành công!');
    }

    public function showReturnForm(Order $order)
    {
        // kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403, 'bạn không có quyền truy cập trang này!');
        }

        return view('client.order.return-form', compact('order'));
    }

    public function index()
    {
        $returns = auth()->user()->orders()
            ->where('return_status', '!=', 'none')
            ->with(['orderDetails.product'])
            ->orderBy('return_requested_at', 'desc')
            ->paginate(10);

        return view('client.return-exchange.index', compact('returns'));
    }

    /**
     * Gửi thông báo cho admin về yêu cầu hoàn hàng mới
     */
    private function sendNotificationToAdmin($order)
    {
        try {
            // Gửi thông báo cho tất cả admin (có thể mở rộng sau)
            // Hiện tại chỉ log để admin có thể theo dõi
            \Log::info('New return request submitted', [
                'order_id' => $order->id,
                'user_id' => $order->user->id,
                'user_name' => $order->user->name,
                'return_method' => $order->return_method,
                'return_reason' => $order->return_reason,
                'message' => "Có yêu cầu đổi hoàn hàng mới cho đơn hàng #{$order->id} từ khách hàng {$order->user->name}"
            ]);

            // Có thể thêm logic gửi email cho admin ở đây
            // hoặc tạo notification trong database cho admin

        } catch (\Exception $e) {
            \Log::error('Error logging return request', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            // Không throw exception vì notification không quan trọng bằng việc tạo yêu cầu
        }
    }
}
