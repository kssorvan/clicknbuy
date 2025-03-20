<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<div class="col-md-12 mb-lg-0 mb-4">
    <div class="mt-4">
        <div class="pb-0 p-3">
            <div class="row">
                <div class="col-3 d-flex align-items-center">
                    <h6 class="mb-0">Motorcycles</h6>
                </div>
                <div class="col-6 d-flex">
                    <div class="input-group">
                        <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" placeholder="Search motorcycles..." id="searchInput">
                    </div>
                </div>
                <div class="col-3 text-end">
                    <a href="/motorcycles/create" class="btn bg-gradient-dark mb-0">
                        <i class="fas fa-plus"></i>&nbsp;&nbsp;Add Motorcycle
                    </a>
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
                                                    <img src="<?= $motorcycle['image_url'] ?? 'asset/images/default-motorcycle.jpg' ?>"
                                                        alt="<?= $motorcycle['name'] ?>"
                                                        class="avatar avatar-xxl me-3">
                                                </td>
                                                <td>
                                                    <p class="text-md font-weight-bold mb-0"><?= $motorcycle['brand_name'] ?></p>
                                                    <p class="text-sm mb-0"><?= $motorcycle['name'] ?></p>
                                                </td>
                                                <td><?= $motorcycle['model_year'] ?></td>
                                                <td>$<?= number_format($motorcycle['price'], 2) ?></td>
                                                <td><?= $motorcycle['stock'] ?></td>
                                                <td><?= $motorcycle['category_name'] ?? 'Uncategorized' ?></td>
                                                <td>
                                                    <?php 
                                                        $spec = (new Core\Models\MotorcycleSpecs())->findByProductId($motorcycle['product_id']);
                                                        echo $spec['condition'] ?? 'New';
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="/motorcycles/edit/<?= $motorcycle['product_id'] ?>" class="btn btn-link text-dark px-3 mb-0">
                                                            <i class="fas fa-pencil-alt text-dark me-2 text-md" aria-hidden="true"></i>
                                                            Edit
                                                        </a>
                                                        <form action="/motorcycles/delete" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this motorcycle?');">
                                                            <input type="hidden" name="product_id" value="<?= $motorcycle['product_id'] ?>">
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

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    let searchValue = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let text = row.textContent.toLowerCase();
        if(text.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php require base_path('views/dashboard/partials/smallerfooter.php') ?>
<?php require base_path('views/dashboard/partials/footer.php') ?>