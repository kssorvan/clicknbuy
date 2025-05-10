<!-- views/client/payment/paypal.view.php -->
<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>

<section class="pt-5 mt-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/cart">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Payment with PayPal</h4>
                    </div>
                    <div class="card-body p-5">
                        <p class="text-center mb-4">Complete your purchase with PayPal. You can pay with your PayPal account or credit card.</p>
                        
                        <div class="text-center mb-4">
                            <p>Order Total: <strong>$<?= $amount ?></strong></p>
                            <p class="small text-muted">Order #<?= $orderId ?></p>
                        </div>
                        
                        <!-- PayPal Button Container -->
                        <div id="paypal-button-container" class="mt-4"></div>
                        
                        <div class="text-center mt-4">
                            <a href="/cart" class="btn btn-outline-secondary">Cancel and Return to Cart</a>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5>Secure Payment with PayPal</h5>
                        <p class="mb-0">Your payment is processed securely through PayPal. We do not store your payment details.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PayPal JavaScript SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=<?= $clientId ?>&currency=USD"></script>

<script>
    paypal.Buttons({
        // Set up the transaction
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?= $amount ?>'
                    },
                    description: 'Order #<?= $orderId ?>'
                }]
            });
        },
        
        // Finalize the transaction
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(orderData) {
                // Show a success message
                document.getElementById('paypal-button-container').innerHTML = '<div class="alert alert-success text-center p-4"><i class="bi bi-check-circle-fill me-2"></i>Payment successful! Processing your order...</div>';
                
                // Redirect to success page
                window.location.href = '<?= $returnUrl ?>?order_id=<?= $orderId ?>&paypal_order_id=' + orderData.id;
            });
        },
        
        // Handle errors
        onError: function(err) {
            console.error('PayPal Error:', err);
            document.getElementById('paypal-button-container').innerHTML = '<div class="alert alert-danger text-center p-4"><i class="bi bi-exclamation-triangle-fill me-2"></i>Payment failed. Please try again or choose another payment method.</div>';
        },
        
        // Buyer cancelled
        onCancel: function(data) {
            window.location.href = '<?= $cancelUrl ?>';
        }
    }).render('#paypal-button-container');
</script>

<?php require base_path('views/client/partials/footer.php') ?>