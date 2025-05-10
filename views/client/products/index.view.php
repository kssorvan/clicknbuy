<!-- views/client/products/index.view.php -->
<?php include base_path('views/client/partials/head.php') ?>
<?php include base_path('views/client/partials/nav.php') ?>
<!-- Cover -->
<section class="hero" id="hero">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-background">
                    <img src="asset/images/TW200cover.png" alt="" style="object-fit: cover;">
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
<!-- Category  -->
<section class="product-category-section my-5">
    <div class="container">
        <div class="row">
            <!-- Category Sidebar -->
            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Categories</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="/products" class="<?= !$currentCategory ? 'text-primary' : 'text-dark' ?>">
                                    All Products
                                </a>
                            </li>
                            <?php foreach ($categories as $cat): ?>
                                <li class="list-group-item">
                                    <a href="/products?category=<?= $cat['category_id'] ?>" 
                                       class="<?= $currentCategory == $cat['category_id'] ? 'text-primary' : 'text-dark' ?>">
                                        <?= htmlspecialchars($cat['category_name']) ?>
                                        <span class="badge bg-secondary float-end"><?= $cat['product_count'] ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Price Filter -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Price Range</h5>
                    </div>
                    <div class="card-body">
                        <form action="/products" method="GET">
                            <?php if ($currentCategory): ?>
                                <input type="hidden" name="category" value="<?= $currentCategory ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label for="price_min" class="form-label">Min Price</label>
                                <input type="number" class="form-control" id="price_min" name="price_min" value="<?= $minPrice ?>">
                            </div>
                            <div class="mb-3">
                                <label for="price_max" class="form-label">Max Price</label>
                                <input type="number" class="form-control" id="price_max" name="price_max" value="<?= $maxPrice ?>">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">
                        <?php if ($currentCategory): ?>
                            <?php 
                            $categoryName = "All Products";
                            foreach ($categories as $cat) {
                                if ($cat['category_id'] == $currentCategory) {
                                    $categoryName = $cat['category_name'];
                                    break;
                                }
                            }
                            echo htmlspecialchars($categoryName);
                            ?>
                        <?php else: ?>
                            All Products
                        <?php endif; ?>
                    </h2>
                    
                    <!-- Sorting Options -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Sort By
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item" href="?<?= http_build_query(array_merge($_GET, ['sort' => 'price_asc'])) ?>">Price: Low to High</a></li>
                            <li><a class="dropdown-item" href="?<?= http_build_query(array_merge($_GET, ['sort' => 'price_desc'])) ?>">Price: High to Low</a></li>
                            <li><a class="dropdown-item" href="?<?= http_build_query(array_merge($_GET, ['sort' => 'newest'])) ?>">Newest First</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Products Display -->
                <div class="row">
                    <?php if (empty($products)): ?>
                        <div class="col-12 text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-search" style="font-size: 3rem;"></i>
                                <h4 class="mt-3">No products found</h4>
                                <p class="text-muted">Try adjusting your filters</p>
                                <a href="/products" class="btn btn-outline-primary">Clear Filters</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="<?= $product['image_url'] ?? 'asset/images/default-product.jpg' ?>" 
                                         class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                        <p class="card-text text-muted"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></p>
                                        <p class="card-text fw-bold">$<?= number_format($product['price'], 2) ?></p>
                                        <div class="d-grid gap-2">
                                            <a href="/product/<?= $product['product_id'] ?>" class="btn btn-outline-primary">View Details</a>
                                            <a href="/addcart/<?= $product['product_id'] ?>" class="btn btn-primary">Add to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

<?php require base_path('views/client/partials/newsletter.php') ?>
<?php require base_path('views/client/partials/footer.php') ?>

<script>
// JavaScript for Weekly Deals countdown timer
document.addEventListener('DOMContentLoaded', function() {
    const endDate = new Date('2025-04-04T23:59:59');
    const timerElement = document.getElementById('weekly-deal-timer');

    function updateTimer() {
        const now = new Date();
        const timeLeft = endDate - now;

        if (timeLeft <= 0) {
            timerElement.innerHTML = 'Deal has ended!';
            return;
        }

        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        timerElement.innerHTML = `Ends in ${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    updateTimer();
    setInterval(updateTimer, 1000);
});
</script>