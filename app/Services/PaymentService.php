<?php

namespace App\Services;

use App\Models\Order;
use App\Notifications\OrderNotification;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Xử lý thanh toán qua VNPay
     */
    public function processVNPay(Order $order): array
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_HashSecret = "VNPAY_HASH_SECRET"; // Thay bằng hash secret thật
        $vnp_TmnCode = "VNPAY_TMN_CODE"; // Thay bằng TMN code thật

        $vnp_Params = array(
            "vnp_Version" => "2.1.0",
            "vnp_Command" => "pay",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Locale" => "vn",
            "vnp_CurrCode" => "VND",
            "vnp_TxnRef" => $order->id,
            "vnp_OrderInfo" => "Thanh toan don hang #" . $order->id,
            "vnp_OrderType" => "billpayment",
            "vnp_Amount" => $order->total_amount * 100,
            "vnp_ReturnUrl" => route('client.order.vnpay-return'),
            "vnp_IpAddr" => request()->ip(),
            "vnp_CreateDate" => date('YmdHis')
        );

        ksort($vnp_Params);
        $query = http_build_query($vnp_Params);
        $vnp_SecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
        $vnp_Url = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        return [
            'success' => true,
            'redirect_url' => $vnp_Url
        ];
    }

    /**
     * Xử lý thanh toán qua ZaloPay
     */
    public function processZaloPay(Order $order): array
    {
        $config = [
            'app_id' => "ZALOPAY_APP_ID",
            'key1' => "ZALOPAY_KEY1",
            'key2' => "ZALOPAY_KEY2",
            'endpoint' => "https://sandbox.zalopay.com.vn/v001/tpe/createorder"
        ];

        $order_data = [
            'app_id' => $config['app_id'],
            'app_trans_id' => date("ymd") . "_" . $order->id,
            'app_user' => $order->user_id,
            'app_time' => round(microtime(true) * 1000),
            'amount' => $order->total_amount,
            'description' => "Thanh toan don hang #" . $order->id,
            'bank_code' => "",
            'callback_url' => route('client.order.zalopay-callback'),
            'item' => json_encode([
                ['id' => $order->id, 'name' => 'Order #' . $order->id, 'amount' => $order->total_amount]
            ])
        ];

        $data = $order_data['app_id'] . "|" . $order_data['app_trans_id'] . "|" . $order_data['app_user'] . "|" . 
                $order_data['amount'] . "|" . $order_data['app_time'] . "|" . $order_data['embed_data'] . "|" . 
                $order_data['item'];
        $order_data['mac'] = hash_hmac('sha256', $data, $config['key1']);

        try {
            $response = Http::post($config['endpoint'], $order_data);
            $result = $response->json();

            if ($result['return_code'] == 1) {
                return [
                    'success' => true,
                    'redirect_url' => $result['order_url']
                ];
            }

            throw new Exception($result['return_message']);
        } catch (Exception $e) {
            Log::error('ZaloPay Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Không thể kết nối đến ZaloPay'
            ];
        }
    }

    /**
     * Xử lý thanh toán qua PayPal
     */
    public function processPayPal(Order $order): array
    {
        $config = [
            'client_id' => 'PAYPAL_CLIENT_ID',
            'client_secret' => 'PAYPAL_CLIENT_SECRET',
            'environment' => 'sandbox' // hoặc 'production'
        ];

        $endpoint = $config['environment'] === 'sandbox' 
            ? "https://api-m.sandbox.paypal.com" 
            : "https://api-m.paypal.com";

        try {
            // Lấy access token
            $tokenResponse = Http::withBasicAuth($config['client_id'], $config['client_secret'])
                ->post($endpoint . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            $token = $tokenResponse->json()['access_token'];

            // Tạo đơn hàng
            $response = Http::withToken($token)
                ->post($endpoint . '/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => $order->id,
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format($order->total_amount / 23000, 2, '.', '') // Chuyển đổi VND sang USD
                        ],
                        'description' => 'Order #' . $order->id
                    ]],
                    'application_context' => [
                        'return_url' => route('client.order.paypal-success'),
                        'cancel_url' => route('client.order.paypal-cancel')
                    ]
                ]);

            $result = $response->json();

            if (isset($result['links'])) {
                $approvalUrl = collect($result['links'])->firstWhere('rel', 'approve')['href'];
                return [
                    'success' => true,
                    'redirect_url' => $approvalUrl
                ];
            }

            throw new Exception('PayPal response missing approval URL');
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Không thể kết nối đến PayPal'
            ];
        }
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function handleVNPayCallback(array $data): bool
    {
        $vnp_SecureHash = $data['vnp_SecureHash'];
        $vnp_HashSecret = "VNPAY_HASH_SECRET"; // Thay bằng hash secret thật

        unset($data['vnp_SecureHash']);
        ksort($data);
        $query = http_build_query($data);
        $secureHash = hash_hmac('sha512', $query, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            $order = Order::find($data['vnp_TxnRef']);
            if ($order && $data['vnp_ResponseCode'] === '00') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
                $order->user->notify(new OrderNotification($order, 'payment_success'));
                return true;
            }
        }

        return false;
    }

    /**
     * Xử lý callback từ ZaloPay
     */
    public function handleZaloPayCallback(array $data): bool
    {
        $config = [
            'key2' => "ZALOPAY_KEY2"
        ];

        $checksum = $data['checksum'];
        unset($data['checksum']);
        ksort($data);
        $query = http_build_query($data);
        $calculatedChecksum = hash_hmac('sha256', $query, $config['key2']);

        if ($checksum === $calculatedChecksum) {
            $order = Order::find(substr($data['app_trans_id'], 7));
            if ($order && $data['status'] === 1) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
                $order->user->notify(new OrderNotification($order, 'payment_success'));
                return true;
            }
        }

        return false;
    }

    /**
     * Xử lý callback từ PayPal
     */
    public function handlePayPalCallback(string $orderId, string $token): bool
    {
        $config = [
            'client_id' => 'PAYPAL_CLIENT_ID',
            'client_secret' => 'PAYPAL_CLIENT_SECRET',
            'environment' => 'sandbox'
        ];

        $endpoint = $config['environment'] === 'sandbox' 
            ? "https://api-m.sandbox.paypal.com" 
            : "https://api-m.paypal.com";

        try {
            // Lấy access token
            $tokenResponse = Http::withBasicAuth($config['client_id'], $config['client_secret'])
                ->post($endpoint . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            $accessToken = $tokenResponse->json()['access_token'];

            // Capture payment
            $response = Http::withToken($accessToken)
                ->post($endpoint . "/v2/checkout/orders/{$token}/capture");

            $result = $response->json();

            if ($result['status'] === 'COMPLETED') {
                $order = Order::find($orderId);
                if ($order) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing'
                    ]);
                    $order->user->notify(new OrderNotification($order, 'payment_success'));
                    return true;
                }
            }
        } catch (Exception $e) {
            Log::error('PayPal Capture Error: ' . $e->getMessage());
        }

        return false;
    }
}