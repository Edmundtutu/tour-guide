<!-- Booking Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= View::url('/') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= View::url('/hotels') ?>">Hotels</a></li>
                        <li class="breadcrumb-item"><a href="<?= View::url('/hotel-details?id=' . $hotel['id']) ?>"><?= htmlspecialchars($hotel['name']) ?></a></li>
                        <li class="breadcrumb-item active">Booking</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Booking Form -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="booking-form">
                    <h2 class="mb-4">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>Complete Your Booking
                    </h2>
                    
                    <!-- Booking Steps -->
                    <div class="booking-step-indicator mb-5">
                        <div class="step active">1</div>
                        <div class="step">2</div>
                        <div class="step">3</div>
                    </div>
                    
                    <form action="<?= View::url('/book') ?>" method="POST" id="booking-form" data-ajax="true">
                        <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                        
                        <!-- Step 1: Dates and Guests -->
                        <div class="booking-step active" id="step-1">
                            <h4 class="mb-4">Select Dates and Guests</h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="check_in" class="form-label">Check-in Date</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="check_in" 
                                           name="check_in" 
                                           required
                                           min="<?= date('Y-m-d') ?>"
                                           value="<?= View::old('check_in') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="check_out" class="form-label">Check-out Date</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="check_out" 
                                           name="check_out" 
                                           required
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                           value="<?= View::old('check_out') ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="guests" class="form-label">Number of Guests</label>
                                    <select class="form-select" id="guests" name="guests" required>
                                        <option value="">Select guests</option>
                                        <option value="1" <?= View::old('guests') == '1' ? 'selected' : '' ?>>1 Guest</option>
                                        <option value="2" <?= View::old('guests') == '2' ? 'selected' : '' ?>>2 Guests</option>
                                        <option value="3" <?= View::old('guests') == '3' ? 'selected' : '' ?>>3 Guests</option>
                                        <option value="4" <?= View::old('guests') == '4' ? 'selected' : '' ?>>4 Guests</option>
                                        <option value="5" <?= View::old('guests') == '5' ? 'selected' : '' ?>>5+ Guests</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="room_id" class="form-label">Room Type</label>
                                    <select class="form-select" id="room_id" name="room_id">
                                        <option value="">Any Available Room</option>
                                        <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room['id'] ?>" 
                                                data-price="<?= $room['price'] ?>"
                                                <?= View::old('room_id') == $room['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($room['room_type']) ?> - 
                                            UGX <?= number_format($room['price']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-primary booking-next">
                                    Next Step <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 2: Guest Information -->
                        <div class="booking-step" id="step-2">
                            <h4 class="mb-4">Guest Information</h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="guest_name" class="form-label">Full Name</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="guest_name" 
                                           name="guest_name" 
                                           required
                                           value="<?= View::old('guest_name') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guest_email" class="form-label">Email Address</label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="guest_email" 
                                           name="guest_email" 
                                           required
                                           value="<?= View::old('guest_email') ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="guest_phone" class="form-label">Phone Number</label>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="guest_phone" 
                                           name="guest_phone" 
                                           required
                                           value="<?= View::old('guest_phone') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="special_requests" class="form-label">Special Requests</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="special_requests" 
                                           name="special_requests"
                                           placeholder="Any special requirements?"
                                           value="<?= View::old('special_requests') ?>">
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary booking-prev">
                                    <i class="fas fa-arrow-left me-1"></i>Previous
                                </button>
                                <button type="button" class="btn btn-primary booking-next">
                                    Next Step <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 3: Review and Confirm -->
                        <div class="booking-step" id="step-3">
                            <h4 class="mb-4">Review Your Booking</h4>
                            
                            <div class="booking-summary">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Booking Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Hotel Details</h6>
                                                <p class="mb-1"><strong><?= htmlspecialchars($hotel['name']) ?></strong></p>
                                                <p class="mb-1 text-muted"><?= htmlspecialchars($hotel['location']) ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Booking Details</h6>
                                                <p class="mb-1">Check-in: <span id="summary-checkin"></span></p>
                                                <p class="mb-1">Check-out: <span id="summary-checkout"></span></p>
                                                <p class="mb-1">Guests: <span id="summary-guests"></span></p>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="price-breakdown">
                                            <div class="d-flex justify-content-between">
                                                <span>Price per night:</span>
                                                <span id="summary-price-per-night">UGX 0</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Number of nights:</span>
                                                <span id="summary-nights">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Subtotal:</span>
                                                <span id="summary-subtotal">UGX 0</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Tax (18%):</span>
                                                <span id="summary-tax">UGX 0</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between fw-bold">
                                                <span>Total:</span>
                                                <span id="summary-total">UGX 0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary booking-prev">
                                    <i class="fas fa-arrow-left me-1"></i>Previous
                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>Confirm Booking
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Hotel Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card sticky-top">
                    <img src="<?= $hotel['image_url'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($hotel['name']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($hotel['name']) ?></h5>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-muted me-1"></i>
                            <?= htmlspecialchars($hotel['location']) ?>
                        </p>
                        <p class="card-text"><?= htmlspecialchars(substr($hotel['description'] ?? '', 0, 150)) ?>...</p>
                        
                        <div class="hotel-rating mb-3">
                            <div class="d-flex align-items-center">
                                <div class="rating me-2">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                                <span class="text-muted">4.5 (127 reviews)</span>
                            </div>
                        </div>
                        
                        <div class="hotel-amenities">
                            <h6>Amenities</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-wifi me-1"></i>WiFi<br>
                                        <i class="fas fa-car me-1"></i>Parking<br>
                                        <i class="fas fa-utensils me-1"></i>Restaurant
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-swimming-pool me-1"></i>Pool<br>
                                        <i class="fas fa-dumbbell me-1"></i>Gym<br>
                                        <i class="fas fa-concierge-bell me-1"></i>Room Service
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 3;
    
    // Step navigation
    $('.booking-next').on('click', function() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                updateProgress();
            }
        }
    });
    
    $('.booking-prev').on('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
            updateProgress();
        }
    });
    
    function showStep(step) {
        $('.booking-step').removeClass('active');
        $(`#step-${step}`).addClass('active');
    }
    
    function updateProgress() {
        $('.step').removeClass('active completed');
        for (let i = 1; i <= currentStep; i++) {
            if (i < currentStep) {
                $(`.step:nth-child(${i})`).addClass('completed');
            } else {
                $(`.step:nth-child(${i})`).addClass('active');
            }
        }
    }
    
    function validateCurrentStep() {
        let isValid = true;
        
        if (currentStep === 1) {
            // Validate dates and guests
            const checkIn = $('#check_in').val();
            const checkOut = $('#check_out').val();
            const guests = $('#guests').val();
            
            if (!checkIn || !checkOut || !guests) {
                showError('Please fill in all required fields');
                isValid = false;
            } else if (new Date(checkOut) <= new Date(checkIn)) {
                showError('Check-out date must be after check-in date');
                isValid = false;
            }
        } else if (currentStep === 2) {
            // Validate guest information
            const name = $('#guest_name').val();
            const email = $('#guest_email').val();
            const phone = $('#guest_phone').val();
            
            if (!name || !email || !phone) {
                showError('Please fill in all required fields');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('Please enter a valid email address');
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    // Update booking summary
    function updateBookingSummary() {
        const checkIn = $('#check_in').val();
        const checkOut = $('#check_out').val();
        const guests = $('#guests').val();
        const roomId = $('#room_id').val();
        const pricePerNight = roomId ? $('#room_id option:selected').data('price') : <?= $hotel['price_per_night'] ?>;
        
        if (checkIn && checkOut) {
            const nights = calculateNights(checkIn, checkOut);
            const subtotal = pricePerNight * nights;
            const tax = subtotal * 0.18;
            const total = subtotal + tax;
            
            $('#summary-checkin').text(formatDate(checkIn));
            $('#summary-checkout').text(formatDate(checkOut));
            $('#summary-guests').text(guests + ' guest' + (guests > 1 ? 's' : ''));
            $('#summary-price-per-night').text('UGX ' + pricePerNight.toLocaleString());
            $('#summary-nights').text(nights);
            $('#summary-subtotal').text('UGX ' + subtotal.toLocaleString());
            $('#summary-tax').text('UGX ' + tax.toLocaleString());
            $('#summary-total').text('UGX ' + total.toLocaleString());
        }
    }
    
    // Update summary when form changes
    $('#check_in, #check_out, #guests, #room_id').on('change', updateBookingSummary);
    
    // Date validation
    $('#check_in').on('change', function() {
        const checkIn = new Date($(this).val());
        const minCheckOut = new Date(checkIn);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        
        $('#check_out').attr('min', minCheckOut.toISOString().split('T')[0]);
        
        if (new Date($('#check_out').val()) <= checkIn) {
            $('#check_out').val('');
        }
    });
    
    // Form submission
    $('#booking-form').on('submit', function(e) {
        e.preventDefault();
        
        if (validateCurrentStep()) {
            showLoading();
            
            const formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        showSuccess('Booking confirmed! Redirecting...');
                        setTimeout(() => {
                            window.location.href = response.redirect || '/my-bookings';
                        }, 2000);
                    } else {
                        showError(response.message || 'Booking failed. Please try again.');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    const response = xhr.responseJSON;
                    if (response && response.errors) {
                        displayFormErrors(response.errors);
                    } else {
                        showError('Booking failed. Please try again.');
                    }
                }
            });
        }
    });
    
    // Utility functions
    function calculateNights(checkIn, checkOut) {
        const start = new Date(checkIn);
        const end = new Date(checkOut);
        const diffTime = Math.abs(end - start);
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script>

<style>
.booking-step {
    display: none;
}

.booking-step.active {
    display: block;
}

.booking-step-indicator {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

.step {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.step.active {
    background: var(--primary-color);
    color: white;
}

.step.completed {
    background: var(--success-color);
    color: white;
}

.booking-summary .card {
    border: 2px solid var(--primary-color);
}

.price-breakdown {
    font-size: 0.9rem;
}

.rating i {
    font-size: 0.8rem;
}
</style>
