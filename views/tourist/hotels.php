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
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="toggleFilterPanel()">
                            <i class="fas fa-filter me-1"></i>Filters
                        </button>
                    </div>
                </div>
                
                <!-- Map Container with Filter Panel -->
                <div class="position-relative">
                    <div id="hotelsMap" style="height: 500px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                    
                    <!-- Map Filter Panel -->
                    <div id="mapFilterPanel" class="map-filter-panel" style="display: none;">
                        <h6><i class="fas fa-filter me-2"></i>Map Filters</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Near Destination</label>
                            <select class="form-select" id="nearDestination" onchange="filterByDestination()">
                                <option value="">All Destinations</option>
                                <?php foreach ($destinations as $destination): ?>
                                    <option value="<?= $destination['id'] ?>" 
                                            data-lat="<?= $destination['latitude'] ?>" 
                                            data-lng="<?= $destination['longitude'] ?>">
                                        <?= htmlspecialchars($destination['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" id="minPrice" placeholder="Min" min="0">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" id="maxPrice" placeholder="Max" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Distance (km)</label>
                            <select class="form-select" id="searchRadius" onchange="updateMapFilters()">
                                <option value="25">25 km</option>
                                <option value="50" selected>50 km</option>
                                <option value="100">100 km</option>
                                <option value="200">200 km</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-sm" onclick="applyMapFilters()">
                                <i class="fas fa-search me-1"></i>Apply Filters
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearMapFilters()">
                                <i class="fas fa-times me-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                    
                    <!-- Map Legend -->
                    <div class="map-legend">
                        <div class="legend-item">
                            <div class="legend-marker hotel-marker"></div>
                            <span>Hotels</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-marker destination-marker"></div>
                            <span>Destinations</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-marker user-location-marker"></div>
                            <span>Your Location</span>
                        </div>
                    </div>
                </div>
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
// Pure vanilla JavaScript - no jQuery dependency
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewToggleButtons = document.querySelectorAll('.view-toggle button');
    viewToggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            viewToggleButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const view = this.getAttribute('data-view');
            const hotelsGrid = document.getElementById('hotels-grid');
            const hotelCards = document.querySelectorAll('.hotel-card');
            
            if (view === 'list') {
                hotelsGrid.classList.remove('row');
                hotelsGrid.classList.add('list-view');
                hotelCards.forEach(card => card.classList.add('d-flex'));
            } else {
                hotelsGrid.classList.remove('list-view');
                hotelsGrid.classList.add('row');
                hotelCards.forEach(card => card.classList.remove('d-flex'));
            }
        });
    });
    
    // Favorite functionality
    const favoriteButtons = document.querySelectorAll('.favorite-btn button');
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const hotelId = this.getAttribute('data-hotel-id');
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('far')) {
                // Add to favorites
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.classList.add('text-danger');
                // Here you would make an AJAX call to save the favorite
            } else {
                // Remove from favorites
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.classList.remove('text-danger');
                // Here you would make an AJAX call to remove the favorite
            }
        });
    });
});

// Enhanced Leaflet Map Integration
let hotelsMap;
let hotelMarkers = [];
let destinationMarkers = [];
let userLocationMarker = null;
let currentFilters = {
    destination: null,
    minPrice: null,
    maxPrice: null,
    radius: 50
};

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeHotelsMap();
});

function initializeHotelsMap() {
    // Check if Leaflet is available
    if (typeof L === 'undefined') {
        console.error('Leaflet library not loaded');
        return;
    }
    
    // Check if map container exists
    const mapContainer = document.getElementById('hotelsMap');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }
    
    console.log('Initializing hotels map...');
    
    // Default center (Kampala, Uganda)
    const defaultCenter = [0.3476, 32.5825];
    
    // Initialize map
    hotelsMap = L.map('hotelsMap').setView(defaultCenter, 7);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(hotelsMap);
    
    // Add all markers
    addAllMarkers();
    
    // Fit map to show all markers
    setTimeout(fitMapToMarkers, 1000);
}

function addAllMarkers() {
    addHotelMarkers();
    addDestinationMarkers();
}

