<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>

<section class="pt-5 mt-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/motorcycles">Motorcycles</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $motorcycle['name'] ?></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Image Gallery -->
            <div class="col-lg-7 mb-4">
                <div class="product-gallery">
                    <!-- Main Image -->
                    <div class="main-image-container mb-3">
                        <img id="mainImage" src="<?= $motorcycle['image_url'] ?>" 
                            class="img-fluid rounded main-image" 
                            alt="<?= $motorcycle['name'] ?>">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <?php if (!empty($additionalImages)): ?>
                        <div class="thumbnail-gallery d-flex overflow-auto pb-2">
                            <div class="thumbnail-item me-2" onclick="updateMainImage('<?= $motorcycle['image_url'] ?>')">
                                <img src="<?= $motorcycle['image_url'] ?>" class="img-thumbnail" 
                                    alt="<?= $motorcycle['name'] ?>" width="100">
                            </div>
                            
                            <?php foreach ($additionalImages as $image): ?>
                                <div class="thumbnail-item me-2" onclick="updateMainImage('<?= $image['image_url'] ?>')">
                                    <img src="<?= $image['image_url'] ?>" class="img-thumbnail" 
                                        alt="<?= $motorcycle['name'] ?>" width="100">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-5">
                <div class="product-info">
                    <h1 class="mb-2"><?= $motorcycle['name'] ?></h1>
                    <div class="brand-year d-flex align-items-center mb-3">
                        <span class="badge bg-secondary me-2"><?= $motorcycle['brand_name'] ?></span>
                        <span class="badge bg-primary"><?= $motorcycle['model_year'] ?></span>
                        <?php if ($motorcycle['condition'] !== 'New'): ?>
                            <span class="badge bg-warning ms-2"><?= $motorcycle['condition'] ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <h2 class="price text-primary mb-4">$<?= number_format($motorcycle['price'], 2) ?></h2>
                    
                    <div class="availability mb-4">
                        <?php if ($motorcycle['stock'] > 0): ?>
                            <p class="text-success"><i class="bi bi-check-circle-fill"></i> In Stock</p>
                        <?php else: ?>
                            <p class="text-danger"><i class="bi bi-x-circle-fill"></i> Out of Stock</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="key-specs mb-4">
                        <h6>Key Specifications</h6>
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
                            <?php if (!empty($motorcycle['transmission_type'])): ?>
                                <div class="col-6">
                                    <small class="text-muted d-block">Transmission</small>
                                    <span><?= $motorcycle['transmission_type'] ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($motorcycle['mileage']) && $motorcycle['condition'] !== 'New'): ?>
                                <div class="col-6">
                                    <small class="text-muted d-block">Mileage</small>
                                    <span><?= number_format($motorcycle['mileage']) ?> km</span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($motorcycle['weight'])): ?>
                                <div class="col-6">
                                    <small class="text-muted d-block">Weight</small>
                                    <span><?= $motorcycle['weight'] ?> kg</span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($motorcycle['color'])): ?>
                                <div class="col-6">
                                    <small class="text-muted d-block">Color</small>
                                    <span><?= $motorcycle['color'] ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="actions mb-4">
                        <?php if ($motorcycle['stock'] > 0): ?>
                            <div class="d-grid gap-2">
                                <a href="/addcart/<?= $motorcycle['product_id'] ?>" class="btn btn-dark btn-lg">
                                    Add to Cart
                                </a>
                                <a href="/test-ride/request/<?= $motorcycle['product_id'] ?>" class="btn btn-outline-primary">
                                    Schedule Test Ride
                                </a>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#financingModal">
                                    Financing Options
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="d-grid gap-2">
                                <button class="btn btn-secondary btn-lg" disabled>Out of Stock</button>
                                <button class="btn btn-outline-primary" id="notifyBtn">
                                    Notify Me When Available
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                            data-bs-target="#description" type="button" role="tab" 
                            aria-controls="description" aria-selected="true">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specs-tab" data-bs-toggle="tab" 
                            data-bs-target="#specs" type="button" role="tab" 
                            aria-controls="specs" aria-selected="false">Specifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="features-tab" data-bs-toggle="tab" 
                            data-bs-target="#features" type="button" role="tab" 
                            aria-controls="features" aria-selected="false">Features</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                            data-bs-target="#reviews" type="button" role="tab" 
                            aria-controls="reviews" aria-selected="false">Reviews</button>
                    </li>
                </ul>
                
                <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabsContent">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="row">
                            <div class="col-md-8">
                                <?php if (!empty($motorcycle['description'])): ?>
                                    <?= nl2br(htmlspecialchars($motorcycle['description'])) ?>
                                <?php else: ?>
                                    <p class="text-muted">No detailed description available for this motorcycle.</p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <?php if ($motorcycle['condition'] !== 'New' && !empty($motorcycle['vin'])): ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Vehicle Information</h5>
                                            <p class="mb-1"><strong>VIN:</strong> <?= $motorcycle['vin'] ?></p>
                                            <p><strong>Mileage:</strong> <?= number_format($motorcycle['mileage']) ?> km</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Need Help?</h5>
                                        <p>Have questions about this motorcycle?</p>
                                        <a href="/contact" class="btn btn-outline-primary btn-sm">Contact Us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Specifications Tab -->
                    <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="specs-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Engine & Performance</h5>
                                <table class="table table-striped">
                                    <tbody>
                                    <?php if (!empty($motorcycle['engine_type'])): ?>
                                            <tr>
                                                <th>Engine Type</th>
                                                <td><?= $motorcycle['engine_type'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['engine_displacement'])): ?>
                                            <tr>
                                                <th>Displacement</th>
                                                <td><?= $motorcycle['engine_displacement'] ?> cc</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['horsepower'])): ?>
                                            <tr>
                                                <th>Horsepower</th>
                                                <td><?= $motorcycle['horsepower'] ?> hp</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['torque'])): ?>
                                            <tr>
                                                <th>Torque</th>
                                                <td><?= $motorcycle['torque'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['transmission_type'])): ?>
                                            <tr>
                                                <th>Transmission</th>
                                                <td><?= $motorcycle['transmission_type'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['gear_count'])): ?>
                                            <tr>
                                                <th>Gears</th>
                                                <td><?= $motorcycle['gear_count'] ?> Speed</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Dimensions & Capacities</h5>
                                <table class="table table-striped">
                                    <tbody>
                                        <?php if (!empty($motorcycle['weight'])): ?>
                                            <tr>
                                                <th>Weight</th>
                                                <td><?= $motorcycle['weight'] ?> kg</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['seat_height'])): ?>
                                            <tr>
                                                <th>Seat Height</th>
                                                <td><?= $motorcycle['seat_height'] ?> mm</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['fuel_capacity'])): ?>
                                            <tr>
                                                <th>Fuel Capacity</th>
                                                <td><?= $motorcycle['fuel_capacity'] ?> L</td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['fuel_economy'])): ?>
                                            <tr>
                                                <th>Fuel Economy</th>
                                                <td><?= $motorcycle['fuel_economy'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($motorcycle['color'])): ?>
                                            <tr>
                                                <th>Color</th>
                                                <td><?= $motorcycle['color'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Features Tab -->
                    <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                        <?php if (!empty($features)): ?>
                            <div class="row">
                                <?php foreach ($features as $feature): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $feature['feature_name'] ?></h5>
                                                <?php if (!empty($feature['feature_description'])): ?>
                                                    <p class="card-text"><?= $feature['feature_description'] ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No specific features listed for this motorcycle.</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <?php if (!empty($reviews)): ?>
                            <div class="reviews-container">
                                <?php foreach ($reviews as $review): ?>
                                    <div class="review-card mb-4">
                                        <div class="review-header d-flex justify-content-between">
                                            <div>
                                                <h5 class="mb-1"><?= $review['title'] ?></h5>
                                                <div class="rating">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="bi bi-star<?= ($i <= $review['rating']) ? '-fill' : '' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <div class="text-muted">
                                                <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                            </div>
                                        </div>
                                        <div class="review-body mt-2">
                                            <p><?= $review['comment'] ?></p>
                                        </div>
                                        <div class="review-footer d-flex align-items-center">
                                            <span class="text-muted">By <?= $review['user_name'] ?></span>
                                            <?php if ($review['verified_purchase']): ?>
                                                <span class="badge bg-success ms-2">Verified Purchase</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No reviews yet for this motorcycle.</p>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="write-review mt-4">
                                <h5>Write a Review</h5>
                                <form action="/reviews/submit" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $motorcycle['product_id'] ?>">
                                    <div class="mb-3">
                                        <label for="reviewTitle" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="reviewTitle" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-input">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" required>
                                                <label class="form-check-label" for="rating1">1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                                <label class="form-check-label" for="rating2">2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating3" value="3">
                                                <label class="form-check-label" for="rating3">3</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating4" value="4">
                                                <label class="form-check-label" for="rating4">4</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating5" value="5">
                                                <label class="form-check-label" for="rating5">5</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reviewComment" class="form-label">Comment</label>
                                        <textarea class="form-control" id="reviewComment" name="comment" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mt-4">
                                <i class="bi bi-info-circle"></i> Please <a href="#" data-bs-toggle="modal" data-bs-target="#authModal">login</a> to write a review.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Motorcycles -->
        <section class="related-products mt-5">
            <h3 class="mb-4">Related Motorcycles</h3>
            <div class="row">
                <?php foreach ($relatedMotorcycles as $related): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <img src="<?= $related['image_url'] ?? 'asset/images/default-motorcycle.jpg' ?>" 
                                class="card-img-top" alt="<?= $related['name'] ?>" 
                                style="height: 180px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= $related['name'] ?></h5>
                                <p class="text-muted"><?= $related['brand_name'] ?> | <?= $related['model_year'] ?></p>
                                <p class="fw-bold mb-3">$<?= number_format($related['price'], 2) ?></p>
                                <div class="d-grid">
                                    <a href="/motorcycle/<?= $related['product_id'] ?>" class="btn btn-outline-dark btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</section>

<!-- Financing Modal -->
<div class="modal fade" id="financingModal" tabindex="-1" aria-labelledby="financingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="financingModalLabel">Financing Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="financing-calculator mb-4">
                    <h6>Financing Calculator</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="financeAmount" class="form-label">Purchase Price</label>
                                <input type="number" class="form-control" id="financeAmount" value="<?= $motorcycle['price'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="downPayment" class="form-label">Down Payment</label>
                                <input type="number" class="form-control" id="downPayment" value="<?= round($motorcycle['price'] * 0.1) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="interestRate" class="form-label">Interest Rate (%)</label>
                                <select class="form-select" id="interestRate">
                                    <option value="5.99">Standard - 5.99%</option>
                                    <option value="3.99">Premium - 3.99%</option>
                                    <option value="7.99">Zero Down - 7.99%</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="termLength" class="form-label">Term Length</label>
                                <select class="form-select" id="termLength">
                                    <option value="24">24 months</option>
                                    <option value="36" selected>36 months</option>
                                    <option value="48">48 months</option>
                                    <option value="60">60 months</option>
                                    <option value="72">72 months</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" id="calculateBtn">Calculate Payment</button>
                    </div>
                </div>
                
                <div class="calculation-results" id="calculationResults" style="display: none;">
                    <div class="alert alert-primary">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="alert-heading">Estimated Monthly Payment</h4>
                                <h3 class="mb-0" id="monthlyPayment">$0</h3>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Total Loan Amount:</strong> <span id="loanAmount">$0</span></p>
                                <p class="mb-1"><strong>Total Interest:</strong> <span id="totalInterest">$0</span></p>
                                <p class="mb-0"><strong>Total Cost:</strong> <span id="totalCost">$0</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="financing-options mt-4">
                    <h6>Available Plans</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Interest Rate</th>
                                    <th>Term</th>
                                    <th>Min. Down Payment</th>
                                    <th>Requirements</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($financingOptions as $option): ?>
                                    <tr>
                                        <td><?= $option['name'] ?></td>
                                        <td><?= $option['interest_rate'] ?>%</td>
                                        <td><?= $option['min_term'] ?>-<?= $option['max_term'] ?> months</td>
                                        <td><?= $option['min_downpayment_percentage'] ?>%</td>
                                        <td><?= $option['requirements'] ?? 'Standard approval process' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="/financing/apply/<?= $motorcycle['product_id'] ?>" class="btn btn-primary">Apply Now</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Update main image when clicking thumbnails
    function updateMainImage(imageUrl) {
        document.getElementById('mainImage').src = imageUrl;
    }
    
    // Financing calculator
    document.getElementById('calculateBtn').addEventListener('click', function() {
        const price = parseFloat(document.getElementById('financeAmount').value);
        const downPayment = parseFloat(document.getElementById('downPayment').value);
        const interestRate = parseFloat(document.getElementById('interestRate').value);
        const term = parseInt(document.getElementById('termLength').value);
        
        // Calculate loan amount
        const loanAmount = price - downPayment;
        
        // Calculate monthly interest rate
        const monthlyRate = interestRate / 100 / 12;
        
        // Calculate monthly payment
        const monthlyPayment = loanAmount * monthlyRate * Math.pow(1 + monthlyRate, term) / 
                              (Math.pow(1 + monthlyRate, term) - 1);
                              
        // Calculate total interest
        const totalInterest = (monthlyPayment * term) - loanAmount;
        
        // Calculate total cost
        const totalCost = downPayment + (monthlyPayment * term);
        
        // Display results
        document.getElementById('monthlyPayment').textContent = '$' + monthlyPayment.toFixed(2);
        document.getElementById('loanAmount').textContent = '$' + loanAmount.toFixed(2);
        document.getElementById('totalInterest').textContent = '$' + totalInterest.toFixed(2);
        document.getElementById('totalCost').textContent = '$' + totalCost.toFixed(2);
        
        document.getElementById('calculationResults').style.display = 'block';
    });
</script>

<?php require base_path('views/client/partials/footer.php') ?>