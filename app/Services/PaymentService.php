<?php

namespace App\Services;

use App\Models\Order;
use App\Models\VnpayTransaction;
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
            "vnp_TxnRef" => $order->id . '_' . time(),
            "vnp_OrderInfo" => "Thanh toan don hang #" . $order->code_order,
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

        // Lưu transaction vào database
        VnpayTransaction::create([
            'order_id' => $order->id,
            'vnp_TxnRef' => $vnp_Params['vnp_TxnRef'],
            'vnp_Amount' => $vnp_Params['vnp_Amount'],
            'status' => 'pending',
            'raw_data' => $vnp_Params
        ]);

        return [
            'success' => true,
            'redirect_url' => $vnp_Url
        ];
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function handleVNPayCallback(array $data): array
    {
        try {
            $vnp_SecureHash = $data['vnp_SecureHash'] ?? '';
            $vnp_HashSecret = config('payments.vnpay.hash_secret');
            $vnp_ResponseCode = $data['vnp_ResponseCode'] ?? '';
            $vnp_TxnRef = $data['vnp_TxnRef'] ?? '';

            // Tìm transaction
            $transaction = VnpayTransaction::where('vnp_TxnRef', $vnp_TxnRef)->first();
            if (!$transaction) {
                Log::error('VNPay transaction not found', ['vnp_TxnRef' => $vnp_TxnRef]);
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy giao dịch'
                ];
            }

            // Verify hash
            $inputData = $data;
            unset($inputData['vnp_SecureHash']);
            ksort($inputData);
            $query = http_build_query($inputData);
            $secureHash = hash_hmac('sha512', $query, $vnp_HashSecret);

            if ($secureHash !== $vnp_SecureHash) {
                Log::error('VNPay hash verification failed', [
                    'expected' => $secureHash,
                    'received' => $vnp_SecureHash
                ]);
                
                $transaction->update([
                    'status' => 'failed',
                    'message' => 'Hash verification failed',
                    'raw_data' => $data
                ]);

                return [
                    'success' => false,
                    'message' => 'Xác thực giao dịch thất bại'
                ];
            }

            // Cập nhật transaction
            $transaction->update([
                'vnp_ResponseCode' => $vnp_ResponseCode,
                'vnp_TransactionNo' => $data['vnp_TransactionNo'] ?? null,
                'vnp_PayDate' => $data['vnp_PayDate'] ?? null,
                'vnp_BankCode' => $data['vnp_BankCode'] ?? null,
                'vnp_CardType' => $data['vnp_CardType'] ?? null,
                'vnp_SecureHash' => $vnp_SecureHash,
                'status' => $vnp_ResponseCode === '00' ? 'success' : 'failed',
                'message' => $this->getVNPayResponseMessage($vnp_ResponseCode),
                'raw_data' => $data
            ]);

            // Xử lý đơn hàng
            if ($vnp_ResponseCode === '00') {
                $order = $transaction->order;
                if ($order) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing'
                    ]);
                    
                    Log::info('VNPay payment successful', [
                        'order_id' => $order->id,
                        'transaction_id' => $transaction->id
                    ]);
                }

                return [
                    'success' => true,
                    'message' => 'Thanh toán thành công',
                    'order' => $order
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $this->getVNPayResponseMessage($vnp_ResponseCode)
                ];
            }

        } catch (Exception $e) {
            Log::error('VNPay callback error: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Lỗi xử lý giao dịch'
            ];
        }
    }

    /**
     * Lấy thông báo lỗi từ mã response của VNPay
     */
    private function getVNPayResponseMessage($responseCode): string
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '01' => 'Giao dịch chưa hoàn tất',
            '02' => 'Giao dịch bị lỗi',
            '04' => 'Giao dịch đảo (Khách hàng đã bị trừ tiền tại Ngân hàng nhưng GD chưa thành công ở VNPAY)',
            '05' => 'VNPAY đang xử lý',
            '06' => 'VNPAY đã gửi yêu cầu hoàn tiền sang Ngân hàng',
            '07' => 'Giao dịch bị nghi ngờ gian lận',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản bị khóa',
            '13' => 'Giao dịch không thành công do: Nhập sai mật khẩu xác thực giao dịch (OTP)',
            '65' => 'Giao dịch không thành công do: Tài khoản không đủ số dư',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Giao dịch không thành công do: Nhập sai mật khẩu thanh toán',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)'
        ];

        return $messages[$responseCode] ?? 'Mã lỗi không xác định: ' . $responseCode;
    }
}