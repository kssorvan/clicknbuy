<?php// Http/controller/client/payment/paypal.php


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
$db = App::resolve(Database::class);

// Start a transaction
$db->conncetion->beginTransaction();

try {
    // Insert order with pending status
    $db->query("
        INSERT INTO orders (
            user_id, total_amount, status, shipping_address, 
            payment_method, shipping_method, tax_amount, shipping_amount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ", [
        $pendingOrder['user_id'],
        $pendingOrder['total'],
        'pending',
        $pendingOrder['shipping_address'],
        'paypal',
        $pendingOrder['shipping_method'],
        $pendingOrder['tax'],
        $pendingOrder['shipping']
    ]);
    
    $orderId = $db->conncetion->lastInsertId();
    
    // Insert order items
    foreach ($pendingOrder['items'] as $item) {
        $db->query("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ", [
            $orderId,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        ]);
    }
    
    // Commit the transaction
    $db->conncetion->commit();
    
    // Store order ID in session for PayPal return handling
    $_SESSION['paypal_order_id'] = $orderId;
    
    // Get PayPal config
    $config = require base_path('config.php');
    $paypalConfig = $config['paypal'];
    
    // Format amount for PayPal
    $amount = number_format($pendingOrder['total'], 2, '.', '');
    
    view("client/payment/paypal.view.php", [
        'orderId' => $orderId,
        'amount' => $amount,
        'clientId' => $paypalConfig['client_id'],
        'returnUrl' => $paypalConfig['return_url'],
        'cancelUrl' => $paypalConfig['cancel_url']
    ]);
    
} catch (\Exception $e) {
    // Rollback if something went wrong
    $db->conncetion->rollBack();
    
    $_SESSION['error'] = "An error occurred while processing your order: " . $e->getMessage();
    redirect('/payment');
}