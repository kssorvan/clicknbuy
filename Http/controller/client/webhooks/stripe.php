<?php// Http/controller/webhooks/stripe.php


use Core\App;
use Core\Database;
use Stripe\Stripe;
use Stripe\WebhookSignature;
use Stripe\Exception\SignatureVerificationException;

// Get the configuration
$config = require base_path('config.php');

// Get the webhook payload
$payload = @file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    // Initialize Stripe
    Stripe::setApiKey($config['stripe']['secret_key']);
    
    // Verify the webhook signature
    $event = WebhookSignature::constructEvent(
        $payload, $sigHeader, $config['stripe']['webhook_secret']
    );
    
    // Get the database
    $db = App::resolve(Database::class);
    
    // Handle the event based on its type
    switch ($event->type) {
        case 'payment_intent.succeeded':
            $paymentIntent = $event->data->object;
            $transactionId = $paymentIntent->id;
            
            // Find transaction by provider_transaction_id
            $transaction = $db->query("
                SELECT * FROM payment_transactions
                WHERE provider_transaction_id = ?
            ", [$transactionId])->find();
            
            if ($transaction) {
                // Update transaction status
                $db->query("
                    UPDATE payment_transactions
                    SET status = 'completed', updated_at = CURRENT_TIMESTAMP
                    WHERE transaction_id = ?
                ", [$transaction['transaction_id']]);
                
                // Update order status
                $db->query("
                    UPDATE orders
                    SET status = 'processing', updated_at = CURRENT_TIMESTAMP
                    WHERE order_id = ?
                ", [$transaction['order_id']]);
            }
            break;
            
        case 'payment_intent.payment_failed':
            $paymentIntent = $event->data->object;
            $transactionId = $paymentIntent->id;
            
            // Find transaction by provider_transaction_id
            $transaction = $db->query("
                SELECT * FROM payment_transactions
                WHERE provider_transaction_id = ?
            ", [$transactionId])->find();
            
            if ($transaction) {
                // Update transaction status
                $db->query("
                    UPDATE payment_transactions
                    SET status = 'failed', updated_at = CURRENT_TIMESTAMP
                    WHERE transaction_id = ?
                ", [$transaction['transaction_id']]);
                
                // Update order status
                $db->query("
                    UPDATE orders
                    SET status = 'failed', updated_at = CURRENT_TIMESTAMP
                    WHERE order_id = ?
                ", [$transaction['order_id']]);
            }
            break;
            
        case 'charge.refunded':
            $charge = $event->data->object;
            $transactionId = $charge->payment_intent;
            
            // Find transaction by provider_transaction_id
            $transaction = $db->query("
                SELECT * FROM payment_transactions
                WHERE provider_transaction_id = ?
            ", [$transactionId])->find();
            
            if ($transaction) {
                // Update transaction status
                $db->query("
                    UPDATE payment_transactions
                    SET status = 'refunded', updated_at = CURRENT_TIMESTAMP
                    WHERE transaction_id = ?
                ", [$transaction['transaction_id']]);
                
                // Update order status
                $db->query("
                    UPDATE orders
                    SET status = 'refunded', updated_at = CURRENT_TIMESTAMP
                    WHERE order_id = ?
                ", [$transaction['order_id']]);
            }
            break;
    }
    
    http_response_code(200);
    echo json_encode(['status' => 'success']);
    
} catch (SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    echo json_encode(['error' => 'Invalid signature']);
} catch (\Exception $e) {
    // Other errors
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}