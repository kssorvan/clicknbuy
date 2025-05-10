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
                                <td>#<?php echo $order['order_id']; ?></td>
                                <td><?php echo htmlspecialchars($order['user_name'] ?? 'N/A'); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
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
                                    <span class="badge badge-<?php echo $statusClass; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewOrderModal<?php echo $order['order_id']; ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#updateOrderModal<?php echo $order['order_id']; ?>">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                    <form action="/tborders/delete" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this order?');">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- View Order Modal -->
                            <div class="modal fade" id="viewOrderModal<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewOrderModal<?php echo $order['order_id']; ?>Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewOrderModal<?php echo $order['order_id']; ?>Label">Order #<?php echo $order['order_id']; ?> Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <h6 class="font-weight-bold">Customer Information</h6>
                                                    <p>Name: <?php echo htmlspecialchars($order['user_name'] ?? 'N/A'); ?><br>
                                                    Email: <?php echo htmlspecialchars($order['email'] ?? 'N/A'); ?><br>
                                                    Phone: <?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="font-weight-bold">Order Information</h6>
                                                    <p>Order Date: <?php echo date('M d, Y', strtotime($order['created_at'])); ?><br>
                                                    Status: <span class="badge badge-<?php echo $statusClass; ?>"><?php echo ucfirst($order['status']); ?></span><br>
                                                    Payment Method: <?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                            
                                            <h6 class="font-weight-bold">Shipping Address</h6>
                                            <p><?php echo htmlspecialchars($order['shipping_address'] ?? 'N/A'); ?></p>
                                            
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
                                                        <?php
                                                        $items = $this->db->query("
                                                            SELECT oi.*, p.name 
                                                            FROM order_items oi 
                                                            JOIN products p ON oi.product_id = p.product_id 
                                                            WHERE oi.order_id = ?
                                                        ", [$order['order_id']])->fetchAll();
                                                        foreach ($items as $item):
                                                        ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                                <td><?php echo $item['quantity']; ?></td>
                                                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-right font-weight-bold">Subtotal</td>
                                                            <td>$<?php echo number_format($order['subtotal'] ?? $order['total_amount'], 2); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-right font-weight-bold">Tax</td>
                                                            <td>$<?php echo number_format($order['tax'] ?? 0, 2); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-right font-weight-bold">Shipping</td>
                                                            <td>$<?php echo number_format($order['shipping_cost'] ?? 0, 2); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-right font-weight-bold">Total</td>
                                                            <td class="font-weight-bold">$<?php echo number_format($order['total_amount'], 2); ?></td>
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

                            <!-- Update Order Modal -->
                            <div class="modal fade" id="updateOrderModal<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateOrderModal<?php echo $order['order_id']; ?>Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateOrderModal<?php echo $order['order_id']; ?>Label">Update Order #<?php echo $order['order_id']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="/tborders/update" method="POST">
                                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" name="status">
                                                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                        <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                        <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                        <option value="canceled" <?php echo $order['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="payment_status">Payment Status</label>
                                                    <select class="form-control" name="payment_status">
                                                        <option value="pending" <?php echo $order['payment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="paid" <?php echo $order['payment_status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                                                        <option value="failed" <?php echo $order['payment_status'] == 'failed' ? 'selected' : ''; ?>>Failed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update Order</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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

<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        "order": [[0, "desc"]]
    });
});
</script>

<?php require base_path('views/dashboard/partials/footer.php') ?>