<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>

<section class="hero" id="hero">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="carousel-background">
                <img src="/../asset/images/slider7.png" alt="" style="object-fit: cover;">
                <div class="carousel-container">
                    <div class="carousel-content-container">
                        <h2>Product Details</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($product['image_url']) ?>"
                alt="<?= htmlspecialchars($product['name']) ?>"
                class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3"><?= htmlspecialchars($product['name']) ?></h1>
            <div class="mb-3">
                <span class="text-muted">Category: <?= htmlspecialchars($product['category_name']) ?></span>
            </div>
            <h2 class="text-primary mb-3">$<?= number_format($product['price'], 2) ?></h2>

            <div class="mb-3">
                <strong>Description:</strong>
                <p><?= htmlspecialchars($product['description']) ?></p>
            </div>

            <div class="mb-3">
                <strong>Stock:</strong>
                <span><?= $product['stock'] ?> units available</span>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <a href="/addcart/<?= $product['product_id'] ?>" class="btn btn-dark w-100">
                        Add to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="container my-5">
    <h2 class="text-center mb-4">Related Products</h2>
    <div class="row">
        <?php foreach ($relatedProducts as $relatedProduct): ?>
            <div class="col-md-3 ">
                <div class="card mb-4  pt-4" style="border-radius: 20px;">
                    <img src="<?= htmlspecialchars($relatedProduct['image_url']) ?>"
                        class="card-img-top"
                        alt="<?= htmlspecialchars($relatedProduct['name']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($relatedProduct['name']) ?></h5>
                        <p class="card-text">$<?= number_format($relatedProduct['price'], 2) ?></p>
                        <a href="/product/<?= $relatedProduct['product_id'] ?>" class="btn btn-dark w-100">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php require base_path('views/client/partials/footer.php') ?>
