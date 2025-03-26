<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>

<section class="order-confirmation container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h3>Order Confirmation</h3>
                </div>
                <div class="card-body">
                    <div class="order-details">
                        <h4>Order #<?= $order['order_id'] ?></h4>
                        <p>Date: <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
                    </div>
                    
                    <div class="payment-details">
                        <h5>Payment Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Payment Method:</strong> 
                                <?= htmlspecialchars($order['payment_method']) ?>
                            </div>
                            
                            <?php if ($order['payment_method'] === 'Credit Card'): ?>
                                <div class="col-md-6">
                                    <strong>Card:</strong> 
                                    **** **** **** <?= $order['card_last_four'] ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($order['payment_method'] === 'Cash on Delivery'): ?>
                                <div class="col-md-12 mt-3">
                                    <div class="alert alert-info">
                                        <strong>COD Instructions:</strong>
                                        <ul>
                                            <li>Please prepare exact cash amount</li>
                                            <li>Payment will be collected upon delivery</li>
                                            <li>Our delivery partner accepts cash and minor card transactions</li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="order-status mt-4">
                        <h5>Order Status</h5>
                        <div class="progress">
                            <?php 
                            $statusClass = [
                                'pending' => 'bg-warning',
                                'processing' => 'bg-info',
                                'shipped' => 'bg-primary',
                                'delivered' => 'bg-success',
                                'canceled' => 'bg-danger'
                            ];
                            
                            $statusPercentage = match($order['status']) {
                                'pending' => 25,
                                'processing' => 50,
                                'shipped' => 75,
                                'delivered' => 100,
                                'canceled' => 0,
                                default => 0
                            };
                            ?>
                            <div class="progress-bar <?= $statusClass[$order['status']] ?>" 
                                 role="progressbar" 
                                 style="width: <?= $statusPercentage ?>%">
                                <?= ucfirst($order['status']) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-items mt-4">
                        <h5>Order Items</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td>
                                            <img src="<?= $item['image_url'] ?>" 
                                                 alt="<?= $item['name'] ?>" 
                                                 style="width: 50px; margin-right: 10px;">
                                            <?= $item['name'] ?>
                                        </td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>$<?= number_format($item['price'], 2) ?></td>
                                        <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                    <td>$<?= number_format($order['total_amount'] - $order['tax_amount'] - $order['shipping_amount'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Tax:</strong></td>
                                    <td>$<?= number_format($order['tax_amount'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Shipping:</strong></td>
                                    <td>$<?= number_format($order['shipping_amount'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="shipping-details mt-4">
                        <h5>Shipping Information</h5>
                        <p><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                        <p><strong>Shipping Method:</strong> <?= $order['shipping_method'] ?? 'Standard Shipping' ?></p>
                    </div>
                    
                    <div class="actions mt-4 text-center">
                        <a href="/orders" class="btn btn-primary me-2">View All Orders</a>
                        <button onclick="window.print()" class="btn btn-outline-secondary">
                            <i class="bi bi-printer"></i> Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require base_path('views/client/partials/footer.php') ?>