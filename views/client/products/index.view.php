<?php include base_path('views/client/partials/head.php') ?>
<?php include base_path('views/client/partials/nav.php') ?>

<section class="hero" id="hero">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-background">
                    <img src="asset/images/slider2.png" alt="" style="object-fit: cover;">
                    <div class="carousel-container">
                        <div class="carousel-content-container">
                            <h2>Products</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="productfeature mt-5 mb-5">
    <div class="container-fluid" style="padding: 5rem;">
        <div class="row">
            <p class="text-center" style="font-size: 0.9rem; color: #828282;">Cool Stuff</p>
            <h2 class="text-center" style="font-size: 3.3rem; font-weight: 700;">Featured Products</h2>
            <p class="text-center" style="font-size: 1.1rem; color: #828282; letter-spacing: 3px;">Dignissimos asperiores vitae velit veniam totam fuga molestias accusamus alias autem provident. Odit ab aliquam dolor eius.</p>
        </div>
        <div class="row mt-5">
            <?php foreach ($products as $product) : ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="single-courses-box">
                        <div class="icon">
                            <img src="<?= $product['image_url'] ?>" class="img-fluid product-grid" alt="">
                        </div>
                        <h3><?= $product['name'] ?></h3>
                        <p>$ <?= $product['price'] ?></p>
                        <div class="buttons">
                            <a href="/product/<?= $product['product_id'] ?>" class="button3">
                                Read More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</section>

<?php require base_path('views/client/partials/newsletter.php') ?>
<?php require base_path('views/client/partials/footer.php') ?>
