<?php

use Core\App;
use Core\Database;
use Core\Validator;
use Core\ValidationException;

$db = App::resolve(Database::class);
$errors = [];

if (!Validator::email($_POST['email'])) {
    $errors['email'] = 'Please provide a valid email address.';
}



if (empty($_SESSION['cart'])) {
    $_SESSION['cart_message'] = 'Your cart is empty.';
    redirect('/cart');
}

if (!empty($errors)) {
    $_SESSION['cart_message'] = implode(', ', $errors);
    redirect('/cart');
}

$db->conncetion->beginTransaction();

try {
    $totalPrice = array_reduce($_SESSION['cart'], function ($total, $item) {
        return $total + ($item['price'] * $item['quantity']);
    }, 0);

    $userId = $_SESSION['user']['id'] ?? null;
    $db->query("
        INSERT INTO orders (user_id, total_price, status)
        VALUES (?, ?, 'pending')
    ", [$userId, $totalPrice]);
    $orderId = $db->conncetion->lastInsertId();
    $stmt = $db->conncetion->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");

    // Update product stock
    $stockStmt = $db->conncetion->prepare("
        UPDATE products 
        SET stock = stock - ? 
        WHERE product_id = ?
    ");

    foreach ($_SESSION['cart'] as $item) {
        $stmt->execute([
            $orderId,
            $item['id'],
            $item['quantity'],
            $item['price']
        ]);
        $stockStmt->execute([
            $item['quantity'],
            $item['id']
        ]);
    }

    $db->conncetion->commit();
    unset($_SESSION['cart']);
    $_SESSION['cart_message'] = "Order #$orderId placed successfully!";
    redirect('/cart');
} catch (\Exception $e) {
    $db->conncetion->rollBack();
    error_log('Checkout failed: ' . $e->getMessage());

    $_SESSION['cart_message'] = 'An error occurred while processing your order.';
    redirect('/cart');
}
