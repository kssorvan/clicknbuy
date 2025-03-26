<?php// Http/controller/client/payment/callback.php


use Core\App;
use Core\Database;

// Get the parameters from ABA PayWay
$transId = $_GET['tran_id'] ?? '';
$status = $_GET['status'] ?? '';
$description = $_GET['description'] ?? '';

// Validate the transaction
$config = require base_path('config.php');
$abaConfig = $config['aba_payway'];
$apiKey = $abaConfig['api_key'];

// Get the payment info from session
if (!isset($_SESSION['payment_info']) || $_SESSION['payment_info']['trans_id'] !== $transId) {
    $_SESSION['error'] = "Invalid transaction.";
    redirect('/cart');
    exit();
}

$paymentInfo = $_SESSION['payment_info'];
$db = App::resolve(Database::class);

// Check the payment status
if ($status === 'success') {
    // Update order status to 'paid'
    $db->query("
        UPDATE orders 
        SET status = 'processing', updated_at = CURRENT_TIMESTAMP
        WHERE order_id = ?
    ", [$paymentInfo['order_id']]);
    
    // Store payment transaction details
    $db->query("
        INSERT INTO payment_transactions (
            order_id, payment_provider, provider_transaction_id, 
            amount, status, payment_method, payment_details
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ", [
        $paymentInfo['order_id'],
        'ABA PayWay',
        $transId,
        $paymentInfo['amount'],
        'completed',
        'aba_payway',
        json_encode($_GET)
    ]);
    
    // Update product stock
    $orderItems = $db->query("
        SELECT product_id, quantity FROM order_items
        WHERE order_id = ?
    ", [$paymentInfo['order_id']])->get();
    
    foreach ($orderItems as $item) {
        $db->query("
            UPDATE products 
            SET stock = stock - ? 
            WHERE product_id = ?
        ", [
            $item['quantity'],
            $item['product_id']
        ]);
    }
    
    // Clear cart and payment info
    $_SESSION['cart'] = [];
    unset($_SESSION['pending_order']);
    unset($_SESSION['payment_info']);
    
    // Set success message
    $_SESSION['success'] = "Your payment was successful! Order #" . $paymentInfo['order_id'];
    
    // Redirect to order confirmation
    redirect("/order/confirmation/" . $paymentInfo['order_id']);
} else {
    // Payment failed
    $db->query("
        UPDATE orders 
        SET status = 'failed', updated_at = CURRENT_TIMESTAMP
        WHERE order_id = ?
    ", [$paymentInfo['order_id']]);
    
    // Store payment transaction details
    $db->query("
        INSERT INTO payment_transactions (
            order_id, payment_provider, provider_transaction_id, 
            amount, status, payment_method, payment_details
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ", [
        $paymentInfo['order_id'],
        'ABA PayWay',
        $transId,
        $paymentInfo['amount'],
        'failed',
        'aba_payway',
        json_encode($_GET)
    ]);
    
    // Set error message
    $_SESSION['error'] = "Payment failed: " . $description;
    
    // Redirect to cart
    redirect('/cart');
}