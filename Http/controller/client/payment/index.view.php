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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']) ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Payment Information</h4>
                    </div>
                    <div class="card-body">
                        <form id="payment-form" action="/payment/process" method="POST">
                            <div class="mb-4">
                                <h5>Billing Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h5>Card Information</h5>
                                <div class="mb-3">
                                    <label for="card-element" class="form-label">Credit or Debit Card</label>
                                    <div id="card-element" class="form-control" style="height: 2.4em; padding-top: .7em;"></div>
                                    <div id="card-errors" class="text-danger mt-2"></div>
                                </div>
                            </div>
                            
                            <input type="hidden" id="payment_token" name="payment_token">
                            <input type="hidden" name="payment_method" value="credit_card">
                            
                            <div class="d-grid">
                                <button id="submit-button" type="submit" class="btn btn-primary btn-lg">
                                    Pay $<?= number_format($order['total'], 2) ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5>Secure Payment</h5>
                        <p class="mb-0">All payment information is encrypted and secure. We do not store your credit card details.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$<?= number_format($order['subtotal'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>$<?= number_format($order['tax'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>$<?= number_format($order['shipping'], 2) ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total:</strong>
                            <strong>$<?= number_format($order['total'], 2) ?></strong>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($order['items'] as $item): ?>
                                <?php
                                $product = $db->query("SELECT name FROM products WHERE product_id = ?", [$item['product_id']])->find();
                                $itemTotal = $item['price'] * $item['quantity'];
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold"><?= $product['name'] ?></span>
                                        <div class="text-muted">
                                            <?= $item['quantity'] ?> × $<?= number_format($item['price'], 2) ?>
                                        </div>
                                    </div>
                                    <span>$<?= number_format($itemTotal, 2) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stripe JavaScript -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    const stripe = Stripe('<?= $config["stripe"]["public_key"] ?>');
    const elements = stripe.elements();
    
    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });
    
    cardElement.mount('#card-element');
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const cardErrors = document.getElementById('card-errors');
    
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        // Disable the submit button to prevent repeated clicks
        submitButton.disabled = true;
        submitButton.textContent = 'Processing Payment...';
        
        try {
            // Create payment method
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement
            });
            
            if (error) {
                // Show error and re-enable button
                cardErrors.textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Pay $<?= number_format($order['total'], 2) ?>';
            } else {
                // Set the payment token and submit the form
                document.getElementById('payment_token').value = paymentMethod.id;
                form.submit();
            }
        } catch (err) {
            cardErrors.textContent = 'An unexpected error occurred. Please try again.';
            submitButton.disabled = false;
            submitButton.textContent = 'Pay $<?= number_format($order['total'], 2) ?>';
        }
    });
</script>

<?php require base_path('views/client/partials/footer.php') ?>