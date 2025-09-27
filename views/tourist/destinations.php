<!-- Page Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4 text-center mb-4">
                    <i class="fas fa-map-marker-alt me-3 text-primary"></i>Discover Uganda
                </h1>
                <p class="text-center text-muted">Explore amazing destinations and plan your perfect Uganda adventure</p>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form action="<?= View::url('/destinations') ?>" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search Destinations</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Enter destination name or location"
                               value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            <option value="national-park">National Parks</option>
                            <option value="wildlife">Wildlife</option>
                            <option value="cultural">Cultural Sites</option>
                            <option value="adventure">Adventure</option>
                            <option value="beaches">Beaches</option>
                        </select>
                    </div>
                    <div class="col-md-3">
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
                        <i class="fas fa-map me-2 text-primary"></i>Uganda Destinations Map
                    </h3>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleDestinationsMapView()">
                            <i class="fas fa-expand me-1"></i>Toggle View
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="getCurrentLocationForDestinations()">
                            <i class="fas fa-map-marker-alt me-1"></i>My Location
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="toggleDestinationsFilterPanel()">
                            <i class="fas fa-filter me-1"></i>Filters
                        </button>
                    </div>
                </div>
                
                <!-- Map Container with Filter Panel -->
                <div class="position-relative">
                    <div id="destinationsMap" style="height: 500px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                    
                    <!-- Map Filter Panel -->
                    <div id="destinationsFilterPanel" class="map-filter-panel" style="display: none;">
                        <h6><i class="fas fa-filter me-2"></i>Destination Filters</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Entry Fee Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" id="minEntryFee" placeholder="Min" min="0">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" id="maxEntryFee" placeholder="Max" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Show Nearby Hotels</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showNearbyHotels" onchange="toggleNearbyHotels()">
                                <label class="form-check-label" for="showNearbyHotels">
                                    Display hotels near destinations
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Distance (km)</label>
                            <select class="form-select" id="destinationsRadius" onchange="updateDestinationsFilters()">
                                <option value="25">25 km</option>
                                <option value="50" selected>50 km</option>
                                <option value="100">100 km</option>
                                <option value="200">200 km</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-sm" onclick="applyDestinationsFilters()">
                                <i class="fas fa-search me-1"></i>Apply Filters
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearDestinationsFilters()">
                                <i class="fas fa-times me-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                    
                    <!-- Map Legend -->
                    <div class="map-legend">
                        <div class="legend-item">
                            <div class="legend-marker destination-marker"></div>
                            <span>Destinations</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-marker hotel-marker"></div>
                            <span>Nearby Hotels</span>
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

<!-- Destinations Grid -->
<section class="py-5">
    <div class="container">
        <?php if (empty($destinations)): ?>
        <div class="text-center py-5">
            <i class="fas fa-map-marked-alt fa-4x text-muted mb-4"></i>
            <h3>No Destinations Found</h3>
            <p class="text-muted mb-4">
                <?php if ($search): ?>
                We couldn't find any destinations matching "<?= htmlspecialchars($search) ?>". 
                Try searching with different keywords.
                <?php else: ?>
                No destinations are currently available. Please check back later.
                <?php endif; ?>
            </p>
            <a href="<?= View::url('/destinations') ?>" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Browse All Destinations
            </a>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><?= count($destinations) ?> Destination<?= count($destinations) !== 1 ? 's' : '' ?> Found</h4>
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
        
        <div class="row" id="destinations-grid">
            <?php foreach ($destinations as $destination): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card destination-card h-100">
                    <div class="position-relative">
                        <img src="<?= $destination['image_url'] ?: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($destination['name']) ?>">
                        <div class="destination-overlay">
                            <div class="destination-badge">
                                <?php if ($destination['entry_fee'] > 0): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-ticket-alt me-1"></i>
                                    UGX <?= number_format($destination['entry_fee']) ?>
                                </span>
                                <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-gift me-1"></i>Free Entry
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($destination['name']) ?></h5>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-muted me-1"></i>
                            <?= htmlspecialchars($destination['location']) ?>
                        </p>
                        <p class="card-text">
                            <?= htmlspecialchars(substr($destination['description'] ?? '', 0, 120)) ?>...
                        </p>
                        
                        <!-- Destination Features -->
                        <div class="destination-features mb-3">
                            <small class="text-muted">
                                <i class="fas fa-camera me-1"></i>Photography
                                <i class="fas fa-hiking me-2 ms-2"></i>Hiking
                                <i class="fas fa-binoculars me-2"></i>Wildlife Viewing
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="destination-rating">
                                <div class="rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                                <small class="text-muted">4.8 (89 reviews)</small>
                            </div>
                            <div class="destination-actions">
                                <button class="btn btn-outline-primary btn-sm me-2" 
                                        onclick="viewDestination(<?= $destination['id'] ?>)">
                                    <i class="fas fa-eye me-1"></i>View
                                </button>
                                <button class="btn btn-primary btn-sm" 
                                        onclick="planVisit(<?= $destination['id'] ?>)">
                                    <i class="fas fa-calendar-plus me-1"></i>Plan Visit
                                </button>
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
                <nav aria-label="Destinations pagination">
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

