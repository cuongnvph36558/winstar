<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Events\OrderStatusUpdated;
use App\Events\OrderReceivedConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        // filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // filter by payment method
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // filter by payment status
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // filter by return status
        if ($request->filled('return_status') && $request->return_status !== 'all') {
            $query->where('return_status', $request->return_status);
        }

        // filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('total_amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('total_amount', '<=', $request->amount_max);
        }

        // search by order code, customer name, phone, address
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code_order', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('billing_address', 'like', "%{$search}%")
                  ->orWhere('billing_city', 'like', "%{$search}%")
                  ->orWhere('billing_district', 'like', "%{$search}%")
                  ->orWhere('billing_ward', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // filter by city
        if ($request->filled('city') && $request->city !== 'all') {
            $query->where('billing_city', $request->city);
        }

        // filter by district
        if ($request->filled('district') && $request->district !== 'all') {
            $query->where('billing_district', $request->district);
        }

        // filter by has coupon
        if ($request->filled('has_coupon')) {
            if ($request->has_coupon === 'yes') {
                $query->whereNotNull('coupon_id');
            } elseif ($request->has_coupon === 'no') {
                $query->whereNull('coupon_id');
            }
        }

        // filter by points usage
        if ($request->filled('points_used')) {
            if ($request->points_used === 'yes') {
                $query->where('points_used', '>', 0);
            } elseif ($request->points_used === 'no') {
                $query->where('points_used', 0);
            }
        }

        // sort orders
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'total_amount', 'status', 'payment_method'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        // handle export
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportOrders($query);
        }

        $orders = $query->paginate(10)->withQueryString();

        // get unique values for filter dropdowns
        $statuses = Order::distinct()->pluck('status')->filter();
        $paymentMethods = Order::distinct()->pluck('payment_method')->filter();
        $paymentStatuses = Order::distinct()->pluck('payment_status')->filter();
        $returnStatuses = Order::distinct()->pluck('return_status')->filter();
        $cities = Order::distinct()->pluck('billing_city')->filter();
        $districts = Order::distinct()->pluck('billing_district')->filter();

        return view('admin.orders.index', compact(
            'orders', 
            'statuses', 
            'paymentMethods', 
            'paymentStatuses', 
            'returnStatuses',
            'cities',
            'districts'
        ));
    }

    private function exportOrders($query)
    {
        $orders = $query->get();
        
        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // add utf-8 bom for excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // headers
            fputcsv($file, [
                'Mã đơn hàng',
                'Khách hàng',
                'Người nhận',
                'Số điện thoại',
                'Địa chỉ',
                'Tỉnh/Thành phố',
                'Quận/Huyện',
                'Phường/Xã',
                'Trạng thái đơn hàng',
                'Trạng thái thanh toán',
                'Phương thức thanh toán',
                'Tổng tiền (VNĐ)',
                'Điểm sử dụng',
                'Có mã giảm giá',
                'Trạng thái đổi/trả',
                'Ngày đặt hàng',
                'Ghi chú'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->code_order ?? '#' . $order->id,
                    $order->user ? $order->user->name : 'Khách vãng lai',
                    $order->receiver_name,
                    $order->phone,
                    $order->billing_address,
                    $order->billing_city,
                    $order->billing_district,
                    $order->billing_ward,
                    $this->getStatusText($order->status),
                    $this->getPaymentStatusText($order->payment_status),
                    ucfirst($order->payment_method),
                    number_format($order->total_amount, 0, ',', '.'),
                    $order->points_used > 0 ? number_format($order->points_used) : '0',
                    $order->coupon_id ? 'Có' : 'Không',
                    $this->getReturnStatusText($order->return_status ?? 'none'),
                    $order->created_at->format('d/m/Y H:i:s'),
                    $order->description
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getPaymentStatusText($status): string
    {
        return [
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'failed' => 'Thất bại',
            'refunded' => 'Hoàn tiền',
            'cancelled' => 'Đã hủy',
        ][$status ?? 'pending'] ?? ($status ?? 'Chờ thanh toán');
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

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'receiver_name' => $data['receiver_name'],
                'receiver_phone' => $data['receiver_phone'],
                'address' => $data['address'],
                'shipping_address' => $data['shipping_address'] ?? $data['address'],
                'payment_method' => $data['payment_method'],
                'total_amount' => 0,
                'status' => 'pending',
                'stock_reserved' => false, // Chưa đặt trước kho
            ]);

            $totalAmount = 0;
            $stockService = app(\App\Services\StockService::class);
            $stockErrors = [];

            foreach ($data['items'] as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $quantity = $item['quantity'];
                $price = $variant->price;
                $lineTotal = $price * $quantity;
                $productName = $variant->product->name;

                // Đặt trước kho
                $stockResult = $stockService->reserveStock(
                    $variant->product_id,
                    $variant->id,
                    $quantity
                );

                if (!$stockResult['success']) {
                    $stockErrors[] = "Sản phẩm '{$productName}': {$stockResult['message']}";
                }

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
            }

            // Nếu có lỗi stock, rollback và trả về lỗi
            if (!empty($stockErrors)) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Không thể tạo đơn hàng: ' . implode('; ', $stockErrors))
                    ->withInput();
            }

            // Cập nhật tổng tiền và đánh dấu đã đặt trước kho
            $order->update([
                'total_amount' => $totalAmount,
                'stock_reserved' => true
            ]);

            DB::commit();

            // Broadcast event for realtime updates
            try {
                event(new OrderStatusUpdated($order, null, $order->status));
            } catch (\Exception $e) {
                \Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
            }

            return redirect()->route('admin.order.index')->with('success', 'Tạo đơn hàng thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create order: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage())
                ->withInput();
        }
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
            'status' => 'required|string|in:pending,processing,shipping,delivered,completed,cancelled'
        ];

        if (strtolower($order->payment_method) !== 'cod') {
            $rules['payment_status'] = 'required|string|in:pending,paid,processing,completed,failed,refunded,cancelled';
        }

        $data = $request->validate($rules);

        $oldStatus = $order->status;
        $newStatus = $data['status'];

        // logic kiểm tra trạng thái đơn hàng - admin có thể chuyển đến delivered
        $statusFlow = [
            'pending' => 1,
            'processing' => 2,
            'shipping' => 3,
            'delivered' => 4,
            'completed' => 5,
            'cancelled' => 99 // cancelled luôn cho phép
        ];

        // khi trạng thái là 'delivered' hoặc 'completed', admin không thể cập nhật trạng thái nữa
        if ($oldStatus === 'delivered' || $oldStatus === 'completed') {
            return redirect()->back()->with('error', 'Đơn hàng đã được giao hàng hoặc đã hoàn thành. Admin không thể cập nhật trạng thái nữa!');
        }

        // Thêm thông báo khi chuyển sang trạng thái "delivered"
        if ($newStatus === 'delivered') {
            session()->flash('info', 'Đơn hàng đã được chuyển sang trạng thái "Đã giao hàng". Hệ thống sẽ tự động hoàn thành đơn hàng sau 1 ngày nếu khách hàng không xác nhận.');
        }

        // cho phép chuyển đến trạng thái cao hơn hoặc cancelled
        if (
            isset($statusFlow[$oldStatus], $statusFlow[$newStatus]) &&
            $newStatus !== 'cancelled' &&
            $statusFlow[$newStatus] < $statusFlow[$oldStatus]
        ) {
            return redirect()->back()->with('error', 'Không thể chuyển về trạng thái thấp hơn!');
        }

        // không cho phép hủy đơn khi đã giao hàng hoặc hoàn thành
        if (($oldStatus === 'delivered' || $oldStatus === 'completed') && $newStatus === 'cancelled') {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng đã được giao hàng hoặc đã hoàn thành!');
        }
        
        // admin không thể set trạng thái completed - chỉ khách hàng mới được xác nhận
        if ($newStatus === 'completed') {
            return redirect()->back()->with('error', 'Admin không thể hoàn thành đơn hàng! Chỉ khách hàng mới có thể xác nhận nhận hàng và hoàn thành đơn hàng.');
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
        
        // Tự động cập nhật trạng thái thanh toán khi chuyển sang "delivered"
        if ($newStatus === 'delivered') {
            \Log::info("🎯 Processing delivered status for order #{$order->code_order}", [
                'payment_method' => $order->payment_method,
                'old_payment_status' => $order->payment_status,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
            
            // Nếu đang chuyển sang delivered hoặc đã ở trạng thái delivered nhưng chưa thanh toán
            if ($oldStatus !== 'delivered' || $order->payment_status !== 'paid') {
                $order->payment_status = 'paid';
                \Log::info("🎯 Auto-updated payment status to 'paid' for order #{$order->code_order} when changing to delivered");
            }
        }

        // xử lý khi đơn hàng bị hủy
        if ($newStatus === 'cancelled') {
            $stockService = app(\App\Services\StockService::class);
            
            // Hoàn lại kho đã đặt trước nếu đã đặt trước
            if ($order->stock_reserved) {
                foreach ($order->orderDetails as $detail) {
                    $stockService->releaseReservedStock(
                        $detail->product_id,
                        $detail->variant_id,
                        $detail->quantity
                    );
                }
                \Log::info("Đã hoàn lại kho đặt trước cho đơn hàng #{$order->code_order} khi hủy");
            }
            // Hoàn lại kho thực tế nếu đã giao (trạng thái delivered, completed)
            elseif ($oldStatus === 'delivered' || $oldStatus === 'completed') {
                foreach ($order->orderDetails as $detail) {
                    if ($detail->variant) {
                        $detail->variant->increment('stock_quantity', $detail->quantity);
                    } else {
                        $detail->product->increment('stock_quantity', $detail->quantity);
                    }
                }
                \Log::info("Đã hoàn lại kho thực tế cho đơn hàng #{$order->code_order} khi hủy");
            }

            // HOÀN ĐIỂM cho đơn hàng đã thanh toán online
            if ($oldStatus === 'processing' && $order->payment_method === 'vnpay') {
                $pointService = app(\App\Services\PointService::class);
                
                // Tính số tiền cần hoàn điểm (tổng tiền đơn hàng)
                $refundAmount = $order->total_amount;
                $pointsToRefund = $pointService->calculatePointsNeeded($refundAmount);
                
                // Hoàn điểm cho khách hàng
                $refundSuccess = $pointService->refundPointsForOrder(
                    $order->user, 
                    $pointsToRefund, 
                    $order->code_order
                );
                
                if ($refundSuccess) {
                    // Force refresh điểm để đảm bảo dữ liệu mới được hiển thị
                    $pointService->forceRefreshUserPoints($order->user);
                    
                    \Log::info("Admin đã hoàn {$pointsToRefund} điểm cho đơn hàng #{$order->code_order} khi hủy", [
                        'order_id' => $order->id,
                        'refund_amount' => $refundAmount,
                        'points_refunded' => $pointsToRefund,
                        'admin_id' => Auth::id(),
                        'user_current_points' => $order->user->point ? $order->user->point->total_points : 0
                    ]);
                } else {
                    \Log::error("Admin không thể hoàn điểm cho đơn hàng #{$order->code_order}", [
                        'order_id' => $order->id,
                        'refund_amount' => $refundAmount,
                        'admin_id' => Auth::id()
                    ]);
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

        // Xử lý khi chuyển sang trạng thái "delivered" - đánh dấu đã giao
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            // Kho đã được đặt trước khi tạo đơn hàng, chỉ cần đánh dấu đã giao
            $order->stock_reserved = false; // Đã giao, không còn đặt trước
            \Log::info("Đã chuyển đơn hàng #{$order->code_order} sang trạng thái delivered - kho đã được đặt trước");
        }

        $order->save();

        // dispatch event for realtime updates
        try {
            event(new OrderStatusUpdated($order, $oldStatus, $order->status));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
        }

        // return json response for ajax requests
        if ($request->ajax() || $request->has('_ajax')) {
            $message = 'Cập nhật trạng thái đơn hàng thành công.';
            if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                $message .= ' Trạng thái thanh toán đã được tự động cập nhật thành "Đã thanh toán".';
            }
            // Thông báo hoàn điểm nếu hủy đơn hàng VNPay
            if ($newStatus === 'cancelled' && $oldStatus === 'processing' && $order->payment_method === 'vnpay') {
                $refundAmount = $order->total_amount;
                $pointsToRefund = app(\App\Services\PointService::class)->calculatePointsNeeded($refundAmount);
                $message .= " Đã hoàn {$pointsToRefund} điểm cho khách hàng (tương đương {$refundAmount} VND).";
            }
            
            $responseData = [
                'success' => true,
                'message' => $message,
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'status_text' => $this->getStatusText($order->status),
                    'payment_status' => $order->payment_status,
                    'payment_status_text' => $this->getPaymentStatusText($order->payment_status)
                ]
            ];
            
            \Log::info("🎯 AJAX response for order #{$order->code_order}", $responseData);
            
            return response()->json($responseData);
        }

        // redirect for non-ajax requests
        $successMessage = 'Cập nhật trạng thái đơn hàng thành công.';
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $successMessage .= ' Trạng thái thanh toán đã được tự động cập nhật thành "Đã thanh toán".';
        }
        // Thông báo hoàn điểm nếu hủy đơn hàng VNPay
        if ($newStatus === 'cancelled' && $oldStatus === 'processing' && $order->payment_method === 'vnpay') {
            $refundAmount = $order->total_amount;
            $pointsToRefund = app(\App\Services\PointService::class)->calculatePointsNeeded($refundAmount);
            $successMessage .= " Đã hoàn {$pointsToRefund} điểm cho khách hàng (tương đương {$refundAmount} VND).";
        }
        return redirect()->route('admin.order.edit', $order->id)->with('success', $successMessage);
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

    /**
     * Sửa trạng thái thanh toán cho các đơn hàng đã delivered
     */
    public function fixDeliveredPaymentStatus()
    {
        $orders = Order::where('status', 'delivered')
                      ->where('payment_status', '!=', 'paid')
                      ->get();

        $fixedCount = 0;
        foreach ($orders as $order) {
            $order->payment_status = 'paid';
            $order->save();
            $fixedCount++;
            \Log::info("Đã sửa trạng thái thanh toán cho đơn hàng #{$order->code_order} từ {$order->payment_status} thành paid");
        }

        return redirect()->route('admin.order.index')->with('success', "Đã sửa trạng thái thanh toán cho {$fixedCount} đơn hàng đã delivered.");
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
            'status' => 'required|string|in:pending,processing,shipping,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Xử lý khi đơn hàng bị hủy
        if ($newStatus === 'cancelled') {
            $stockService = app(\App\Services\StockService::class);
            
            // Hoàn lại kho đã đặt trước nếu đã đặt trước
            if ($order->stock_reserved) {
                foreach ($order->orderDetails as $detail) {
                    $stockService->releaseReservedStock(
                        $detail->product_id,
                        $detail->variant_id,
                        $detail->quantity
                    );
                }
                \Log::info("Đã hoàn lại kho đặt trước cho đơn hàng #{$order->code_order} khi hủy");
            }
            // Hoàn lại kho thực tế nếu đã giao (trạng thái delivered, completed)
            elseif ($oldStatus === 'delivered' || $oldStatus === 'completed') {
                foreach ($order->orderDetails as $detail) {
                    if ($detail->variant) {
                        $detail->variant->increment('stock_quantity', $detail->quantity);
                    } else {
                        $detail->product->increment('stock_quantity', $detail->quantity);
                    }
                }
                \Log::info("Đã hoàn lại kho thực tế cho đơn hàng #{$order->code_order} khi hủy");
            }
        }

        // Xử lý khi chuyển sang trạng thái "delivered" - đánh dấu đã giao
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            // Kho đã được đặt trước khi tạo đơn hàng, chỉ cần đánh dấu đã giao
            $order->stock_reserved = false; // Đã giao, không còn đặt trước
            \Log::info("Đã chuyển đơn hàng #{$order->code_order} sang trạng thái delivered - kho đã được đặt trước");
        }

        $order->status = $newStatus;
        
        // Tự động cập nhật trạng thái thanh toán khi đơn hàng được giao
        $paymentStatusUpdated = false;
        if ($newStatus === 'delivered' && $order->payment_status !== 'paid') {
            $order->payment_status = 'paid';
            $paymentStatusUpdated = true;
            \Log::info("Auto-updated payment status to 'paid' for order #{$order->code_order} (ID: {$order->id})");
        }
        
        $order->save();

        // Dispatch event for realtime updates
        try {
            event(new OrderStatusUpdated($order, $oldStatus, $order->status));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
        }

        // Thông báo thành công với thông tin về cập nhật trạng thái thanh toán
        $successMessage = 'Cập nhật trạng thái đơn hàng thành công.';
        if ($paymentStatusUpdated) {
            $successMessage .= ' Trạng thái thanh toán đã được tự động cập nhật thành "Đã thanh toán".';
        }

        return redirect()->back()->with('success', $successMessage);
    }

    private function getStatusText($status): string
    {
        return [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ][$status] ?? $status;
    }



    // Return/Exchange Management Methods
    public function approveReturn(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->return_status !== 'requested') {
            return redirect()->back()->with('error', 'Chỉ có thể chấp thuận yêu cầu đang ở trạng thái requested!');
        }

        $request->validate([
            'admin_return_note' => 'nullable|string|max:1000',
            'return_amount' => 'nullable|numeric|min:0|max:' . $order->total_amount
        ]);

        try {
            \DB::beginTransaction();

            $order->update([
                'return_status' => 'approved',
                'admin_return_note' => $request->admin_return_note,
                'return_amount' => $request->return_amount,
                'return_processed_at' => now()
            ]);

            // Nếu là đổi điểm/refund và có số điểm hoàn
            if (($order->return_method === 'points' || $order->return_method === 'refund') && $request->return_amount > 0) {
                $this->addPointsToUser($order->user, $request->return_amount, $order);
            }

            // Gửi thông báo cho user
            $this->sendNotificationToUser($order, 'approved');

            \DB::commit();

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

    public function rejectReturn(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
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

    public function completeReturn(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
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

    public function getReturnStatusText($status): string
    {
        return [
            'none' => 'Không có',
            'requested' => 'Chờ xử lý',
            'approved' => 'Đã chấp thuận',
            'rejected' => 'Đã từ chối',
            'completed' => 'Hoàn thành',
        ][$status] ?? $status;
    }

    public function getReturnMethodText($method): string
    {
        return [
            'refund' => 'Hoàn tiền',
            'exchange' => 'Đổi hàng',
            'credit' => 'Tín dụng',
        ][$method] ?? $method;
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
                ['total_points' => 0, 'used_points' => 0]
            );

            // Cộng điểm
            $pointRecord->total_points += $points;
            $pointRecord->save();

            // Tạo transaction log
            \App\Models\PointTransaction::create([
                'user_id' => $user->id,
                'points' => $points,
                'type' => 'earned',
                'description' => "Điểm hoàn từ yêu cầu đổi hoàn hàng đơn hàng #{$order->id}",
                'order_id' => $order->id,
                'balance_before' => $pointRecord->total_points - $points,
                'balance_after' => $pointRecord->total_points
            ]);

            \Log::info('Points added to user', [
                'user_id' => $user->id,
                'points_added' => $points,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding points to user', [
                'user_id' => $user->id,
                'points' => $points,
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
}


