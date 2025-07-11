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
        'tmn_code' => env('VNPAY_TMN_CODE', ''),
        'hash_secret' => env('VNPAY_HASH_SECRET', ''),
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

    'zalopay' => [
        'app_id' => env('ZALOPAY_APP_ID', ''),
        'key1' => env('ZALOPAY_KEY1', ''),
        'key2' => env('ZALOPAY_KEY2', ''),
        'endpoint' => env('ZALOPAY_ENDPOINT', 'https://sb-openapi.zalopay.vn/v2/create'),
        'callback_url' => env('APP_URL') . '/order/payment/zalopay-callback',
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
        'environment' => env('PAYPAL_ENVIRONMENT', 'sandbox'), // sandbox or live
        'sandbox_url' => 'https://api-m.sandbox.paypal.com',
        'live_url' => 'https://api-m.paypal.com',
        'return_url' => env('APP_URL') . '/order/payment/paypal-success',
        'cancel_url' => env('APP_URL') . '/order/payment/paypal-cancel',
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