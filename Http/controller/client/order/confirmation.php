<?php// Http/controller/client/order/confirmation.php


use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    redirect('/profile');
    exit();
}

$db = App::resolve(Database::class);
$userId = $_SESSION['user']['id'];

// Get order details
$order = $db->query("
    SELECT * FROM orders
    WHERE order_id = ? AND user_id = ?
", [$orderId, $userId])->find();

if (!$order) {
    abort(404);
}

// Get order items
$orderItems = $db->query("
    SELECT oi.*, p.name, p.image_url
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
", [$orderId])->get();

// Calculate order summary
$subtotal = array_reduce($orderItems, function($carry, $item) {
    return $carry + ($item['price'] * $item['quantity']);
}, 0);

view("client/order/confirmation.view.php", [
    'order' => $order,
    'orderItems' => $orderItems,
    'subtotal' => $subtotal
]);