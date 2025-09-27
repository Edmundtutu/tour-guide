<?php
$title = 'My Itinerary';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">My Itinerary</h1>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createItineraryModal">
                        <i class="fas fa-plus"></i> Create New Itinerary
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#suggestionsModal">
                        <i class="fas fa-lightbulb"></i> Get Suggestions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Active Itineraries -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-route"></i> Active Itineraries
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($itineraries) && !empty($itineraries)): ?>
                        <div class="row">
                            <?php foreach ($itineraries as $itinerary): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="card-title mb-0"><?= htmlspecialchars($itinerary['title']) ?></h6>
                                                <span class="badge bg-<?= $itinerary['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($itinerary['status']) ?>
                                                </span>
                                            </div>
                                            
                                            <div class="itinerary-dates mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar"></i>
                                                    <?= date('M j', strtotime($itinerary['start_date'])) ?> - 
                                                    <?= date('M j, Y', strtotime($itinerary['end_date'])) ?>
                                                </small>
                                            </div>
                                            
                                            <div class="itinerary-destinations mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <?= count($itinerary['destinations']) ?> destinations
                                                </small>
                                            </div>
                                            
                                            <div class="itinerary-progress mb-3">
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" style="width: <?= $itinerary['progress'] ?>%"></div>
                                                </div>
                                                <small class="text-muted"><?= $itinerary['progress'] ?>% completed</small>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/tourist/itinerary/<?= $itinerary['id'] ?>" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <button class="btn btn-outline-secondary" onclick="editItinerary(<?= $itinerary['id'] ?>)">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </div>
                                                <div class="itinerary-cost">
                                                    <strong class="text-success">UGX <?= number_format($itinerary['estimated_cost']) ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-route fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No itineraries yet</h5>
                            <p class="text-muted">Create your first travel itinerary to start planning your Uganda adventure!</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItineraryModal">
                                <i class="fas fa-plus"></i> Create Itinerary
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check"></i> Recent Bookings
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($recent_bookings) && !empty($recent_bookings)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_bookings as $booking): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($booking['hotel_name']) ?></h6>
                                        <p class="mb-1 small text-muted">
                                            <i class="fas fa-calendar"></i>
                                            <?= date('M j', strtotime($booking['check_in'])) ?> - 
                                            <?= date('M j, Y', strtotime($booking['check_out'])) ?>
                                        </p>
                                        <p class="mb-0 small text-muted">
                                            <i class="fas fa-users"></i> <?= $booking['guests'] ?> guest(s)
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= $booking['status'] === 'approved' ? 'success' : 'warning' ?> mb-2">
                                            <?= ucfirst($booking['status']) ?>
                                        </span>
                                        <div class="text-success">
                                            <strong>UGX <?= number_format($booking['total_price']) ?></strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No recent bookings</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Travel Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h4 class="text-primary mb-1"><?= $stats['total_itineraries'] ?? 0 ?></h4>
                                <small class="text-muted">Itineraries</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h4 class="text-success mb-1"><?= $stats['total_bookings'] ?? 0 ?></h4>
                                <small class="text-muted">Bookings</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-warning mb-1"><?= $stats['destinations_visited'] ?? 0 ?></h4>
                                <small class="text-muted">Destinations</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-info mb-1"><?= $stats['total_spent'] ?? 0 ?></h4>
                                <small class="text-muted">Total Spent</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Destinations -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-star"></i> Popular Destinations
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($popular_destinations) && !empty($popular_destinations)): ?>
                        <?php foreach ($popular_destinations as $destination): ?>
                            <div class="destination-item d-flex align-items-center mb-3">
                                <img src="<?= htmlspecialchars($destination['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($destination['name']) ?>" 
                                     class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?= htmlspecialchars($destination['name']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($destination['location']) ?></small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" onclick="addToItinerary(<?= $destination['id'] ?>)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">No popular destinations available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Travel Tips -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-lightbulb"></i> Travel Tips
                    </h6>
                </div>
                <div class="card-body">
                    <div class="tip-item mb-3">
                        <h6 class="text-primary mb-1">Best Time to Visit</h6>
                        <p class="small text-muted mb-0">Dry seasons (Dec-Feb, Jun-Aug) offer the best wildlife viewing and hiking conditions.</p>
                    </div>
                    <div class="tip-item mb-3">
                        <h6 class="text-success mb-1">Budget Planning</h6>
                        <p class="small text-muted mb-0">Plan for UGX 50,000-100,000 per day for comfortable travel including accommodation and meals.</p>
                    </div>
                    <div class="tip-item">
                        <h6 class="text-warning mb-1">Health & Safety</h6>
                        <p class="small text-muted mb-0">Yellow fever vaccination is required. Malaria prophylaxis is recommended.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Itinerary Modal -->
<div class="modal fade" id="createItineraryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Itinerary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/tourist/itinerary/create" method="POST" id="createItineraryForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Itinerary Title *</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="e.g., Uganda Wildlife Safari" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="budget" class="form-label">Budget (UGX)</label>
                                <input type="number" class="form-control" id="budget" name="budget" 
                                       min="0" step="10000" placeholder="500000">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="travel_style" class="form-label">Travel Style</label>
                        <select class="form-select" id="travel_style" name="travel_style">
                            <option value="budget">Budget</option>
                            <option value="mid-range">Mid-range</option>
                            <option value="luxury">Luxury</option>
                            <option value="adventure">Adventure</option>
                            <option value="cultural">Cultural</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="interests" class="form-label">Interests</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="wildlife" name="interests[]" value="wildlife">
                                    <label class="form-check-label" for="wildlife">Wildlife</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hiking" name="interests[]" value="hiking">
                                    <label class="form-check-label" for="hiking">Hiking</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="culture" name="interests[]" value="culture">
                                    <label class="form-check-label" for="culture">Culture</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="photography" name="interests[]" value="photography">
                                    <label class="form-check-label" for="photography">Photography</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="relaxation" name="interests[]" value="relaxation">
                                    <label class="form-check-label" for="relaxation">Relaxation</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="adventure" name="interests[]" value="adventure">
                                    <label class="form-check-label" for="adventure">Adventure</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Tell us about your travel plans..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Itinerary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Suggestions Modal -->
<div class="modal fade" id="suggestionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Get Travel Suggestions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="suggestion-card card border-0 shadow-sm mb-3">
                            <div class="card-body text-center">
                                <i class="fas fa-mountain fa-2x text-primary mb-3"></i>
                                <h6>Mountain Gorilla Trekking</h6>
                                <p class="small text-muted">Experience the thrill of meeting mountain gorillas in their natural habitat.</p>
                                <button class="btn btn-sm btn-outline-primary" onclick="addSuggestion('gorilla-trekking')">
                                    Add to Itinerary
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="suggestion-card card border-0 shadow-sm mb-3">
                            <div class="card-body text-center">
                                <i class="fas fa-water fa-2x text-info mb-3"></i>
                                <h6>Murchison Falls Safari</h6>
                                <p class="small text-muted">Witness the powerful Murchison Falls and spot the Big Five.</p>
                                <button class="btn btn-sm btn-outline-primary" onclick="addSuggestion('murchison-falls')">
                                    Add to Itinerary
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="suggestion-card card border-0 shadow-sm mb-3">
                            <div class="card-body text-center">
                                <i class="fas fa-tree fa-2x text-success mb-3"></i>
                                <h6>Bwindi Forest Experience</h6>
                                <p class="small text-muted">Explore the ancient Bwindi Impenetrable Forest and its biodiversity.</p>
                                <button class="btn btn-sm btn-outline-primary" onclick="addSuggestion('bwindi-forest')">
                                    Add to Itinerary
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="suggestion-card card border-0 shadow-sm mb-3">
                            <div class="card-body text-center">
                                <i class="fas fa-ship fa-2x text-warning mb-3"></i>
                                <h6>Lake Victoria Cruise</h6>
                                <p class="small text-muted">Relax on a scenic cruise across Africa's largest lake.</p>
                                <button class="btn btn-sm btn-outline-primary" onclick="addSuggestion('lake-victoria')">
                                    Add to Itinerary
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').min = today;
    document.getElementById('end_date').min = today;
    
    // Update end date minimum when start date changes
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').min = this.value;
    });
    
    // Form validation
    document.getElementById('createItineraryForm').addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);
        
        if (endDate <= startDate) {
            e.preventDefault();
            alert('End date must be after start date!');
            return false;
        }
    });
});

function editItinerary(itineraryId) {
    // Redirect to edit page
    window.location.href = `/tourist/itinerary/${itineraryId}/edit`;
}

function addToItinerary(destinationId) {
    // Show modal to select itinerary or create new one
    alert(`Adding destination ${destinationId} to itinerary...`);
}

function addSuggestion(suggestionType) {
    // Add suggestion to current itinerary or create new one
    alert(`Adding ${suggestionType} to your itinerary...`);
}
</script>
