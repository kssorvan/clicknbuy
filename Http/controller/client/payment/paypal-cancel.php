
<?php// Http/controller/client/payment/paypal-cancel.php

use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Get order ID from session
$orderId = $_SESSION['paypal_order_id'] ?? null;

if (!$orderId) {
    $_SESSION['error'] = "No order to cancel.";
    redirect('/cart');
    exit();
}

$db = App::resolve(Database::class);

// Update order status to cancelled
$db->query("
    UPDATE orders 
    SET status = 'canceled', updated_at = CURRENT_TIMESTAMP
    WHERE order_id = ?
", [$orderId]);

// Set message
$_SESSION['notice'] = "Your payment was cancelled. Your order has been saved, and you can complete it later.";

// Clear PayPal session data
unset($_SESSION['paypal_order_id']);

// Redirect to cart
redirect('/cart');