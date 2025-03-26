<?php

return [
    'database' => [
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'port'     => getenv('DB_PORT') ?: 3306,
        'database' => getenv('DB_NAME') ?: 'clicknbuy',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
        'charset'  => 'utf8mb4',
    ],
    'cloudinary' => [
        'cloud_name' => 'unknownEAE46',
        'api_key'    => '393515422477893',
        'api_secret' => 'Cy-ROBqmcN01AOeEjD85-wAPa68',
    ],  // config.php (add this to your existing config)
    'paypal' => [
        'client_id' => 'YOUR_SANDBOX_CLIENT_ID',  // Use your PayPal sandbox client ID
        'client_secret' => 'YOUR_SANDBOX_SECRET',  // Use your PayPal sandbox secret
        'return_url' => 'http://localhost:8000/payment/paypal-success',
        'cancel_url' => 'http://localhost:8000/payment/paypal-cancel'
    ],  // config.php (add this to your existing config)
    'aba_payway' => [
        'merchant_id' => 'your_merchant_id',
        'api_key' => 'your_api_key',
        'api_url' => 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase',  // Use the sandbox URL for testing
        'return_url' => 'http://localhost:8000/payment/callback',
        'continue_success_url' => 'http://localhost:8000/order/confirmation',
        'continue_fail_url' => 'http://localhost:8000/cart'
    ],
    
    
];