function addHotelMarkers() {
    const hotels = <?= json_encode($hotels ?? []) ?>;
    
    // Clear existing hotel markers
    hotelMarkers.forEach(marker => {
        if (hotelsMap.hasLayer(marker)) {
            hotelsMap.removeLayer(marker);
        }
    });
    hotelMarkers = [];
    
    hotels.forEach(function(hotel) {
        if (hotel.latitude && hotel.longitude) {
            
            const marker = L.marker([parseFloat(hotel.latitude), parseFloat(hotel.longitude)], {
                icon: L.divIcon({
                    className: 'circular-marker hotel-marker',
                    html: '<i class="fas fa-hotel"></i>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                })
            })
            .addTo(hotelsMap)
            .bindTooltip(hotel.name, {
                permanent: false,
                direction: 'top',
                offset: [0, -10]
            })
            .bindPopup(`
                <div class="map-popup">
                    <h6>${hotel.name}</h6>
                    <div class="popup-meta">${hotel.location}</div>
                    <div class="popup-price">UGX ${parseInt(hotel.price_per_night).toLocaleString()}/night</div>
                    <div class="popup-actions">
                        <a href="${BASE_URL}/hotel-details?id=${hotel.id}" class="btn btn-primary btn-sm text-white">
                            <i class="fas fa-eye me-1"></i>View Details
                        </a>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${hotel.latitude},${hotel.longitude}" 
                           target="_blank" class="btn btn-success btn-sm text-white">
                            <i class="fas fa-directions me-1"></i>Get Directions
                        </a>
                    </div>
                </div>
            `);
            
            // Store hotel data in marker
            marker.hotelData = hotel;
            hotelMarkers.push(marker);
        }
    });
}

function addDestinationMarkers() {
    const destinations = <?= json_encode($destinations ?? []) ?>;
    
    // Clear existing destination markers
    destinationMarkers.forEach(marker => {
        if (hotelsMap.hasLayer(marker)) {
            hotelsMap.removeLayer(marker);
        }
    });
    destinationMarkers = [];
    
    destinations.forEach(function(destination) {
        if (destination.latitude && destination.longitude) {
            
            const marker = L.marker([parseFloat(destination.latitude), parseFloat(destination.longitude)], {
                icon: L.divIcon({
                    className: 'circular-marker destination-marker',
                    html: '<i class="fas fa-map-marker-alt"></i>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                })
            })
            .addTo(hotelsMap)
            .bindTooltip(destination.name, {
                permanent: false,
                direction: 'top',
                offset: [0, -10]
            })
            .bindPopup(`
                <div class="map-popup">
                    <h6>${destination.name}</h6>
                    <div class="popup-meta">${destination.location}</div>
                    ${destination.entry_fee > 0 ? `<div class="popup-price">Entry: UGX ${parseInt(destination.entry_fee).toLocaleString()}</div>` : ''}
                    <div class="popup-actions">
                        <a href="${BASE_URL}/destinations" class="btn btn-success btn-sm text-white">
                            <i class="fas fa-eye me-1"></i>View Details
                        </a>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${destination.latitude},${destination.longitude}" 
                           target="_blank" class="btn btn-primary btn-sm text-white">
                            <i class="fas fa-directions me-1"></i>Get Directions
                        </a>
                    </div>
                </div>
            `);
            
            // Store destination data in marker
            marker.destinationData = destination;
            destinationMarkers.push(marker);
        }
    });
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        // Show loading state
        showMapLoading('Getting your location...');
        
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Remove existing user location marker
            if (userLocationMarker) {
                hotelsMap.removeLayer(userLocationMarker);
            }
            
            // Update map center
            hotelsMap.setView([lat, lng], 12);
            
            // Add user location marker
            userLocationMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'circular-marker user-location-marker',
                    html: '<i class="fas fa-user"></i>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                })
            }).addTo(hotelsMap).bindPopup('Your Location');
            
            // Update search form with coordinates
            updateSearchFormWithLocation(lat, lng);
            
            hideMapLoading();
            
        }, function(error) {
            hideMapLoading();
            alert('Unable to get your location. Please enter a location manually.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function updateSearchFormWithLocation(lat, lng) {
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
}

function toggleMapView() {
    const mapContainer = document.getElementById('hotelsMap');
    if (mapContainer.style.height === '500px') {
        mapContainer.style.height = '700px';
        hotelsMap.invalidateSize();
    } else {
        mapContainer.style.height = '500px';
        hotelsMap.invalidateSize();
    }
}

function toggleFilterPanel() {
    const panel = document.getElementById('mapFilterPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function filterByDestination() {
    const destinationSelect = document.getElementById('nearDestination');
    const selectedOption = destinationSelect.options[destinationSelect.selectedIndex];
    
    if (selectedOption.value) {
        const lat = parseFloat(selectedOption.dataset.lat);
        const lng = parseFloat(selectedOption.dataset.lng);
        
        // Center map on selected destination
        hotelsMap.setView([lat, lng], 10);
        
        // Store current filter
        currentFilters.destination = {
            id: selectedOption.value,
            name: selectedOption.text,
            lat: lat,
            lng: lng
        };
        
        // Apply filters
        applyMapFilters();
    } else {
        currentFilters.destination = null;
        applyMapFilters();
    }
}

function updateMapFilters() {
    const radius = document.getElementById('searchRadius').value;
    currentFilters.radius = parseInt(radius);
    
    if (currentFilters.destination) {
        applyMapFilters();
    }
}

function applyMapFilters() {
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    
    currentFilters.minPrice = minPrice ? parseFloat(minPrice) : null;
    currentFilters.maxPrice = maxPrice ? parseFloat(maxPrice) : null;
    
    // Filter hotel markers
    hotelMarkers.forEach(marker => {
        const hotel = marker.hotelData;
        let showMarker = true;
        
        // Price filter
        if (currentFilters.minPrice && hotel.price_per_night < currentFilters.minPrice) {
            showMarker = false;
        }
        if (currentFilters.maxPrice && hotel.price_per_night > currentFilters.maxPrice) {
            showMarker = false;
        }
        
        // Distance filter from selected destination
        if (currentFilters.destination && showMarker) {
            const distance = calculateDistance(
                currentFilters.destination.lat,
                currentFilters.destination.lng,
                hotel.latitude,
                hotel.longitude
            );
            
            if (distance > currentFilters.radius) {
                showMarker = false;
            }
        }
        
        // Show/hide marker
        if (showMarker) {
            if (!hotelsMap.hasLayer(marker)) {
                hotelsMap.addLayer(marker);
            }
        } else {
            if (hotelsMap.hasLayer(marker)) {
                hotelsMap.removeLayer(marker);
            }
        }
    });
    
    // Update results count
    updateResultsCount();
}

function clearMapFilters() {
    // Reset filter inputs
    document.getElementById('nearDestination').value = '';
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    document.getElementById('searchRadius').value = '50';
    
    // Reset filter state
    currentFilters = {
        destination: null,
        minPrice: null,
        maxPrice: null,
        radius: 50
    };
    
    // Show all hotel markers
    hotelMarkers.forEach(marker => {
        if (!hotelsMap.hasLayer(marker)) {
            hotelsMap.addLayer(marker);
        }
    });
    
    // Reset map view
    fitMapToMarkers();
    updateResultsCount();
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the Earth in kilometers
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function updateResultsCount() {
    const visibleMarkers = hotelMarkers.filter(marker => hotelsMap.hasLayer(marker));
    const countElement = document.querySelector('.results-count');
    if (countElement) {
        countElement.textContent = `${visibleMarkers.length} hotels found`;
    }
}

function showMapLoading(message) {
    const mapContainer = document.getElementById('hotelsMap');
    let loadingDiv = mapContainer.querySelector('.map-loading');
    
    if (!loadingDiv) {
        loadingDiv = document.createElement('div');
        loadingDiv.className = 'map-loading';
        mapContainer.appendChild(loadingDiv);
    }
    
    loadingDiv.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="mt-2">${message}</div>
    `;
}

function hideMapLoading() {
    const mapContainer = document.getElementById('hotelsMap');
    const loadingDiv = mapContainer.querySelector('.map-loading');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

// Fit map to show all markers
function fitMapToMarkers() {
    const allMarkers = [...hotelMarkers, ...destinationMarkers];
    if (allMarkers.length > 0) {
        const group = new L.featureGroup(allMarkers);
        hotelsMap.fitBounds(group.getBounds().pad(0.1));
    }
}
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
