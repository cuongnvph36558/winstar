<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderStatusUpdated;
use App\Events\NewOrderPlaced;
use App\Events\OrderCancelled;
use App\Events\OrderReceivedConfirmed;
use App\Notifications\OrderCancelledNotification;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\VnpayTransaction;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CouponService;
use App\Services\PaymentService;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $couponService;
    protected $paymentService;
    protected $pointService;

    public function __construct(CouponService $couponService, PaymentService $paymentService, PointService $pointService)
    {
        $this->couponService = $couponService;
        $this->paymentService = $paymentService;
        $this->pointService = $pointService;
    }

    public function checkout()
    {
        $user = Auth::user();
        
        // Check if this is a buy now checkout (temp cart)
        $tempCartId = session('temp_cart_id');
        
        if ($tempCartId) {
            // For buy now, find the specific cart by ID
            $cart = Cart::where('id', $tempCartId)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$cart) {
                // Clear invalid session and redirect to cart
                session()->forget('temp_cart_id');
                return redirect()->route('client.cart')->with('error', 'Phiên mua hàng không hợp lệ!');
            }
        } else {
            // Normal cart checkout - find any cart for this user
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
            }
        }

        $cartItems = CartDetail::with(['product', 'variant.color', 'variant.storage'])
            ->where('cart_id', $cart->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $shipping = 30000;
        
        $couponDiscount = session('discount', 0);
        $couponCode = session('coupon_code');
        $pointsUsed = session('points_used', 0);
        $pointsValue = session('points_value', 0);
        
        // Tính tổng tiền sau khi trừ giảm giá
        $total_before_points = $subtotal + $shipping - $couponDiscount;
        
        // Tính tổng tiền cuối cùng sau khi trừ điểm
        if ($pointsValue >= $total_before_points) {
            $total = 0;
        } else {
            $total = $total_before_points - $pointsValue;
        }

        // Lấy thông tin địa chỉ người dùng
        $userAddress = [
            'city' => $user->city,
            'district' => $user->district,
            'ward' => $user->ward,
            'address' => $user->address,
            'phone' => $user->getRealPhone()
        ];

        $availableCoupons = Coupon::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('min_order_value', '<=', $subtotal)
            ->where('exchange_points', 0)
            ->orderBy('discount_value', 'desc')
            ->get();

        $allCoupons = collect();

        // Lấy thông tin điểm của user
        $userPoints = $user->point;
        $availablePoints = $userPoints ? $userPoints->total_points : 0;
        $pointsValue = $this->pointService->calculatePointsValue($availablePoints);
        $maxPointsForOrder = $this->pointService->calculatePointsNeeded($total); // Tối đa 100% đơn hàng

        return view('client.cart-checkout.checkout', compact(
            'cartItems', 
            'subtotal', 
            'shipping', 
            'total', 
            'couponDiscount', 
            'couponCode', 
            'availableCoupons', 
            'allCoupons', 
            'userAddress',
            'availablePoints',
            'pointsValue',
            'maxPointsForOrder'
        ));
    }

    public function checkoutSelected(Request $request)
    {
        $user = Auth::user();
        $selectedCartIds = $request->input('selected_items', []);
        
        if (empty($selectedCartIds)) {
            return redirect()->route('client.cart')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
        }

        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
        }

        $cartItems = CartDetail::with(['product', 'variant.color', 'variant.storage'])
            ->where('cart_id', $cart->id)
            ->whereIn('id', $selectedCartIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart')->with('error', 'Không tìm thấy sản phẩm được chọn!');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $shipping = 30000;
        
        $couponDiscount = session('discount', 0);
        $couponCode = session('coupon_code');
        $pointsUsed = session('points_used', 0);
        $pointsValue = session('points_value', 0);
        
        // Tính tổng tiền sau khi trừ giảm giá
        $total_before_points = $subtotal + $shipping - $couponDiscount;
        
        // Tính tổng tiền cuối cùng sau khi trừ điểm
        if ($pointsValue >= $total_before_points) {
            $total = 0;
        } else {
            $total = $total_before_points - $pointsValue;
        }

        $availableCoupons = Coupon::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('min_order_value', '<=', $subtotal)
            ->where('exchange_points', 0)
            ->orderBy('discount_value', 'desc')
            ->get();

        $allCoupons = collect();

        session(['selected_cart_items' => $selectedCartIds]);

        // Lấy thông tin địa chỉ người dùng
        $userAddress = [
            'city' => $user->city,
            'district' => $user->district,
            'ward' => $user->ward,
            'address' => $user->address,
            'phone' => $user->getRealPhone()
        ];

        // Lấy thông tin điểm của user
        $userPoints = $user->point;
        $availablePoints = $userPoints ? $userPoints->total_points : 0;
        $pointsValue = $this->pointService->calculatePointsValue($availablePoints);
        $maxPointsForOrder = $this->pointService->calculatePointsNeeded($total); // Tối đa 100% đơn hàng

        return view('client.cart-checkout.checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'couponDiscount', 'couponCode', 'availableCoupons', 'allCoupons', 'userAddress', 'availablePoints', 'pointsValue', 'maxPointsForOrder'));
    }

    public function placeOrder(Request $request)
    {
        $validationRules = array_merge([
            'receiver_name' => 'required|string|max:255',
            'billing_city' => 'required|string',
            'billing_district' => 'required|string',
            'billing_ward' => 'required|string',
            'billing_address' => 'required|string',
            'billing_phone' => 'required|regex:/^[0-9]{10}$/',
            'description' => 'nullable|string|max:1000',
        ], PaymentHelper::getValidationRules());

        $request->validate($validationRules, [
            'receiver_name.required' => 'Vui lòng nhập tên người nhận',
            'billing_city.required' => 'Vui lòng chọn tỉnh/thành phố',
            'billing_district.required' => 'Vui lòng chọn quận/huyện',
            'billing_ward.required' => 'Vui lòng chọn phường/xã',
            'billing_address.required' => 'Vui lòng nhập địa chỉ chi tiết',
            'billing_phone.required' => 'Vui lòng nhập số điện thoại',
            'billing_phone.regex' => 'Số điện thoại phải có 10 chữ số',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // Get cart ID from form or session
            $cartId = $request->input('cart_id');
            $tempCartId = session('temp_cart_id');
            
            if ($tempCartId) {
                // Use temp cart ID from session
                $cart = Cart::where('id', $tempCartId)
                    ->where('user_id', $user->id)
                    ->first();
                    
                if (!$cart) {
                    return redirect()->route('client.cart')->with('error', 'Phiên mua hàng không hợp lệ!');
                }
            } elseif ($cartId) {
                // Use cart ID from form
                $cart = Cart::where('id', $cartId)
                    ->where('user_id', $user->id)
                    ->first();
                    
                if (!$cart) {
                    return redirect()->route('client.cart')->with('error', 'Giỏ hàng không hợp lệ!');
                }
            } else {
                // Fallback: find any cart for this user
                $cart = Cart::where('user_id', $user->id)->first();

                if (!$cart) {
                    return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
                }
            }

            // Get cart items
            $cartItemIds = $request->input('cart_item_ids', []);
            $selectedCartItems = session('selected_cart_items', []);

            if (!empty($cartItemIds)) {
                // Use cart item IDs from form
                $cartItems = CartDetail::with(['product', 'variant.color', 'variant.storage'])
                    ->where('cart_id', $cart->id)
                    ->whereIn('id', $cartItemIds)
                    ->get();
            } elseif (!empty($selectedCartItems)) {
                // Use selected cart items from session
                $cartItems = CartDetail::with(['product', 'variant.color', 'variant.storage'])
                    ->where('cart_id', $cart->id)
                    ->whereIn('id', $selectedCartItems)
                    ->get();
            } else {
                // Get all cart items
                $cartItems = CartDetail::with(['product', 'variant.color', 'variant.storage'])
                    ->where('cart_id', $cart->id)
                    ->get();
            }

            if ($cartItems->isEmpty()) {
                return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $shipping = 30000;
            $couponId = null;
            $discountAmount = 0;
            $pointsUsed = 0;
            $pointsValue = 0;

            $couponCode = session('coupon_code');
            $discountAmount = session('discount', 0);
            $pointsUsed = session('points_used', 0);
            
            // Tính toán giá trị điểm đã sử dụng (1 điểm = 1 VND)
            $pointsValue = $pointsUsed; // 1 điểm = 1 VND

            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $couponId = $coupon->id;
                }
            }

            // Tính tổng tiền sau khi trừ giảm giá và điểm
            $total_before_points = $subtotal + $shipping - $discountAmount;
            
            // Nếu điểm sử dụng >= tổng tiền trước điểm, thì tổng tiền = 0
            if ($pointsValue >= $total_before_points) {
                $total = 0;
                $pointsValue = $total_before_points; // Chỉ sử dụng đúng số điểm cần thiết
                $pointsUsed = $total_before_points; // Cập nhật số điểm thực tế đã sử dụng
            } else {
                $total = $total_before_points - $pointsValue;
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'code_order' => 'WS' . time() . rand(1000, 9999),
                'receiver_name' => $request->receiver_name,
                'billing_city' => $request->billing_city,
                'billing_district' => $request->billing_district,
                'billing_ward' => $request->billing_ward,
                'billing_address' => $request->billing_address,
                'phone' => $request->billing_phone,
                'description' => $request->description,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_status' => 'pending',
                'coupon_id' => $couponId,
                'discount_amount' => $discountAmount,
                'points_used' => $pointsUsed,
                'points_value' => $pointsValue,
                'point_voucher_code' => $pointsUsed > 0 ? 'POINTS_' . $pointsUsed : null,
                'stock_reserved' => false, // Chưa đặt trước kho
            ]);

            // Handle coupon
            if ($couponId) {
                \App\Models\CouponUser::create([
                    'coupon_id' => $couponId,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'used_at' => now()
                ]);
                
                $coupon = Coupon::find($couponId);
                if ($coupon) {
                    $coupon->increment('usage_count');
                }
            }

            // Create order details and reserve stock
            $stockService = app(\App\Services\StockService::class);
            $stockErrors = [];

            foreach ($cartItems as $item) {
                $lineTotal = $item->price * $item->quantity;
                $productName = $item->product->name;
                
                if ($item->variant) {
                    $variantInfo = [];
                    if ($item->variant->color) {
                        $variantInfo[] = $item->variant->color->name;
                    }
                    if ($item->variant->storage) {
                        $variantInfo[] = $item->variant->storage->name;
                    }
                    if (!empty($variantInfo)) {
                        $productName .= ' - ' . implode(', ', $variantInfo);
                    }
                }

                // Đặt trước kho
                $stockResult = $stockService->reserveStock(
                    $item->product_id,
                    $item->variant_id,
                    $item->quantity
                );

                if (!$stockResult['success']) {
                    $stockErrors[] = "Sản phẩm '{$productName}': {$stockResult['message']}";
                }
                
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $lineTotal,
                    'status' => 'pending',
                    'product_name' => $productName,
                ]);
            }

            // Nếu có lỗi stock, rollback và trả về lỗi
            if (!empty($stockErrors)) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Không thể đặt hàng: ' . implode('; ', $stockErrors))
                    ->withInput();
            }

            // Đánh dấu đã đặt trước kho
            $order->update(['stock_reserved' => true]);

            // Handle points
            if ($pointsUsed > 0) {
                $point = $user->point;
                if ($point) {
                    $point->update([
                        'total_points' => $point->total_points - $pointsUsed,
                        'used_points' => $point->used_points + $pointsUsed,
                    ]);
                }
            }

            // Try to broadcast event
            try {
                event(new NewOrderPlaced($order));
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast NewOrderPlaced event: ' . $e->getMessage());
            }

            // Clean up cart
            if ($tempCartId) {
                $cart->cartDetails()->delete();
                $cart->delete();
                session()->forget('temp_cart_id');
            } else {
                $selectedCartItems = session('selected_cart_items', []);

                if (!empty($selectedCartItems)) {
                    $cart->cartDetails()->whereIn('id', $selectedCartItems)->delete();
                    if ($cart->cartDetails()->count() === 0) {
                        $cart->delete();
                    }
                } else {
                    $cart->cartDetails()->delete();
                    $cart->delete();
                }

                session()->forget('selected_cart_items');
            }

            session()->forget('coupon_code');
            session()->forget(['points_used', 'points_value']);

            DB::commit();

            // Process payment method
            if ($request->payment_method === 'vnpay') {
                // Xử lý thanh toán VNPay
                $paymentResult = $this->paymentService->processVNPay($order);
                
                if ($paymentResult['success']) {
                    return redirect($paymentResult['redirect_url']);
                } else {
                    return redirect()->back()
                        ->with('error', $paymentResult['message'])
                        ->withInput();
                }
            } else {
                // COD payment
                $this->processCOD($order);
                
                return redirect()->route('client.order.show', ['order' => $order->id])
                    ->with('success', 'Đặt hàng thành công! Đơn hàng của bạn đã được xử lý và đang chờ xác nhận.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Kiểm tra xem có phải lỗi stock không
            if (strpos($e->getMessage(), 'vừa có người đặt trước') !== false || 
                strpos($e->getMessage(), 'hết hàng') !== false) {
                return redirect()->route('client.cart')
                    ->with('error', $e->getMessage())
                    ->with('toast_type', 'error')
                    ->with('toast_title', 'Không thể đặt hàng');
            }
            
            // Kiểm tra xem có phải lỗi validation không
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()
                    ->withErrors($e->validator)
                    ->withInput();
            }
            
            return redirect()->back()
                ->with('error', 'Đã có lỗi xảy ra khi đặt hàng. Vui lòng thử lại sau.')
                ->withInput();
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        $user = Auth::user();
        
        // Check if this is a buy now checkout (temp cart)
        $tempCartId = session('temp_cart_id');
        if ($tempCartId) {
            $cart = Cart::where('id', $tempCartId)
                ->where('user_id', $user->id)
                ->first();
        } else {
            // Normal cart checkout
            $cart = Cart::where('user_id', $user->id)->first();
        }

        if (!$cart || $cart->cartDetails->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống!'
            ]);
        }

        $subtotal = $cart->cartDetails()->selectRaw('SUM(price * quantity) as subtotal')->value('subtotal') ?? 0;

        $result = $this->couponService->validateAndCalculateDiscount(
            $request->coupon_code,
            $subtotal,
            $user
        );

        if ($result['valid']) {
            session()->put('coupon_code', $request->coupon_code);
            session()->put('discount', $result['discount']);
            session()->save();

            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công! Giảm ' . number_format($result['discount'], 0, ',', '.') . 'đ',
                'discount' => $result['discount'],
                'coupon_code' => $request->coupon_code,
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'shipping' => '30,000',
                'total' => number_format($subtotal + 30000 - $result['discount'], 0, ',', '.')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget(['coupon_code', 'discount']);

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa mã giảm giá!'
        ]);
    }

    /**
     * Áp dụng điểm để giảm giá đơn hàng
     */
    public function applyPoints(Request $request)
    {
        $request->validate([
            'points_to_use' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $pointsToUse = $request->points_to_use;
        
        // Check if this is a buy now checkout (temp cart)
        $tempCartId = session('temp_cart_id');
        if ($tempCartId) {
            $cart = Cart::where('id', $tempCartId)
                ->where('user_id', $user->id)
                ->first();
        } else {
            // Normal cart checkout
            $cart = Cart::where('user_id', $user->id)->first();
        }

        if (!$cart || $cart->cartDetails->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống!'
            ]);
        }

        $subtotal = $cart->cartDetails()->selectRaw('SUM(price * quantity) as subtotal')->value('subtotal') ?? 0;
        $shipping = 30000;
        $couponDiscount = session('discount', 0);
        
        // Tính tổng tiền sau khi trừ giảm giá
        $orderTotal = $subtotal + $shipping - $couponDiscount;

        $result = $this->pointService->usePointsForOrder($user, $pointsToUse, $orderTotal);

        if ($result['success']) {
            // Lưu thông tin điểm đã sử dụng vào session
            session()->put('points_used', $pointsToUse);
            session()->put('points_value', $result['points_value']);
            session()->save();

            // Tính tổng tiền cuối cùng
            $finalTotal = max(0, $orderTotal - $result['points_value']);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'points_used' => $pointsToUse,
                'points_value' => $result['points_value'],
                'remaining_points' => $result['remaining_points'],
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'shipping' => '30,000',
                'total' => number_format($finalTotal, 0, ',', '.')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }

    /**
     * Xóa điểm đã áp dụng
     */
    public function removePoints(Request $request)
    {
        $user = Auth::user();
        $pointsUsed = session('points_used', 0);
        
        if ($pointsUsed > 0) {
            // Hoàn trả điểm
            $point = $user->point;
            if ($point) {
                $point->update([
                    'total_points' => $point->total_points + $pointsUsed,
                    'used_points' => $point->used_points - $pointsUsed,
                ]);
            }
        }

        session()->forget(['points_used', 'points_value']);

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa điểm đã áp dụng!'
        ]);
    }



    public function index()
    {
        $orders = Order::with(['orderDetails.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.order.list', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderDetails.product.category', 'orderDetails.variant.color', 'orderDetails.variant.storage', 'coupon', 'user']);

        return view('client.order.show', compact('order'));
    }

    public function getStatus(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Calculate order totals
        $subtotal = $order->orderDetails->sum(function($detail) {
            return $detail->price * $detail->quantity;
        });
        
        $discount_amount = $order->discount_amount ?? 0;
        $shipping_fee = $order->shipping_fee ?? 30000;
        $total_amount = $subtotal - $discount_amount + $shipping_fee;

        // Set headers for fast response
        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'subtotal' => $subtotal,
            'discount_amount' => $discount_amount,
            'shipping_fee' => $shipping_fee,
            'total_amount' => $total_amount,
            'updated_at' => $order->updated_at->format('Y-m-d H:i:s')
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }



    public function cancel(Request $request, Order $order)
    {
        \Log::info('Cancel order request received', [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'order_user_id' => $order->user_id,
            'order_status' => $order->status,
            'payment_status' => $order->payment_status
        ]);

        if ($order->user_id !== Auth::id()) {
            \Log::warning('Unauthorized cancel order attempt', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'order_user_id' => $order->user_id
            ]);
            abort(403);
        }

        if ($order->status !== 'pending' || $order->payment_status !== 'pending') {
            \Log::warning('Invalid order status for cancellation', [
                'order_id' => $order->id,
                'status' => $order->status,
                'payment_status' => $order->payment_status
            ]);
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng này!');
        }

        // Validate cancellation reason
        $request->validate([
            'cancellation_reason' => 'required|string|min:10|max:500'
        ], [
            'cancellation_reason.required' => 'Vui lòng nhập lý do hủy đơn hàng',
            'cancellation_reason.min' => 'Lý do hủy đơn hàng phải có ít nhất 10 ký tự',
            'cancellation_reason.max' => 'Lý do hủy đơn hàng không được quá 500 ký tự'
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $order->status = 'cancelled';
            $order->payment_status = 'cancelled';
            $order->cancellation_reason = $request->cancellation_reason;
            $order->cancelled_at = now();
            $order->save();

            // Hoàn lại số lượng sản phẩm (chỉ khi đã trừ kho trước đó - tức là đã được giao)
            if ($oldStatus === 'delivered' || $oldStatus === 'received' || $oldStatus === 'completed') {
                foreach ($order->orderDetails as $detail) {
                    if ($detail->variant) {
                        $detail->variant->increment('stock_quantity', $detail->quantity);
                    } else {
                        $detail->product->increment('stock_quantity', $detail->quantity);
                    }
                }
            }
            // Hoàn lại kho đã đặt trước nếu đã đặt trước
            elseif ($order->stock_reserved) {
                $stockService = app(\App\Services\StockService::class);
                foreach ($order->orderDetails as $detail) {
                    $stockService->releaseReservedStock(
                        $detail->product_id,
                        $detail->variant_id,
                        $detail->quantity
                    );
                }
                \Log::info("Đã hoàn lại kho đặt trước cho đơn hàng #{$order->code_order} khi khách hàng hủy");
            }

            if ($order->coupon_id) {
                \App\Models\CouponUser::where('order_id', $order->id)->delete();
            }

            try {
                event(new OrderStatusUpdated($order, $oldStatus, $order->status));
                // Dispatch order cancelled event for admin notification
                event(new OrderCancelled($order, Auth::user(), $request->cancellation_reason));
                
                // Send notification to admin users
                $adminUsers = \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'admin');
                })->get();
                
                foreach ($adminUsers as $admin) {
                    $admin->notify(new OrderCancelledNotification($order, Auth::user(), $request->cancellation_reason));
                }
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast events or send notifications: ' . $e->getMessage());
            }

            DB::commit();

            \Log::info('Order cancelled successfully', [
                'order_id' => $order->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to cancel order', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng: ' . $e->getMessage());
        }
    }

    public function track(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderDetails.product', 'orderDetails.variant.color', 'orderDetails.variant.storage']);

        $statusHistory = [];

        if ($order->status === 'cancelled') {
            $statusHistory[] = [
                'status' => 'cancelled',
                'title' => 'Đơn hàng đã hủy',
                'description' => 'Đơn hàng đã bị hủy',
                'time' => $order->updated_at,
                'active' => true,
                'done' => true
            ];
        } else {
            $statusHistory[] = [
                'status' => $order->status,
                'title' => 'Đơn hàng ' . $order->status,
                'description' => 'Trạng thái hiện tại',
                'time' => $order->updated_at,
                'active' => true,
                'done' => true
            ];
        }

        return view('client.order.track', compact('order', 'statusHistory'));
    }

    public function momo_payment(Request $request)
    {
        return redirect()->route('client.checkout')
            ->with('error', 'Tính năng thanh toán MoMo đang được phát triển!');
    }

    public function momoIPN(Request $request)
    {
        return response()->json(['message' => 'Failed']);
    }

    private function processCOD($order)
    {
        $order->update([
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        // Stock đã được trừ trong placeOrder method, không cần trừ lại ở đây
        return ['success' => true];
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function vnpayReturn(Request $request)
    {
        try {
            // Xử lý callback từ VNPay
            $result = $this->paymentService->handleVNPayCallback($request->all());
            
            if ($result['success']) {
                $order = $result['order'];
                return redirect()->route('client.order.show', ['order' => $order->id])
                    ->with('success', 'Thanh toán VNPay thành công! Đơn hàng của bạn đã được xử lý.');
            } else {
                return redirect()->route('client.cart')
                    ->with('error', 'Thanh toán VNPay thất bại: ' . $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('VNPay return error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('client.cart')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán VNPay. Vui lòng liên hệ hỗ trợ.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipping,delivered,received,completed,cancelled'
        ]);
        $oldStatus = $order->status;
        $newStatus = $request->status;
        if ($newStatus === 'completed') {
            $order->payment_status = 'paid';
        }
        $order->status = $newStatus;
        $order->save();
        event(new OrderStatusUpdated($order, $oldStatus, $order->status));
        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function confirmReceived(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này!');
        }

        if ($order->status !== 'shipping' && $order->status !== 'delivered' && $order->status !== 'completed') {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ có thể xác nhận đã nhận hàng khi đơn hàng đang giao, đã giao hoặc đã hoàn thành!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Chỉ có thể xác nhận đã nhận hàng khi đơn hàng đang giao, đã giao hoặc đã hoàn thành!');
        }

        if ($order->is_received) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã xác nhận nhận hàng rồi!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Bạn đã xác nhận nhận hàng rồi!');
        }

        $order->is_received = true;
        $oldStatus = $order->status;

        if ($order->status === 'shipping') {
            $order->status = 'completed'; // Changed from 'received' to 'completed'
            
            // Cập nhật trạng thái thanh toán nếu chưa thanh toán
            if ($order->payment_status !== 'paid') {
                $order->payment_status = 'paid';
                Log::info("Auto-updated payment status to 'paid' for order #{$order->code_order} (ID: {$order->id}) when confirmed received");
            }

            try {
                Log::info("Broadcasting OrderReceivedConfirmed event for order #{$order->code_order} (ID: {$order->id})");
                
                // Broadcast specific event for order received confirmation
                event(new OrderReceivedConfirmed($order));
                Log::info("OrderReceivedConfirmed event broadcasted successfully");
                
                // Also broadcast status update event
                event(new OrderStatusUpdated($order, $oldStatus, $order->status, 'client', [
                    'action' => 'confirm_received',
                    'customer_name' => $order->receiver_name,
                    'customer_phone' => $order->phone,
                    'order_code' => $order->code_order,
                    'total_amount' => $order->total_amount,
                    'confirmation_time' => now()->toISOString()
                ]));
                Log::info("OrderStatusUpdated event broadcasted successfully");
                
                // Send immediate notification to admin users
                $adminUsers = \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'admin');
                })->get();
                
                foreach ($adminUsers as $admin) {
                    $admin->notify(new \App\Notifications\OrderNotification($order, 'Khách hàng đã xác nhận nhận hàng', 'completed'));
                }
                Log::info("Admin notifications sent successfully");
                
            } catch (\Exception $e) {
                Log::error('Failed to broadcast events: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                    'order_code' => $order->code_order,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } elseif ($order->status === 'delivered') {
            $order->status = 'completed';
            
            // Cập nhật trạng thái thanh toán nếu chưa thanh toán
            if ($order->payment_status !== 'paid') {
                $order->payment_status = 'paid';
                Log::info("Auto-updated payment status to 'paid' for order #{$order->code_order} (ID: {$order->id}) when confirmed received");
            }

            try {
                Log::info("Broadcasting OrderReceivedConfirmed event for order #{$order->code_order} (ID: {$order->id})");
                
                // Broadcast specific event for order received confirmation
                event(new OrderReceivedConfirmed($order));
                Log::info("OrderReceivedConfirmed event broadcasted successfully");
                
                // Also broadcast status update event
                event(new OrderStatusUpdated($order, $oldStatus, $order->status, 'client', [
                    'action' => 'confirm_received',
                    'customer_name' => $order->receiver_name,
                    'customer_phone' => $order->phone,
                    'order_code' => $order->code_order,
                    'total_amount' => $order->total_amount,
                    'confirmation_time' => now()->toISOString()
                ]));
                Log::info("OrderStatusUpdated event broadcasted successfully");
                
                // Send immediate notification to admin users
                $adminUsers = \App\Models\User::whereHas('roles', function($query) {
                    $query->where('name', 'admin');
                })->get();
                
                foreach ($adminUsers as $admin) {
                    $admin->notify(new \App\Notifications\OrderNotification($order, 'Khách hàng đã xác nhận nhận hàng', 'completed'));
                }
                Log::info("Admin notifications sent successfully");
                
            } catch (\Exception $e) {
                Log::error('Failed to broadcast events: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                    'order_code' => $order->code_order,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $order->save();

        // Check if request is AJAX
        if (request()->expectsJson()) {
            $message = '🎉 Cảm ơn bạn đã xác nhận nhận hàng! Đơn hàng #' . $order->code_order . ' đã hoàn thành. Hãy đánh giá sản phẩm để giúp chúng tôi cải thiện dịch vụ.';
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $order->status,
                'order_code' => $order->code_order
            ]);
        }

        return redirect()->route('client.order.list')
            ->with('success', '✅ Đã xác nhận nhận hàng thành công! Đơn hàng #' . $order->code_order . ' đã được cập nhật.');
    }

    public function buyNow(Request $request)
    {
        // Double-check authentication (backup for middleware)
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để tiếp tục!',
                'redirect_to_login' => true,
                'login_url' => route('login')
            ], 401);
        }

        $user = Auth::user();
        
        // Validate request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $variantId = $request->variant_id;

        // Get product and variant info
        $product = \App\Models\Product::findOrFail($productId);
        $variant = null;
        $price = $product->price;
        $promotionPrice = $product->promotion_price;

        if ($variantId) {
            $variant = \App\Models\ProductVariant::findOrFail($variantId);
            $price = $variant->price;
            $promotionPrice = $variant->promotion_price;
        }

        // Check stock
        $stockQuantity = $variant ? $variant->stock_quantity : $product->stock_quantity;
        if ($stockQuantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm trong kho không đủ!'
            ]);
        }

        // Create a temporary cart for buy now (separate from main cart)
        $tempCart = Cart::create([
            'user_id' => $user->id,
            'is_temp' => true // Mark as temporary cart
        ]);

        // Add only this product to temp cart
        CartDetail::create([
            'cart_id' => $tempCart->id,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity,
            'price' => $promotionPrice > 0 ? $promotionPrice : $price
        ]);

        // Store temp cart ID in session for checkout
        session(['temp_cart_id' => $tempCart->id]);

        // Clear any existing coupon session
        session()->forget(['discount', 'coupon_code']);

        return response()->json([
            'success' => true,
            'message' => 'Chuyển đến trang thanh toán!',
            'redirect_url' => route('client.checkout')
        ]);
    }

    public function updateShipping(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này!');
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể chỉnh sửa thông tin giao hàng khi đơn hàng đang chờ xử lý!');
        }

        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'billing_city' => 'required|string|max:255',
            'billing_district' => 'required|string|max:255',
            'billing_ward' => 'required|string|max:255',
            'billing_address' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000'
        ], [
            'receiver_name.required' => 'Vui lòng nhập tên người nhận',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'billing_city.required' => 'Vui lòng chọn tỉnh/thành phố',
            'billing_district.required' => 'Vui lòng nhập quận/huyện',
            'billing_ward.required' => 'Vui lòng nhập phường/xã',
            'billing_address.required' => 'Vui lòng nhập địa chỉ chi tiết'
        ]);

        try {
            $order->update([
                'receiver_name' => $request->receiver_name,
                'phone' => $request->phone,
                'billing_city' => $request->billing_city,
                'billing_district' => $request->billing_district,
                'billing_ward' => $request->billing_ward,
                'billing_address' => $request->billing_address,
                'description' => $request->description
            ]);

            return redirect()->back()->with('success', 'Cập nhật thông tin giao hàng thành công!');
        } catch (\Exception $e) {
            Log::error('Failed to update shipping info', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Không thể cập nhật thông tin giao hàng: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý thanh toán lại cho đơn hàng
     */
    public function retryPayment(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền thực hiện hành động này!'
                ], 403);
            }
            abort(403, 'Bạn không có quyền thực hiện hành động này!');
        }

        if ($order->payment_status === 'paid') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng đã được thanh toán!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Đơn hàng đã được thanh toán!');
        }

        if ($order->status === 'cancelled') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thanh toán đơn hàng đã bị hủy!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Không thể thanh toán đơn hàng đã bị hủy!');
        }

        if ($order->payment_method === 'cod') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thanh toán lại đơn hàng COD. Đơn hàng sẽ được thanh toán khi nhận hàng.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Không thể thanh toán lại đơn hàng COD. Đơn hàng sẽ được thanh toán khi nhận hàng.');
        }

        $request->validate([
            'payment_method' => 'required|in:vnpay'
        ]);

        try {
            if ($request->payment_method === 'vnpay') {
                $paymentResult = $this->paymentService->processVNPay($order);
                
                if ($paymentResult['success']) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Chuyển hướng đến cổng thanh toán VNPay',
                            'redirect_url' => $paymentResult['redirect_url']
                        ]);
                    }
                    return redirect($paymentResult['redirect_url']);
                } else {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $paymentResult['message']
                        ], 400);
                    }
                    return redirect()->back()->with('error', $paymentResult['message']);
                }
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phương thức thanh toán không hợp lệ!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ!');
        } catch (\Exception $e) {
            Log::error('Retry payment error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng thử lại sau.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng thử lại sau.');
        }
    }
} 