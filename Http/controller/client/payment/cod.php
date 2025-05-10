<?php// Http/controller/client/payment/cod.php


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
        'cod',
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
    
    // Update product stock
    foreach ($pendingOrder['items'] as $item) {
        $db->query("
            UPDATE products 
            SET stock = stock - ? 
            WHERE product_id = ?
        ", [
            $item['quantity'],
            $item['product_id']
        ]);
    }
    
    // Store payment information
    $db->query("
        INSERT INTO payment_transactions (
            order_id, payment_provider, provider_transaction_id, 
            amount, status, payment_method, payment_details
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ", [
        $orderId,
        'Internal',
        'COD-' . $orderId,
        $pendingOrder['total'],
        'pending',
        'cod',
        json_encode(['method' => 'Cash on Delivery'])
    ]);
    
    // Commit the transaction
    $db->conncetion->commit();
    
    // Clear cart and pending order
    $_SESSION['cart'] = [];
    unset($_SESSION['pending_order']);
    
    // Set success message
    $_SESSION['success'] = "Your order has been placed successfully! Order #" . $orderId;
    
    // Redirect to confirmation page
    redirect("/order/confirmation/" . $orderId);
    
} catch (\Exception $e) {
    // Rollback if something went wrong
    $db->conncetion->rollBack();
    
    $_SESSION['error'] = "An error occurred while processing your order: " . $e->getMessage();
    redirect('/cart');
}
// Add to your cod.php controller
// Simple email function (you may want to use a proper email library)
$to = $_SESSION['user']['email'];
$subject = "Order Confirmation - #" . $orderId;
$message = "
    <html>
    <head>
        <title>Your Order Confirmation</title>
    </head>
    <body>
        <h2>Thank you for your order!</h2>
        <p>Your order #" . $orderId . " has been received and will be processed shortly.</p>
        <p><strong>Payment Method:</strong> Cash on Delivery</p>
        <p><strong>Total Amount:</strong> $" . number_format($pendingOrder['total'], 2) . "</p>
        <p>Please have the exact amount ready when your order is delivered.</p>
        <p>Thank you for shopping with us!</p>
    </body>
    </html>
";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: your@website.com\r\n";

mail($to, $subject, $message, $headers);