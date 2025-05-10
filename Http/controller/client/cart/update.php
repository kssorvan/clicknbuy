<?php
// Http/controller/client/cart/update.php


use Core\App;
use Core\Database;

// Get product ID and action
$productId = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? null;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if (!$productId || !isset($_SESSION['cart'])) {
    redirect('/cart');
    exit();
}

// Get product info to check stock
$db = App::resolve(Database::class);
$product = $db->query("SELECT * FROM products WHERE product_id = ?", [$productId])->find();

if (!$product) {
    redirect('/cart');
    exit();
}

// Update quantity based on action
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $productId) {
        switch ($action) {
            case 'increase':
                if ($item['quantity'] < $product['stock']) {
                    $item['quantity']++;
                }
                break;
            case 'decrease':
                if ($item['quantity'] > 1) {
                    $item['quantity']--;
                }
                break;
            case 'manual':
                // Validate and set manual quantity
                if ($quantity > 0 && $quantity <= $product['stock']) {
                    $item['quantity'] = $quantity;
                } else if ($quantity > $product['stock']) {
                    $item['quantity'] = $product['stock'];
                    $_SESSION['cart_message'] = "Quantity adjusted to available stock";
                } else {
                    $item['quantity'] = 1;
                }
                break;
        }
        break;
    }
}

redirect('/cart');