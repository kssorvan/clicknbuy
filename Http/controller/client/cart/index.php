<?php

session_start();

use Core\App;

$db = App::resolve('Core\Database');

// Initialize variables
$cartItems = [];
$totalPrice = 0;
$totalItems = 0;
$outOfStockItems = [];
$stockChangedItems = [];

// Fetch cart items (from database for logged-in users, session for guests)
$cartData = [];
if (isset($_SESSION['user'])) {
    // Fetch from cart table for logged-in user
    $cartData = $db->query(
        "SELECT product_id AS id, quantity 
         FROM cart 
         WHERE user_id = ?",
        [$_SESSION['user']['user_id']]
    )->get();
} else {
    // Use session cart for guests
    $cartData = $_SESSION['cart'] ?? [];
}

// Process cart items
if (!empty($cartData) && is_array($cartData)) {
    foreach ($cartData as $key => &$item) {
        // Validate cart item structure
        if (!isset($item['id']) || !isset($item['quantity']) || !is_numeric($item['quantity'])) {
            if (isset($_SESSION['user'])) {
                // Remove invalid item from cart table
                $db->query("DELETE FROM cart WHERE user_id = ? AND product_id = ?", [$_SESSION['user']['user_id'], $item['id']]);
            } else {
                unset($_SESSION['cart'][$key]);
            }
            continue;
        }

        // Get latest product data, excluding soft-deleted products
        $product = $db->query(
            "SELECT p.*, c.category_name 
             FROM products p 
             LEFT JOIN categories c 
             ON p.category_id = c.category_id 
             WHERE p.product_id = ? AND p.is_deleted = FALSE",
            [$item['id']]
        )->find();

        if (!$product) {
            // Product no longer exists or is soft-deleted
            if (isset($_SESSION['user'])) {
                $db->query("DELETE FROM cart WHERE user_id = ? AND product_id = ?", [$_SESSION['user']['user_id'], $item['id']]);
            } else {
                unset($_SESSION['cart'][$key]);
            }
            continue;
        }

        // Check stock changes
        if ($product['stock'] <= 0) {
            $outOfStockItems[] = $product['name'];
            if (isset($_SESSION['user'])) {
                $db->query("DELETE FROM cart WHERE user_id = ? AND product_id = ?", [$_SESSION['user']['user_id'], $item['id']]);
            } else {
                unset($_SESSION['cart'][$key]);
            }
            continue;
        }

        // If stock is less than requested quantity, adjust
        if ($product['stock'] < $item['quantity']) {
            $item['quantity'] = $product['stock'];
            $stockChangedItems[] = $product['name'];
            if (isset($_SESSION['user'])) {
                $db->query(
                    "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?",
                    [$item['quantity'], $_SESSION['user']['user_id'], $item['id']]
                );
            }
        }

        // Add to cart items array
        $itemTotal = $product['price'] * $item['quantity'];
        $cartItems[] = [
            'id' => $product['product_id'],
            'name' => $product['name'],
            'description' => $product['description'],
            'price' => number_format($product['price'], 2),
            'image' => $product['image_url'] ?? '/asset/images/default-product.jpg',
            'quantity' => (int) $item['quantity'],
            'stock' => $product['stock'],
            'category' => $product['category_name'] ?? 'Uncategorized',
            'itemTotal' => number_format($itemTotal, 2)
        ];

        $totalPrice += $itemTotal;
        $totalItems += $item['quantity'];
    }

    // Reindex the cart array after potential removals
    if (!isset($_SESSION['user'])) {
        $_SESSION['cart'] = array_values($_SESSION['cart'] ?? []);
    }
}

// Get shipping rates (example)
$shippingOptions = [
    ['id' => 'standard', 'name' => 'Standard Shipping', 'price' => 0, 'days' => '5-7 business days'],
    ['id' => 'express', 'name' => 'Express Shipping', 'price' => 15, 'days' => '2-3 business days'],
    ['id' => 'next_day', 'name' => 'Next Day Delivery', 'price' => 25, 'days' => '1 business day']
];

// Set notices for stock changes
$notices = [];
if (!empty($outOfStockItems)) {
    $notices[] = "Some items have been removed from your cart because they are out of stock: " . implode(", ", $outOfStockItems);
}
if (!empty($stockChangedItems)) {
    $notices[] = "Quantities adjusted for some items due to stock changes: " . implode(", ", $stockChangedItems);
}
if (!empty($notices)) {
    $_SESSION['notice'] = implode(" ", $notices);
}

view("client/cart/index.view.php", [
    'cartItems' => $cartItems,
    'totalPrice' => number_format($totalPrice, 2),
    'totalItems' => $totalItems,
    'shippingOptions' => $shippingOptions
]);