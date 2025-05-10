<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Motorcycle Management</h1>
    <a href="/motorcycles/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add Motorcycle
    </a>
</div>

<!-- Filters Card -->
<div class="card shadow mb-4">
    <a href="#collapseFilters" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseFilters">
        <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
    </a>
    <div class="collapse show" id="collapseFilters">
        <div class="card-body">
            <form action="/motorcycles/admin" method="GET" class="row">
                <div class="col-md-3 mb-3">
                    <label for="brandFilter">Brand</label>
                    <select class="form-control" id="brandFilter" name="brand">
                        <option value="">All Brands</option>
                        <?php if(isset($brands)): ?>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['brand_id'] ?>" <?= isset($_GET['brand']) && $_GET['brand'] == $brand['brand_id'] ? 'selected' : '' ?>>
                                <?= $brand['brand_name'] ?>
                            </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="categoryFilter">Category</label>
                    <select class="form-control" id="categoryFilter" name="category">
                        <option value="">All Categories</option>
                        <?php if(isset($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $category['category_id'] ? 'selected' : '' ?>>
                                <?= $category['category_name'] ?>
                            </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="conditionFilter">Condition</label>
                    <select class="form-control" id="conditionFilter" name="condition">
                        <option value="">All Conditions</option>
                        <option value="New" <?= isset($_GET['condition']) && $_GET['condition'] == 'New' ? 'selected' : '' ?>>New</option>
                        <option value="Used" <?= isset($_GET['condition']) && $_GET['condition'] == 'Used' ? 'selected' : '' ?>>Used</option>
                        <option value="Certified Pre-Owned" <?= isset($_GET['condition']) && $_GET['condition'] == 'Certified Pre-Owned' ? 'selected' : '' ?>>Certified Pre-Owned</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="stockStatus">Stock Status</label>
                    <select class="form-control" id="stockStatus" name="stock_status">
                        <option value="">All</option>
                        <option value="in_stock" <?= isset($_GET['stock_status']) && $_GET['stock_status'] == 'in_stock' ? 'selected' : '' ?>>In Stock</option>
                        <option value="out_of_stock" <?= isset($_GET['stock_status']) && $_GET['stock_status'] == 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="/motorcycles/admin" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Motorcycles Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Motorcycles</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="motorcyclesTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Brand & Model</th>
                        <th>Year</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Condition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($motorcycles as $motorcycle): ?>
                        <tr>
                            <td>
                                <img src="<?= $motorcycle['image_url'] ?? 'asset/sb-admin-2/img/undraw_rocket.svg' ?>"
                                    alt="<?= $motorcycle['name'] ?>"
                                    class="img-fluid" style="max-width: 60px; max-height: 60px;">
                            </td>
                            <td>
                                <div class="font-weight-bold"><?= $motorcycle['brand_name'] ?></div>
                                <?= $motorcycle['name'] ?>
                            </td>
                            <td><?= $motorcycle['model_year'] ?></td>
                            <td>$<?= number_format($motorcycle['price'], 2) ?></td>
                            <td>
                                <?php if ($motorcycle['stock'] > 0): ?>
                                    <span class="text-success"><?= $motorcycle['stock'] ?></span>
                                <?php else: ?>
                                    <span class="text-danger">Out of stock</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $motorcycle['category_name'] ?? 'Uncategorized' ?></td>
                            <td>
                                <?php 
                                    $spec = (new Core\Models\MotorcycleSpecs())->findByProductId($motorcycle['product_id']);
                                    $condition = $spec['condition'] ?? 'New';
                                    
                                    $badgeClass = 'success';
                                    if ($condition === 'Used') {
                                        $badgeClass = 'warning';
                                    } else if ($condition === 'Certified Pre-Owned') {
                                        $badgeClass = 'info';
                                    }
                                ?>
                                <span class="badge badge-<?= $badgeClass ?>"><?= $condition ?></span>
                            </td>
                            <td>
                                <a href="/motorcycles/edit/<?= $motorcycle['product_id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="/motorcycle/<?= $motorcycle['product_id'] ?>" class="btn btn-info btn-sm" target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="/motorcycles/delete" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this motorcycle?');">
                                    <input type="hidden" name="product_id" value="<?= $motorcycle['product_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#motorcyclesTable').DataTable({
        "order": [[2, "desc"]]
    });
});
</script>

<?php require base_path('views/dashboard/partials/footer.php') ?>