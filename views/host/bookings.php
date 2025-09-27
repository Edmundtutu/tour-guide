<!-- Host Bookings Management -->
<section class="py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 mb-2">
                            <i class="fas fa-calendar-check me-3 text-primary"></i>Hotel Bookings
                        </h1>
                        <p class="text-muted">Manage bookings for your hotels</p>
                    </div>
                    <div class="booking-actions">
                        <button class="btn btn-outline-primary" onclick="exportBookings()">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3" id="booking-filters">
                            <div class="col-md-3">
                                <label for="status_filter" class="form-label">Status</label>
                                <select class="form-select" id="status_filter" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="hotel_filter" class="form-label">Hotel</label>
                                <select class="form-select" id="hotel_filter" name="hotel_id">
                                    <option value="">All Hotels</option>
                                    <?php foreach ($hotels as $hotel): ?>
                                    <option value="<?= $hotel['id'] ?>"><?= htmlspecialchars($hotel['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bookings Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Bookings List
                        </h5>
                        <div class="booking-stats">
                            <span class="badge bg-primary me-2">Total: <?= count($bookings) ?></span>
                            <span class="badge bg-warning me-2">Pending: <?= $stats['pending'] ?? 0 ?></span>
                            <span class="badge bg-success">Approved: <?= $stats['approved'] ?? 0 ?></span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($bookings)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Guest</th>
                                        <th>Hotel</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Guests</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                    <tr class="booking-row" data-status="<?= $booking['status'] ?>">
                                        <td>
                                            <strong>#<?= $booking['id'] ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="guest-info">
                                                <strong><?= htmlspecialchars($booking['guest_name']) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    <?= htmlspecialchars($booking['guest_email']) ?>
                                                </small>
                                                <?php if ($booking['guest_phone']): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>
                                                    <?= htmlspecialchars($booking['guest_phone']) ?>
                                                </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($booking['hotel_name']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= htmlspecialchars($booking['hotel_location']) ?></small>
                                        </td>
                                        <td>
                                            <strong><?= date('M j, Y', strtotime($booking['check_in'])) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= date('l', strtotime($booking['check_in'])) ?></small>
                                        </td>
                                        <td>
                                            <strong><?= date('M j, Y', strtotime($booking['check_out'])) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= date('l', strtotime($booking['check_out'])) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="fas fa-users me-1"></i>
                                                <?= $booking['guests'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong>UGX <?= number_format($booking['total_price']) ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge booking-status-<?= $booking['status'] ?>">
                                                <?= ucfirst($booking['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" 
                                                        onclick="viewBooking(<?= $booking['id'] ?>)"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if ($booking['status'] === 'pending'): ?>
                                                <button class="btn btn-outline-success" 
                                                        onclick="approveBooking(<?= $booking['id'] ?>)"
                                                        title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" 
                                                        onclick="rejectBooking(<?= $booking['id'] ?>)"
                                                        title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <?php elseif ($booking['status'] === 'approved'): ?>
                                                <button class="btn btn-outline-warning" 
                                                        onclick="cancelBooking(<?= $booking['id'] ?>)"
                                                        title="Cancel">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h3>No Bookings Found</h3>
                            <p class="text-muted">You don't have any bookings yet. Once tourists start booking your hotels, they'll appear here.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="booking-details">
                <!-- Booking details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize booking management
    initializeBookingManagement();
});

function initializeBookingManagement() {
    // Filter functionality
    $('#booking-filters input, #booking-filters select').on('change', function() {
        filterBookings();
    });
    
    // Auto-refresh bookings every 30 seconds
    setInterval(function() {
        refreshBookings();
    }, 30000);
}

function filterBookings() {
    const status = $('#status_filter').val();
    const hotelId = $('#hotel_filter').val();
    const dateFrom = $('#date_from').val();
    const dateTo = $('#date_to').val();
    
    $('.booking-row').each(function() {
        let show = true;
        
        if (status && $(this).data('status') !== status) {
            show = false;
        }
        
        // Add more filter logic here
        
        if (show) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function refreshBookings() {
    $.ajax({
        url: '/api/host/bookings',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                updateBookingsTable(response.bookings);
            }
        }
    });
}

function updateBookingsTable(bookings) {
    // Update the bookings table with new data
    // This would be implemented based on the response structure
}

function viewBooking(bookingId) {
    $.ajax({
        url: '/api/host/get-booking',
        method: 'GET',
        data: { booking_id: bookingId },
        success: function(response) {
            if (response.success) {
                displayBookingDetails(response.booking);
                $('#bookingModal').modal('show');
            } else {
                showError(response.message || 'Failed to load booking details');
            }
        },
        error: function() {
            showError('Failed to load booking details. Please try again.');
        }
    });
}

function displayBookingDetails(booking) {
    const details = `
        <div class="booking-details">
            <div class="row">
                <div class="col-md-6">
                    <h6>Guest Information</h6>
                    <p><strong>Name:</strong> ${booking.guest_name}</p>
                    <p><strong>Email:</strong> ${booking.guest_email}</p>
                    <p><strong>Phone:</strong> ${booking.guest_phone || 'Not provided'}</p>
                </div>
                <div class="col-md-6">
                    <h6>Booking Information</h6>
                    <p><strong>Booking ID:</strong> #${booking.id}</p>
                    <p><strong>Hotel:</strong> ${booking.hotel_name}</p>
                    <p><strong>Status:</strong> <span class="badge booking-status-${booking.status}">${booking.status}</span></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h6>Dates</h6>
                    <p><strong>Check-in:</strong> ${booking.check_in}</p>
                    <p><strong>Check-out:</strong> ${booking.check_out}</p>
                    <p><strong>Guests:</strong> ${booking.guests}</p>
                </div>
                <div class="col-md-6">
                    <h6>Pricing</h6>
                    <p><strong>Total:</strong> UGX ${booking.total_price.toLocaleString()}</p>
                    <p><strong>Created:</strong> ${booking.created_at}</p>
                </div>
            </div>
        </div>
    `;
    
    $('#booking-details').html(details);
}

function approveBooking(bookingId) {
    if (confirm('Are you sure you want to approve this booking?')) {
        $.ajax({
            url: '/api/host/approve-booking',
            method: 'POST',
            data: { booking_id: bookingId },
            success: function(response) {
                if (response.success) {
                    showSuccess('Booking approved successfully');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to approve booking');
                }
            },
            error: function() {
                showError('Failed to approve booking. Please try again.');
            }
        });
    }
}

function rejectBooking(bookingId) {
    if (confirm('Are you sure you want to reject this booking?')) {
        $.ajax({
            url: '/api/host/reject-booking',
            method: 'POST',
            data: { booking_id: bookingId },
            success: function(response) {
                if (response.success) {
                    showSuccess('Booking rejected');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to reject booking');
                }
            },
            error: function() {
                showError('Failed to reject booking. Please try again.');
            }
        });
    }
}

function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        $.ajax({
            url: '/api/host/cancel-booking',
            method: 'POST',
            data: { booking_id: bookingId },
            success: function(response) {
                if (response.success) {
                    showSuccess('Booking cancelled');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to cancel booking');
                }
            },
            error: function() {
                showError('Failed to cancel booking. Please try again.');
            }
        });
    }
}

function exportBookings() {
    window.open('/api/host/export-bookings', '_blank');
}
</script>

<style>
.booking-row {
    transition: all 0.3s ease;
}

.booking-row:hover {
    background-color: #f8f9fa;
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

.guest-info {
    font-size: 0.9rem;
}

.booking-stats .badge {
    font-size: 0.8rem;
}

.table th {
    background-color: var(--primary-color);
    color: white;
    border: none;
    font-weight: 600;
    padding: 1rem;
}

.table td {
    padding: 1rem;
    vertical-align: middle;
    border-color: #e9ecef;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .booking-actions {
        margin-top: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
}
</style>
