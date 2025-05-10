<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>

<section class="pt-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="mb-3">Thank You for Your Order!</h2>
                        <p class="mb-4">Your order has been placed successfully. We've sent a confirmation email to your inbox.</p>
                        
                        <div class="mb-4">
                            <h4>Order #<?= $order['order_id'] ?></h4>
                            <p class="text-muted">Placed on <?= date('F j, Y', strtotime($order['created_at'])) ?></p>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/profile" class="btn btn-primary">View My Orders</a>
                            <a href="/products" class="btn btn-outline-secondary">Continue Shopping</a>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="mb-0">Order Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Shipping Information</h5>
                                <p class="mb-0"><?= htmlspecialchars($order['shipping_address']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Payment Method</h5>
                                <p class="mb-0">
                                    <?php if ($order['payment_method'] === 'credit_card'): ?>
                                        Credit Card
                                    <?php elseif ($order['payment_method'] === 'paypal'): ?>
                                        PayPal
                                    <?php else: ?>
                                        <?= ucfirst($order['payment_method']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        
                        <h5>Order Items</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                        <?php $itemTotal = $item['price'] * $item['quantity']; ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= $item['image_url'] ?>" alt="<?= $item['name'] ?>" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0"><?= $item['name'] ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center"><?= $item['quantity'] ?></td>
                                            <td class="text-end">$<?= number_format($item['price'], 2) ?></td>
                                            <td class="text-end">$<?= number_format($itemTotal, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Subtotal:</td>
                                        <td class="text-end">$<?= number_format($subtotal, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Tax:</td>
                                        <td class="text-end">$<?= number_format($order['tax_amount'], 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Shipping:</td>
                                        <td class="text-end">$<?= number_format($order['shipping_amount'], 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require base_path('views/client/partials/footer.php') ?>