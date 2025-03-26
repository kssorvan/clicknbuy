<?php
// Http/controller/client/cart/checkout.php

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

$db = App::resolve(Database::class);

// Handle GET request (display checkout form)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get cart items for display
    $cartItems = [];
    $subtotal = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $product = $db->query("
            SELECT p.*, c.category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = ?
        ", [$item['id']])->find();
        
        if ($product) {
            $itemTotal = $product['price'] * $item['quantity'];
            $subtotal += $itemTotal;
            
            $cartItems[] = [
                'id' => $product['product_id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image_url'],
                'quantity' => $item['quantity'],
                'itemTotal' => $itemTotal
            ];
        }
    }
    
    // Get user's previous addresses if available
    $userAddresses = [];
    if (isset($_SESSION['user']['id'])) {
        $userAddresses = $db->query("
            SELECT DISTINCT shipping_address FROM orders
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 3
        ", [$_SESSION['user']['id']])->get();
    }
    
    view("client/cart/checkout.view.php", [
        'cartItems' => $cartItems,
        'subtotal' => $subtotal,
        'userAddresses' => $userAddresses
    ]);
    exit();
}

// For POST request, process the checkout
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';
$zipcode = $_POST['zipcode'] ?? '';
$country = $_POST['country'] ?? '';
$phone = $_POST['phone'] ?? '';
$shippingMethod = $_POST['shipping_method'] ?? 'standard';
$paymentMethod = $_POST['payment_method'] ?? 'aba_payway';
$orderNotes = $_POST['order_notes'] ?? '';

// Validate form data
$errors = [];

if (empty($name)) {
    $errors['name'] = "Name is required";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Valid email is required";
}

if (empty($address)) {
    $errors['address'] = "Address is required";
}

if (empty($city)) {
    $errors['city'] = "City is required";
}

if (empty($state)) {
    $errors['state'] = "State/Province is required";
}

if (empty($zipcode)) {
    $errors['zipcode'] = "Postal code is required";
}

if (empty($country)) {
    $errors['country'] = "Country is required";
}

if (empty($phone)) {
    $errors['phone'] = "Phone number is required";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect('/cart/checkout');
    exit();
}

$fullAddress = "{$address}, {$city}, {$state} {$zipcode}, {$country}";
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
    default: // standard
        $shippingCost = 0.00;
        break;
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
        'price' => $product['price'],
        'name' => $product['name'], // Include name for receipt
        'image' => $product['image_url'] // Include image for receipt
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
    'customer_name' => $name,
    'customer_email' => $email,
    'customer_phone' => $phone,
    'items' => $orderItems,
    'subtotal' => $subtotal,
    'tax' => $taxAmount,
    'shipping' => $shippingCost,
    'total' => $totalAmount,
    'shipping_address' => $fullAddress,
    'payment_method' => $paymentMethod,
    'shipping_method' => $shippingMethod,
    'notes' => $orderNotes,
    'timestamp' => time()
];

// Process based on payment method
switch ($paymentMethod) {
    case 'cod':
        // For Cash on Delivery, create the order immediately
        try {
            // Start a transaction
            $db->conncetion->beginTransaction();
            
            // Insert order
            $db->query("
                INSERT INTO orders (
                    user_id, total_amount, status, shipping_address, 
                    payment_method, shipping_method, tax_amount, shipping_amount
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $userId,
                $totalAmount,
                'pending', // COD orders start as pending
                $fullAddress,
                'cod',
                $shippingMethod,
                $taxAmount,
                $shippingCost
            ]);
            
            $orderId = $db->conncetion->lastInsertId();
            
            // Insert order items
            foreach ($orderItems as $item) {
                $db->query("
                    INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)
                ", [
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);
                
                // Update product stock
                $db->query("
                    UPDATE products 
                    SET stock = stock - ?, updated_at = CURRENT_TIMESTAMP
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
                $totalAmount,
                'pending',
                'cod',
                json_encode([
                    'method' => 'Cash on Delivery',
                    'customer_name' => $name,
                    'customer_phone' => $phone,
                    'notes' => $orderNotes
                ])
            ]);
            
            // Commit the transaction
            $db->conncetion->commit();
            
            // Clear cart
            $_SESSION['cart'] = [];
            unset($_SESSION['pending_order']);
            
            // Set success message
            $_SESSION['success'] = "Your order has been placed successfully! We will contact you to confirm delivery. Order #" . $orderId;
            
            // Redirect to confirmation
            redirect('/order/confirmation/' . $orderId);
            
        } catch (\Exception $e) {
            // Rollback if something went wrong
            $db->conncetion->rollBack();
            
            $_SESSION['error'] = "An error occurred while processing your order: " . $e->getMessage();
            redirect('/cart/checkout');
            exit();
        }
        break;
        
    case 'paypal':
        redirect('/payment/paypal');
        break;
        
    case 'aba_payway':
    default:
        redirect('/payment/aba-payway');
        break;
}