<?php

namespace App\Http\Controllers\Client;

use App\Events\OrderStatusUpdated;
use App\Events\NewOrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\MomoTransaction;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CouponService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $couponService;
    protected $paymentService;

    public function __construct(CouponService $couponService, PaymentService $paymentService)
    {
        $this->couponService = $couponService;
        $this->paymentService = $paymentService;
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
        $total = $subtotal + $shipping;

        $couponDiscount = session('discount', 0);
        $couponCode = session('coupon_code');
        
        if ($couponCode && $couponDiscount > 0) {
            $total -= $couponDiscount;
        }

        // Lấy thông tin địa chỉ người dùng
        $userAddress = [
            'city' => $user->city,
            'district' => $user->district,
            'ward' => $user->ward,
            'address' => $user->address,
            'phone' => $user->phone && $user->phone !== 'g1754230976x9b59a' ? $user->phone : ''
        ];

        $availableCoupons = Coupon::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('min_order_value', '<=', $subtotal)
            ->where('exchange_points', 0)
            ->orderBy('discount_value', 'desc')
            ->get();

        $allCoupons = collect();

        return view('client.cart-checkout.checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'couponDiscount', 'couponCode', 'availableCoupons', 'allCoupons', 'userAddress'));
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
        $total = $subtotal + $shipping;

        $couponDiscount = session('discount', 0);
        $couponCode = session('coupon_code');
        if ($couponCode && $couponDiscount > 0) {
            $total -= $couponDiscount;
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
            'phone' => $user->phone && $user->phone !== 'g1754230976x9b59a' ? $user->phone : ''
        ];

        return view('client.cart-checkout.checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'couponDiscount', 'couponCode', 'availableCoupons', 'allCoupons', 'userAddress'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'billing_city' => 'required|string',
            'billing_district' => 'required|string',
            'billing_ward' => 'required|string',
            'billing_address' => 'required|string',
            'billing_phone' => 'required|regex:/^[0-9]{10}$/',
            'payment_method' => 'required|in:cod,momo,vnpay',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // Check if this is a buy now checkout (temp cart)
            $tempCartId = session('temp_cart_id');
            if ($tempCartId) {
                $cart = Cart::where('id', $tempCartId)
                    ->where('user_id', $user->id)
                    ->first();
                    
                if (!$cart) {
                    return redirect()->route('client.cart')->with('error', 'Phiên mua hàng không hợp lệ!');
                }
            } else {
                // Normal cart checkout
                $cart = Cart::where('user_id', $user->id)->first();

                if (!$cart) {
                    return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
                }
            }

            $selectedCartItems = session('selected_cart_items', []);

            if (!empty($selectedCartItems)) {
                $cartItems = CartDetail::with(['product'])
                    ->where('cart_id', $cart->id)
                    ->whereIn('id', $selectedCartItems)
                    ->get();
            } else {
                $cartItems = CartDetail::with(['product'])
                    ->where('cart_id', $cart->id)
                    ->get();
            }



            if ($cartItems->isEmpty()) {
                return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
            }

            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $shipping = 30000;
            $couponId = null;
            $discountAmount = 0;

            $couponCode = session('coupon_code');
            $discountAmount = session('discount', 0);

            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $couponId = $coupon->id;
                }
            }

            $total = $subtotal + $shipping - $discountAmount;

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
                'discount_amount' => $discountAmount
            ]);

            if ($couponId) {
                \App\Models\CouponUser::create([
                    'coupon_id' => $couponId,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'used_at' => now()
                ]);
            }

            // Kiểm tra và trừ stock trước khi tạo order details
            $failedItems = [];
            $successItems = [];
            
            foreach ($cartItems as $item) {
                // Sử dụng StockService để trừ stock an toàn
                $stockService = app(\App\Services\StockService::class);
                $decrementResult = $stockService->safeDecrementStock(
                    $item->product_id,
                    $item->variant_id,
                    $item->quantity
                );
                
                if (!$decrementResult['success']) {
                    $failedItems[] = [
                        'product' => $item->product->name,
                        'variant' => $item->variant ? $item->variant->variant_name : null,
                        'message' => $decrementResult['message']
                    ];
                } else {
                    $successItems[] = $item;
                }
            }
            
            // Nếu có sản phẩm thất bại, rollback và báo lỗi
            if (!empty($failedItems)) {
                DB::rollBack();
                $errorMessage = "Một số sản phẩm không thể đặt hàng:\n";
                foreach ($failedItems as $failedItem) {
                    $productName = $failedItem['variant'] 
                        ? $failedItem['product'] . ' - ' . $failedItem['variant']
                        : $failedItem['product'];
                    $errorMessage .= "• {$productName}: {$failedItem['message']}\n";
                }
                throw new \Exception($errorMessage);
            }
            
            // Tạo order details cho các sản phẩm thành công
            foreach ($successItems as $item) {
                $lineTotal = $item->price * $item->quantity;
                $productName = $item->product->name;
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

            // Try to broadcast event, but don't fail if Pusher is not available
            try {
                event(new NewOrderPlaced($order));
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast NewOrderPlaced event: ' . $e->getMessage());
                // Don't fail the order if broadcasting fails
            }

            if (in_array($request->payment_method, ['momo', 'vnpay'])) {
                DB::commit();
                return redirect()->route('client.order.success', ['order' => $order->id])
                    ->with('success', 'Đặt hàng thành công!');
            } else {
                $this->processCOD($order);

                // Handle cart cleanup based on type
                if ($tempCartId) {
                    // For buy now (temp cart), always delete the entire temp cart
                    $cart->cartDetails()->delete();
                    $cart->delete();
                    session()->forget('temp_cart_id');
                } else {
                    // For normal cart checkout
                    $selectedCartItems = session('selected_cart_items', []);

                    if (!empty($selectedCartItems)) {
                        // Chỉ xóa những sản phẩm đã được đặt hàng
                        $cart->cartDetails()->whereIn('id', $selectedCartItems)->delete();
                        if ($cart->cartDetails()->count() === 0) {
                            $cart->delete();
                        }
                    } else {
                        // Nếu không có selected items, xóa tất cả (trường hợp mua toàn bộ giỏ hàng)
                        $cart->cartDetails()->delete();
                        $cart->delete();
                    }

                    session()->forget('selected_cart_items');
                }

                session()->forget('coupon_code');

                DB::commit();
            }

            return redirect()->route('client.order.success', ['order' => $order->id])
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement error: ' . $e->getMessage());
            
            // Kiểm tra xem có phải lỗi stock không
            if (strpos($e->getMessage(), 'vừa có người đặt trước') !== false || 
                strpos($e->getMessage(), 'hết hàng') !== false) {
                return redirect()->route('client.cart')
                    ->with('error', $e->getMessage())
                    ->with('toast_type', 'error')
                    ->with('toast_title', 'Không thể đặt hàng');
            }
            
            return redirect()->back()
                ->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage())
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

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load([
            'orderDetails.product',
            'orderDetails.variant.color',
            'orderDetails.variant.storage',
            'coupon'
        ]);

        $subtotal = $order->orderDetails()->get()->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $shipping = 30000;
        $discount = $order->discount_amount ?? 0;
        $total = $subtotal + $shipping - $discount;

        return view('client.cart-checkout.success', [
            'order' => $order,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'couponDiscount' => $discount,
            'total' => $total,
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

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending' || $order->payment_status !== 'pending') {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng này!');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $order->status = 'cancelled';
            $order->payment_status = 'cancelled';
            $order->save();

            foreach ($order->orderDetails as $detail) {
                if ($detail->variant) {
                    $detail->variant->increment('stock_quantity', $detail->quantity);
                } else {
                    $detail->product->increment('stock_quantity', $detail->quantity);
                }
            }

            if ($order->coupon_id) {
                \App\Models\CouponUser::where('order_id', $order->id)->delete();
            }

            try {
                event(new OrderStatusUpdated($order, $oldStatus, $order->status));
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
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

        foreach ($order->orderDetails as $detail) {
            if ($detail->variant) {
                $detail->variant->decrement('stock_quantity', $detail->quantity);
            } else {
                $detail->product->decrement('stock_quantity', $detail->quantity);
            }
        }

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
        return redirect()->route('client.checkout')
            ->with('error', 'Tính năng thanh toán VNPay đang được phát triển!');
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipping,received,completed,cancelled'
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

        if ($order->status !== 'shipping' && $order->status !== 'completed') {
            return redirect()->back()->with('error', 'Chỉ có thể xác nhận đã nhận hàng khi đơn hàng đang giao hoặc đã hoàn thành!');
        }

        if ($order->is_received) {
            return redirect()->back()->with('error', 'Bạn đã xác nhận nhận hàng rồi!');
        }

        $order->is_received = true;

        if ($order->status === 'shipping') {
            $oldStatus = $order->status;
            $order->status = 'completed';
            $order->payment_status = 'paid';

            try {
                event(new OrderStatusUpdated($order, $oldStatus, $order->status));
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
            }
        }

        $order->save();

        return redirect()->route('client.order.show', $order->id) . '?action=review&success=received';
    }

    public function buyNow(Request $request)
    {
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
} 