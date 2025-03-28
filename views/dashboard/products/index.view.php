<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Products Management</h1>
    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#productModel">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Product
    </button>
</div>

<!-- Product Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Products</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="productsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?= $product['image_url'] ?? 'asset/sb-admin-2/img/undraw_profile.svg' ?>"
                                    alt="<?= $product['name'] ?>"
                                    class="img-fluid" style="max-width: 60px; max-height: 60px;">
                            </td>
                            <td><?= $product['name'] ?></td>
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td><?= $product['stock'] ?></td>
                            <td><?= $product['category_name'] ?? 'Uncategorized' ?></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateProductModal<?= $product['product_id'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="/tbproducts/delete" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Edit Product Modal -->
                        <div class="modal fade" id="updateProductModal<?= $product['product_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="updateProductModal<?= $product['product_id'] ?>Label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateProductModal<?= $product['product_id'] ?>Label">Update Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="/tbproducts/update" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" class="form-control" name="product-name-update"
                                                    value="<?= htmlspecialchars($product['name']) ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Stock</label>
                                                <input type="number" class="form-control" name="product-stock-update"
                                                    value="<?= $product['stock'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Price</label>
                                                <input type="number" step="0.01" class="form-control" name="product-price-update"
                                                    value="<?= $product['price'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select class="form-control" name="product-category-update" required>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?= $category['category_id'] ?>"
                                                            <?= $category['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($category['category_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Product Image</label>
                                                <input type="file" class="form-control" name="product-image" accept="image/*">
                                                <?php if (!empty($product['image_url'])): ?>
                                                    <small class="text-muted">Current image will be replaced if a new image is uploaded.</small>
                                                    <div class="mt-2">
                                                        <img src="<?= $product['image_url'] ?>" class="img-thumbnail" style="max-height: 100px" alt="<?= $product['name'] ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" name="product-description-update"
                                                    rows="3"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update Product</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="productModel" tabindex="-1" role="dialog" aria-labelledby="productModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModelLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/tbproducts" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" class="form-control" name="product-name" placeholder="Product Name" required>
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" class="form-control" name="product-stock" placeholder="Product Stock" required>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" class="form-control" name="product-price" placeholder="Product Price" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="product-category" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" class="form-control" name="product-image" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="product-description" placeholder="Product Description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#productsTable').DataTable();
});
</script>

<?php require base_path('views/dashboard/partials/footer.php') ?>