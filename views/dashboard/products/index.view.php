<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<div class="col-md-12 mb-lg-0 mb-4">
    <div class="mt-4">
        <div class="pb-0 p-3">
            <div class="row">
                <div class="col-3 d-flex align-items-center">
                    <h6 class="mb-0">Products Tables</h6>
                </div>
                <div class="col-6 d-flex">
                    <div class="input-group">
                        <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" placeholder="Type here...">
                    </div>
                </div>
                <div class="col-3 text-end">
                    <button type="button" class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#productModel">
                        <i class="fas fa-plus"></i>&nbsp;&nbsp;Create New Product
                    </button>
                    <div class="modal fade" id="productModel" tabindex="-1" aria-labelledby="productModelLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="productModelLabel">New Product</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="/tbproducts" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="product-name" placeholder="Product Name" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" class="form-control" name="product-stock" placeholder="Product Stock" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" step="0.01" class="form-control" name="product-price" placeholder="Product Price" required>
                                        </div>
                                        <div class="mb-3">
                                            <select class="form-select" name="product-category" required>
                                                <option selected>Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= $category['category_id'] ?>">
                                                        <?= $category['category_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <input type="file" class="form-control" name="product-image" accept="image/*">
                                        </div>
                                        <div class="mb-3">
                                            <textarea class="form-control" name="product-decription" placeholder="Product Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
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
                                                    <img src="<?= $product['image_url'] ?? 'asset/images/default-product.jpg' ?>"
                                                        alt="<?= $product['name'] ?>"
                                                        class="avatar avatar-xxl me-3">
                                                </td>
                                                <td><?= $product['name'] ?></td>
                                                <td>$<?= number_format($product['price'], 2) ?></td>
                                                <td><?= $product['stock'] ?></td>
                                                <td><?= $product['category_name'] ?? 'Uncategorized' ?></td>
                                                <td>
                                                    <div class="d-flex">
                                                        <button type="button" class="btn btn-link text-dark px-3 mb-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateProductModal<?= $product['product_id'] ?>">
                                                            <i
                                                                class="fas fa-pencil-alt text-dark me-2 text-md" aria-hidden="true"></i>
                                                            Edit
                                                        </button>
                                                        <div class="modal fade" id="updateProductModal<?= $product['product_id'] ?>" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5">Update Product</h1>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <form action="/tbproducts/update" method="POST" enctype="multipart/form-data">
                                                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Product Name</label>
                                                                                <input type="text" class="form-control" name="product-name-update"
                                                                                    value="<?= htmlspecialchars($product['name']) ?>" required>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Stock</label>
                                                                                <input type="number" class="form-control" name="product-stock-update"
                                                                                    value="<?= $product['stock'] ?>" required>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Price</label>
                                                                                <input type="number" step="0.01" class="form-control" name="product-price-update"
                                                                                    value="<?= $product['price'] ?>" required>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Category</label>
                                                                                <select class="form-select" name="product-category-update" required>
                                                                                    <?php foreach ($categories as $category): ?>
                                                                                        <option value="<?= $category['category_id'] ?>"
                                                                                            <?= $category['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                                                                                            <?= htmlspecialchars($category['category_name']) ?>
                                                                                        </option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Product Image</label>
                                                                                <input type="file" class="form-control" name="product-image" accept="image/*">
                                                                                <?php if (!empty($product['image_url'])): ?>
                                                                                    <small class="text-muted">Current image will be replaced if a new image is uploaded.</small>
                                                                                    <br />
                                                                                    <img src="<?= $product['image_url'] ?>" class="avatar avatar-xxxl " alt="<?= $product['name'] ?>">
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Description</label>
                                                                                <textarea class="form-control" name="product-decription-update"
                                                                                    rows="3"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary">Update Product</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form action="/tbproducts/delete" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                                            <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0">
                                                                <i class="far fa-trash-alt me-2 text-md"></i>
                                                                Delete
                                                            </button>
                                                        </form>
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
    </div>
</div>

<?php require base_path('views/dashboard/partials/smallerfooter.php') ?>
<?php require base_path('views/dashboard/partials/footer.php') ?>
