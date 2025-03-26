<?php// Http/controller/client/cart/checkout.php


use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['redirect_after_login'] = '/cart';
    $_SESSION['error'] = "Please log in to complete your purchase.";
    redirect('/');
    exit();
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Your cart is empty.";
    redirect('/cart');
    exit();
}

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';
$zipcode = $_POST['zipcode'] ?? '';
$country = $_POST['country'] ?? '';
$phone = $_POST['phone'] ?? '';
$shippingMethod = $_POST['shipping_method'] ?? 'standard';
$paymentMethod = $_POST['payment_method'] ?? 'credit_card';

// Validate form data
$errors = [];

if (empty($name) || empty($email) || empty($address) || empty($city) || 
    empty($state) || empty($zipcode) || empty($country) || empty($phone)) {
    $_SESSION['checkout_errors'] = "Please fill in all required fields.";
    redirect('/cart');
    exit();
}

$fullAddress = "{$address}, {$city}, {$state} {$zipcode}, {$country}";

$db = App::resolve(Database::class);
$userId = $_SESSION['user']['id'];

// Calculate order totals
$subtotal = 0;
$orderItems = [];
$shippingCost = 0;

// Set shipping cost based on method
switch ($shippingMethod) {
    case 'express':
        $shippingCost = 15.00;
        break;
    case 'next_day':
        $shippingCost = 25.00;
        break;
    default:
        $shippingCost = 0.00;
}

// Validate products and calculate totals
foreach ($_SESSION['cart'] as $item) {
    // Get current product price and stock
    $product = $db->query("SELECT * FROM products WHERE product_id = ?", [$item['id']])->find();
    
    if (!$product) {
        $_SESSION['error'] = "One or more products in your cart are no longer available.";
        redirect('/cart');
        exit();
    }
    
    if ($product['stock'] < $item['quantity']) {
        $_SESSION['error'] = "Some products in your cart have insufficient stock. Please review your cart.";
        redirect('/cart');
        exit();
    }
    
    $itemTotal = $product['price'] * $item['quantity'];
    $subtotal += $itemTotal;
    
    $orderItems[] = [
        'product_id' => $item['id'],
        'quantity' => $item['quantity'],
        'price' => $product['price']
    ];
}

// Calculate tax (example: 8%)
$taxRate = 0.08;
$taxAmount = $subtotal * $taxRate;

// Calculate final total
$totalAmount = $subtotal + $taxAmount + $shippingCost;

// Store order details in session for payment processing
$_SESSION['pending_order'] = [
    'user_id' => $userId,
    'items' => $orderItems,
    'subtotal' => $subtotal,
    'tax' => $taxAmount,
    'shipping' => $shippingCost,
    'total' => $totalAmount,
    'shipping_address' => $fullAddress,
    'phone' => $phone,
    'payment_method' => $paymentMethod,
    'shipping_method' => $shippingMethod
];

// Redirect to payment page
redirect('/payment');