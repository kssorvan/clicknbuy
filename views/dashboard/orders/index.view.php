<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Order Management</h1>
</div>

<!-- Orders Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="ordersTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($orders) && !empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= $order['order_id'] ?></td>
                                <td><?= $order['user_name'] ?? 'N/A' ?></td>
                                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <?php 
                                        $statusClass = '';
                                        switch ($order['status']) {
                                            case 'pending':
                                                $statusClass = 'warning';
                                                break;
                                            case 'processing':
                                                $statusClass = 'info';
                                                break;
                                            case 'shipped':
                                                $statusClass = 'primary';
                                                break;
                                            case 'delivered':
                                                $statusClass = 'success';
                                                break;
                                            case 'canceled':
                                                $statusClass = 'danger';
                                                break;
                                            default:
                                                $statusClass = 'secondary';
                                        }
                                    ?>
                                    <span class="badge badge-<?= $statusClass ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewOrderModal<?= $order['order_id'] ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#updateOrderModal<?= $order['order_id'] ?>">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                    <form action="/tborders/delete" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this order?');">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No orders found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Sample Order for Display (Replace with actual data when available) -->
<div class="modal fade" id="viewOrderModal123" tabindex="-1" role="dialog" aria-labelledby="viewOrderModal123Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOrderModal123Label">Order #123 Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Customer Information</h6>
                        <p>Name: John Doe<br>
                        Email: john@example.com<br>
                        Phone: 555-123-4567</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Order Information</h6>
                        <p>Order Date: March 15, 2023<br>
                        Status: <span class="badge badge-warning">Pending</span><br>
                        Payment Method: Credit Card</p>
                    </div>
                </div>
                
                <h6 class="font-weight-bold">Shipping Address</h6>
                <p>123 Main St, Apt 4B<br>
                New York, NY 10001<br>
                United States</p>
                
                <h6 class="font-weight-bold">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Honda CBR 650R</td>
                                <td>$7,999.00</td>
                                <td>1</td>
                                <td>$7,999.00</td>
                            </tr>
                            <tr>
                                <td>Motorcycle Helmet</td>
                                <td>$299.99</td>
                                <td>1</td>
                                <td>$299.99</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Subtotal</td>
                                <td>$8,298.99</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Tax</td>
                                <td>$497.94</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Shipping</td>
                                <td>$0.00</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total</td>
                                <td class="font-weight-bold">$8,796.93</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        "order": [[0, "desc"]]
    });
});
</script>

<?php require base_path('views/dashboard/partials/footer.php') ?>