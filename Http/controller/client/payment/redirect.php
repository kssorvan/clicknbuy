<?php// Http/controller/client/payment/redirect.php


// Check if payment data exists
if (!isset($_SESSION['aba_payment_data'])) {
    redirect('/cart');
    exit();
}

$paymentData = $_SESSION['aba_payment_data'];
$config = require base_path('config.php');
$abaConfig = $config['abapayway'];
$checkoutUrl = $abaConfig['base_url'] . '/api/payment-gateway/v1/payments/purchase';

// Create a form to post to ABA PayWay
view("client/payment/redirect.view.php", [
    'paymentData' => $paymentData,
    'checkoutUrl' => $checkoutUrl
]);