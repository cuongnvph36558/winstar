<?php

namespace App\Services;

use App\Models\Order;
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
        $config = config('payments.vnpay');
        
        // Validate config
        if (empty($config['hash_secret']) || empty($config['tmn_code'])) {
            Log::error('VNPay configuration incomplete');
            return [
                'success' => false,
                'message' => 'Cấu hình VNPay chưa đầy đủ'
            ];
        }
        
        $vnp_Url = $config['url'];
        $vnp_HashSecret = $config['hash_secret'];
        $vnp_TmnCode = $config['tmn_code'];

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
     * Xử lý callback từ VNPay
     */
    public function handleVNPayCallback(array $data): bool
    {
        $vnp_SecureHash = $data['vnp_SecureHash'];
        $vnp_HashSecret = config('payments.vnpay.hash_secret');

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
                return true;
            }
        }

        return false;
    }
}