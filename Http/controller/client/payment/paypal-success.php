<?php// Http/controller/client/payment/paypal-success.php


use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Get order ID from query params
$orderId = $_GET['order_id'] ?? null;
$paypalOrderId = $_GET['paypal_order_id'] ?? null;

// Validate order ID from session
if (!$orderId || !$paypalOrderId || !isset($_SESSION['paypal_order_id']) || $_SESSION['paypal_order_id'] != $orderId) {
    $_SESSION['error'] = "Invalid order information.";
    redirect('/cart');
    exit();
}

$db = App::resolve(Database::class);

// Verify the order exists and belongs to the user
$order = $db->query("
    SELECT * FROM orders 
    WHERE order_id = ? AND user_id = ?
", [$orderId, $_SESSION['user']['id']])->find();

if (!$order) {
    $_SESSION['error'] = "Order not found.";
    redirect('/cart');
    exit();
}

// Update order status
$db->query("
    UPDATE orders 
    SET status = 'processing', updated_at = CURRENT_TIMESTAMP
    WHERE order_id = ?
", [$orderId]);

// Store payment transaction details
$db->query("
    INSERT INTO payment_transactions (
        order_id, payment_provider, provider_transaction_id, 
        amount, status, payment_method, payment_details
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
", [
    $orderId,
    'PayPal',
    $paypalOrderId,
    $order['total_amount'],
    'completed',
    'paypal',
    json_encode($_GET)
]);

// Update product stock
$orderItems = $db->query("
    SELECT product_id, quantity FROM order_items
    WHERE order_id = ?
", [$orderId])->get();

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

// Clear cart and PayPal session data
$_SESSION['cart'] = [];
unset($_SESSION['pending_order']);
unset($_SESSION['paypal_order_id']);

// Set success message
$_SESSION['success'] = "Your payment was successful! Order #" . $orderId;

// Redirect to order confirmation
redirect("/order/confirmation/" . $orderId);