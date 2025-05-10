<?php include 'partials/head.php' ?>
<?php include 'partials/nav.php' ?>

<section class="hero" id="hero">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-background">
                    <img src="asset/images/slider1.png" alt="" style="object-fit: cover;">
                    <div class="carousel-container">
                        <div class="carousel-content-container">
                            <p style="color: gray; font-size: 20px; text-align: left; padding-left: 10rem;">$800</p>
                            <h2>Ride to win.</h2>
                            <p>Bred in the highly competitive world of professional supercross and motocross, the new Honda CRF250R is our most advanced competition machine to date. Lightweight, agile, and highly responsive, this powerhouse dominates even the most challenging tracks with its torquey engine and redesigned chassis. One lap, and you'll see why winners Ride Red.
                            </p>
                            <div class="buttons">
                                <a href="/products" class="button1">Explore Products</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="feature mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 d-flex">
                <div class="main-feature-box flex-grow-1 d-flex flex-column align-items-start text-start p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon me-3" style="background-color:#fddde4;">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h3 class="m-0">Credit Card</h3>
                    </div>
                    <p class="flex-grow-1">Unlock seamless shopping with our exclusive credit card! Enjoy instant approvals, cashback rewards, and interest-free EMIs—designed for smart buyers like you.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 d-flex">
                <div class="main-feature-box flex-grow-1 d-flex flex-column align-items-start text-start p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon me-3" style="background-color:#cdebbc;">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h3 class="m-0">Save Money</h3>
                    </div>
                    <p class="flex-grow-1">Shop smart, save big! Get cashback, discounts, and exclusive offers with our credit card—because every penny counts.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 d-flex">
                <div class="main-feature-box flex-grow-1 d-flex flex-column align-items-start text-start p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon me-3" style="background-color:#d1e8f2;">
                            <i class="bi bi-send"></i>
                        </div>
                        <h3 class="m-0">Free Delivery</h3>
                    </div>
                    <p class="flex-grow-1">Shop more, spend less! Enjoy exclusive free delivery on all your orders with our credit card benefits.</p>
                </div>
            </div>
        </div>
    </div>


</section>


<!-- Featured Products Section -->
<section class="productfeature mt-5 mb-5">
    <div class="container-fluid" style="padding: 5rem;">
        <div class="row">
            <p class="text-center" style="font-size: 0.9rem; color: #828282;">Cool Stuff</p>
            <h2 class="text-center" style="font-size: 3.3rem; font-weight: 700;">Featured Products</h2>
            <p class="text-center" style="font-size: 1.1rem; color: #828282; letter-spacing: 3px;">Explore our hand-picked selection of top products.</p>
        </div>
        <div class="row mt-5">
            <?php if (empty($featuredProducts)): ?>
                <div class="col-12 text-center">
                    <p>No featured products available at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($featuredProducts as $product) : ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="single-courses-box">
                            <div class="icon">
                                <img src="<?= $product['image_url'] ?>" class="img-fluid product-grid" alt="">
                            </div>
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>$ <?= number_format($product['price'], 2) ?></p>
                            <div class="buttons">
                                <a href="/product/<?= $product['product_id'] ?>" class="button3">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Big Sale Section -->
<section class="productfeature mt-5 mb-5">
    <div class="container-fluid" style="padding: 5rem;">
        <div class="row">
            <p class="text-center" style="font-size: 0.9rem; color: #828282;">Limited Time Offers</p>
            <h2 class="text-center" style="font-size: 3.3rem; font-weight: 700;">Big Sale</h2>
            <p class="text-center" style="font-size: 1.1rem; color: #828282; letter-spacing: 3px;">Grab these deals before they're gone!</p>
        </div>
        <div class="row mt-5">
            <?php if (empty($bigSaleProducts)): ?>
                <div class="col-12 text-center">
                    <p>No big sale products available at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($bigSaleProducts as $product) : ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="single-courses-box">
                            <div class="icon">
                                <img src="<?= $product['image_url'] ?>" class="img-fluid product-grid" alt="">
                            </div>
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>
                                $ <?= number_format($product['price'], 2) ?>
                                <?php if ($product['original_price']): ?>
                                    <span style="text-decoration: line-through; color: #828282;">$ <?= number_format($product['original_price'], 2) ?></span>
                                <?php endif; ?>
                            </p>
                            <div class="buttons">
                                <a href="/product/<?= $product['product_id'] ?>" class="button3">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Weekly Deals Section -->