<!-- Featured Destinations -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-5">Must-Visit Destinations</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card feature-destination h-100">
                    <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         class="card-img-top" 
                         alt="Bwindi Impenetrable National Park">
                    <div class="card-body">
                        <h5 class="card-title">Bwindi Impenetrable National Park</h5>
                        <p class="card-text">Home to half of the world's mountain gorillas. Experience the thrill of gorilla trekking in this UNESCO World Heritage Site.</p>
                        <div class="destination-meta">
                            <span class="badge bg-primary">Wildlife</span>
                            <span class="badge bg-success">UNESCO Site</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-destination h-100">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         class="card-img-top" 
                         alt="Murchison Falls National Park">
                    <div class="card-body">
                        <h5 class="card-title">Murchison Falls National Park</h5>
                        <p class="card-text">Witness the powerful Murchison Falls where the Nile River forces its way through a narrow gorge. Perfect for game drives and boat safaris.</p>
                        <div class="destination-meta">
                            <span class="badge bg-primary">Wildlife</span>
                            <span class="badge bg-info">Waterfalls</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-destination h-100">
                    <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         class="card-img-top" 
                         alt="Queen Elizabeth National Park">
                    <div class="card-body">
                        <h5 class="card-title">Queen Elizabeth National Park</h5>
                        <p class="card-text">Diverse wildlife including tree-climbing lions, elephants, and over 600 bird species. A paradise for nature lovers.</p>
                        <div class="destination-meta">
                            <span class="badge bg-primary">Wildlife</span>
                            <span class="badge bg-warning">Bird Watching</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Travel Tips -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-5">Travel Tips for Uganda</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <i class="fas fa-passport fa-3x text-primary mb-3"></i>
                    <h5>Visa Requirements</h5>
                    <p class="text-muted">Most visitors need a visa. Apply online or get one on arrival at Entebbe Airport.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <i class="fas fa-sun fa-3x text-primary mb-3"></i>
                    <h5>Best Time to Visit</h5>
                    <p class="text-muted">June to August and December to February offer the best weather for wildlife viewing.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h5>Health & Safety</h5>
                    <p class="text-muted">Yellow fever vaccination is required. Malaria prophylaxis is recommended.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="text-center">
                    <i class="fas fa-money-bill-wave fa-3x text-primary mb-3"></i>
                    <h5>Currency</h5>
                    <p class="text-muted">Ugandan Shilling (UGX) is the local currency. US Dollars are widely accepted.</p>
                </div>
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
            $('#destinations-grid').removeClass('row').addClass('list-view');
            $('.destination-card').addClass('d-flex');
        } else {
            $('#destinations-grid').removeClass('list-view').addClass('row');
            $('.destination-card').removeClass('d-flex');
        }
    });
});

// Destination actions
function viewDestination(destinationId) {
    // Open destination details modal or redirect to details page
    window.location.href = `/destination-details?id=${destinationId}`;
}

function planVisit(destinationId) {
    // Open trip planning modal or redirect to planning page
    window.location.href = `/plan-trip?destination_id=${destinationId}`;
}
</script>

