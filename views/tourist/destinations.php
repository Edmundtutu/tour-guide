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
                    </div>
                </div>
                <div id="destinationsMap" style="height: 400px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
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
// Destinations Map Integration
let destinationsMap;
let destinationMarkers = [];

// Initialize destinations map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeDestinationsMap();
});

function initializeDestinationsMap() {
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
    
    // Add nearby hotels
    addNearbyHotelsToMap();
}

function addDestinationMarkersToMap() {
    const destinations = <?= json_encode($destinations ?? []) ?>;
    
    destinations.forEach(function(destination) {
        if (destination.latitude && destination.longitude) {
            const marker = L.marker([parseFloat(destination.latitude), parseFloat(destination.longitude)], {
                icon: L.divIcon({
                    className: 'destination-marker',
                    html: '<i class="fas fa-map-marker-alt text-success"></i>',
                    iconSize: [25, 25],
                    iconAnchor: [12, 25]
                })
            })
                .addTo(destinationsMap)
                .bindPopup(`
                    <div class="map-popup">
                        <h6 class="mb-2">${destination.name}</h6>
                        <p class="mb-1 text-muted">${destination.location}</p>
                        ${destination.entry_fee > 0 ? `<p class="mb-2"><strong>Entry: UGX ${parseInt(destination.entry_fee).toLocaleString()}</strong></p>` : ''}
                        <div class="d-flex gap-2">
                            <a href="${BASE_URL}/destinations" class="btn btn-success btn-sm">
                                View Details
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="addToItinerary(${destination.id})">
                                Add to Itinerary
                            </button>
                        </div>
                    </div>
                `);
            
            destinationMarkers.push(marker);
        }
    });
}

function addNearbyHotelsToMap() {
    const hotels = <?= json_encode($hotels ?? []) ?>;
    
    hotels.forEach(function(hotel) {
        if (hotel.latitude && hotel.longitude) {
            const marker = L.marker([parseFloat(hotel.latitude), parseFloat(hotel.longitude)], {
                icon: L.divIcon({
                    className: 'hotel-marker',
                    html: '<i class="fas fa-hotel text-primary"></i>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 20]
                })
            })
                .addTo(destinationsMap)
                .bindPopup(`
                    <div class="map-popup">
                        <h6 class="mb-2">${hotel.name}</h6>
                        <p class="mb-1 text-muted">${hotel.location}</p>
                        <p class="mb-2"><strong>UGX ${parseInt(hotel.price_per_night).toLocaleString()}/night</strong></p>
                        <a href="${BASE_URL}/hotel-details?id=${hotel.id}" class="btn btn-primary btn-sm">
                            View Hotel
                        </a>
                    </div>
                `);
            
            destinationMarkers.push(marker);
        }
    });
}

function getCurrentLocationForDestinations() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Update map center
            destinationsMap.setView([lat, lng], 10);
            
            // Add user location marker
            L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'user-location-marker',
                    html: '<i class="fas fa-user text-primary"></i>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 20]
                })
            }).addTo(destinationsMap).bindPopup('Your Location');
            
        }, function(error) {
            alert('Unable to get your location. Please search manually.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function toggleDestinationsMapView() {
    const mapContainer = document.getElementById('destinationsMap');
    if (mapContainer.style.height === '400px') {
        mapContainer.style.height = '600px';
        destinationsMap.invalidateSize();
    } else {
        mapContainer.style.height = '400px';
        destinationsMap.invalidateSize();
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
    if (destinationMarkers.length > 0) {
        const group = new L.featureGroup(destinationMarkers);
        destinationsMap.fitBounds(group.getBounds().pad(0.1));
    }
}

// Call fitMapToMarkers after markers are added
setTimeout(fitDestinationsMapToMarkers, 1000);
</script>