<section class="productfeature mt-5 mb-5">
    <div class="container-fluid" style="padding: 5rem;">
        <div class="row">
            <p class="text-center" style="font-size: 0.9rem; color: #828282;">This Week's Specials</p>
            <h2 class="text-center" style="font-size: 3.3rem; font-weight: 700;">Weekly Deals</h2>
            <p class="text-center" style="font-size: 1.1rem; color: #828282; letter-spacing: 3px;">
                Check out our best deals of the week. 
                <span id="weekly-deal-timer"></span>
            </p>
        </div>
        <div class="row mt-5">
            <?php if (empty($weeklyDealProducts)): ?>
                <div class="col-12 text-center">
                    <p>No weekly deals available at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($weeklyDealProducts as $product) : ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="single-courses-box">
                            <div class="icon">
                                <img src="<?= $product['image_url'] ?>" class="img-fluid product-grid" alt="">
                            </div>
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>$ <?= number_format($product['price'], 2) ?></p>
                            <div class="buttons">
                                <a href="/product/<?= $product['product_id'] ?>" class="button3">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="productfeature mt-5 mb-5">
    <div class="container-fluid" style="padding: 5rem;">
        <div class="row">
            <p class="text-center" style="font-size: 0.9rem; color: #828282;">Fresh Stock</p>
            <h2 class="text-center" style="font-size: 3.3rem; font-weight: 700;">New Arrivals</h2>
            <p class="text-center" style="font-size: 1.1rem; color: #828282; letter-spacing: 3px;">Discover the latest additions to our collection.</p>
        </div>
        <div class="row mt-5">
            <?php if (empty($newArrivalProducts)): ?>
                <div class="col-12 text-center">
                    <p>No new arrivals available at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($newArrivalProducts as $product) : ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="single-courses-box">
                            <div class="icon">
                                <img src="<?= $product['image_url'] ?>" class="img-fluid product-grid" alt="">
                            </div>
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>$ <?= number_format($product['price'], 2) ?></p>
                            <div class="buttons">
                                <a href="/product/<?= $product['product_id'] ?>" class="button3">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    </div>
</section>



<!-- Weekly Deals Section -->
<section class="productfeature mt-5 mb-5">
    <div class="container-fluid" style="padding: 5rem;">
        <div class="row">
            <p class="text-center" style="font-size: 0.9rem; color: #828282;">This Week's Specials</p>
            <h2 class="text-center" style="font-size: 3.3rem; font-weight: 700;">Weekly Deals</h2>
            <p class="text-center" style="font-size: 1.1rem; color: #828282; letter-spacing: 3px;">
                Check out our best deals of the week. 
                <span id="weekly-deal-timer"></span>
            </p>
        </div>
        <div class="row mt-5">
            <?php if (empty($weeklyDealProducts)): ?>
                <div class="col-12 text-center">
                    <p>No weekly deals available at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($weeklyDealProducts as $product) : ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="single-courses-box">
                            <div class="icon">
                                <img src="<?= $product['image_url'] ?>" class="img-fluid product-grid" alt="">
                            </div>
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>$ <?= number_format($product['price'], 2) ?></p>
                            <div class="buttons">
                                <a href="/product/<?= $product['product_id'] ?>" class="button3">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="productfeature mt-5 mb-5">
    <div class="container-fluid" style="padding: 5rem;">
        <div class="row">
            <p class="text-center" style="font-size: 0.9rem; color: #828282;">Fresh Stock</p>
            <h2 class="text-center" style="font-size: 3.3rem; font-weight: 700;">New Arrivals</h2>
            <p class="text-center" style="font-size: 1.1rem; color: #828282; letter-spacing: 3px;">Discover the latest additions to our collection.</p>
        </div>
        <div class="row mt-5">
            <?php if (empty($newArrivalProducts)): ?>
                <div class="col-12 text-center">
                    <p>No new arrivals available at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($newArrivalProducts as $product) : ?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="single-courses-box">
                            <div class="icon">
                                <img src="<?= $product['image_url'] ?>" class="img-fluid product-grid" alt="">
                            </div>
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>$ <?= number_format($product['price'], 2) ?></p>
                            <div class="buttons">
                                <a href="/product/<?= $product['product_id'] ?>" class="button3">
                                    Read More <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    </div>
</section>



<?php include 'partials/newsletter.php' ?>

<?php include 'partials/footer.php' ?>