<style>
.destination-card {
    transition: all 0.3s ease;
}

.destination-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.destination-card .card-img-top {
    height: 200px;
    object-fit: cover;
}

.destination-overlay {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.destination-badge .badge {
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
}

.destination-features i {
    color: var(--primary-color);
}

.rating i {
    font-size: 0.8rem;
}

.feature-destination .card-img-top {
    height: 180px;
    object-fit: cover;
}

.destination-meta .badge {
    font-size: 0.75rem;
    margin-right: 0.25rem;
}

.list-view .destination-card {
    margin-bottom: 1rem;
}

.list-view .destination-card .card-img-top {
    width: 200px;
    height: 150px;
    object-fit: cover;
}

@media (max-width: 768px) {
    .destination-card .card-img-top {
        height: 150px;
    }
    
    .destination-actions {
        margin-top: 1rem;
    }
}
</style>

<script>
// Enhanced Destinations Map Integration
let destinationsMap;
let destinationMarkers = [];
let hotelMarkers = [];
let userLocationMarker = null;
let showHotels = false;
let currentFilters = {
    minEntryFee: null,
    maxEntryFee: null,
    radius: 50
};

// Initialize destinations map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeDestinationsMap();
});

function initializeDestinationsMap() {
    // Check if Leaflet is available
    if (typeof L === 'undefined') {
        console.error('Leaflet library not loaded');
        return;
    }
    
    // Check if map container exists
    const mapContainer = document.getElementById('destinationsMap');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }
    
    console.log('Initializing destinations map...');
    
    // Default center (Kampala, Uganda)
    const defaultCenter = [0.3476, 32.5825];
    
    // Initialize map
    destinationsMap = L.map('destinationsMap').setView(defaultCenter, 7);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(destinationsMap);
    
    // Add destination markers
    addDestinationMarkersToMap();
    
    // Fit map to show all markers
    setTimeout(fitDestinationsMapToMarkers, 1000);
}

