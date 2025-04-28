<?php require base_path('views/dashboard/partials/head.php') ?>
<?php require base_path('views/dashboard/partials/sidebar.php') ?>
<?php require base_path('views/dashboard/partials/nav.php') ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New Motorcycle</h1>
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
                <form action="/motorcycles" method="POST" enctype="multipart/form-data" id="motorcycleForm">
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
                                        <input type="text" class="form-control" id="motorcycle-name" name="motorcycle-name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="motorcycle-brand">Brand</label>
                                        <select class="form-control" id="motorcycle-brand" name="motorcycle-brand" required>
                                            <option value="">Select Brand</option>
                                            <?php foreach ($brands as $brand): ?>
                                                <option value="<?php echo $brand['brand_id']; ?>"><?php echo htmlspecialchars($brand['brand_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="motorcycle-year">Year</label>
                                        <input type="number" class="form-control" id="motorcycle-year" name="motorcycle-year" min="1900" max="<?php echo date('Y') + 1; ?>" required>
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
                                                <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
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
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="motorcycle-featured" name="motorcycle-featured">
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
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="motorcycle-image" name="motorcycle-image" accept="image/*" required>
                                        <label class="custom-file-label" for="motorcycle-image">Choose file...</label>
                                    </div>
                                    <small class="form-text text-muted">This will be the main image displayed for this motorcycle.</small>
                                    <div id="primaryImagePreview" class="mt-3"></div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <h5 class="font-weight-bold mb-3">Additional Images</h5>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="motorcycle-additional-images" name="motorcycle-additional-images[]" multiple accept="image/*">
                                        <label class="custom-file-label" for="motorcycle-additional-images">Choose files...</label>
                                    </div>
                                    <small class="form-text text-muted">You can select multiple images (max 8) to create a gallery for this motorcycle.</small>
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
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="financing-standard" name="financing-options[]" value="1">
                                    <label class="custom-control-label" for="financing-standard">
                                        <span class="font-weight-bold">Standard Financing</span> - 5.99% interest, 12-60 months
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="financing-premium" name="financing-options[]" value="2">
                                    <label class="custom-control-label" for="financing-premium">
                                        <span class="font-weight-bold">Premium Financing</span> - 3.99% interest, 24-72 months
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="financing-zero-down" name="financing-options[]" value="3">
                                    <label class="custom-control-label" for="financing-zero-down">
                                        <span class="font-weight-bold">Zero Down Special</span> - 7.99% interest, 36-60 months
                                    </label>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="button" class="btn btn-secondary" onclick="$('#images-tab').tab('show')">
                                    <i class="fas fa-arrow-left"></i> Previous
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Save Motorcycle
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

// File upload labels and previews
document.getElementById('motorcycle-image').addEventListener('change', function(e) {
    // Update file label
    var fileName = e.target.files[0].name;
    var label = e.target.nextElementSibling;
    label.innerText = fileName;
    
    // Display image preview
    var preview = document.getElementById('primaryImagePreview');
    preview.innerHTML = '';
    
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="card" style="max-width: 300px;">
                    <div class="card-header">Primary Image Preview</div>
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
    // Update file label
    var label = e.target.nextElementSibling;
    if (this.files.length > 1) {
        label.innerText = this.files.length + ' files selected';
    } else if (this.files.length == 1) {
        label.innerText = this.files[0].name;
    }
    
    // Display image previews
    var preview = document.getElementById('additionalImagesPreview');
    preview.innerHTML = '';
    
    if (this.files.length > 8) {
        alert('You can only upload up to 8 additional images.');
        this.value = '';
        label.innerText = 'Choose files...';
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
            
            // Find the tab this field is in
            var tabPane = field.closest('.tab-pane');
            var tabId = tabPane.id;
            
            // Activate the tab with the invalid field
            $('#formTabs a[href="#' + tabId + '"]').tab('show');
            
            // Focus the first invalid field
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
</script>

<?php require base_path('views/dashboard/partials/footer.php') ?>