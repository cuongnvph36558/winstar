<?php

namespace App\Helpers;

class PaymentHelper
{
    /**
     * Get payment method display information
     */
    public static function getPaymentMethodInfo($paymentMethod)
    {
        $methods = [
            'cod' => [
                'name' => 'Thanh toán khi nhận hàng (COD)',
                'icon' => 'fas fa-money-bill-wave',
                'color' => 'yellow',
                'description' => 'Thanh toán bằng tiền mặt khi nhận hàng',
                'bg_class' => 'bg-yellow-100',
                'text_class' => 'text-yellow-800',
                'border_class' => 'border-yellow-300'
            ],
            'vnpay' => [
                'name' => 'VNPay',
                'icon' => 'fas fa-credit-card',
                'color' => 'blue',
                'description' => 'Thanh toán trực tuyến an toàn qua VNPay',
                'bg_class' => 'bg-blue-100',
                'text_class' => 'text-blue-800',
                'border_class' => 'border-blue-300'
            ],
            'momo' => [
                'name' => 'MoMo',
                'icon' => 'fas fa-mobile-alt',
                'color' => 'pink',
                'description' => 'Thanh toán qua ví điện tử MoMo',
                'bg_class' => 'bg-pink-100',
                'text_class' => 'text-pink-800',
                'border_class' => 'border-pink-300'
            ]
        ];

        return $methods[$paymentMethod] ?? [
            'name' => ucfirst($paymentMethod),
            'icon' => 'fas fa-credit-card',
            'color' => 'gray',
            'description' => 'Phương thức thanh toán',
            'bg_class' => 'bg-gray-100',
            'text_class' => 'text-gray-800',
            'border_class' => 'border-gray-300'
        ];
    }

    /**
     * Get payment status display information
     */
    public static function getPaymentStatusInfo($paymentStatus, $orderStatus = null)
    {
        // Đồng bộ trạng thái thanh toán với trạng thái đơn hàng
        $displayStatus = $paymentStatus;
        if ($orderStatus === 'completed' && $paymentStatus === 'pending') {
            $displayStatus = 'paid';
        }

        $statuses = [
            'paid' => [
                'name' => 'Đã thanh toán',
                'icon' => 'fas fa-check-circle',
                'color' => 'green',
                'bg_class' => 'bg-green-100',
                'text_class' => 'text-green-800',
                'border_class' => 'border-green-300'
            ],
            'pending' => [
                'name' => 'Chưa thanh toán',
                'icon' => 'fas fa-clock',
                'color' => 'yellow',
                'bg_class' => 'bg-yellow-100',
                'text_class' => 'text-yellow-800',
                'border_class' => 'border-yellow-300'
            ],
            'processing' => [
                'name' => 'Đang xử lý',
                'icon' => 'fas fa-spinner fa-spin',
                'color' => 'blue',
                'bg_class' => 'bg-blue-100',
                'text_class' => 'text-blue-800',
                'border_class' => 'border-blue-300'
            ],
            'failed' => [
                'name' => 'Thanh toán thất bại',
                'icon' => 'fas fa-times-circle',
                'color' => 'red',
                'bg_class' => 'bg-red-100',
                'text_class' => 'text-red-800',
                'border_class' => 'border-red-300'
            ],
            'cancelled' => [
                'name' => 'Đã hủy',
                'icon' => 'fas fa-ban',
                'color' => 'red',
                'bg_class' => 'bg-red-100',
                'text_class' => 'text-red-800',
                'border_class' => 'border-red-300'
            ],
            'refunded' => [
                'name' => 'Đã hoàn tiền',
                'icon' => 'fas fa-undo',
                'color' => 'purple',
                'bg_class' => 'bg-purple-100',
                'text_class' => 'text-purple-800',
                'border_class' => 'border-purple-300'
            ]
        ];

        return $statuses[$displayStatus] ?? [
            'name' => ucfirst($displayStatus),
            'icon' => 'fas fa-question-circle',
            'color' => 'gray',
            'bg_class' => 'bg-gray-100',
            'text_class' => 'text-gray-800',
            'border_class' => 'border-gray-300'
        ];
    }

