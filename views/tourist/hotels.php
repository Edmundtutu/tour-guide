<!-- Page Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4 text-center mb-4">
                    <i class="fas fa-hotel me-3 text-primary"></i>Find Your Perfect Hotel
                </h1>
                <?php if ($location): ?>
                <p class="text-center text-muted">
                    Showing results for: <strong><?= htmlspecialchars($location) ?></strong>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filters -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form action="<?= View::url('/hotels') ?>" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" 
                               class="form-control" 
                               id="location" 
                               name="location" 
                               placeholder="Enter destination"
                               value="<?= htmlspecialchars($location ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="price_min" class="form-label">Min Price</label>
                        <input type="number" 
                               class="form-control" 
                               id="price_min" 
                               name="price_min" 
                               placeholder="0"
                               min="0">
                    </div>
                    <div class="col-md-2">
                        <label for="price_max" class="form-label">Max Price</label>
                        <input type="number" 
                               class="form-control" 
                               id="price_max" 
                               name="price_max" 
                               placeholder="1000000"
                               min="0">
                    </div>
                    <div class="col-md-2">
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="rating_desc">Highest Rated</option>
                            <option value="name_asc">Name: A to Z</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Hotels Grid -->
<section class="py-5">
    <div class="container">
        <?php if (empty($hotels)): ?>
        <div class="text-center py-5">
            <i class="fas fa-hotel fa-4x text-muted mb-4"></i>
            <h3>No Hotels Found</h3>
            <p class="text-muted mb-4">
                <?php if ($location): ?>
                We couldn't find any hotels in "<?= htmlspecialchars($location) ?>". 
                Try searching in a different location.
                <?php else: ?>
                No hotels are currently available. Please check back later.
                <?php endif; ?>
            </p>
            <a href="<?= View::url('/hotels') ?>" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Browse All Hotels
            </a>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><?= count($hotels) ?> Hotel<?= count($hotels) !== 1 ? 's' : '' ?> Found</h4>
                    <div class="view-toggle">
                        <button class="btn btn-outline-secondary active" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="btn btn-outline-secondary" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" id="hotels-grid">
            <?php foreach ($hotels as $hotel): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card hotel-card h-100">
                    <div class="position-relative">
                        <img src="<?= $hotel['image_url'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($hotel['name']) ?>">
                        <div class="price-badge">
                            UGX <?= number_format($hotel['price_per_night']) ?>/night
                        </div>
                        <div class="rating">
                            <i class="fas fa-star"></i> 4.5
                        </div>
                        <div class="favorite-btn">
                            <button class="btn btn-sm btn-light rounded-circle" 
                                    data-hotel-id="<?= $hotel['id'] ?>"
                                    title="Add to Favorites">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($hotel['name']) ?></h5>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-muted me-1"></i>
                            <?= htmlspecialchars($hotel['location']) ?>
                        </p>
                        <p class="card-text">
                            <?= htmlspecialchars(substr($hotel['description'] ?? '', 0, 120)) ?>...
                        </p>
                        
                        <!-- Amenities -->
                        <div class="amenities mb-3">
                            <small class="text-muted">
                                <i class="fas fa-wifi me-1"></i>WiFi
                                <i class="fas fa-car me-2 ms-2"></i>Parking
                                <i class="fas fa-utensils me-2"></i>Restaurant
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="hotel-status">
                                <?php if ($hotel['status'] === 'approved'): ?>
                                <span class="badge bg-success">Available</span>
                                <?php else: ?>
                                <span class="badge bg-warning">Pending</span>
                                <?php endif; ?>
                            </div>
                            <div class="hotel-actions">
                                <a href="<?= View::url('/hotel-details?id=' . $hotel['id']) ?>" 
                                   class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <a href="<?= View::url('/book?hotel_id=' . $hotel['id']) ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-calendar-check me-1"></i>Book
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="Hotels pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="mb-3">Can't Find What You're Looking For?</h3>
                <p class="mb-0">Contact our travel experts to help you find the perfect accommodation for your Uganda adventure.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="mailto:info@ugandatourguide.com" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // View toggle functionality
    $('.view-toggle button').on('click', function() {
        $('.view-toggle button').removeClass('active');
        $(this).addClass('active');
        
        const view = $(this).data('view');
        if (view === 'list') {
            $('#hotels-grid').removeClass('row').addClass('list-view');
            $('.hotel-card').addClass('d-flex');
        } else {
            $('#hotels-grid').removeClass('list-view').addClass('row');
            $('.hotel-card').removeClass('d-flex');
        }
    });
    
    // Favorite functionality
    $('.favorite-btn button').on('click', function() {
        const $btn = $(this);
        const hotelId = $btn.data('hotel-id');
        const $icon = $btn.find('i');
        
        if ($icon.hasClass('far')) {
            // Add to favorites
            $icon.removeClass('far').addClass('fas');
            $btn.addClass('text-danger');
            // Here you would make an AJAX call to save the favorite
        } else {
            // Remove from favorites
            $icon.removeClass('fas').addClass('far');
            $btn.removeClass('text-danger');
            // Here you would make an AJAX call to remove the favorite
        }
    });
});
</script>

<style>
.list-view .hotel-card {
    margin-bottom: 1rem;
}

.list-view .hotel-card .card-img-top {
    width: 200px;
    height: 150px;
    object-fit: cover;
}

.favorite-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.amenities i {
    color: var(--primary-color);
}
</style>
