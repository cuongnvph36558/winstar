<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $variants = ProductVariant::all();
        return view('admin.orders.create', compact('variants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'shipping_address' => 'nullable|string',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'receiver_name' => $data['receiver_name'],
            'receiver_phone' => $data['receiver_phone'],
            'address' => $data['address'],
            'shipping_address' => $data['shipping_address'] ?? $data['address'],
            'payment_method' => $data['payment_method'],
            'total_amount' => 0,
            'status' => 'pending',
        ]);

        $totalAmount = 0;

        foreach ($data['items'] as $item) {
            $variant = ProductVariant::findOrFail($item['variant_id']);
            $quantity = $item['quantity'];
            $price = $variant->price;
            $lineTotal = $price * $quantity;
            $productName = $variant->product->name;

            $order->orderDetails()->create([
                'product_id' => $variant->product_id,
                'variant_id' => $variant->id,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $lineTotal,
                'status' => 'pending',
                'product_name' => $productName,
            ]);

            // Cộng dồn tổng tiền
            $totalAmount += $lineTotal;

            // Trừ kho ngay khi tạo đơn hàng
            $variant->decrement('stock_quantity', $quantity);
        }

        $order->update(['total_amount' => $totalAmount]);

        // Broadcast event for realtime updates
        try {
            event(new OrderStatusUpdated($order, null, $order->status));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
        }

        return redirect()->route('admin.order.index')->with('success', 'Tạo đơn hàng thành công.');
    }

    public function show($id)
    {
        $order = Order::with('orderDetails.variant')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $rules = [
            'status' => 'required|string|in:pending,processing,shipping,received,completed,cancelled'
        ];

        if (strtolower($order->payment_method) !== 'cod') {
            $rules['payment_status'] = 'required|string|in:pending,paid,processing,completed,failed,refunded,cancelled';
        }

        $data = $request->validate($rules);

        $oldStatus = $order->status;
        $newStatus = $data['status'];

        // logic kiểm tra trạng thái đơn hàng - admin có thể chuyển đến shipping
        $statusFlow = [
            'pending' => 1,
            'processing' => 2,
            'shipping' => 3,
            'received' => 4,
            'completed' => 5,
            'cancelled' => 99 // cancelled luôn cho phép
        ];

        // khi trạng thái là 'received' hoặc 'completed', admin không thể cập nhật trạng thái nữa
        if ($oldStatus === 'received' || $oldStatus === 'completed') {
            return redirect()->back()->with('error', 'Đơn hàng đã được khách hàng xác nhận nhận hàng hoặc đã hoàn thành. Admin không thể cập nhật trạng thái nữa!');
        }

        // cho phép chuyển đến trạng thái cao hơn hoặc cancelled
        if (
            isset($statusFlow[$oldStatus], $statusFlow[$newStatus]) &&
            $newStatus !== 'cancelled' &&
            $statusFlow[$newStatus] < $statusFlow[$oldStatus]
        ) {
            return redirect()->back()->with('error', 'Không thể chuyển về trạng thái thấp hơn!');
        }

        // không cho phép hủy đơn khi đã hoàn thành
        if ($oldStatus === 'completed' && $newStatus === 'cancelled') {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng đã hoàn thành!');
        }
        
        // admin không thể set trạng thái received hoặc completed - chỉ khách hàng mới được xác nhận
        if ($newStatus === 'received' || $newStatus === 'completed') {
            return redirect()->back()->with('error', 'Admin không thể xác nhận nhận hàng hoặc hoàn thành đơn hàng! Chỉ khách hàng mới có thể thực hiện các hành động này.');
        }



        // yêu cầu lý do hủy khi hủy đơn hàng
        if ($newStatus === 'cancelled') {
            if (empty($request->cancellation_reason)) {
                return redirect()->back()->with('error', 'Vui lòng nhập lý do hủy đơn hàng để thông báo cho khách hàng!');
            }
            
            // validate độ dài lý do hủy
            if (strlen($request->cancellation_reason) < 10) {
                return redirect()->back()->with('error', 'Lý do hủy đơn hàng phải có ít nhất 10 ký tự!');
            }
        }

        $order->status = $newStatus;

        // cập nhật payment_status nếu có
        if (isset($data['payment_status'])) {
            $order->payment_status = $data['payment_status'];
        }

        // xử lý khi đơn hàng bị hủy
        if ($newStatus === 'cancelled') {
            // hoàn lại số lượng sản phẩm
            foreach ($order->orderDetails as $detail) {
                if ($detail->variant) {
                    $detail->variant->increment('stock_quantity', $detail->quantity);
                } else {
                    $detail->product->increment('stock_quantity', $detail->quantity);
                }
            }

            // xóa record trong coupon_users nếu có sử dụng mã giảm giá
            if ($order->coupon_id) {
                \App\Models\CouponUser::where('order_id', $order->id)->delete();
            }
            
            // cập nhật thông tin hủy đơn hàng
            $order->cancelled_at = now();
            if ($request->has('cancellation_reason')) {
                $order->cancellation_reason = $request->cancellation_reason;
            }
        }

        $order->save();

        // dispatch event for realtime updates
        try {
            event(new OrderStatusUpdated($order, $oldStatus, $order->status));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
        }

        // return json response for ajax requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái đơn hàng thành công.',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'status_text' => $this->getStatusText($order->status)
                ]
            ]);
        }

        // redirect for non-ajax requests
        return redirect()->route('admin.order.edit', $order->id)->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }


    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Xóa record trong coupon_users nếu có sử dụng mã giảm giá
        if ($order->coupon_id) {
            \App\Models\CouponUser::where('order_id', $order->id)->delete();
        }
        
        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Đã xoá đơn hàng.');
    }

    public function trash()
    {
        $orders = Order::onlyTrashed()->with('user')->latest()->paginate(10);
        return view('admin.orders.trash', compact('orders'));
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('admin.order.trash')->with('success', 'Đã khôi phục đơn hàng.');
    }

    public function forceDelete($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        
        // Xóa record trong coupon_users nếu có sử dụng mã giảm giá
        if ($order->coupon_id) {
            \App\Models\CouponUser::where('order_id', $order->id)->delete();
        }
        
        $order->forceDelete();

        return redirect()->route('admin.order.trash')->with('success', 'Đã xóa vĩnh viễn đơn hàng.');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipping,received,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Xử lý khi đơn hàng bị hủy
        if ($newStatus === 'cancelled') {
            // Hoàn lại số lượng sản phẩm
            foreach ($order->orderDetails as $detail) {
                if ($detail->variant) {
                    $detail->variant->increment('stock_quantity', $detail->quantity);
                } else {
                    $detail->product->increment('stock_quantity', $detail->quantity);
                }
            }
        }

        $order->status = $newStatus;
        $order->save();

        // Dispatch event for realtime updates
        try {
            event(new OrderStatusUpdated($order, $oldStatus, $order->status));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    private function getStatusText($status): string
    {
        return [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang giao hàng',
            'received' => 'Đã nhận hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ][$status] ?? $status;
    }
}
