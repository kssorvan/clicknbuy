<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>
<div class="col-md-12 mb-lg-0 mb-4">
    <div class=" mt-4">
        <div class=" pb-0 p-3">
            <div class="row">
                <div class="col-3 d-flex align-items-center">
                    <h6 class="mb-0">Categories Tables</h6>
                </div>
                <div class="col-6 d-flex">
                    <div class="input-group">
                        <span class="input-group-text text-body"><i class="fas fa-search"
                                aria-hidden="true"></i></span>
                        <input type="text" class="form-control" placeholder="Type here...">
                    </div>
                </div>
                <div class="col-3 text-end">
                    <button type="button" class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#categoryModel" data-bs-whatever="@mdo"><i
                            class="fas fa-plus"></i>&nbsp;&nbsp;Create New Category</button>
                    <div class="modal fade" id="categoryModel" tabindex="-1" aria-labelledby="categoryModelLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="categoryModelLabel">New Category</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="/tbcategories" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="category-name" name="category-name" placeholder="Category Name">
                                        </div>
                                        <div class="mb-3">
                                            <textarea class="form-control" id="category-decription" name="category-decription" placeholder="Category Description"></textarea>
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
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Name</th>
                                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Description</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-lg"><?= $category['category_name'] ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <p class="text-md font-weight-bold mb-0 text-truncate" style="max-width: 250px;"><?= $category['description'] ?></p>
                                                </td>
                                                <td>
                                                    <div class="ms-9 text-end ">
                                                        <button type="button" class="btn btn-link text-dark px-3 mb-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateCategoriesModal<?= $category['category_id'] ?>">
                                                            <i
                                                                class="fas fa-pencil-alt text-dark me-2 text-md" aria-hidden="true"></i>
                                                            Edit
                                                        </button>
                                                        <div class="modal fade" id="updateCategoriesModal<?= $category['category_id'] ?>" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5">Update Category</h1>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <form action="/tbcategories/update" method="POST" enctype="multipart/form-data">
                                                                        <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <input type="text" class="form-control" name="categories-name-update"
                                                                                    value="<?= htmlspecialchars($category['category_name']) ?>" required>
                                                                            </div>
                                                                            <div class="mb-3">

                                                                                <textarea class="form-control" name="categories-decription-update"
                                                                                    rows="3"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary">Update Category</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form action="/tbcategories/delete" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this category?');" style="float: right;">
                                                            <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
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