function addDestinationMarkersToMap() {
    const destinations = <?= json_encode($destinations ?? []) ?>;
    
    // Clear existing destination markers
    destinationMarkers.forEach(marker => {
        if (destinationsMap.hasLayer(marker)) {
            destinationsMap.removeLayer(marker);
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
            .addTo(destinationsMap)
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
                        <button class="btn btn-info btn-sm text-white" onclick="addToItinerary(${destination.id})">
                            <i class="fas fa-plus me-1"></i>Add to Itinerary
                        </button>
                    </div>
                </div>
            `);
            
            // Store destination data in marker
            marker.destinationData = destination;
            destinationMarkers.push(marker);
        }
    });
}

function addNearbyHotelsToMap() {
    const hotels = <?= json_encode($hotels ?? []) ?>;
    
    // Clear existing hotel markers
    hotelMarkers.forEach(marker => {
        if (destinationsMap.hasLayer(marker)) {
            destinationsMap.removeLayer(marker);
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
            .addTo(destinationsMap)
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
                            <i class="fas fa-eye me-1"></i>View Hotel
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

function getCurrentLocationForDestinations() {
    if (navigator.geolocation) {
        // Show loading state
        showDestinationsMapLoading('Getting your location...');
        
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Remove existing user location marker
            if (userLocationMarker) {
                destinationsMap.removeLayer(userLocationMarker);
            }
            
            // Update map center
            destinationsMap.setView([lat, lng], 10);
            
            // Add user location marker
            userLocationMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'circular-marker user-location-marker',
                    html: '<i class="fas fa-user"></i>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                })
            }).addTo(destinationsMap).bindPopup('Your Location');
            
            hideDestinationsMapLoading();
            
        }, function(error) {
            hideDestinationsMapLoading();
            alert('Unable to get your location. Please search manually.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function toggleDestinationsMapView() {
    const mapContainer = document.getElementById('destinationsMap');
    if (mapContainer.style.height === '500px') {
        mapContainer.style.height = '700px';
        destinationsMap.invalidateSize();
    } else {
        mapContainer.style.height = '500px';
        destinationsMap.invalidateSize();
    }
}

function toggleDestinationsFilterPanel() {
    const panel = document.getElementById('destinationsFilterPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function toggleNearbyHotels() {
    const checkbox = document.getElementById('showNearbyHotels');
    showHotels = checkbox.checked;
    
    if (showHotels) {
        addNearbyHotelsToMap();
    } else {
        // Remove hotel markers
        hotelMarkers.forEach(marker => destinationsMap.removeLayer(marker));
    }
}

function updateDestinationsFilters() {
    const radius = document.getElementById('destinationsRadius').value;
    currentFilters.radius = parseInt(radius);
    
    applyDestinationsFilters();
}

function applyDestinationsFilters() {
    const minEntryFee = document.getElementById('minEntryFee').value;
    const maxEntryFee = document.getElementById('maxEntryFee').value;
    
    currentFilters.minEntryFee = minEntryFee ? parseFloat(minEntryFee) : null;
    currentFilters.maxEntryFee = maxEntryFee ? parseFloat(maxEntryFee) : null;
    
    // Filter destination markers
    destinationMarkers.forEach(marker => {
        const destination = marker.destinationData;
        let showMarker = true;
        
        // Entry fee filter
        if (currentFilters.minEntryFee && destination.entry_fee < currentFilters.minEntryFee) {
            showMarker = false;
        }
        if (currentFilters.maxEntryFee && destination.entry_fee > currentFilters.maxEntryFee) {
            showMarker = false;
        }
        
        // Show/hide marker
        if (showMarker) {
            if (!destinationsMap.hasLayer(marker)) {
                destinationsMap.addLayer(marker);
            }
        } else {
            if (destinationsMap.hasLayer(marker)) {
                destinationsMap.removeLayer(marker);
            }
        }
    });
    
    // Update results count
    updateDestinationsResultsCount();
}

function clearDestinationsFilters() {
    // Reset filter inputs
    document.getElementById('minEntryFee').value = '';
    document.getElementById('maxEntryFee').value = '';
    document.getElementById('destinationsRadius').value = '50';
    document.getElementById('showNearbyHotels').checked = false;
    
    // Reset filter state
    currentFilters = {
        minEntryFee: null,
        maxEntryFee: null,
        radius: 50
    };
    showHotels = false;
    
    // Show all destination markers
    destinationMarkers.forEach(marker => {
        if (!destinationsMap.hasLayer(marker)) {
            destinationsMap.addLayer(marker);
        }
    });
    
    // Remove hotel markers
    hotelMarkers.forEach(marker => destinationsMap.removeLayer(marker));
    
    // Reset map view
    fitDestinationsMapToMarkers();
    updateDestinationsResultsCount();
}

function updateDestinationsResultsCount() {
    const visibleMarkers = destinationMarkers.filter(marker => destinationsMap.hasLayer(marker));
    const countElement = document.querySelector('.destinations-results-count');
    if (countElement) {
        countElement.textContent = `${visibleMarkers.length} destinations found`;
    }
}

function showDestinationsMapLoading(message) {
    const mapContainer = document.getElementById('destinationsMap');
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

function hideDestinationsMapLoading() {
    const mapContainer = document.getElementById('destinationsMap');
    const loadingDiv = mapContainer.querySelector('.map-loading');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

function addToItinerary(destinationId) {
    if (!confirm('Add this destination to your itinerary?')) {
        return;
    }
    
    // Create form data
    const formData = new FormData();
    formData.append('destination_id', destinationId);
    formData.append('csrf_token', '<?= View::csrfToken() ?>');
    formData.append('ajax', '1');
    
    // Make AJAX request
    fetch('<?= View::url('/itinerary/add-destination') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        showError('An error occurred while adding to itinerary.');
    });
}

function showSuccess(message) {
    // Create success alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

function showError(message) {
    // Create error alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

// Fit map to show all markers
function fitDestinationsMapToMarkers() {
    const allMarkers = [...destinationMarkers, ...hotelMarkers];
    if (allMarkers.length > 0) {
        const group = new L.featureGroup(allMarkers);
        destinationsMap.fitBounds(group.getBounds().pad(0.1));
    }
}
</script>
