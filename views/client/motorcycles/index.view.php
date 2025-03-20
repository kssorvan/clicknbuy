<?php include base_path('views/client/partials/head.php') ?>
<?php include base_path('views/client/partials/nav.php') ?>

<section class="hero" id="hero">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-background">
                    <img src="asset/images/motorcycle-banner.jpg" alt="" style="object-fit: cover;">
                    <div class="carousel-container">
                        <div class="carousel-content-container">
                            <h2>Motorcycles</h2>
                            <p>Discover your perfect ride with our premium selection of motorcycles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="filter-section my-4">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Find Your Perfect Motorcycle</h5>
                <form id="filterForm" action="/motorcycles" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <select class="form-select" id="brand" name="brand">
                                <option value="">All Brands</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['brand_id'] ?>" <?= isset($_GET['brand']) && $_GET['brand'] == $brand['brand_id'] ? 'selected' : '' ?>>
                                        <?= $brand['brand_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['category_id'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $category['category_id'] ? 'selected' : '' ?>>
                                        <?= $category['category_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="price_min" class="form-label">Min Price</label>
                            <input type="number" class="form-control" id="price_min" name="price_min" value="<?= $_GET['price_min'] ?? '' ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="price_max" class="form-label">Max Price</label>
                            <input type="number" class="form-control" id="price_max" name="price_max" value="<?= $_GET['price_max'] ?? '' ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="year_min" class="form-label">Min Year</label>
                            <input type="number" class="form-control" id="year_min" name="year_min" value="<?= $_GET['year_min'] ?? '' ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="year_max" class="form-label">Max Year</label>
                            <input type="number" class="form-control" id="year_max" name="year_max" value="<?= $_GET['year_max'] ?? '' ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="condition" class="form-label">Condition</label>
                            <select class="form-select" id="condition" name="condition">
                                <option value="">Any Condition</option>
                                <option value="New" <?= isset($_GET['condition']) && $_GET['condition'] == 'New' ? 'selected' : '' ?>>New</option>
                                <option value="Used" <?= isset($_GET['condition']) && $_GET['condition'] == 'Used' ? 'selected' : '' ?>>Used</option>
                                <option value="Certified Pre-Owned" <?= isset($_GET['condition']) && $_GET['condition'] == 'Certified Pre-Owned' ? 'selected' : '' ?>>Certified Pre-Owned</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="motorcycle-listing mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Available Motorcycles (<?= count($motorcycles) ?>)</h2>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Sort By
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                    <li><a class="dropdown-item sort-option" data-sort="price-asc" href="#">Price: Low to High</a></li>
                    <li><a class="dropdown-item sort-option" data-sort="price-desc" href="#">Price: High to Low</a></li>
                    <li><a class="dropdown-item sort-option" data-sort="year-desc" href="#">Year: Newest First</a></li>
                    <li><a class="dropdown-item sort-option" data-sort="year-asc" href="#">Year: Oldest First</a></li>
                </ul>
            </div>
        </div>

        <div class="row" id="motorcycleContainer">
            <?php foreach ($motorcycles as $motorcycle): ?>
                <div class="col-lg-4 col-md-6 mb-4 motorcycle-card" 
                     data-price="<?= $motorcycle['price'] ?>" 
                     data-year="<?= $motorcycle['model_year'] ?>">
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="<?= $motorcycle['image_url'] ?? 'asset/images/default-motorcycle.jpg' ?>" 
                                class="card-img-top" alt="<?= $motorcycle['name'] ?>" 
                                style="height: 220px; object-fit: cover;">
                            <?php if ($motorcycle['condition'] !== 'New'): ?>
                                <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                                    <?= $motorcycle['condition'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0"><?= $motorcycle['name'] ?></h5>
                                <span class="badge bg-primary"><?= $motorcycle['model_year'] ?></span>
                            </div>
                            <p class="text-muted mb-1"><?= $motorcycle['brand_name'] ?></p>
                            <p class="fw-bold mb-2 text-primary">$<?= number_format($motorcycle['price'], 2) ?></p>
                            
                            <div class="specs-highlights mb-3">
                                <div class="row g-2">
                                    <?php if (!empty($motorcycle['engine_displacement'])): ?>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Engine</small>
                                            <span><?= $motorcycle['engine_displacement'] ?> cc</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($motorcycle['horsepower'])): ?>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Power</small>
                                            <span><?= $motorcycle['horsepower'] ?> hp</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($motorcycle['mileage']) && $motorcycle['condition'] !== 'New'): ?>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Mileage</small>
                                            <span><?= $motorcycle['mileage'] ?> km</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($motorcycle['transmission_type'])): ?>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Transmission</small>
                                            <span><?= $motorcycle['transmission_type'] ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="/motorcycle/<?= $motorcycle['product_id'] ?>" class="btn btn-dark">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (empty($motorcycles)): ?>
                <div class="col-12 text-center py-5">
                    <div class="empty-state">
                        <i class="bi bi-search" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">No motorcycles found</h4>
                        <p class="text-muted">Try adjusting your search filters</p>
                        <a href="/motorcycles" class="btn btn-outline-primary">Clear Filters</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    // Sorting functionality
    document.querySelectorAll('.sort-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const sortOption = this.dataset.sort;
            const container = document.getElementById('motorcycleContainer');
            const items = Array.from(container.querySelectorAll('.motorcycle-card'));
            
            items.sort((a, b) => {
                if (sortOption === 'price-asc') {
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                } else if (sortOption === 'price-desc') {
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                } else if (sortOption === 'year-desc') {
                    return parseInt(b.dataset.year) - parseInt(a.dataset.year);
                } else if (sortOption === 'year-asc') {
                    return parseInt(a.dataset.year) - parseInt(b.dataset.year);
                }
            });
            
            // Update DOM
            items.forEach(item => container.appendChild(item));
            
            // Update dropdown text
            const sortText = this.textContent;
            document.getElementById('sortDropdown').textContent = 'Sort: ' + sortText;
        });
    });
</script>

<?php require base_path('views/client/partials/newsletter.php') ?>
<?php require base_path('views/client/partials/footer.php') ?>