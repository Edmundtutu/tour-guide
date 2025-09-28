<!-- Host Dashboard -->
<section class="py-5">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 mb-2">
                            <i class="fas fa-tachometer-alt me-3 text-primary"></i>Host Dashboard
                        </h1>
                        <p class="text-muted">Welcome back, <?= htmlspecialchars($user['name']) ?>! Manage your hotels and bookings.</p>
                    </div>
                    <div class="dashboard-actions">
                        <a href="<?= View::url('/host/create-hotel') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Hotel
                        </a>
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
        
        <!-- Statistics Cards -->
        <div class="minimalist-grid">
            <div class="stat-item">
                <div class="stat-number"><?= $stats['total_hotels'] ?? 0 ?></div>
                <div class="stat-label">Total Hotels</div>
                <div class="stat-change positive">
                    ▲+2
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['total_bookings'] ?? 0 ?></div>
                <div class="stat-label">Total Bookings</div>
                <div class="stat-change positive">
                    ▲+18%
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['pending_bookings'] ?? 0 ?></div>
                <div class="stat-label">Pending Bookings</div>
                <div class="stat-change neutral">
                    <i class="fas fa-minus me-1"></i>0%
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-number">UGX <?= number_format($stats['total_revenue'] ?? 0) ?></div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-change positive">
                    ▲+25%
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Recent Bookings -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Recent Bookings
                        </h5>
                        <a href="<?= View::url('/host/bookings') ?>" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_bookings)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Hotel</th>
                                        <th>Guest</th>
                                        <th>Check-in</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($recent_bookings, 0, 5) as $booking): ?>
                                    <tr>
                                        <td>#<?= $booking['id'] ?></td>
                                        <td><?= htmlspecialchars($booking['hotel_name']) ?></td>
                                        <td><?= htmlspecialchars($booking['guest_name']) ?></td>
                                        <td><?= date('M j, Y', strtotime($booking['check_in'])) ?></td>
                                        <td>
                                            <span class="badge booking-status-<?= $booking['status'] ?>">
                                                <?= ucfirst($booking['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= View::url('/host/bookings?id=' . $booking['id']) ?>" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($booking['status'] === 'pending'): ?>
                                                <button class="btn btn-outline-success btn-sm" 
                                                        onclick="approveBooking(<?= $booking['id'] ?>)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm" 
                                                        onclick="rejectBooking(<?= $booking['id'] ?>)">
                                                    <i class="fas fa-times"></i>
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
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>No Bookings Yet</h5>
                            <p class="text-muted">You'll see your hotel bookings here once guests start making reservations.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions & Info -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= View::url('/host/create-hotel') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New Hotel
                            </a>
                            <a href="<?= View::url('/host/hotels') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-hotel me-2"></i>Manage Hotels
                            </a>
                            <a href="<?= View::url('/host/bookings') ?>" class="btn btn-outline-success">
                                <i class="fas fa-calendar-check me-2"></i>View Bookings
                            </a>
                            <a href="<?= View::url('/host/subscription') ?>" class="btn btn-outline-warning">
                                <i class="fas fa-credit-card me-2"></i>Subscription
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Subscription Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>Subscription Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($subscription): ?>
                        <div class="subscription-info">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Plan:</span>
                                <strong><?= ucfirst($subscription['plan']) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Status:</span>
                                <span class="badge bg-<?= $subscription['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($subscription['status']) ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Expires:</span>
                                <span><?= date('M j, Y', strtotime($subscription['end_date'])) ?></span>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                            <h6>No Active Subscription</h6>
                            <p class="text-muted small">Subscribe to start listing your hotels.</p>
                            <a href="<?= View::url('/host/subscribe') ?>" class="btn btn-warning btn-sm">
                                Subscribe Now
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Activity
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <div class="activity-item">
                                <i class="fas fa-hotel text-primary"></i>
                                <div class="activity-content">
                                    <strong>Hotel Added</strong>
                                    <small class="text-muted d-block">2 hours ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <i class="fas fa-calendar-check text-success"></i>
                                <div class="activity-content">
                                    <strong>New Booking</strong>
                                    <small class="text-muted d-block">5 hours ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <i class="fas fa-edit text-primary"></i>
                                <div class="activity-content">
                                    <strong>Hotel Updated</strong>
                                    <small class="text-muted d-block">1 day ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Pure vanilla JavaScript - no jQuery dependency
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard widgets
    initializeDashboard();
});

function initializeDashboard() {
    // Dashboard initialization - no auto-refresh needed with form submissions
}

function viewBooking(bookingId) {
    // Navigate to view booking page
    window.location.href = `<?= BASE_URL ?>/host/view-booking?booking_id=${bookingId}`;
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
/* Minimalist Grid Layout */
.minimalist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}

.stat-item {
    padding: 2rem 1.5rem;
    text-align: center;
    border: none;
    background: transparent;
    transition: all 0.3s ease;
    position: relative;
}

.stat-item::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 20%;
    width: 60%;
    height: 1px;
    background: #e2e8f0;
    transition: all 0.3s ease;
}

.stat-item:hover::after {
    width: 80%;
    left: 10%;
    background: #3b82f6;
    height: 2px;
}

.stat-item:hover {
    background: rgba(59, 130, 246, 0.02);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.stat-change {
    font-size: 0.85rem;
    font-weight: 500;
}

.positive { color: #10b981; }
.negative { color: #ef4444; }
.neutral { color: #6b7280; }

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

.activity-list {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item i {
    width: 20px;
    margin-right: 0.75rem;
    font-size: 1.1rem;
}

.activity-content {
    flex: 1;
}

.subscription-info {
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .stat-number {
        font-size: 2rem;
    }
    
    .dashboard-actions {
        margin-top: 1rem;
    }
}
</style>
