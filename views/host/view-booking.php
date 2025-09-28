<?php
$booking = $data['booking'];
$hotel = $data['hotel'];
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Booking Details</h1>
            <p class="text-muted">Booking #<?= htmlspecialchars($booking['id']) ?></p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/host/bookings" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Bookings
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Booking Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Guest Details</h6>
                            <p><strong>Name:</strong> <?= htmlspecialchars($booking['guest_name']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($booking['guest_email']) ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($booking['guest_phone'] ?? 'Not provided') ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Booking Details</h6>
                            <p><strong>Booking ID:</strong> #<?= htmlspecialchars($booking['id']) ?></p>
                            <p><strong>Hotel:</strong> <?= htmlspecialchars($booking['hotel_name']) ?></p>
                            <p><strong>Status:</strong> 
                                <span class="badge booking-status-<?= $booking['status'] ?>">
                                    <?= ucfirst($booking['status']) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Dates & Guests</h6>
                            <p><strong>Check-in:</strong> <?= date('M d, Y', strtotime($booking['check_in'])) ?></p>
                            <p><strong>Check-out:</strong> <?= date('M d, Y', strtotime($booking['check_out'])) ?></p>
                            <p><strong>Duration:</strong> <?= (strtotime($booking['check_out']) - strtotime($booking['check_in'])) / (60 * 60 * 24) ?> nights</p>
                            <p><strong>Guests:</strong> <?= $booking['guests'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Pricing</h6>
                            <p><strong>Total Amount:</strong> UGX <?= number_format($booking['total_price']) ?></p>
                            <p><strong>Payment Status:</strong> 
                                <span class="badge payment-status-<?= $booking['payment_status'] ?>">
                                    <?= ucfirst($booking['payment_status']) ?>
                                </span>
                            </p>
                            <p><strong>Created:</strong> <?= date('M d, Y H:i', strtotime($booking['created_at'])) ?></p>
                        </div>
                    </div>
                    
                    <?php if (!empty($booking['special_requests'])): ?>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary">Special Requests</h6>
                            <p class="text-muted"><?= htmlspecialchars($booking['special_requests']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Actions & Hotel Info -->
        <div class="col-lg-4">
            <!-- Booking Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <?php if ($booking['status'] === 'pending'): ?>
                        <div class="d-grid gap-2">
                            <button onclick="approveBooking(<?= $booking['id'] ?>)" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Approve Booking
                            </button>
                            <button onclick="rejectBooking(<?= $booking['id'] ?>)" class="btn btn-danger">
                                <i class="fas fa-times me-2"></i>Reject Booking
                            </button>
                        </div>
                    <?php elseif ($booking['status'] === 'approved'): ?>
                        <div class="d-grid gap-2">
                            <button onclick="cancelBooking(<?= $booking['id'] ?>)" class="btn btn-warning">
                                <i class="fas fa-ban me-2"></i>Cancel Booking
                            </button>
                        </div>
                        <div class="alert alert-success mt-3">
                            <i class="fas fa-check-circle me-2"></i>
                            This booking has been approved and is confirmed.
                        </div>
                    <?php elseif ($booking['status'] === 'rejected'): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            This booking has been rejected.
                        </div>
                    <?php elseif ($booking['status'] === 'cancelled'): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-ban me-2"></i>
                            This booking has been cancelled.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Hotel Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hotel Information</h6>
                </div>
                <div class="card-body">
                    <h6><?= htmlspecialchars($hotel['name']) ?></h6>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <?= htmlspecialchars($hotel['location']) ?>
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-2"></i>
                        <?= htmlspecialchars($hotel['phone'] ?? 'Not provided') ?>
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <?= htmlspecialchars($hotel['email'] ?? 'Not provided') ?>
                    </p>
                    
                    <div class="mt-3">
                        <a href="<?= BASE_URL ?>/host/hotels" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Manage Hotel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>

<style>
.booking-status-pending { background-color: #ffc107; color: #000; }
.booking-status-approved { background-color: #28a745; color: #fff; }
.booking-status-rejected { background-color: #dc3545; color: #fff; }
.booking-status-cancelled { background-color: #6c757d; color: #fff; }

.payment-status-pending { background-color: #ffc107; color: #000; }
.payment-status-paid { background-color: #28a745; color: #fff; }
.payment-status-failed { background-color: #dc3545; color: #fff; }
</style>
