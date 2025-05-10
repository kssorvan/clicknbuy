<?php// Http/controller/client/payment/index.php

use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Check if there's a pending order
if (!isset($_SESSION['pending_order'])) {
    redirect('/cart');
    exit();
}

$pendingOrder = $_SESSION['pending_order'];

// Generate a unique transaction ID
$transactionId = uniqid('ORDER-');
$_SESSION['transaction_id'] = $transactionId;

view("client/payment/index.view.php", [
    'order' => $pendingOrder,
    'transactionId' => $transactionId
]);