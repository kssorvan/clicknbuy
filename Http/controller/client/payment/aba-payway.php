// Http/controller/client/payment/aba-payway.php
<?php

use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Check if payment info exists
if (!isset($_SESSION['payment_info'])) {
    redirect('/cart');
    exit();
}

$paymentInfo = $_SESSION['payment_info'];
$config = require base_path('config.php');
$abaConfig = $config['aba_payway'];

// Get order details
$db = App::resolve(Database::class);
$order = $db->query("
    SELECT * FROM orders WHERE order_id = ?
", [$paymentInfo['order_id']])->find();

if (!$order) {
    redirect('/cart');
    exit();
}

// Prepare hash data for security
$merchantId = $abaConfig['merchant_id'];
$apiKey = $abaConfig['api_key'];
$reqTime = date('YmdHis');
$transId = $paymentInfo['trans_id'];
$amount = $paymentInfo['amount'];
$items = "Order #" . $paymentInfo['order_id'];
$firstName = $_SESSION['user']['name'];
$email = $_SESSION['user']['email'];
$returnUrl = $abaConfig['return_url'];
$continueSuccessUrl = $abaConfig['continue_success_url'];
$continueFailUrl = $abaConfig['continue_fail_url'];

// Create hash
$stringToHash = $merchantId . $apiKey . $reqTime . $transId . $amount . $items;
$hash = hash_hmac('sha512', $stringToHash, $apiKey);

view("client/payment/aba-payway.view.php", [
    'merchantId' => $merchantId,
    'apiUrl' => $abaConfig['api_url'],
    'reqTime' => $reqTime,
    'transId' => $transId,
    'amount' => $amount,
    'items' => $items,
    'firstName' => $firstName,
    'email' => $email,
    'returnUrl' => $returnUrl,
    'continueSuccessUrl' => $continueSuccessUrl,
    'continueFailUrl' => $continueFailUrl,
    'hash' => $hash,
    'order' => $order
]);