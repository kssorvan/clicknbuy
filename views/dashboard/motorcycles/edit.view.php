<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Motorcycle</h1>
    <a href="/motorcycles/admin" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
    </a>
</div>

<div class="row">
    <div class="col-12">
        <!-- Main Form Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Motorcycle Details</h6>
            </div>
            <div class="card-body">
                <?php if ($error = \Core\Session::get('error')): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if ($success = \Core\Session::get('success')): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                <form action="/motorcycles/update" method="POST" enctype="multipart/form-data" id="motorcycleForm">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                    <input type="hidden" name="existing-image-url" value="<?php echo htmlspecialchars($product['image_url']); ?>">
                    <!-- Form Navigation Tabs -->
                    <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">Basic Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="specs-tab" data-toggle="tab" href="#specs" role="tab" aria-controls="specs" aria-selected="false">Specifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="features-tab" data-toggle="tab" href="#features" role="tab" aria-controls="features" aria-selected="false">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="images-tab" data-toggle="tab" href="#images" role="tab" aria-controls="images" aria-selected="false">Images</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="financing-tab" data-toggle="tab" href="#financing" role="tab" aria-controls="financing" aria-selected="false">Financing</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="formTabContent">
                        <!-- Basic Information Tab -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="motorcycle-name">Motorcycle Name</label>
                                        <input type="text" class="form-control <?php echo isset($errors['motorcycle-name']) ? 'is-invalid' : ''; ?>" 
                                               id="motorcycle-name" name="motorcycle-name" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-name'] ?? $product['name']); ?>" required>
                                        <?php if (isset($errors['motorcycle-name'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['motorcycle-name']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="motorcycle-brand">Brand</label>
                                        <select class="form-control <?php echo isset($errors['motorcycle-brand']) ? 'is-invalid' : ''; ?>" 
                                                id="motorcycle-brand" name="motorcycle-brand" required>
                                            <option value="">Select Brand</option>
                                            <?php foreach ($brands as $brand): ?>
                                                <option value="<?php echo $brand['brand_id']; ?>" 
                                                        <?php echo ($old['motorcycle-brand'] ?? $product['brand_id']) == $brand['brand_id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['motorcycle-brand'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['motorcycle-brand']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-year">Year</label>
                                        <input type="number" class="form-control <?php echo isset($errors['motorcycle-year']) ? 'is-invalid' : ''; ?>" 
                                               id="motorcycle-year" name="motorcycle-year" min="1900" max="<?php echo date('Y') + 1; ?>" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-year'] ?? $product['model_year']); ?>" required>
                                        <?php if (isset($errors['motorcycle-year'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['motorcycle-year']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-price">Price ($)</label>
                                        <input type="number" step="0.01" class="form-control <?php echo isset($errors['motorcycle-price']) ? 'is-invalid' : ''; ?>" 
                                               id="motorcycle-price" name="motorcycle-price" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-price'] ?? $product['price']); ?>" required>
                                        <?php if (isset($errors['motorcycle-price'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['motorcycle-price']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-stock">Stock</label>
                                        <input type="number" class="form-control <?php echo isset($errors['motorcycle-stock']) ? 'is-invalid' : ''; ?>" 
                                               id="motorcycle-stock" name="motorcycle-stock" min="1" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-stock'] ?? $product['stock']); ?>" required>
                                        <?php if (isset($errors['motorcycle-stock'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['motorcycle-stock']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-category">Category</label>
                                        <select class="form-control <?php echo isset($errors['motorcycle-category']) ? 'is-invalid' : ''; ?>" 
                                                id="motorcycle-category" name="motorcycle-category" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['category_id']; ?>" 
                                                        <?php echo ($old['motorcycle-category'] ?? $product['category_id']) == $category['category_id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['motorcycle-category'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['motorcycle-category']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-condition">Condition</label>
                                        <select class="form-control <?php echo isset($errors['motorcycle-condition']) ? 'is-invalid' : ''; ?>" 
                                                id="motorcycle-condition" name="motorcycle-condition" required>
                                            <option value="New" <?php echo ($old['motorcycle-condition'] ?? $product['condition']) == 'New' ? 'selected' : ''; ?>>New</option>
                                            <option value="Used" <?php echo ($old['motorcycle-condition'] ?? $product['condition']) == 'Used' ? 'selected' : ''; ?>>Used</option>
                                            <option value="Certified Pre-Owned" <?php echo ($old['motorcycle-condition'] ?? $product['condition']) == 'Certified Pre-Owned' ? 'selected' : ''; ?>>Certified Pre-Owned</option>
                                        </select>
                                        <?php if (isset($errors['motorcycle-condition'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['motorcycle-condition']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-color">Color</label>
                                        <input type="text" class="form-control" id="motorcycle-color" name="motorcycle-color" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-color'] ?? $product['color']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-mileage">Mileage</label>
                                        <input type="number" class="form-control" id="motorcycle-mileage" name="motorcycle-mileage" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-mileage'] ?? $product['mileage']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-vin">VIN</label>
                                        <input type="text" class="form-control" id="motorcycle-vin" name="motorcycle-vin" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-vin'] ?? $product['vin']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="motorcycle-description">Description</label>
                                        <textarea class="form-control" id="motorcycle-description" name="motorcycle-description" rows="4"><?php echo htmlspecialchars($old['motorcycle-description'] ?? $product['description']); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="motorcycle-featured" name="motorcycle-featured" 
                                               <?php echo ($old['motorcycle-featured'] ?? $product['is_featured']) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="motorcycle-featured">Feature this motorcycle</label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-primary" onclick="$('#specs-tab').tab('show')">
                                    Next: Specifications <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Specifications Tab -->
                        <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="specs-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-engine-type">Engine Type</label>
                                        <input type="text" class="form-control" id="motorcycle-engine-type" name="motorcycle-engine-type" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-engine-type'] ?? $product['engine_type']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-displacement">Engine Displacement (cc)</label>
                                        <input type="number" class="form-control" id="motorcycle-displacement" name="motorcycle-displacement" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-displacement'] ?? $product['engine_displacement']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-horsepower">Horsepower</label>
                                        <input type="number" class="form-control" id="motorcycle-horsepower" name="motorcycle-horsepower" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-horsepower'] ?? $product['horsepower']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-torque">Torque</label>
                                        <input type="text" class="form-control" id="motorcycle-torque" name="motorcycle-torque" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-torque'] ?? $product['torque']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-transmission">Transmission Type</label>
                                        <input type="text" class="form-control" id="motorcycle-transmission" name="motorcycle-transmission" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-transmission'] ?? $product['transmission_type']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-gears">Number of Gears</label>
                                        <input type="number" class="form-control" id="motorcycle-gears" name="motorcycle-gears" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-gears'] ?? $product['gear_count']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-fuel-capacity">Fuel Capacity (L)</label>
                                        <input type="number" step="0.1" class="form-control" id="motorcycle-fuel-capacity" name="motorcycle-fuel-capacity" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-fuel-capacity'] ?? $product['fuel_capacity']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-fuel-economy">Fuel Economy</label>
                                        <input type="text" class="form-control" id="motorcycle-fuel-economy" name="motorcycle-fuel-economy" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-fuel-economy'] ?? $product['fuel_economy']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-seat-height">Seat Height (mm)</label>
                                        <input type="number" class="form-control" id="motorcycle-seat-height" name="motorcycle-seat-height" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-seat-height'] ?? $product['seat_height']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorcycle-weight">Weight (kg)</label>
                                        <input type="number" step="0.1" class="form-control" id="motorcycle-weight" name="motorcycle-weight" 
                                               value="<?php echo htmlspecialchars($old['motorcycle-weight'] ?? $product['weight']); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-secondary" onclick="$('#basic-tab').tab('show')">
                                    <i class="fas fa-arrow-left"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary" onclick="$('#features-tab').tab('show')">
                                    Next: Features <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Features Tab -->
                        <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <h5 class="font-weight-bold">Key Features</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="addFeatureBtn">
                                    <i class="fas fa-plus-circle"></i> Add Feature
                                </button>
                            </div>
                            <div id="featuresContainer">
                                <?php if (empty($features)): ?>
                                    <div class="row feature-row mb-3">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Feature Name</label>
                                                <input type="text" class="form-control" name="feature-name[]" placeholder="Feature Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Feature Description</label>
                                                <input type="text" class="form-control" name="feature-description[]" placeholder="Feature Description">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block remove-feature">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($features as $index => $feature): ?>
                                        <div class="row feature-row mb-3">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Feature Name</label>
                                                    <input type="text" class="form-control" name="feature-name[]" 
                                                           value="<?php echo htmlspecialchars($old['feature-name'][$index] ?? $feature['feature_name']); ?>" 
                                                           placeholder="Feature Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Feature Description</label>
                                                    <input type="text" class="form-control" name="feature-description[]" 
                                                           value="<?php echo htmlspecialchars($old['feature-description'][$index] ?? $feature['feature_description']); ?>" 
                                                           placeholder="Feature Description">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-block remove-feature">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="text-right mt-3">
                                <button type="button" class="btn btn-secondary" onclick="$('#specs-tab').tab('show')">
                                    <i class="fas fa-arrow-left"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary" onclick="$('#images-tab').tab('show')">
                                    Next: Images <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Images Tab -->
                        <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <h5 class="font-weight-bold mb-3">Primary Image</h5>
                                    <div class="mb-3">
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                             alt="Current Primary Image" class="img-fluid" style="max-width: 300px;">
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input <?php echo isset($errors['motorcycle-image']) ? 'is-invalid' : ''; ?>" 
                                               id="motorcycle-image" name="motorcycle-image" accept="image/*">
                                        <label class="custom-file-label" for="motorcycle-image">Choose new file...</label>
                                        <?php if (isset($errors['motorcycle-image'])): ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['motorcycle-image']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <small class="form-text text-muted">Upload a new image to replace the current one (optional).</small>
                                    <div id="primaryImagePreview" class="mt-3"></div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <h5 class="font-weight-bold mb-3">Additional Images</h5>
                                    <div class="row mb-3">
                                        <?php foreach ($additionalImages as $image): ?>
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                                                         class="card-img-top" alt="Additional Image" style="height: 150px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <button type="button" class="btn btn-danger btn-sm btn-block remove-image" 
                                                                data-image-id="<?php echo $image['id']; ?>">
                                                            <i class="fas fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="motorcycle-additional-images" 
                                               name="motorcycle-additional-images[]" multiple accept="image/*">
                                        <label class="custom-file-label" for="motorcycle-additional-images">Choose new files...</label>
                                    </div>
                                    <small class="form-text text-muted">You can select multiple images (max 8) to add to the gallery.</small>
                                    <div id="additionalImagesPreview" class="row mt-3"></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-secondary" onclick="$('#features-tab').tab('show')">
                                    <i class="fas fa-arrow-left"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary" onclick="$('#financing-tab').tab('show')">
                                    Next: Financing <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Financing Tab -->
                        <div class="tab-pane fade" id="financing" role="tabpanel" aria-labelledby="financing-tab">
                            <h5 class="font-weight-bold mb-3">Available Financing Options</h5>
                            <p class="text-muted mb-4">Select the financing options that will be available for this motorcycle.</p>
                            <div class="form-group">
                                <?php 
                                $selectedFinancing = explode(',', $product['financing_options'] ?? '');
                                foreach ($financingOptions as $option): ?>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="financing-<?php echo $option['id']; ?>" 
                                               name="financing-options[]" 
                                               value="<?php echo $option['id']; ?>" 
                                               <?php echo in_array($option['id'], $selectedFinancing) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="financing-<?php echo $option['id']; ?>">
                                            <span class="font-weight-bold"><?php echo htmlspecialchars($option['name']); ?></span> - 
                                            <?php echo htmlspecialchars($option['description']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-right mt-4">
                                <button type="button" class="btn btn-secondary" onclick="$('#images-tab').tab('show')">
                                    <i class="fas fa-arrow-left"></i> Previous
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update Motorcycle
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Add Feature functionality
document.getElementById('addFeatureBtn').addEventListener('click', function() {
    const container = document.getElementById('featuresContainer');
    const newRow = document.createElement('div');
    newRow.className = 'row feature-row mb-3';
    newRow.innerHTML = `
        <div class="col-md-5">
            <div class="form-group">
                <label>Feature Name</label>
                <input type="text" class="form-control" name="feature-name[]" placeholder="Feature Name">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Feature Description</label>
                <input type="text" class="form-control" name="feature-description[]" placeholder="Feature Description">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-danger btn-block remove-feature">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    
    newRow.querySelector('.remove-feature').addEventListener('click', function() {
        container.removeChild(newRow);
    });
});

// Remove Feature functionality
document.querySelectorAll('.remove-feature').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('.feature-row');
        row.parentNode.removeChild(row);
    });
});

// File upload labels and previews
document.getElementById('motorcycle-image').addEventListener('change', function(e) {
    var fileName = e.target.files[0]?.name || 'Choose new file...';
    var label = e.target.nextElementSibling;
    label.innerText = fileName;
    
    var preview = document.getElementById('primaryImagePreview');
    preview.innerHTML = '';
    
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="card" style="max-width: 300px;">
                    <div class="card-header">New Primary Image Preview</div>
                    <img src="${e.target.result}" class="card-img-top" alt="Primary Image Preview">
                    <div class="card-body py-2">
                        <small class="text-muted">${fileName}</small>
                    </div>
                </div>
            `;
        }
        reader.readAsDataURL(this.files[0]);
    }
});

document.getElementById('motorcycle-additional-images').addEventListener('change', function(e) {
    var label = e.target.nextElementSibling;
    if (this.files.length > 1) {
        label.innerText = this.files.length + ' files selected';
    } else if (this.files.length == 1) {
        label.innerText = this.files[0].name;
    } else {
        label.innerText = 'Choose new files...';
    }
    
    var preview = document.getElementById('additionalImagesPreview');
    preview.innerHTML = '';
    
    if (this.files.length > 8) {
        alert('You can only upload up to 8 additional images.');
        this.value = '';
        label.innerText = 'Choose new files...';
        return;
    }
    
    for (var i = 0; i < this.files.length; i++) {
        var reader = new FileReader();
        var file = this.files[i];
        
        reader.onload = (function(file) {
            return function(e) {
                var div = document.createElement('div');
                div.className = 'col-md-3 mb-3';
                div.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" alt="Preview" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">${file.name}</small>
                        </div>
                    </div>
                `;
                preview.appendChild(div);
            }
        })(file);
        
        reader.readAsDataURL(file);
    }
});

// Form validation
document.getElementById('motorcycleForm').addEventListener('submit', function(e) {
    var requiredFields = this.querySelectorAll('[required]');
    var valid = true;
    
    requiredFields.forEach(function(field) {
        if (!field.value) {
            valid = false;
            field.classList.add('is-invalid');
            
            var tabPane = field.closest('.tab-pane');
            var tabId = tabPane.id;
            
            $('#formTabs a[href="#' + tabId + '"]').tab('show');
            
            if (field === requiredFields[0]) {
                field.focus();
            }
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Please fill out all required fields.');
    }
});

// Remove existing additional images (client-side placeholder; actual deletion requires server-side logic)
document.querySelectorAll('.remove-image').forEach(button => {
    button.addEventListener('click', function() {
        if (confirm('Are you sure you want to remove this image?')) {
            // This is a placeholder; actual deletion should be handled server-side
            this.closest('.col-md-3').remove();
            // Optionally, send an AJAX request to delete the image from the database
        }
    });
});
</script>

<?php require base_path('views/dashboard/partials/footer.php') ?>