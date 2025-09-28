<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-container">
        <!-- Left Column - Content -->
        <div class="hero-content">
            <div class="hero-badge">Discover Uganda's Beauty with ease</div>
            <h1 class="hero-title">
                Your Favourite<br>Tour Guide
            </h1>
            <p class="hero-description">
                Find the perfect accommodation and explore amazing destinations across Uganda. 
                From the source of the Nile to the mountain gorillas, experience it all.
            </p>
            
            <div class="cta-buttons">
                <a href="<?= View::url('/hotels') ?>" class="btn-primary hero-btn">
                        <i class="fas fa-hotel me-2"></i>Find Hotels
                    </a>
                    <a href="<?= View::url('/destinations') ?>" class="btn-secondary hero-btn">
                        <i class="fas fa-map-marker-alt me-2"></i>Explore Destinations
                    </a>
            </div>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($hotels); ?></div>
                    <div class="stat-label">Accommodation Facilities</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($destinations); ?></div>
                    <div class="stat-label">Destinations</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Carousel -->
        <div class="hero-carousel">
            <div class="carousel-container">
                <div class="carousel-track">
                    <!-- Carousel Item 1 -->
                    <div class="carousel-item active">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/36/Murchison_Falls%2C_Uganda_%2823475021234%29.jpg" 
                                alt="Mountain Landscape" class="carousel-image">
                        <div class="carousel-caption">
                            <div class="carousel-title">Murchison Falls National Pack</div>
                            <div class="carousel-location">Northwestern Uganda</div>
                        </div>
                    </div>
                    
                    <!-- Carousel Item 2 -->
                    <div class="carousel-item">
                        <img src="https://imageio.forbes.com/blogs-images/adriennejordan/files/2019/07/mu4.jpg?fit=bounds&format=jpg&height=600&width=1200" 
                                alt="Beach Paradise" class="carousel-image">
                        <div class="carousel-caption">
                            <div class="carousel-title">Kidepo Valley National Park</div>
                            <div class="carousel-location">Northeastern Uganda</div>
                        </div>
                    </div>
                    
                    <!-- Carousel Item 3 -->
                    <div class="carousel-item">
                        <img src="https://destinationuganda.com/wp-content/uploads/2019/07/murchison-falls.jpg" 
                                alt="Ancient City" class="carousel-image">
                        <div class="carousel-caption">
                            <div class="carousel-title">Source of the Nile</div>
                            <div class="carousel-location">Eastern Uganda</div>
                        </div>
                    </div>
                </div>
                
                <div class="carousel-arrows">
                    <div class="arrow prev">❮</div>
                    <div class="arrow next">❯</div>
                </div>
                
                <div class="carousel-nav">
                    <div class="nav-dot active" data-index="0"></div>
                    <div class="nav-dot" data-index="1"></div>
                    <div class="nav-dot" data-index="2"></div>
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

<script>
    // Wait for DOM to be fully loaded and ensure no conflicts
    document.addEventListener('DOMContentLoaded', function() {
        // Add a small delay to ensure all CSS is loaded
        setTimeout(function() {
            initializeHeroCarousel();
        }, 100);
    });
    
    function initializeHeroCarousel() {
        const track = document.querySelector('.carousel-track');
        const items = document.querySelectorAll('.carousel-item');
        const dots = document.querySelectorAll('.nav-dot');
        const prevBtn = document.querySelector('.arrow.prev');
        const nextBtn = document.querySelector('.arrow.next');
        
        // Check if elements exist
        if (!track || !items.length || !dots.length || !prevBtn || !nextBtn) {
            console.warn('Hero carousel elements not found');
            return;
        }
        
        let currentIndex = 0;
        const totalItems = items.length;
        let autoSlide;
        
        // Function to update carousel position
        function updateCarousel() {
            if (!track) return;
            
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            
            // Update active classes
            items.forEach((item, index) => {
                item.classList.toggle('active', index === currentIndex);
            });
            
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }
        
        // Next slide function
        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        }
        
        // Previous slide function
        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            updateCarousel();
        }
        
        // Start auto-advance carousel
        function startAutoSlide() {
            autoSlide = setInterval(nextSlide, 5000);
        }
        
        // Stop auto-advance carousel
        function stopAutoSlide() {
            if (autoSlide) {
                clearInterval(autoSlide);
            }
        }
        
        // Initialize carousel
        updateCarousel();
        startAutoSlide();
        
        // Pause auto-slide on hover
        const carouselContainer = document.querySelector('.carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoSlide);
            carouselContainer.addEventListener('mouseleave', startAutoSlide);
        }
        
        // Event listeners for arrows
        nextBtn.addEventListener('click', function() {
            stopAutoSlide();
            nextSlide();
            setTimeout(startAutoSlide, 1000); // Resume after 1 second
        });
        
        prevBtn.addEventListener('click', function() {
            stopAutoSlide();
            prevSlide();
            setTimeout(startAutoSlide, 1000); // Resume after 1 second
        });
        
        // Event listeners for dots
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                stopAutoSlide();
                currentIndex = parseInt(this.getAttribute('data-index'));
                updateCarousel();
                setTimeout(startAutoSlide, 1000); // Resume after 1 second
            });
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                stopAutoSlide();
                prevSlide();
                setTimeout(startAutoSlide, 1000);
            } else if (e.key === 'ArrowRight') {
                stopAutoSlide();
                nextSlide();
                setTimeout(startAutoSlide, 1000);
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            updateCarousel();
        });
    }
</script>
