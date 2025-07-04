<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Notifications\OrderNotification;
use App\Services\CouponService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    /**
     * Hiển thị trang thanh toán
     */
    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        
        if (!$cart) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
        }

        $cartItems = CartDetail::with(['product', 'variant.color', 'variant.storage'])
            ->where('cart_id', $cart->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
        }

        // Tính toán giá trị đơn hàng
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $shipping = 30000; // Phí vận chuyển cố định
        $total = $subtotal + $shipping;

        // Lấy mã giảm giá từ session nếu có
        $couponDiscount = 0;
        $couponCode = session('coupon_code');
        if ($couponCode) {
            $couponResult = $this->couponService->validateAndCalculateDiscount($couponCode, $subtotal, Auth::user());
            if ($couponResult['valid']) {
                $couponDiscount = $couponResult['discount'];
                $total -= $couponDiscount;
            }
        }

        return view('client.cart-checkout.checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'couponDiscount', 'couponCode'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'billing_city' => 'required|string',
            'billing_district' => 'required|string',
            'billing_ward' => 'required|string',
            'billing_address' => 'required|string',
            'billing_phone' => 'required|regex:/^[0-9]{10}$/',
            'payment_method' => 'required|in:bank_transfer,cod,momo,vnpay,zalopay,paypal',
            'full_address' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
            }

            $cartItems = CartDetail::with(['product', 'variant'])
                ->where('cart_id', $cart->id)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống!');
            }

            // Tính tổng giá trị đơn hàng
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            
            $shipping = 30000;
            $total = $subtotal + $shipping;

            // Áp dụng mã giảm giá nếu có
            $couponId = null;
            if ($couponCode = session('coupon_code')) {
                $couponResult = $this->couponService->validateAndCalculateDiscount($couponCode, $subtotal, $user);
                if ($couponResult['valid']) {
                    $total -= $couponResult['discount'];
                    $couponId = $couponResult['coupon']->id;
                }
            }

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
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
                'coupon_id' => $couponId
            ]);

            // Tạo chi tiết đơn hàng
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);

                // Cập nhật số lượng sản phẩm
                if ($item->variant) {
                    $item->variant->decrement('stock_quantity', $item->quantity);
                } else {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            // Xử lý thanh toán MoMo
            if ($request->payment_method === 'momo') {
                // Commit transaction trước khi chuyển sang MoMo
                DB::commit();
                
                // Lưu thông tin đơn hàng vào session
                session([
                    'momo_payment_info' => [
                        'order_id' => $order->id,
                        'amount' => $total
                    ]
                ]);
                
                // Xóa giỏ hàng và mã giảm giá
                $cart->cartDetails()->delete();
                $cart->delete();
                session()->forget('coupon_code');
                
                // Chuyển sang trang thanh toán MoMo
                return $this->momo_payment($request);
            }

            // Xử lý các phương thức thanh toán khác
            $paymentResult = null;
            switch ($request->payment_method) {
                case 'bank_transfer':
                    $paymentResult = $this->processBankTransfer($order);
                    break;
                case 'vnpay':
                    $paymentResult = $this->paymentService->processVNPay($order);
                    break;
                case 'zalopay':
                    $paymentResult = $this->paymentService->processZaloPay($order);
                    break;
                case 'paypal':
                    $paymentResult = $this->paymentService->processPayPal($order);
                    break;
                case 'cod':
                    $paymentResult = $this->processCOD($order);
                    break;
                default:
                    throw new \Exception('Phương thức thanh toán không hợp lệ');
            }

            // Xóa giỏ hàng và mã giảm giá
            $cart->cartDetails()->delete();
            $cart->delete();
            session()->forget('coupon_code');

            // Gửi email thông báo
            try {
                $order->user->notify(new OrderNotification($order, 'placed'));
            } catch (\Exception $e) {
                Log::error('Error sending order notification: ' . $e->getMessage());
            }

            DB::commit();

            // Chuyển hướng dựa trên kết quả thanh toán
            if (isset($paymentResult['redirect_url'])) {
                return redirect()->away($paymentResult['redirect_url']);
            }

            return redirect()->route('client.order.success', ['order' => $order->id])
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị trang đặt hàng thành công
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Load các quan hệ cần thiết
        $order->load([
            'orderDetails.product',
            'orderDetails.variant.color',
            'orderDetails.variant.storage',
            'coupon'
        ]);

        return view('client.cart-checkout.success', compact('order'));
    }

    /**
     * Xử lý thanh toán qua MoMo
     */
    public function momo_payment(Request $request)
    {
        // Lấy thông tin đơn hàng từ session
        $paymentInfo = session('momo_payment_info');
        if (!$paymentInfo) {
            return redirect()->route('client.checkout')
                ->with('error', 'Không tìm thấy thông tin đơn hàng');
        }

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        
        $orderId = time() . "_" . $paymentInfo['order_id'];
        $amount = (int)$paymentInfo['amount']; // Chuyển đổi sang số nguyên
        $orderInfo = "Thanh toán đơn hàng #" . $paymentInfo['order_id'];
        
        $redirectUrl = route('client.order.success', ['order' => $paymentInfo['order_id']]);
        $ipnUrl = route('client.order.momo-ipn');
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Win Star",
            'storeId' => "Win Star Store",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        try {
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            if (isset($jsonResult['payUrl'])) {
                return redirect()->to($jsonResult['payUrl']);
            }

            throw new \Exception('Không thể kết nối đến MoMo: ' . ($jsonResult['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('MoMo Payment Error: ' . $e->getMessage());
            return redirect()->route('client.checkout')
                ->with('error', 'Không thể kết nối đến MoMo. Vui lòng thử lại sau.');
        }
    }

    /**
     * Xử lý callback từ MoMo
     */
    public function momoIPN(Request $request)
    {
        $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
        $accessKey = "klm05TvNBzhg7h7j";
        
        // Lấy các tham số từ MoMo
        $partnerCode = $request->partnerCode;
        $orderId = $request->orderId;
        $requestId = $request->requestId;
        $amount = $request->amount;
        $orderInfo = $request->orderInfo;
        $orderType = $request->orderType;
        $transId = $request->transId;
        $resultCode = $request->resultCode;
        $message = $request->message;
        $payType = $request->payType;
        $responseTime = $request->responseTime;
        $extraData = $request->extraData;
        $m2signature = $request->signature;

        // Tạo chữ ký để kiểm tra
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // Kiểm tra chữ ký và kết quả
        if ($signature === $m2signature) {
            if ($resultCode == 0) {
                // Lấy ID đơn hàng từ orderId
                $orderIdParts = explode('_', $orderId);
                $realOrderId = end($orderIdParts);
                
                // Cập nhật trạng thái đơn hàng
                $order = Order::find($realOrderId);
                if ($order) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing'
                    ]);

                    // Gửi email thông báo
                    try {
                        $order->user->notify(new OrderNotification($order, 'payment_success'));
                    } catch (\Exception $e) {
                        Log::error('Error sending MoMo payment notification: ' . $e->getMessage());
                    }

                    return response()->json(['message' => 'Success']);
                }
            }
        }

        return response()->json(['message' => 'Failed']);
    }

    /**
     * Xử lý thanh toán qua ngân hàng
     */
    private function processBankTransfer($order)
    {
        // Thông tin tài khoản ngân hàng
        $bankInfo = [
            'bank_name' => 'Vietcombank',
            'account_number' => '1234567890',
            'account_name' => 'NGUYEN VAN A',
            'amount' => $order->total_amount,
            'content' => 'Thanh toan don hang #' . $order->id
        ];

        // Lưu thông tin thanh toán vào session
        session(['bank_payment_info' => $bankInfo]);

        return ['success' => true];
    }

    /**
     * Xử lý thanh toán COD
     */
    private function processCOD($order)
    {
        // Cập nhật trạng thái đơn hàng
        $order->update([
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        return ['success' => true];
    }

    /**
     * Gửi request POST
     */
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    /**
     * Hiển thị danh sách đơn hàng của khách hàng
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.order.list', compact('orders'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderDetails.product', 'orderDetails.variant.color', 'orderDetails.variant.storage', 'coupon']);

        return view('client.order.show', compact('order'));
    }

    /**
     * Hủy đơn hàng
     */
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

            // Cập nhật trạng thái đơn hàng
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            // Hoàn lại số lượng sản phẩm
            foreach ($order->orderDetails as $detail) {
                if ($detail->variant) {
                    $detail->variant->increment('stock_quantity', $detail->quantity);
                } else {
                    $detail->product->increment('stock_quantity', $detail->quantity);
                }
            }

            // Gửi thông báo
            try {
                $order->user->notify(new OrderNotification($order, 'cancelled'));
            } catch (\Exception $e) {
                Log::error('Error sending order cancellation notification: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Theo dõi đơn hàng
     */
    public function track(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderDetails.product', 'orderDetails.variant.color', 'orderDetails.variant.storage']);

        // Khởi tạo mảng trạng thái cơ bản
        $baseStatuses = [
            'pending' => [
                'status' => 'pending',
                'title' => 'Đơn hàng đã đặt',
                'description' => 'Đơn hàng của bạn đã được đặt thành công',
                'time' => $order->created_at,
                'active' => false,
                'done' => false
            ],
            'processing' => [
                'status' => 'processing',
                'title' => 'Đang xử lý',
                'description' => 'Đơn hàng đang được xử lý',
                'time' => null,
                'active' => false,
                'done' => false
            ],
            'shipping' => [
                'status' => 'shipping',
                'title' => 'Đang giao hàng',
                'description' => 'Đơn hàng đang được giao đến bạn',
                'time' => null,
                'active' => false,
                'done' => false
            ],
            'completed' => [
                'status' => 'completed',
                'title' => 'Đã giao hàng',
                'description' => 'Đơn hàng đã được giao thành công',
                'time' => null,
                'active' => false,
                'done' => false
            ]
        ];

        // Mảng lưu trữ trạng thái sẽ hiển thị
        $statusHistory = [];

        // Nếu đơn hàng đã hủy
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
            // Xử lý các trạng thái dựa trên trạng thái hiện tại
            switch ($order->status) {
                case 'completed':
                    $baseStatuses['completed']['time'] = $order->updated_at;
                    $baseStatuses['completed']['active'] = true;
                    $baseStatuses['completed']['done'] = true;
                    $baseStatuses['shipping']['done'] = true;
                    $baseStatuses['processing']['done'] = true;
                    $baseStatuses['pending']['done'] = true;
                    break;

                case 'shipping':
                    $baseStatuses['shipping']['time'] = $order->updated_at;
                    $baseStatuses['shipping']['active'] = true;
                    $baseStatuses['shipping']['done'] = true;
                    $baseStatuses['processing']['done'] = true;
                    $baseStatuses['pending']['done'] = true;
                    break;

                case 'processing':
                    $baseStatuses['processing']['time'] = $order->updated_at;
                    $baseStatuses['processing']['active'] = true;
                    $baseStatuses['processing']['done'] = true;
                    $baseStatuses['pending']['done'] = true;
                    break;

                case 'pending':
                    $baseStatuses['pending']['time'] = $order->created_at;
                    $baseStatuses['pending']['active'] = true;
                    $baseStatuses['pending']['done'] = true;
                    break;
            }

            // Thêm các trạng thái đã và đang thực hiện vào mảng hiển thị
            foreach ($baseStatuses as $status) {
                if ($status['done'] || $status['active']) {
                    $statusHistory[] = $status;
                }
            }
        }

        return view('client.order.track', compact('order', 'statusHistory'));
    }
}
