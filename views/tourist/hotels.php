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

<!-- Map Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">
                        <i class="fas fa-map me-2 text-primary"></i>Interactive Map
                    </h3>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleMapView()">
                            <i class="fas fa-expand me-1"></i>Toggle View
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="getCurrentLocation()">
                            <i class="fas fa-map-marker-alt me-1"></i>My Location
                        </button>
                    </div>
                </div>
                <div id="hotelsMap" style="height: 400px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
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

// Leaflet Map Integration
let hotelsMap;
let mapMarkers = [];

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeHotelsMap();
});

function initializeHotelsMap() {
    // Default center (Kampala, Uganda)
    const defaultCenter = [0.3476, 32.5825];
    
    // Initialize map
    hotelsMap = L.map('hotelsMap').setView(defaultCenter, 7);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(hotelsMap);
    
    // Add hotels markers
    addHotelMarkers();
    
    // Add destinations markers
    addDestinationMarkers();
}

function addHotelMarkers() {
    const hotels = <?= json_encode($hotels ?? []) ?>;
    
    hotels.forEach(function(hotel) {
        if (hotel.latitude && hotel.longitude) {
            const marker = L.marker([parseFloat(hotel.latitude), parseFloat(hotel.longitude)])
                .addTo(hotelsMap)
                .bindPopup(`
                    <div class="map-popup">
                        <h6 class="mb-2">${hotel.name}</h6>
                        <p class="mb-1 text-muted">${hotel.location}</p>
                        <p class="mb-2"><strong>UGX ${parseInt(hotel.price_per_night).toLocaleString()}/night</strong></p>
                        <a href="${BASE_URL}/hotel-details?id=${hotel.id}" class="btn btn-primary btn-sm">
                            View Details
                        </a>
                    </div>
                `);
            
            mapMarkers.push(marker);
        }
    });
}

function addDestinationMarkers() {
    const destinations = <?= json_encode($destinations ?? []) ?>;
    
    destinations.forEach(function(destination) {
        if (destination.latitude && destination.longitude) {
            const marker = L.marker([parseFloat(destination.latitude), parseFloat(destination.longitude)], {
                icon: L.divIcon({
                    className: 'destination-marker',
                    html: '<i class="fas fa-map-marker-alt text-success"></i>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 20]
                })
            })
                .addTo(hotelsMap)
                .bindPopup(`
                    <div class="map-popup">
                        <h6 class="mb-2">${destination.name}</h6>
                        <p class="mb-1 text-muted">${destination.location}</p>
                        ${destination.entry_fee > 0 ? `<p class="mb-2"><strong>Entry: UGX ${parseInt(destination.entry_fee).toLocaleString()}</strong></p>` : ''}
                        <a href="${BASE_URL}/destinations" class="btn btn-success btn-sm">
                            View Details
                        </a>
                    </div>
                `);
            
            mapMarkers.push(marker);
        }
    });
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Update map center
            hotelsMap.setView([lat, lng], 12);
            
            // Add user location marker
            L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'user-location-marker',
                    html: '<i class="fas fa-user text-primary"></i>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 20]
                })
            }).addTo(hotelsMap).bindPopup('Your Location');
            
            // Update search form with coordinates
            const form = document.querySelector('form');
            const locationInput = document.getElementById('location');
            locationInput.value = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            
            // Add hidden inputs for coordinates
            let latInput = form.querySelector('input[name="lat"]');
            let lngInput = form.querySelector('input[name="lng"]');
            
            if (!latInput) {
                latInput = document.createElement('input');
                latInput.type = 'hidden';
                latInput.name = 'lat';
                form.appendChild(latInput);
            }
            
            if (!lngInput) {
                lngInput = document.createElement('input');
                lngInput.type = 'hidden';
                lngInput.name = 'lng';
                form.appendChild(lngInput);
            }
            
            latInput.value = lat;
            lngInput.value = lng;
            
        }, function(error) {
            alert('Unable to get your location. Please enter a location manually.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function toggleMapView() {
    const mapContainer = document.getElementById('hotelsMap');
    if (mapContainer.style.height === '400px') {
        mapContainer.style.height = '600px';
        hotelsMap.invalidateSize();
    } else {
        mapContainer.style.height = '400px';
        hotelsMap.invalidateSize();
    }
}

// Fit map to show all markers
function fitMapToMarkers() {
    if (mapMarkers.length > 0) {
        const group = new L.featureGroup(mapMarkers);
        hotelsMap.fitBounds(group.getBounds().pad(0.1));
    }
}

// Call fitMapToMarkers after markers are added
setTimeout(fitMapToMarkers, 1000);
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
