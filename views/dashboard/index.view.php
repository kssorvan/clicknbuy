<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<div class="container-fluid py-4">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Revenue</p>
                                <h5 class="font-weight-bolder mb-0">
                                    $<?= number_format($totalRevenue, 2) ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Orders</p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?= $totalOrders ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Users</p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?= $totalUsers ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Products</p>
                                <h5 class="font-weight-bolder mb-0">
                                    <?= $totalProducts ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-app text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h6>Recent Orders</h6>
                        <a href="/dashboard/orders" class="btn btn-link text-sm">View All</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Order ID</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Customer</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <a href="/dashboard/orders/show?id=<?= $order['order_id'] ?>" class="mb-0 text-sm">#<?= $order['order_id'] ?></a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0"><?= $order['customer_name'] ?></p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">$<?= number_format($order['total_amount'], 2) ?></p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-sm bg-<?= getStatusBadgeColor($order['status']) ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= date('M d, Y', strtotime($order['created_at'])) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Top Selling Products</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        <?php foreach ($topProducts as $product): ?>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <img src="<?= $product['image_url'] ?>" class="avatar avatar-sm" alt="<?= $product['name'] ?>">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm"><?= $product['name'] ?></h6>
                                        <span class="text-xs"><?= $product['total_sold'] ?> sold</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="d-flex flex-column">
                                        <span class="text-dark text-sm">$<?= number_format($product['revenue'], 2) ?></span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php require base_path('views/dashboard/partials/smallerfooter.php') ?>
</div>

<?php 
// Helper function for status badge colors
function getStatusBadgeColor($status) {
    switch ($status) {
        case 'pending':
            return 'warning';
        case 'processing':
            return 'info';
        case 'shipped':
            return 'primary';
        case 'delivered':
            return 'success';
        case 'canceled':
            return 'danger';
        default:
            return 'secondary';
    }
}
?>

<?php require base_path('views/dashboard/partials/footer.php') ?>


