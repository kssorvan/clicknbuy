<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>
<div class="col-md-12 mb-lg-0 mb-4">
    <div class=" mt-4">
        <div class=" pb-0 p-3">
            <div class="row">
                <div class="col-6 d-flex align-items-center">
                    <h6 class="mb-0">Users Tables</h6>
                </div>
                <div class="col-6 d-flex">
                    <div class="input-group">
                        <span class="input-group-text text-body"><i class="fas fa-search"
                                aria-hidden="true"></i></span>
                        <input type="text" class="form-control" placeholder="Type here...">
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
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Email</th>
                                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Roles</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user ): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="<?= $user['image_url']?>" class="avatar avatar-xl me-3" alt="user1">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-lg"><?= $user['name']?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-md font-weight-bold mb-0"><?= $user['email']?></p>

                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-md font-weight-bold mb-0"><?= $user['role_name']?></p>
                                            </td>


                                            <td class="align-middle">
                                                <div class="ms-auto text-end">

                                                        <form action="/tbusers/delete" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                                            <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0">
                                                                <i class="far fa-trash-alt me-2 text-md"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                </div>
                                            </td>


                                        </tr>
                                    <?php endforeach ?>
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
