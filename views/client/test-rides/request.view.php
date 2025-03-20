<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>

<section class="pt-5 mt-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/motorcycles">Motorcycles</a></li>
                <li class="breadcrumb-item"><a href="/motorcycle/<?= $motorcycle['product_id'] ?>"><?= $motorcycle['name'] ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Schedule Test Ride</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <img src="<?= $motorcycle['image_url'] ?>" class="card-img-top" alt="<?= $motorcycle['name'] ?>">
                    <div class="card-body">
                        <h5 class="card-title mb-1"><?= $motorcycle['name'] ?></h5>
                        <p class="text-muted"><?= $motorcycle['brand_name'] ?> | <?= $motorcycle['model_year'] ?></p>
                        <p class="fw-bold text-primary">$<?= number_format($motorcycle['price'], 2) ?></p>
                        
                        <div class="key-specs small">
                            <?php if (!empty($motorcycle['engine_displacement'])): ?>
                                <p class="mb-1"><strong>Engine:</strong> <?= $motorcycle['engine_displacement'] ?> cc</p>
                            <?php endif; ?>
                            <?php if (!empty($motorcycle['horsepower'])): ?>
                                <p class="mb-1"><strong>Power:</strong> <?= $motorcycle['horsepower'] ?> hp</p>
                            <?php endif; ?>
                            <?php if (!empty($motorcycle['weight'])): ?>
                                <p class="mb-1"><strong>Weight:</strong> <?= $motorcycle['weight'] ?> kg</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Schedule Your Test Ride</h4>
                    </div>
                    <div class="card-body">
                        <form action="/test-ride/submit" method="POST">
                            <input type="hidden" name="product_id" value="<?= $motorcycle['product_id'] ?>">
                            
                            <div class="mb-4">
                                <h5>Select a Date</h5>
                                <div class="date-grid mb-3">
                                    <?php foreach ($availableDates as $date): ?>
                                        <?php
                                            $dateObj = new DateTime($date);
                                            $displayDate = $dateObj->format('D, M j');
                                            $isFullyBooked = true;
                                            
                                            // Check if any time slots are available for this date
                                            foreach ($availableTimeSlots as $timeValue => $timeLabel) {
                                                if (!isset($bookedSlots[$date . ' ' . $timeValue])) {
                                                    $isFullyBooked = false;
                                                    break;
                                                }
                                            }
                                        ?>
                                        <div class="form-check date-option">
                                            <input class="form-check-input date-radio" type="radio" name="date" 
                                                id="date-<?= $date ?>" value="<?= $date ?>" 
                                                <?= $isFullyBooked ? 'disabled' : '' ?>>
                                            <label class="form-check-label date-label <?= $isFullyBooked ? 'disabled' : '' ?>" 
                                                for="date-<?= $date ?>">
                                                <span class="date-text"><?= $displayDate ?></span>
                                                <?php if ($isFullyBooked): ?>
                                                    <span class="badge bg-secondary">Fully Booked</span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h5>Select a Time</h5>
                                <div class="time-slots" id="timeSlots">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> Please select a date first to see available time slots.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h5>Your Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" value="<?= $_SESSION['user']['name'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" value="<?= $_SESSION['user']['email'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                        placeholder="Any questions or special requests for your test ride?"></textarea>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h5>Test Ride Policy</h5>
                                <div class="alert alert-light">
                                    <p class="mb-2"><strong>Requirements:</strong></p>
                                    <ul class="mb-3">
                                        <li>Valid motorcycle license</li>
                                        <li>Proof of insurance</li>
                                        <li>Minimum age of 21 years</li>
                                        <li>Proper riding gear (helmet, jacket, gloves, boots)</li>
                                    </ul>
                                    <p class="mb-0 small">By scheduling a test ride, you agree to our test ride policies. We will contact you to confirm your appointment and provide additional details.</p>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="policyAgreement" required>
                                    <label class="form-check-label" for="policyAgreement">
                                        I agree to the test ride policy and have the required license, insurance, and gear.
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                    Schedule Test Ride
                                </button>
                                <a href="/motorcycle/<?= $motorcycle['product_id'] ?>" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Date selection
    const dateOptions = document.querySelectorAll('.date-radio');
    const timeSlots = document.getElementById('timeSlots');
    const submitBtn = document.getElementById('submitBtn');
    const policyCheckbox = document.getElementById('policyAgreement');
    
    // Available time slots from// Available time slots from PHP
    const availableTimeSlots = <?= json_encode($availableTimeSlots) ?>;
    
    // Booked slots from PHP
    const bookedSlots = <?= json_encode($bookedSlots) ?>;
    
    // Update available time slots based on selected date
    dateOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.checked) {
                const selectedDate = this.value;
                updateTimeSlots(selectedDate);
            }
        });
    });
    
    function updateTimeSlots(date) {
        let html = '<div class="row">';
        let hasAvailableSlot = false;
        
        for (const [timeValue, timeLabel] of Object.entries(availableTimeSlots)) {
            const isBooked = bookedSlots[date + ' ' + timeValue] === true;
            hasAvailableSlot = hasAvailableSlot || !isBooked;
            
            html += `
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="form-check time-option">
                        <input class="form-check-input time-radio" type="radio" name="time" 
                            id="time-${timeValue}" value="${timeValue}" 
                            ${isBooked ? 'disabled' : ''}>
                        <label class="form-check-label time-label ${isBooked ? 'disabled' : ''}" 
                            for="time-${timeValue}">
                            ${timeLabel}
                            ${isBooked ? '<span class="badge bg-secondary">Booked</span>' : ''}
                        </label>
                    </div>
                </div>
            `;
        }
        
        html += '</div>';
        
        if (!hasAvailableSlot) {
            html = `
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> All time slots for this date are booked. Please select another date.
                </div>
            `;
        }
        
        timeSlots.innerHTML = html;
        
        // Add event listeners to new time slots
        document.querySelectorAll('.time-radio').forEach(radio => {
            radio.addEventListener('change', checkFormValidity);
        });
        
        checkFormValidity();
    }
    
    // Form validation
    function checkFormValidity() {
        const dateSelected = document.querySelector('.date-radio:checked') !== null;
        const timeSelected = document.querySelector('.time-radio:checked') !== null;
        const policyAgreed = policyCheckbox.checked;
        
        submitBtn.disabled = !(dateSelected && timeSelected && policyAgreed);
    }
    
    // Check policy agreement
    policyCheckbox.addEventListener('change', checkFormValidity);
    
    // Initial form validation check
    checkFormValidity();
</script>

<style>
    .date-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .date-option, .time-option {
        margin: 0;
    }
    
    .date-label, .time-label {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .date-label:hover, .time-label:hover {
        background-color: #f8f9fa;
    }
    
    .date-radio:checked + .date-label, 
    .time-radio:checked + .time-label {
        background-color: #cfe2ff;
        border-color: #0d6efd;
    }
    
    .date-label.disabled, .time-label.disabled {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }
    
    .form-check-input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    
    .date-text {
        font-weight: 500;
        margin-bottom: 5px;
    }
</style>

<?php require base_path('views/client/partials/footer.php') ?>