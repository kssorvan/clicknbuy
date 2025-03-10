<header>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/"
                style="font-size: 1.5rem; text-transform: uppercase; font-weight: 600;">Shop.</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="navbar-icon bi-filter-right"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= urlIs('/') ? "active" : "" ?> " aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= urlIs('/products') ?  "active" : "" ?>" aria-current="page" href="/products">Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= urlIs('/about') ?  "active" : "" ?>" aria-current="page" href="/about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= urlIs('/contact') ?  "active" : "" ?>" aria-current="page" href="/contact">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link cart position-relative <?= urlIs('/cart') ? "active" : "" ?>"
                            href="/cart">
                            <i class="bi bi-cart-fill" style="font-size: 19px;"></i>
                            <?php
                            $cartItemCount = isset($_SESSION['cart'])
                                ? array_reduce($_SESSION['cart'], function ($carry, $item) {
                                    return $carry + $item['quantity'];
                                }, 0)
                                : 0;
                            ?>

                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                <?= $cartItemCount ?>
                                <span class="visually-hidden">items in cart</span>
                            </span>

                        </a>
                    </li>
                    <li class="nav-item ">

                        <?php if ($_SESSION['user'] ?? false) : ?>

                            <a style="padding: 0 1rem;" aria-current="page" href="/profile"> <span>
                                    <img src="<?= $_SESSION['user']['image_path'] ?> " class=" avatar rounded-circle avatar-sm " />
                            </a>
                        <?php else : ?>
                            <button
                                type="button"
                                class="btn nav-link btnLogin"
                                data-bs-toggle="modal"
                                data-bs-target="#authModal">
                                Login / Register
                            </button>
                            <div
                                class="modal fade"
                                id="authModal"
                                tabindex="-1"
                                aria-labelledby="authModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <!-- Modal Header with Nav Tabs -->
                                        <div class="modal-header">
                                            <ul class="nav nav-tabs w-100" id="authTab" role="tablist">

                                                <li class="nav-item " role="presentation">
                                                    <button
                                                        class="nav-link active"
                                                        id="login-tab"
                                                        data-bs-toggle="tab"
                                                        data-bs-target="#login"
                                                        type="button"
                                                        role="tab"
                                                        aria-controls="login"
                                                        aria-selected="true">
                                                        Login
                                                    </button>
                                                </li>
                                                <li class="nav-item " role="presentation">
                                                    <button
                                                        class="nav-link"
                                                        id="signup-tab"
                                                        data-bs-toggle="tab"
                                                        data-bs-target="#signup"
                                                        type="button"
                                                        role="tab"
                                                        aria-controls="signup"
                                                        aria-selected="false">
                                                        Register
                                                    </button>
                                                </li>

                                            </ul>
                                            <button
                                                type="button "
                                                class="btn-close btnFormClose"
                                                data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <!-- Modal Body with Tab Content -->
                                        <div class="modal-body">
                                            <div class="tab-content" id="authTabContent">
                                                <!-- Login Form -->
                                                <div
                                                    class="tab-pane fade show active"
                                                    id="login"
                                                    role="tabpanel"
                                                    aria-labelledby="login-tab">
                                                    <form action="/login" method="POST">
                                                        <div class="mb-3">
                                                            <label for="loginEmail" class="form-label">Email address</label>
                                                            <input
                                                                type="email"
                                                                class="form-control"
                                                                id="loginEmail"
                                                                placeholder="Enter your email"
                                                                name="email-login" />
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="loginPassword" class="form-label">Password</label>
                                                            <input
                                                                type="password"
                                                                class="form-control"
                                                                id="loginPassword"
                                                                placeholder="Enter your password"
                                                                name="password-login" />
                                                        </div>
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-primary">
                                                                Sign In
                                                            </button>
                                                        </div>
                                                        <div class="mt-3">
                                                            <small>forgot password ? </small>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- Sign Up Form -->
                                                <div
                                                    class="tab-pane fade"
                                                    id="signup"
                                                    role="tabpanel"
                                                    aria-labelledby="signup-tab">
                                                    <form action="/register" method="POST">
                                                        <div class="mb-3">
                                                            <label for="signupName" class="form-label">Full Name</label>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="signupName"
                                                                placeholder="Enter your full name"
                                                                name="user-name" />
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="signupEmail" class="form-label">Email address</label>
                                                            <input
                                                                type="email"
                                                                class="form-control"
                                                                id="signupEmail"
                                                                placeholder="Enter your email"
                                                                name="user-email" />
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="signupPassword" class="form-label">Password</label>
                                                            <input
                                                                type="password"
                                                                class="form-control"
                                                                id="signupPassword"
                                                                placeholder="Create a password"
                                                                name="user-password" />
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="signupConfirmPassword" class="form-label">Confirm Password</label>
                                                            <input
                                                                type="password"
                                                                class="form-control"
                                                                id="signupConfirmPassword"
                                                                placeholder="Confirm your password"
                                                                name="user-password-confirm" />
                                                        </div>
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-primary">
                                                                Register
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <small>By continuing, you agree to our <a href="#">Terms</a> and
                                                <a href="#">Privacy Policy</a>.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</header>
