<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">Discover Uganda's Beauty</h1>
                    <p class="hero-subtitle">
                        Find the perfect accommodation and explore amazing destinations across Uganda. 
                        From the source of the Nile to the mountain gorillas, experience it all.
                    </p>
                    <div class="hero-buttons">
                        <a href="<?= View::url('/hotels') ?>" class="btn btn-light btn-lg me-3">
                            <i class="fas fa-hotel me-2"></i>Find Hotels
                        </a>
                        <a href="<?= View::url('/destinations') ?>" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-map-marker-alt me-2"></i>Explore Destinations
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                         alt="Uganda Tourism" 
                         class="img-fluid rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="search-card">
                    <h3 class="text-center mb-4">
                        <i class="fas fa-search me-2"></i>Find Your Perfect Stay
                    </h3>
                    <form action="<?= View::url('/hotels') ?>" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="location" class="form-label">Where are you going?</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="location" 
                                   name="location" 
                                   placeholder="Enter destination"
                                   value="<?= htmlspecialchars($location ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="check_in" class="form-label">Check-in</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="check_in" 
                                   name="check_in"
                                   min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="check_out" class="form-label">Check-out</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="check_out" 
                                   name="check_out"
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="guests" class="form-label">Guests</label>
                            <select class="form-select" id="guests" name="guests">
                                <option value="1">1 Guest</option>
                                <option value="2">2 Guests</option>
                                <option value="3">3 Guests</option>
                                <option value="4">4+ Guests</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Hotels Section -->
<?php if (!empty($hotels)): ?>
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fas fa-star me-2 text-warning"></i>Featured Hotels
                </h2>
            </div>
        </div>
        <div class="row">
            <?php foreach (array_slice($hotels, 0, 6) as $hotel): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card hotel-card h-100">
                    <img src="<?= $hotel['image_url'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($hotel['name']) ?>">
                    <div class="price-badge">
                        UGX <?= number_format($hotel['price_per_night']) ?>/night
                    </div>
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.5
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($hotel['name']) ?></h5>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-muted me-1"></i>
                            <?= htmlspecialchars($hotel['location']) ?>
                        </p>
                        <p class="card-text">
                            <?= htmlspecialchars(substr($hotel['description'] ?? '', 0, 100)) ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= View::url('/hotel-details?id=' . $hotel['id']) ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                            <a href="<?= View::url('/book?hotel_id=' . $hotel['id']) ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-calendar-check me-1"></i>Book Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= View::url('/hotels') ?>" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-hotel me-2"></i>View All Hotels
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Popular Destinations Section -->
<?php if (!empty($destinations)): ?>
<section class="destinations-section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>Popular Destinations
                </h2>
            </div>
        </div>
        <div class="row">
            <?php foreach (array_slice($destinations, 0, 6) as $destination): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card destination-card h-100">
                    <img src="<?= $destination['image_url'] ?: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($destination['name']) ?>">
                    <div class="destination-overlay">
                        <h5 class="card-title text-white mb-2"><?= htmlspecialchars($destination['name']) ?></h5>
                        <p class="text-white-50 mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <?= htmlspecialchars($destination['location']) ?>
                        </p>
                        <?php if ($destination['entry_fee'] > 0): ?>
                        <p class="text-white mb-0">
                            <i class="fas fa-ticket-alt me-1"></i>
                            Entry Fee: UGX <?= number_format($destination['entry_fee']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= View::url('/destinations') ?>" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-map me-2"></i>Explore All Destinations
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">Why Choose Uganda Tour Guide?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4 class="feature-title">Secure Booking</h4>
                    <p class="feature-description">
                        Your bookings are protected with our secure payment system and verified accommodations.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4 class="feature-title">24/7 Support</h4>
                    <p class="feature-description">
                        Get help anytime with our dedicated customer support team available around the clock.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="feature-title">Verified Reviews</h4>
                    <p class="feature-description">
                        Read authentic reviews from fellow travelers to make informed decisions.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="mb-3">Ready to Explore Uganda?</h3>
                <p class="mb-0">Join thousands of travelers who have discovered the beauty of Uganda through our platform.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= View::url('/register') ?>" class="btn btn-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Get Started Today
                </a>
            </div>
        </div>
    </div>
</section>
