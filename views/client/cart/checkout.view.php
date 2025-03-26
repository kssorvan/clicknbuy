<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>

<section class="pt-5 mt-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/cart">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
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
                        <h4 class="mb-0">Checkout Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="/cart/checkout" method="POST">
                            <!-- Shipping Information -->
                            <div class="mb-4">
                                <h5>Shipping Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                            value="<?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                            value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Street Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="state" class="form-label">State/Province</label>
                                        <input type="text" class="form-control" id="state" name="state" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="zipcode" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" id="zipcode" name="zipcode" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select" id="country" name="country" required>
                                        <option value="Cambodia" selected>Cambodia</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Vietnam">Vietnam</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Singapore">Singapore</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Shipping Method -->
                            <div class="mb-4">
                                <h5>Shipping Method</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="shipping_method" id="shippingStandard" value="standard" checked>
                                    <label class="form-check-label" for="shippingStandard">
                                        <div class="d-flex justify-content-between w-100">
                                            <span>Standard Shipping (5-7 business days)</span>
                                            <span>Free</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="shipping_method" id="shippingExpress" value="express">
                                    <label class="form-check-label" for="shippingExpress">
                                        <div class="d-flex justify-content-between w-100">
                                            <span>Express Shipping (2-3 business days)</span>
                                            <span>$15.00</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_method" id="shippingNextDay" value="next_day">
                                    <label class="form-check-label" for="shippingNextDay">
                                        <div class="d-flex justify-content-between w-100">
                                            <span>Next Day Delivery (1 business day)</span>
                                            <span>$25.00</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Payment Method -->
                            <div class="mb-4">
                                <h5>Payment Method</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="paymentAba" value="aba_payway" checked>
                                    <label class="form-check-label d-flex align-items-center" for="paymentAba">
                                        <img src="/asset/images/aba-payway-logo.png" alt="ABA PayWay" height="24" class="me-2">
                                        ABA PayWay
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="paymentPaypal" value="paypal">
                                    <label class="form-check-label d-flex align-items-center" for="paymentPaypal">
                                        <img src="/asset/images/paypal-logo.png" alt="PayPal" height="24" class="me-2">
                                        PayPal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="paymentCod" value="cod">
                                    <label class="form-check-label d-flex align-items-center" for="paymentCod">
                                        <i class="bi bi-cash me-2" style="font-size: 1.2rem;"></i>
                                        Cash on Delivery
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Order Notes -->
                            <div class="mb-4">
                                <h5>Order Notes (Optional)</h5>
                                <textarea class="form-control" name="order_notes" rows="3" placeholder="Special instructions for delivery or any other notes"></textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Complete Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <?php 
                        $subtotal = 0;
                        foreach ($cartItems as $item) {
                            $itemTotal = $item['price'] * $item['quantity'];
                            $subtotal += $itemTotal;
                        }
                        
                        // Calculate tax (example: 8%)
                        $taxRate = 0.08;
                        $taxAmount = $subtotal * $taxRate;
                        
                        // Default shipping is free
                        $shippingCost = 0;
                        
                        // Calculate total
                        $total = $subtotal + $taxAmount + $shippingCost;
                        ?>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8%):</span>
                            <span>$<?= number_format($taxAmount, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 shipping-cost">
                            <span>Shipping:</span>
                            <span>$<?= number_format($shippingCost, 2) ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total:</strong>
                            <strong class="order-total">$<?= number_format($total, 2) ?></strong>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($cartItems as $item): ?>
                                <?php $itemTotal = $item['price'] * $item['quantity']; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold"><?= $item['name'] ?></span>
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

<script>
    // Update shipping cost and total based on shipping method selection
    document.addEventListener('DOMContentLoaded', function() {
        const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
        const shippingCostElement = document.querySelector('.shipping-cost span');
        const orderTotalElement = document.querySelector('.order-total');
        
        // Base amounts
        const subtotal = <?= $subtotal ?>;
        const tax = <?= $taxAmount ?>;
        
        shippingMethods.forEach(method => {
            method.addEventListener('change', function() {
                let shippingCost = 0;
                
                // Set shipping cost based on selected method
                if (this.value === 'express') {
                    shippingCost = 15;
                } else if (this.value === 'next_day') {
                    shippingCost = 25;
                }
                
                // Update shipping cost display
                shippingCostElement.textContent = '$' + shippingCost.toFixed(2);
                
                // Update total
                const total = subtotal + tax + shippingCost;
                orderTotalElement.textContent = '$' + total.toFixed(2);
            });
        });
    });
</script>

<?php require base_path('views/client/partials/footer.php') ?>