    /**
     * Get payment method options for forms
     */
    public static function getPaymentMethodOptions()
    {
        return [
            'cod' => [
                'value' => 'cod',
                'label' => 'Thanh toán khi nhận hàng (COD)',
                'icon' => 'fas fa-truck',
                'description' => 'Thanh toán bằng tiền mặt khi nhận hàng',
                'logo' => null,
                'features' => [
                    'Không cần thẻ ngân hàng',
                    'Thanh toán khi nhận hàng',
                    'An toàn và tiện lợi'
                ]
            ],
            'vnpay' => [
                'value' => 'vnpay',
                'label' => 'VNPay',
                'icon' => 'fas fa-credit-card',
                'description' => 'Thanh toán trực tuyến an toàn qua VNPay',
                'logo' => 'assets/external/images/logo-vnpay.png',
                'features' => [
                    'Thanh toán trực tuyến 24/7',
                    'Bảo mật cao',
                    'Hỗ trợ nhiều ngân hàng'
                ]
            ]
        ];
    }

    /**
     * Check if payment method is available for retry
     */
    public static function canRetryPayment($order)
    {
        return $order->payment_status === 'pending' 
            && $order->status !== 'cancelled' 
            && $order->payment_method !== 'cod';
    }

    /**
     * Get payment method display HTML
     */
    public static function getPaymentMethodDisplay($paymentMethod)
    {
        $info = self::getPaymentMethodInfo($paymentMethod);
        
        return sprintf(
            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium %s %s">
                <i class="%s mr-1"></i>
                %s
            </span>',
            $info['bg_class'],
            $info['text_class'],
            $info['icon'],
            $info['name']
        );
    }

    /**
     * Get payment status display HTML
     */
    public static function getPaymentStatusDisplay($paymentStatus, $orderStatus = null)
    {
        $info = self::getPaymentStatusInfo($paymentStatus, $orderStatus);
        
        return sprintf(
            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium %s %s">
                <i class="%s mr-1"></i>
                %s
            </span>',
            $info['bg_class'],
            $info['text_class'],
            $info['icon'],
            $info['name']
        );
    }

    /**
     * Format payment amount
     */
    public static function formatAmount($amount)
    {
        return number_format($amount, 0, ',', '.') . '₫';
    }

    /**
     * Get payment method validation rules
     */
    public static function getValidationRules()
    {
        return [
            'payment_method' => 'required|in:cod,vnpay',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ (Chỉ hỗ trợ COD và VNPay)'
        ];
    }

    /**
     * Check if VNPay is available for the given amount
     */
    public static function isVNPayAvailable($amount)
    {
        $minAmount = 5000; // 5 nghìn VND
        $maxAmount = 1000000000; // 1 tỷ VND
        
        return $amount >= $minAmount && $amount <= $maxAmount;
    }

    /**
     * Get VNPay availability message
     */
    public static function getVNPayAvailabilityMessage($amount)
    {
        $minAmount = 5000; // 5 nghìn VND
        $maxAmount = 1000000000; // 1 tỷ VND
        
        if ($amount < $minAmount) {
            return 'VNPay chỉ áp dụng cho đơn hàng từ ' . number_format($minAmount, 0, ',', '.') . ' VND trở lên';
        }
        
        if ($amount > $maxAmount) {
            return 'VNPay chỉ áp dụng cho đơn hàng dưới ' . number_format($maxAmount, 0, ',', '.') . ' VND';
        }
        
        return null; // VNPay is available
    }

    /**
     * Get available payment methods for the given amount
     */
    public static function getAvailablePaymentMethods($amount)
    {
        $methods = self::getPaymentMethodOptions();
        
        // Kiểm tra VNPay availability
        if (!self::isVNPayAvailable($amount)) {
            unset($methods['vnpay']);
        }
        
        return $methods;
    }
}
