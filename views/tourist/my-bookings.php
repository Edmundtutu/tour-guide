<!-- Page Header -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4 text-center mb-4">
                    <i class="fas fa-calendar-check me-3 text-primary"></i>My Bookings
                </h1>
                <p class="text-center text-muted">Manage your hotel reservations and bookings</p>
            </div>
        </div>
    </div>
</section>

<!-- Bookings List -->
<section class="py-5">
    <div class="container">
        <?php if (empty($bookings)): ?>
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
            <h3>No Bookings Found</h3>
            <p class="text-muted mb-4">You haven't made any bookings yet. Start exploring our amazing hotels!</p>
            <a href="<?= View::url('/hotels') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-hotel me-2"></i>Browse Hotels
            </a>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4><?= count($bookings) ?> Booking<?= count($bookings) !== 1 ? 's' : '' ?></h4>
                    <div class="booking-filters">
                        <select class="form-select" id="status-filter">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" id="bookings-list">
            <?php foreach ($bookings as $booking): ?>
            <div class="col-12 mb-4 booking-item" data-status="<?= $booking['status'] ?>">
                <div class="card booking-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                <img src="<?= $booking['hotel_image'] ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80' ?>" 
                     class="img-fluid rounded booking-image" 
                     alt="<?= htmlspecialchars($booking['hotel_name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <h5 class="booking-title"><?= htmlspecialchars($booking['hotel_name']) ?></h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= htmlspecialchars($booking['hotel_location']) ?>
                                </p>
                                
                                <div class="booking-details">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="mb-1">
                                                <i class="fas fa-calendar-check me-2 text-primary"></i>
                                                <strong>Check-in:</strong> <?= date('M j, Y', strtotime($booking['check_in'])) ?>
                                            </p>
                                            <p class="mb-1">
                                                <i class="fas fa-calendar-times me-2 text-primary"></i>
                                                <strong>Check-out:</strong> <?= date('M j, Y', strtotime($booking['check_out'])) ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1">
                                                <i class="fas fa-users me-2 text-primary"></i>
                                                <strong>Guests:</strong> <?= $booking['guests'] ?>
                                            </p>
                                            <p class="mb-1">
                                                <i class="fas fa-bed me-2 text-primary"></i>
                                                <strong>Room:</strong> <?= htmlspecialchars($booking['room_type'] ?? 'Any Available') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="booking-meta mt-3">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <span class="badge booking-status-<?= $booking['status'] ?>">
                                                <?= ucfirst($booking['status']) ?>
                                            </span>
                                            <span class="badge payment-status-<?= $booking['payment_status'] ?> ms-2">
                                                <?= ucfirst($booking['payment_status']) ?>
                                            </span>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <small class="text-muted">
                                                Booked on <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="booking-price text-end">
                                    <div class="price-amount">
                                        <span class="h4 text-primary">UGX <?= number_format($booking['total_price']) ?></span>
                                    </div>
                                    <small class="text-muted">Total Amount</small>
                                    
                                    <div class="booking-actions mt-3">
                                        <a href="<?= View::url('/hotel-details?id=' . $booking['hotel_id']) ?>" 
                                           class="btn btn-outline-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-eye me-1"></i>View Hotel
                                        </a>
                                        
                                        <?php if ($booking['status'] === 'pending'): ?>
                                        <button class="btn btn-warning btn-sm w-100 mb-2" 
                                                onclick="cancelBooking(<?= $booking['id'] ?>)">
                                            <i class="fas fa-times me-1"></i>Cancel
                                        </button>
                                        <?php elseif ($booking['status'] === 'approved'): ?>
                                        <button class="btn btn-success btn-sm w-100 mb-2" 
                                                onclick="downloadReceipt(<?= $booking['id'] ?>)">
                                            <i class="fas fa-download me-1"></i>Receipt
                                        </button>
                                        <?php endif; ?>
                                        
                                        <button class="btn btn-outline-secondary btn-sm w-100" 
                                                onclick="contactHost(<?= $booking['hotel_id'] ?>)">
                                            <i class="fas fa-envelope me-1"></i>Contact Host
                                        </button>
                                    </div>
                                </div>
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
                <nav aria-label="Bookings pagination">
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

<!-- Quick Actions -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">Quick Actions</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-hotel fa-3x text-primary mb-3"></i>
                        <h5>Find Hotels</h5>
                        <p class="text-muted">Discover amazing accommodations across Uganda</p>
                        <a href="<?= View::url('/hotels') ?>" class="btn btn-primary">
                            Browse Hotels
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                        <h5>Explore Destinations</h5>
                        <p class="text-muted">Plan your trip with our destination guides</p>
                        <a href="<?= View::url('/destinations') ?>" class="btn btn-primary">
                            View Destinations
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5>Get Help</h5>
                        <p class="text-muted">Need assistance? Our support team is here to help</p>
                        <a href="mailto:support@ugandatourguide.com" class="btn btn-primary">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Status filter
    $('#status-filter').on('change', function() {
        const status = $(this).val();
        
        if (status === '') {
            $('.booking-item').show();
        } else {
            $('.booking-item').hide();
            $(`.booking-item[data-status="${status}"]`).show();
        }
    });
});

// Booking actions
function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        showLoading();
        
        $.ajax({
            url: '/api/cancel-booking',
            method: 'POST',
            data: { booking_id: bookingId },
            success: function(response) {
                hideLoading();
                if (response.success) {
                    showSuccess('Booking cancelled successfully');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to cancel booking');
                }
            },
            error: function() {
                hideLoading();
                showError('Failed to cancel booking. Please try again.');
            }
        });
    }
}

function downloadReceipt(bookingId) {
    window.open(`/api/download-receipt?booking_id=${bookingId}`, '_blank');
}

function contactHost(hotelId) {
    // Open contact modal or redirect to contact page
    window.location.href = `/contact-host?hotel_id=${hotelId}`;
}
</script>

<style>
.booking-card {
    transition: all 0.3s ease;
}

.booking-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.booking-image {
    height: 150px;
    object-fit: cover;
    width: 100%;
}

.booking-status-pending {
    background-color: var(--warning-color);
    color: var(--dark-color);
}

.booking-status-approved {
    background-color: var(--success-color);
    color: white;
}

.booking-status-rejected {
    background-color: var(--danger-color);
    color: white;
}

.booking-status-cancelled {
    background-color: var(--dark-color);
    color: white;
}

.payment-status-paid {
    background-color: var(--success-color);
    color: white;
}

.payment-status-unpaid {
    background-color: var(--warning-color);
    color: var(--dark-color);
}

.booking-actions .btn {
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .booking-image {
        height: 200px;
        margin-bottom: 1rem;
    }
    
    .booking-actions {
        margin-top: 1rem;
    }
}
</style>
