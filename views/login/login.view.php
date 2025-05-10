<?php require base_path('views/partials/head.php') ?>
<?php require base_path('views/partials/nav.php') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header text-center">
                    <h3>Login</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($errors['email'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errors['email']); ?></div>
                    <?php endif; ?>

                    <form action="/login" method="POST">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <small class="text-danger"><?php echo htmlspecialchars($errors['email']); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <?php if (isset($errors['password'])): ?>
                                <small class="text-danger"><?php echo htmlspecialchars($errors['password']); ?></small>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Don't have an account? <a href="/register">Register here</a></p>
                </div>
					<p><a href="/forgot-password">Forgot your password?</a></p>
            </div>
        </div>
    </div>
</div>

<?php require base_path('views/partials/footer.php') ?>