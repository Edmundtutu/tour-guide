<!-- Host Hotels Management -->
<section class="py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 mb-2">
                            <i class="fas fa-hotel me-3 text-primary"></i>My Hotels
                        </h1>
                        <p class="text-muted">Manage your hotel listings and properties</p>
                    </div>
                    <div class="hotel-actions">
                        <a href="<?= View::url('/host/create-hotel') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Hotel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hotels Grid -->
        <?php if (!empty($hotels)): ?>
        <div class="row">
            <?php foreach ($hotels as $hotel): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card hotel-management-card h-100">
                    <div class="position-relative">
                        <img src="<?= $hotel['image_url'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80' ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($hotel['name']) ?>">
                        <div class="hotel-status-badge">
                            <span class="badge hotel-status-<?= $hotel['status'] ?>">
                                <?= ucfirst($hotel['status']) ?>
                            </span>
                        </div>
                        <div class="hotel-actions-overlay">
                            <div class="btn-group">
                                <a href="<?= View::url('/host/edit-hotel?id=' . $hotel['id']) ?>" 
                                   class="btn btn-sm btn-outline-light" 
                                   title="Edit Hotel">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-light" 
                                        onclick="toggleHotelStatus(<?= $hotel['id'] ?>)"
                                        title="Toggle Status">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-light" 
                                        onclick="deleteHotel(<?= $hotel['id'] ?>)"
                                        title="Delete Hotel">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
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
                        
                        <!-- Hotel Stats -->
                        <div class="hotel-stats mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="stat-item">
                                        <div class="stat-number"><?= $hotel['total_rooms'] ?? 0 ?></div>
                                        <div class="stat-label">Rooms</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item">
                                        <div class="stat-number"><?= $hotel['total_bookings'] ?? 0 ?></div>
                                        <div class="stat-label">Bookings</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-item">
                                        <div class="stat-number"><?= $hotel['rating'] ?? '4.5' ?></div>
                                        <div class="stat-label">Rating</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hotel Actions -->
                        <div class="d-grid gap-2">
                            <a href="<?= View::url('/host/rooms?hotel_id=' . $hotel['id']) ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-bed me-1"></i>Manage Rooms
                            </a>
                            <a href="<?= View::url('/host/bookings?hotel_id=' . $hotel['id']) ?>" 
                               class="btn btn-outline-success btn-sm">
                                <i class="fas fa-calendar-check me-1"></i>View Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="fas fa-hotel fa-4x text-muted mb-4"></i>
            <h3>No Hotels Yet</h3>
            <p class="text-muted mb-4">Start by adding your first hotel to begin accepting bookings from tourists.</p>
            <a href="<?= View::url('/host/create-hotel') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Add Your First Hotel
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Hotel Management Modal -->
<div class="modal fade" id="hotelModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hotel Management</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="hotel-form">
                    <input type="hidden" id="hotel_id" name="hotel_id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hotel_name" class="form-label">Hotel Name</label>
                            <input type="text" class="form-control" id="hotel_name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hotel_location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="hotel_location" name="location" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hotel_description" class="form-label">Description</label>
                        <textarea class="form-control" id="hotel_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hotel_price" class="form-label">Price per Night (UGX)</label>
                            <input type="number" class="form-control" id="hotel_price" name="price_per_night" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hotel_status" class="form-label">Status</label>
                            <select class="form-select" id="hotel_status" name="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveHotel()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize hotel management
    initializeHotelManagement();
});

function initializeHotelManagement() {
    // Add hover effects to hotel cards
    $('.hotel-management-card').hover(
        function() {
            $(this).find('.hotel-actions-overlay').fadeIn();
        },
        function() {
            $(this).find('.hotel-actions-overlay').fadeOut();
        }
    );
}

function toggleHotelStatus(hotelId) {
    if (confirm('Are you sure you want to change this hotel\'s status?')) {
        $.ajax({
            url: '/api/host/toggle-hotel-status',
            method: 'POST',
            data: { hotel_id: hotelId },
            success: function(response) {
                if (response.success) {
                    showSuccess('Hotel status updated successfully');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to update hotel status');
                }
            },
            error: function() {
                showError('Failed to update hotel status. Please try again.');
            }
        });
    }
}

function deleteHotel(hotelId) {
    if (confirm('Are you sure you want to delete this hotel? This action cannot be undone.')) {
        $.ajax({
            url: '/api/host/delete-hotel',
            method: 'POST',
            data: { hotel_id: hotelId },
            success: function(response) {
                if (response.success) {
                    showSuccess('Hotel deleted successfully');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to delete hotel');
                }
            },
            error: function() {
                showError('Failed to delete hotel. Please try again.');
            }
        });
    }
}

function editHotel(hotelId) {
    // Load hotel data and show modal
    $.ajax({
        url: '/api/host/get-hotel',
        method: 'GET',
        data: { hotel_id: hotelId },
        success: function(response) {
            if (response.success) {
                const hotel = response.hotel;
                $('#hotel_id').val(hotel.id);
                $('#hotel_name').val(hotel.name);
                $('#hotel_location').val(hotel.location);
                $('#hotel_description').val(hotel.description);
                $('#hotel_price').val(hotel.price_per_night);
                $('#hotel_status').val(hotel.status);
                
                $('#hotelModal').modal('show');
            } else {
                showError(response.message || 'Failed to load hotel data');
            }
        },
        error: function() {
            showError('Failed to load hotel data. Please try again.');
        }
    });
}

function saveHotel() {
    const formData = new FormData(document.getElementById('hotel-form'));
    
    $.ajax({
        url: '/api/host/save-hotel',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showSuccess('Hotel saved successfully');
                $('#hotelModal').modal('hide');
                location.reload();
            } else {
                showError(response.message || 'Failed to save hotel');
            }
        },
        error: function() {
            showError('Failed to save hotel. Please try again.');
        }
    });
}
</script>

<style>
.hotel-management-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.hotel-management-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.hotel-management-card .card-img-top {
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.hotel-management-card:hover .card-img-top {
    transform: scale(1.05);
}

.hotel-status-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
}

.hotel-actions-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    align-items: center;
    justify-content: center;
}

.hotel-status-pending {
    background-color: var(--warning-color);
    color: var(--dark-color);
}

.hotel-status-approved {
    background-color: var(--success-color);
    color: white;
}

.hotel-status-blocked {
    background-color: var(--danger-color);
    color: white;
}

.hotel-stats {
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 0;
}

.stat-item {
    text-align: center;
}

.stat-item .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
}

.stat-item .stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .hotel-actions {
        margin-top: 1rem;
    }
    
    .hotel-management-card .card-img-top {
        height: 150px;
    }
}
</style>
