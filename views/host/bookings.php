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
        
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>
        
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
// Pure vanilla JavaScript - no jQuery dependency
document.addEventListener('DOMContentLoaded', function() {
    // Initialize booking management
    initializeBookingManagement();
});

function initializeBookingManagement() {
    // Filter functionality
    const filterInputs = document.querySelectorAll('#booking-filters input, #booking-filters select');
    filterInputs.forEach(input => {
        input.addEventListener('change', filterBookings);
    });
}

function viewBooking(bookingId) {
    // Navigate to view booking page
    window.location.href = `<?= BASE_URL ?>/host/view-booking?booking_id=${bookingId}`;
}

function filterBookings() {
    const status = document.getElementById('status_filter')?.value;
    const hotelId = document.getElementById('hotel_filter')?.value;
    const dateFrom = document.getElementById('date_from')?.value;
    const dateTo = document.getElementById('date_to')?.value;
    
    const bookingRows = document.querySelectorAll('.booking-row');
    bookingRows.forEach(row => {
        let show = true;
        
        if (status && row.getAttribute('data-status') !== status) {
            show = false;
        }
        
        // Add more filter logic here
        
        if (show) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

function approveBooking(bookingId) {
    if (confirm('Are you sure you want to approve this booking?')) {
        // Create and submit a form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/host/approve-booking';
        
        const bookingIdInput = document.createElement('input');
        bookingIdInput.type = 'hidden';
        bookingIdInput.name = 'booking_id';
        bookingIdInput.value = bookingId;
        
        form.appendChild(bookingIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectBooking(bookingId) {
    if (confirm('Are you sure you want to reject this booking?')) {
        // Create and submit a form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/host/reject-booking';
        
        const bookingIdInput = document.createElement('input');
        bookingIdInput.type = 'hidden';
        bookingIdInput.name = 'booking_id';
        bookingIdInput.value = bookingId;
        
        form.appendChild(bookingIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        // Create and submit a form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/host/cancel-booking';
        
        const bookingIdInput = document.createElement('input');
        bookingIdInput.type = 'hidden';
        bookingIdInput.name = 'booking_id';
        bookingIdInput.value = bookingId;
        
        form.appendChild(bookingIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportBookings() {
    window.open('/api/host/export-bookings', '_blank');
}

function showSuccess(message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Add new success alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show';
    alert.role = 'alert';
    alert.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alert, container.firstChild);
    }
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

function showError(message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Add new error alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger alert-dismissible fade show';
    alert.role = 'alert';
    alert.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alert, container.firstChild);
    }
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
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
