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