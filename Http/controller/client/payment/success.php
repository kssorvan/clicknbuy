<?php// Http/controller/client/payment/success.php


use Core\App;
use Core\Database;

$orderId = $_GET['order_id'] ?? null;
$tranId = $_GET['tran_id'] ?? null;
$status = $_GET['status'] ?? null;

if (!$orderId || !$tranId || !$status) {
    redirect('/');
    exit();
}

$db = App::resolve(Database::class);

// Update the transaction and order status
if ($status === 'success') {
    $db->query("
        UPDATE payment_transactions
        SET status = 'completed', updated_at = CURRENT_TIMESTAMP
        WHERE order_id = ? AND provider_transaction_id = ?
    ", [$orderId, $tranId]);
    
    $db->query("
        UPDATE orders
        SET status = 'processing', updated_at = CURRENT_TIMESTAMP
        WHERE order_id = ?
    ", [$orderId]);
    
    // Clear cart and pending order
    $_SESSION['cart'] = [];
    unset($_SESSION['pending_order']);
    unset($_SESSION['transaction_id']);
    unset($_SESSION['aba_payment_data']);
    unset($_SESSION['processing_order_id']);
    
    $_SESSION['success'] = "Payment successful! Your order is being processed.";
    redirect("/order/confirmation/" . $orderId);
} else {
    $db->query("
        UPDATE payment_transactions
        SET status = 'failed', updated_at = CURRENT_TIMESTAMP
        WHERE order_id = ? AND provider_transaction_id = ?
    ", [$orderId, $tranId]);
    
    $db->query("
        UPDATE orders
        SET status = 'failed', updated_at = CURRENT_TIMESTAMP
        WHERE order_id = ?
    ", [$orderId]);
    
    $_SESSION['error'] = "Payment failed. Please try again or contact customer support.";
    redirect('/cart');
}