<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0">Add New Motorcycle</h6>
                        <a href="/motorcycles" class="btn btn-link text-secondary ms-auto">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="/motorcycles" method="POST" enctype="multipart/form-data">
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="mb-3">Basic Information</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="motorcycle-name">Motorcycle Name</label>
                                    <input type="text" class="form-control" id="motorcycle-name" name="motorcycle-name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="motorcycle-brand">Brand</label>
                                    <select class="form-control" id="motorcycle-brand" name="motorcycle-brand" required>
                                        <option value="">Select Brand</option>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?= $brand['brand_id'] ?>"><?= $brand['brand_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motorcycle-year">Year</label>
                                    <input type="number" class="form-control" id="motorcycle-year" name="motorcycle-year" min="1900" max="<?= date('Y') + 1 ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motorcycle-price">Price ($)</label>
                                    <input type="number" step="0.01" class="form-control" id="motorcycle-price" name="motorcycle-price" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motorcycle-stock">Stock</label>
                                    <input type="number" class="form-control" id="motorcycle-stock" name="motorcycle-stock" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motorcycle-category">Category</label>
                                    <select class="form-control" id="motorcycle-category" name="motorcycle-category" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motorcycle-condition">Condition</label>
                                    <select class="form-control" id="motorcycle-condition" name="motorcycle-condition" required>
                                        <option value="New">New</option>
                                        <option value="Used">Used</option>
                                        <option value="Certified Pre-Owned">Certified Pre-Owned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motorcycle-color">Color</label>
                                    <input type="text" class="form-control" id="motorcycle-color" name="motorcycle-color">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motorcycle-mileage">Mileage</label>
                                    <input type="number" class="form-control" id="motorcycle-mileage" name="motorcycle-mileage" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="motorcycle-vin">VIN</label>
                                    <input type="text" class="form-control" id="motorcycle-vin" name="motorcycle-vin">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="motorcycle-description">Description</label>
                                    <textarea class="form-control" id="motorcycle-description" name="motorcycle-description" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="motorcycle-featured" name="motorcycle-featured">
                                    <label class="form-check-label" for="motorcycle-featured">
                                        Feature this motorcycle
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Specifications -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="mb-3">Technical Specifications</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-engine-type">Engine Type</label>
                                    <input type="text" class="form-control" id="motorcycle-engine-type" name="motorcycle-engine-type">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-displacement">Engine Displacement (cc)</label>
                                    <input type="number" class="form-control" id="motorcycle-displacement" name="motorcycle-displacement">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-horsepower">Horsepower</label>
                                    <input type="number" class="form-control" id="motorcycle-horsepower" name="motorcycle-horsepower">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-torque">Torque</label>
                                    <input type="text" class="form-control" id="motorcycle-torque" name="motorcycle-torque">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-transmission">Transmission Type</label>
                                    <input type="text" class="form-control" id="motorcycle-transmission" name="motorcycle-transmission">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-gears">Number of Gears</label>
                                    <input type="number" class="form-control" id="motorcycle-gears" name="motorcycle-gears">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-fuel-capacity">Fuel Capacity (L)</label>
                                    <input type="number" step="0.1" class="form-control" id="motorcycle-fuel-capacity" name="motorcycle-fuel-capacity">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-fuel-economy">Fuel Economy</label>
                                    <input type="text" class="form-control" id="motorcycle-fuel-economy" name="motorcycle-fuel-economy">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-seat-height">Seat Height (mm)</label>
                                    <input type="number" class="form-control" id="motorcycle-seat-height" name="motorcycle-seat-height">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="motorcycle-weight">Weight (kg)</label>
                                    <input type="number" step="0.1" class="form-control" id="motorcycle-weight" name="motorcycle-weight">
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="row mb-4">
                            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Features</h6>
                                <button type="button" class="btn btn-sm btn-info" id="addFeatureBtn">
                                    <i class="fas fa-plus"></i> Add Feature
                                </button>
                            </div>
                            <div class="col-12">
                                <div id="featuresContainer">
                                    <!-- Features will be added dynamically -->
                                    <div class="row feature-row mb-2">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="feature-name[]"placeholder="Feature Name">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="feature-description[]" 
                                            placeholder="Feature Description">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-danger remove-feature">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="mb-3">Images</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="motorcycle-image">Primary Image</label>
                                    <input type="file" class="form-control" id="motorcycle-image" name="motorcycle-image" accept="image/*" required>
                                    <small class="text-muted">This will be the main image displayed for this motorcycle.</small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="motorcycle-additional-images">Additional Images</label>
                                    <input type="file" class="form-control" id="motorcycle-additional-images" name="motorcycle-additional-images[]" accept="image/*" multiple>
                                    <small class="text-muted">You can select multiple images to create a gallery (max 8).</small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="preview-container row" id="imagePreviewContainer">
                                    <!-- Image previews will be added here -->
                                </div>
                            </div>
                        </div>

                        <!-- Financing Options -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="mb-3">Financing Options</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input financing-option" type="checkbox" value="1" id="financing-standard" name="financing-options[]">
                                        <label class="form-check-label" for="financing-standard">
                                            Standard Financing (5.99% interest, 12-60 months)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input financing-option" type="checkbox" value="2" id="financing-premium" name="financing-options[]">
                                        <label class="form-check-label" for="financing-premium">
                                            Premium Financing (3.99% interest, 24-72 months)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input financing-option" type="checkbox" value="3" id="financing-zero-down" name="financing-options[]">
                                        <label class="form-check-label" for="financing-zero-down">
                                            Zero Down Special (7.99% interest, 36-60 months)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save Motorcycle</button>
                                <a href="/motorcycles" class="btn btn-light">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Add Feature functionality
    document.getElementById('addFeatureBtn').addEventListener('click', function() {
        const container = document.getElementById('featuresContainer');
        const newRow = document.createElement('div');
        newRow.className = 'row feature-row mb-2';
        newRow.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="feature-name[]" placeholder="Feature Name">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="feature-description[]" placeholder="Feature Description">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger remove-feature">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        
        // Add event listener to the new remove button
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
    
    // Image preview functionality
    document.getElementById('motorcycle-image').addEventListener('change', function(e) {
        previewImage(this, 'primary-preview');
    });
    
    document.getElementById('motorcycle-additional-images').addEventListener('change', function(e) {
        const container = document.getElementById('imagePreviewContainer');
        container.innerHTML = ''; // Clear previous previews
        
        if (this.files.length > 8) {
            alert('You can only upload up to 8 additional images.');
            this.value = '';
            return;
        }
        
        for (let i = 0; i < this.files.length; i++) {
            const file = this.files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'col-md-3 mb-3';
                previewDiv.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" alt="Preview" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">${file.name}</small>
                        </div>
                    </div>
                `;
                container.appendChild(previewDiv);
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                let preview = document.getElementById(previewId);
                
                if (!preview) {
                    const container = document.getElementById('imagePreviewContainer');
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'col-md-12 mb-3';
                    previewDiv.innerHTML = `
                        <div class="card">
                            <div class="card-header">Primary Image</div>
                            <img src="${e.target.result}" id="${previewId}" class="card-img-top" alt="Primary Preview" style="height: 200px; object-fit: contain;">
                            <div class="card-body p-2">
                                <small class="text-muted">${input.files[0].name}</small>
                            </div>
                        </div>
                    `;
                    container.prepend(previewDiv);
                } else {
                    preview.src = e.target.result;
                    preview.parentNode.querySelector('small').textContent = input.files[0].name;
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = this.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill out all required fields.');
            window.scrollTo(0, 0);
        }
    });
</script>

<?php require base_path('views/dashboard/partials/footer.php') ?>