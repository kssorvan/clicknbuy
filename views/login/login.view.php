<?php include base_path('views/client/partials/nav.php'); ?>

<div class="container mt-5">
    <h1>Login</h1>

    <?php if (isset($errors['login'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errors['login']) ?>
        </div>
    <?php endif; ?>

    <form action="<?= $router->url('login.post') ?>" method="POST">
        <input type="hidden" name="_csrf" value="<?= \Core\Csrf::generateToken() ?>">

        <div class="mb-3">
            <label for="email-login" class="form-label">Email address</label>
            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email-login" name="email-login" value="<?= htmlspecialchars($_POST['email-login'] ?? '') ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback">
                    <?= htmlspecialchars($errors['email']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="password-login" class="form-label">Password</label>
            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password-login" name="password-login">
            <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback">
                    <?= htmlspecialchars($errors['password']) ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
        <a href="<?= $router->url('register') ?>" class="btn btn-link">Don't have an account? Register</a>
    </form>
</div>