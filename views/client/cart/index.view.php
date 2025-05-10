<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>
<?php
$totalPrice = 0;
$cartItems = $_SESSION['cart'] ?? [];
?>
<section class="hero" id="hero">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="carousel-background">
                <img src="/../asset/images/slider5.png" alt="" style="object-fit: cover;">
                <div class="carousel-container">
                    <div class="carousel-content-container">
                        <h2>Shopping Cart</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container my-5">
    <?php if (isset($_SESSION['cart_message'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['cart_message']) ?>
            <?php unset($_SESSION['cart_message']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($cartItems)): ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Cart Items</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td>
                                            <img src="<?= htmlspecialchars($item['image']) ?>"
                                                alt="<?= htmlspecialchars($item['name']) ?>"
                                                style="width: 50px; margin-right: 10px;">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </td>
                                        <td>$<?= number_format($item['price'], 2) ?></td>
                                        <td>
                                            <form action="/updatecart" method="POST">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <div class="input-group">
                                                    <button type="submit" name="action" value="decrease" class="btn btn-sm btn-outline-secondary">-</button>
                                                    <input type="text" name="quantity" class="form-control text-center"
                                                        value="<?= $item['quantity'] ?? $item['stock'] ?>"
                                                        style="max-width: 70px;"
                                                        onchange="this.form.action.value='manual';this.form.submit();">
                                                    <button type="submit" name="action" value="increase" class="btn btn-sm btn-outline-secondary">+</button>
                                                </div>
                                            </form>
                                        </td>
                                        <?php
                                        $itemTotal = $item['price'] * $item['stock'];
                                        $totalPrice += $itemTotal;
                                        ?>
                                        <td>$<?= number_format($itemTotal, 2) ?></td>
                                        <td>
                                            <form action="/removecart" method="POST"
                                                onsubmit="return confirm('Remove this item?');">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Remove
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

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Order Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Subtotal:</strong> $<?= number_format($totalPrice, 2) ?>
                        </div>
                        <div class="mb-3">
                            <strong>Shipping:</strong> Free
                        </div>
                        <div class="mb-3">
                            <h4><strong>Total:</strong> $<?= number_format($totalPrice, 2) ?></h4>
                        </div>

                        <?php if (!($_SESSION['user'] ?? false)): ?>
                            <div class="alert alert-warning">
                                Please <a href="#" data-bs-toggle="modal" data-bs-target="#authModal" style="text-decoration: none; color:black;"><span style="color:aqua">Login</span></a> to checkout.
                            </div>
                        <?php else: ?>
                            <form action="/cart/checkout" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    Proceed to Checkout
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center">
            <h3>Your cart is empty</h3>
            <p>Explore our <a href="/products">products</a> to start shopping.</p>
        </div>
    <?php endif; ?>
</div>
<?php require base_path('views/client/partials/newsletter.php') ?>
<?php require base_path('views/client/partials/footer.php') ?>
