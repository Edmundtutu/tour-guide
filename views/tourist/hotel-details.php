<!-- Hotel Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= View::url('/') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= View::url('/hotels') ?>">Hotels</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($hotel['name']) ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Hotel Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Hotel Images -->
            <div class="col-lg-8">
                <div class="hotel-gallery mb-4">
                    <div class="main-image">
                        <img src="<?= $hotel['image_url'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' ?>" 
                             class="img-fluid rounded-lg shadow" 
                             alt="<?= htmlspecialchars($hotel['name']) ?>"
                             id="main-image">
                    </div>
                    <div class="thumbnail-images mt-3">
                        <div class="row">
                            <div class="col-3">
                                <img src="<?= $hotel['image_url'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' ?>" 
                                     class="img-fluid rounded thumbnail-img active" 
                                     alt="Main view"
                                     data-src="<?= $hotel['image_url'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' ?>">
                            </div>
                            <div class="col-3">
                                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" 
                                     class="img-fluid rounded thumbnail-img" 
                                     alt="Room view"
                                     data-src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80">
                            </div>
                            <div class="col-3">
                                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" 
                                     class="img-fluid rounded thumbnail-img" 
                                     alt="Lobby"
                                     data-src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80">
                            </div>
                            <div class="col-3">
                                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" 
                                     class="img-fluid rounded thumbnail-img" 
                                     alt="Restaurant"
                                     data-src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hotel Information -->
                <div class="hotel-info">
                    <h1 class="display-5 mb-3"><?= htmlspecialchars($hotel['name']) ?></h1>
                    
                    <div class="hotel-meta mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <strong>Location:</strong> <?= htmlspecialchars($hotel['location']) ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <strong>Rating:</strong> 4.5/5 (127 reviews)
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <strong>Check-in:</strong> 2:00 PM
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <strong>Check-out:</strong> 11:00 AM
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hotel-description">
                        <h4>About This Hotel</h4>
                        <p class="lead"><?= htmlspecialchars($hotel['description'] ?? 'No description available.') ?></p>
                    </div>
                    
                    <!-- Amenities -->
                    <div class="amenities mt-4">
                        <h4>Amenities</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-wifi text-primary me-2"></i>Free WiFi
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-car text-primary me-2"></i>Free Parking
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-utensils text-primary me-2"></i>Restaurant
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-swimming-pool text-primary me-2"></i>Swimming Pool
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-dumbbell text-primary me-2"></i>Fitness Center
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-concierge-bell text-primary me-2"></i>Room Service
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-plane text-primary me-2"></i>Airport Shuttle
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-paw text-primary me-2"></i>Pet Friendly
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking Sidebar -->
            <div class="col-lg-4">
                <div class="booking-card sticky-top">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-check me-2"></i>Book Your Stay
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="price-display mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h4 text-primary mb-0">
                                        UGX <?= number_format($hotel['price_per_night']) ?>
                                    </span>
                                    <small class="text-muted">per night</small>
                                </div>
                            </div>
                            
                            <form action="<?= View::url('/book') ?>" method="GET" class="booking-form">
                                <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
                                
                                <div class="mb-3">
                                    <label for="check_in" class="form-label">Check-in Date</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="check_in" 
                                           name="check_in" 
                                           required
                                           min="<?= date('Y-m-d') ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="check_out" class="form-label">Check-out Date</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="check_out" 
                                           name="check_out" 
                                           required
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="guests" class="form-label">Number of Guests</label>
                                    <select class="form-select" id="guests" name="guests" required>
                                        <option value="1">1 Guest</option>
                                        <option value="2">2 Guests</option>
                                        <option value="3">3 Guests</option>
                                        <option value="4">4 Guests</option>
                                        <option value="5">5+ Guests</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="room_type" class="form-label">Room Type</label>
                                    <select class="form-select" id="room_type" name="room_id">
                                        <option value="">Any Available Room</option>
                                        <?php foreach ($rooms as $room): ?>
                                        <option value="<?= $room['id'] ?>">
                                            <?= htmlspecialchars($room['room_type']) ?> - 
                                            UGX <?= number_format($room['price']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="price-summary mb-4">
                                    <div class="d-flex justify-content-between">
                                        <span>Price per night:</span>
                                        <span id="price-per-night">UGX <?= number_format($hotel['price_per_night']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Number of nights:</span>
                                        <span id="nights">0</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span id="total-price">UGX 0</span>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-calendar-check me-2"></i>Book Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Available Rooms -->
<?php if (!empty($rooms)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">Available Rooms</h3>
            </div>
        </div>
        <div class="row">
            <?php foreach ($rooms as $room): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card room-card h-100">
                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($room['room_type']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($room['room_type']) ?></h5>
                        <p class="card-text">
                            <i class="fas fa-users text-muted me-1"></i>
                            Capacity: <?= $room['capacity'] ?> guest<?= $room['capacity'] !== 1 ? 's' : '' ?>
                        </p>
                        <p class="card-text">
                            <i class="fas fa-bed text-muted me-1"></i>
                            <?= $room['room_type'] === 'Single' ? '1 Single Bed' : ($room['room_type'] === 'Double' ? '1 Double Bed' : 'Multiple Beds') ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="room-price">
                                <span class="h5 text-primary">UGX <?= number_format($room['price']) ?></span>
                                <small class="text-muted d-block">per night</small>
                            </div>
                            <div class="room-status">
                                <?php if ($room['availability_status'] === 'available'): ?>
                                <span class="badge bg-success">Available</span>
                                <?php else: ?>
                                <span class="badge bg-warning">Unavailable</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Reviews Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">Guest Reviews</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">John Doe</h6>
                                <div class="rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">"Excellent service and beautiful location. The staff was very helpful and the rooms were clean and comfortable."</p>
                        <small class="text-muted">2 days ago</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Jane Smith</h6>
                                <div class="rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">"Great value for money. The breakfast was delicious and the location is perfect for exploring the city."</p>
                        <small class="text-muted">1 week ago</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Mike Johnson</h6>
                                <div class="rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">"Amazing experience! The hotel exceeded all our expectations. Will definitely come back."</p>
                        <small class="text-muted">2 weeks ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Image gallery functionality
    $('.thumbnail-img').on('click', function() {
        $('.thumbnail-img').removeClass('active');
        $(this).addClass('active');
        $('#main-image').attr('src', $(this).data('src'));
    });
    
    // Price calculation
    function calculatePrice() {
        const checkIn = new Date($('#check_in').val());
        const checkOut = new Date($('#check_out').val());
        const pricePerNight = <?= $hotel['price_per_night'] ?>;
        
        if (checkIn && checkOut && checkOut > checkIn) {
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const total = pricePerNight * nights;
            
            $('#nights').text(nights);
            $('#total-price').text('UGX ' + total.toLocaleString());
        } else {
            $('#nights').text('0');
            $('#total-price').text('UGX 0');
        }
    }
    
    $('#check_in, #check_out').on('change', calculatePrice);
    
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
});
</script>

<style>
.thumbnail-img {
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.thumbnail-img:hover,
.thumbnail-img.active {
    opacity: 1;
}

.booking-card {
    top: 2rem;
}

.room-card .card-img-top {
    height: 200px;
    object-fit: cover;
}

.rating i {
    font-size: 0.9rem;
}
</style>
