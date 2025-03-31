
<?php
// Http/controller/client/cart/addcart.php
session_start();

use Core\App;

$db = App::resolve('Core\Database');

// Get the product ID from the URL
$productId = isset($params['id']) ? (int)$params['id'] : null;

if (!$productId) {
    $_SESSION['error'] = 'Invalid product ID.';
    header('Location: /products');
    exit();
}

// Fetch the product
$product = $db->query(
    "SELECT * FROM products WHERE product_id = ? AND is_deleted = FALSE",
    [$productId]
)->find();

if (!$product) {
    $_SESSION['error'] = 'Product not found.';
    header('Location: /products');
    exit();
}

if ($product['stock'] <= 0) {
    $_SESSION['error'] = 'This product is out of stock.';
    header('Location: /products');
    exit();
}

// Add to cart
$quantity = 1; // Default quantity

if (isset($_SESSION['user'])) {
    // Add to cart table for logged-in user
    $existing = $db->query(
        "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?",
        [$_SESSION['user']['user_id'], $productId]
    )->find();

    if ($existing) {
        // Update quantity
        $newQuantity = $existing['quantity'] + $quantity;
        if ($newQuantity > $product['stock']) {
            $newQuantity = $product['stock'];
            $_SESSION['notice'] = 'Quantity adjusted due to stock limit.';
        }
        $db->query(
            "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?",
            [$newQuantity, $_SESSION['user']['user_id'], $productId]
        );
    } else {
        // Insert new cart item
        $db->query(
            "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)",
            [$_SESSION['user']['user_id'], $productId, $quantity]
        );
    }
} else {
    // Add to session cart for guests
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] += $quantity;
            if ($item['quantity'] > $product['stock']) {
                $item['quantity'] = $product['stock'];
                $_SESSION['notice'] = 'Quantity adjusted due to stock limit.';
            }
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $productId,
            'quantity' => $quantity
        ];
    }
}

$_SESSION['success'] = 'Product added to cart successfully.';
header('Location: /cart');
exit();