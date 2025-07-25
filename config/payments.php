<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the payment gateways for your application.
    | You should set the credentials in your .env file.
    |
    */

    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE', '1VYBIYQP'),
        'hash_secret' => env('VNPAY_HASH_SECRET', 'NOH6MBGNLQL9O9OMMFMZ2AX8NIEP50W1'),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('APP_URL') . '/order/payment/vnpay-return',
        'version' => '2.1.0',
    ],

    'momo' => [
        'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'),
        'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'),
        'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'),
        'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
        'return_url' => env('APP_URL') . '/order/success',
        'notify_url' => env('APP_URL') . '/order/payment/momo-ipn',
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    */
    'currency' => [
        'vnd_to_usd_rate' => env('VND_TO_USD_RATE', 23000), // Tỷ giá VND/USD
        'default' => 'VND',
    ],

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */
    'timeout' => 30, // seconds
    'retry_attempts' => 3,
]; 