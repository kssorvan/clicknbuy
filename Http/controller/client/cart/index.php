<?php// Http/controller/client/cart/index.php


use Core\App;
use Core\Database;

// Initialize cart if needed
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get fresh product data for each cart item
$cartItems = [];
$totalPrice = 0;
$totalItems = 0;
$outOfStockItems = [];
$stockChangedItems = [];

if (!empty($_SESSION['cart'])) {
    $db = App::resolve(Database::class);
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        // Get latest product data
        $product = $db->query("
            SELECT p.*, c.category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = ?
        ", [$item['id']])->find();
        
        if (!$product) {
            // Product no longer exists
            unset($_SESSION['cart'][$key]);
            continue;
        }
        
        // Check stock changes
        if ($product['stock'] <= 0) {
            $outOfStockItems[] = $product['name'];
            unset($_SESSION['cart'][$key]);
            continue;
        }
        
        // If stock is less than requested quantity, adjust
        if ($product['stock'] < $item['quantity']) {
            $item['quantity'] = $product['stock'];
            $stockChangedItems[] = $product['name'];
        }
        
        // Add to cart items array
        $itemTotal = $product['price'] * $item['quantity'];
        $cartItems[] = [
            'id' => $product['product_id'],
            'name' => $product['name'],
            'description' => $product['description'],
            'price' => $product['price'],
            'image' => $product['image_url'],
            'quantity' => $item['quantity'],
            'stock' => $product['stock'],
            'category' => $product['category_name'],
            'itemTotal' => $itemTotal
        ];
        
        $totalPrice += $itemTotal;
        $totalItems += $item['quantity'];
    }
    
    // Reindex the cart array after potential removals
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Get shipping rates (example)
$shippingOptions = [
    ['id' => 'standard', 'name' => 'Standard Shipping', 'price' => 0, 'days' => '5-7 business days'],
    ['id' => 'express', 'name' => 'Express Shipping', 'price' => 15, 'days' => '2-3 business days'],
    ['id' => 'next_day', 'name' => 'Next Day Delivery', 'price' => 25, 'days' => '1 business day']
];

// Set notices for stock changes
if (!empty($outOfStockItems)) {
    $_SESSION['notice'] = "Some items have been removed from your cart because they are out of stock: " . implode(", ", $outOfStockItems);
}

if (!empty($stockChangedItems)) {
    $_SESSION['notice'] = ($_SESSION['notice'] ?? "") . " Quantities adjusted for some items due to stock changes: " . implode(", ", $stockChangedItems);
}

view("client/cart/index.view.php", [
    'cartItems' => $cartItems,
    'totalPrice' => $totalPrice,
    'totalItems' => $totalItems,
    'shippingOptions' => $shippingOptions
]);