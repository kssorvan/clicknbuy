<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Orders Management</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Total Amount</th>
                                    <th>Payment Method</th>
                                    <th>Order Status</th>
                                    <th>COD Actions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= $order['order_id'] ?></td>
                                        <td><?= $order['customer_name'] ?></td>
                                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                        <td>
                                            <span class="badge 
                                                <?= $order['payment_method'] === 'Cash on Delivery' 
                                                    ? 'bg-warning' 
                                                    : 'bg-success' ?>">
                                                <?= $order['payment_method'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                <?= [
                                                    'pending' => 'bg-secondary',
                                                    'processing' => 'bg-info',
                                                    'shipped' => 'bg-primary',
                                                    'delivered' => 'bg-success',
                                                    'canceled' => 'bg-danger'
                                                ][$order['status']] ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($order['payment_method'] === 'Cash on Delivery'): ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                            type="button" 
                                                            data-bs-toggle="dropdown">
                                                        COD Options
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="/orders/cod-confirm" method="POST">
                                                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                                                <button type="submit" class="dropdown-item">
                                                                    Confirm Cash Received
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="/orders/cod-reject" method="POST">
                                                                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    Payment Issue
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="/orders/view/<?= $order['order_id'] ?>" 
                                                   class="btn btn-link text-info px-3 mb-0">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="/orders/edit/<?= $order['order_id'] ?>" 
                                                   class="btn btn-link text-dark px-3 mb-0">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require base_path('views/dashboard/partials/smallerfooter.php') ?>

<?php require base_path('views/dashboard/partials/footer.php') ?>
