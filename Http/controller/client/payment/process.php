<?php// Http/controller/client/payment/process.php


use Core\App;
use Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    redirect('/');
    exit();
}

// Check if there's a pending order
if (!isset($_SESSION['pending_order']) || !isset($_SESSION['transaction_id'])) {
    redirect('/cart');
    exit();
}

$pendingOrder = $_SESSION['pending_order'];
$transactionId = $_SESSION['transaction_id'];
$paymentOption = $_POST['payment_option'] ?? 'cards';

// Get configuration
$config = require base_path('config.php');
$abaConfig = $config['abapayway'];

// Create database entry for pending order
$db = App::resolve(Database::class);

// Start transaction
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
        'abapayway_' . $paymentOption,
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
    
    // Store transaction info
    $db->query("
        INSERT INTO payment_transactions (
            order_id, payment_provider, provider_transaction_id, amount, status, payment_method
        ) VALUES (?, ?, ?, ?, ?, ?)
    ", [
        $orderId,
        'abapayway',
        $transactionId,
        $pendingOrder['total'],
        'pending',
        'abapayway_' . $paymentOption
    ]);
    
    // Commit transaction
    $db->conncetion->commit();
    
    // Prepare ABA PayWay request data
    $reqTime = date('YmdHis');
    $amount = number_format($pendingOrder['total'], 2, '.', '');
    
    $items = [];
    foreach ($pendingOrder['items'] as $item) {
        $product = $db->query("SELECT name FROM products WHERE product_id = ?", [$item['product_id']])->find();
        $items[] = [
            'name' => $product['name'],
            'quantity' => $item['quantity'],
            'price' => number_format($item['price'], 2, '.', '')
        ];
    }
    
    $returnUrl = $abaConfig['return_url'] . '?order_id=' . $orderId;
    $continueSuccessUrl = $abaConfig['continue_success_url'] . '/' . $orderId;
    
    $paymentRequest = [
        'merchant_id' => $abaConfig['merchant_id'],
        'req_time' => $reqTime,
        'tran_id' => $transactionId,
        'amount' => $amount,
        'items' => json_encode($items),
        'shipping' => number_format($pendingOrder['shipping'], 2, '.', ''),
        'tax' => number_format($pendingOrder['tax'], 2, '.', ''),
        'currency' => 'USD',
        'firstname' => $_SESSION['user']['name'],
        'email' => $_SESSION['user']['email'],
        'return_url' => $returnUrl,
        'continue_success_url' => $continueSuccessUrl,
        'payment_option' => $paymentOption,
        'type' => 'purchase',
        'version' => '1.0'
    ];
    
    // Generate HMAC signature (required by ABA PayWay)
    $stringToHash = implode('', array_values($paymentRequest));
    $hash = hash_hmac('sha512', $stringToHash, $abaConfig['merchant_api_key']);
    
    $paymentRequest['hash'] = $hash;
    
    // Save order ID in session for callback handling
    $_SESSION['processing_order_id'] = $orderId;
    
    // Redirect to ABA PayWay checkout page
    $checkoutUrl = $abaConfig['base_url'] . '/api/payment-gateway/v1/payments/purchase';
    
    // Store data for form submission
    $_SESSION['aba_payment_data'] = $paymentRequest;
    redirect('/payment/redirect');
    
} catch (\Exception $e) {
    // Rollback on error
    $db->conncetion->rollBack();
    
    $_SESSION['error'] = "An error occurred while processing your order: " . $e->getMessage();
    redirect('/payment');
}