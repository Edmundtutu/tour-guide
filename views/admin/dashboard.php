<!-- Admin Dashboard -->
<section class="py-5">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 mb-2">
                            <i class="fas fa-crown me-3 text-primary"></i>Admin Dashboard
                        </h1>
                        <p class="text-muted">System overview and management</p>
                    </div>
                    <div class="admin-actions">
                        <button class="btn btn-outline-primary" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="dashboard-stat">
                            <div class="stat-number"><?= $stats['total_users'] ?? 0 ?></div>
                            <div class="stat-label">Total Users</div>
                        </div>
                        <i class="fas fa-users fa-2x text-primary mt-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="dashboard-stat">
                            <div class="stat-number"><?= $stats['total_hotels'] ?? 0 ?></div>
                            <div class="stat-label">Total Hotels</div>
                        </div>
                        <i class="fas fa-hotel fa-2x text-success mt-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="dashboard-stat">
                            <div class="stat-number"><?= $stats['total_bookings'] ?? 0 ?></div>
                            <div class="stat-label">Total Bookings</div>
                        </div>
                        <i class="fas fa-calendar-check fa-2x text-warning mt-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="dashboard-stat">
                            <div class="stat-number">UGX <?= number_format($stats['total_revenue'] ?? 0) ?></div>
                            <div class="stat-label">Total Revenue</div>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x text-info mt-3"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Stats -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="dashboard-stat">
                            <div class="stat-number"><?= $stats['pending_hotels'] ?? 0 ?></div>
                            <div class="stat-label">Pending Hotels</div>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning mt-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="dashboard-stat">
                            <div class="stat-number"><?= $stats['active_subscriptions'] ?? 0 ?></div>
                            <div class="stat-label">Active Subscriptions</div>
                        </div>
                        <i class="fas fa-credit-card fa-2x text-success mt-3"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="stat-number"><?= $stats['new_users_today'] ?? 0 ?></div>
                        <div class="stat-label">New Users Today</div>
                    </div>
                    <i class="fas fa-user-plus fa-2x text-info mt-3"></i>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <div class="dashboard-stat">
                            <div class="stat-number"><?= $stats['system_health'] ?? 'Good' ?></div>
                            <div class="stat-label">System Health</div>
                        </div>
                        <i class="fas fa-heartbeat fa-2x text-success mt-3"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Recent Activity -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Activity
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshActivity()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <div class="activity-item">
                                <div class="activity-icon bg-primary">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>New User Registration</h6>
                                    <p class="text-muted mb-1">John Doe registered as a tourist</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-success">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Hotel Added</h6>
                                    <p class="text-muted mb-1">Kampala Hotel was added by host</p>
                                    <small class="text-muted">4 hours ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-warning">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>New Booking</h6>
                                    <p class="text-muted mb-1">Booking #1234 created for Kampala Hotel</p>
                                    <small class="text-muted">6 hours ago</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon bg-info">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Subscription Payment</h6>
                                    <p class="text-muted mb-1">Monthly subscription payment received</p>
                                    <small class="text-muted">1 day ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions & System Status -->
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
                            <a href="<?= View::url('/admin/users') ?>" class="btn btn-primary">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                            <a href="<?= View::url('/admin/hotels') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-hotel me-2"></i>Manage Hotels
                            </a>
                            <a href="<?= View::url('/admin/bookings') ?>" class="btn btn-outline-success">
                                <i class="fas fa-calendar-check me-2"></i>View Bookings
                            </a>
                            <a href="<?= View::url('/admin/subscriptions') ?>" class="btn btn-outline-warning">
                                <i class="fas fa-credit-card me-2"></i>Subscriptions
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- System Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-server me-2"></i>System Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="system-status">
                            <div class="status-item">
                                <span class="status-label">Database</span>
                                <span class="status-value text-success">
                                    <i class="fas fa-check-circle me-1"></i>Online
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Email Service</span>
                                <span class="status-value text-success">
                                    <i class="fas fa-check-circle me-1"></i>Online
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Payment Gateway</span>
                                <span class="status-value text-success">
                                    <i class="fas fa-check-circle me-1"></i>Online
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Storage</span>
                                <span class="status-value text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>75% Full
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Approvals -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Pending Approvals
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="pending-approvals">
                            <div class="approval-item">
                                <div class="approval-info">
                                    <strong>Hotel Approval</strong>
                                    <small class="text-muted d-block">Kampala Hotel - 2 hours ago</small>
                                </div>
                                <div class="approval-actions">
                                    <button class="btn btn-sm btn-success" onclick="approveHotel(1)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectHotel(1)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="approval-item">
                                <div class="approval-info">
                                    <strong>User Verification</strong>
                                    <small class="text-muted d-block">Host Account - 4 hours ago</small>
                                </div>
                                <div class="approval-actions">
                                    <button class="btn btn-sm btn-success" onclick="verifyUser(1)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectUser(1)">
                                        <i class="fas fa-times"></i>
                                    </button>
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
$(document).ready(function() {
    // Initialize admin dashboard
    initializeAdminDashboard();
});

function initializeAdminDashboard() {
    // Auto-refresh dashboard every 60 seconds
    setInterval(function() {
        refreshDashboard();
    }, 60000);
}

function refreshDashboard() {
    $.ajax({
        url: '/api/admin/dashboard-stats',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                updateDashboardStats(response.stats);
            }
        }
    });
}

function updateDashboardStats(stats) {
    // Update dashboard statistics
    $('.stat-number').each(function() {
        const label = $(this).next('.stat-label').text();
        if (stats[label]) {
            $(this).text(stats[label]);
        }
    });
}

function refreshActivity() {
    $.ajax({
        url: '/api/admin/recent-activity',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                updateActivityTimeline(response.activities);
            }
        }
    });
}

function updateActivityTimeline(activities) {
    // Update activity timeline with new data
    // This would be implemented based on the response structure
}

function approveHotel(hotelId) {
    if (confirm('Are you sure you want to approve this hotel?')) {
        $.ajax({
            url: '/api/admin/approve-hotel',
            method: 'POST',
            data: { hotel_id: hotelId },
            success: function(response) {
                if (response.success) {
                    showSuccess('Hotel approved successfully');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to approve hotel');
                }
            },
            error: function() {
                showError('Failed to approve hotel. Please try again.');
            }
        });
    }
}

function rejectHotel(hotelId) {
    if (confirm('Are you sure you want to reject this hotel?')) {
        $.ajax({
            url: '/api/admin/reject-hotel',
            method: 'POST',
            data: { hotel_id: hotelId },
            success: function(response) {
                if (response.success) {
                    showSuccess('Hotel rejected');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to reject hotel');
                }
            },
            error: function() {
                showError('Failed to reject hotel. Please try again.');
            }
        });
    }
}

function verifyUser(userId) {
    if (confirm('Are you sure you want to verify this user?')) {
        $.ajax({
            url: '/api/admin/verify-user',
            method: 'POST',
            data: { user_id: userId },
            success: function(response) {
                if (response.success) {
                    showSuccess('User verified successfully');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to verify user');
                }
            },
            error: function() {
                showError('Failed to verify user. Please try again.');
            }
        });
    }
}

function rejectUser(userId) {
    if (confirm('Are you sure you want to reject this user?')) {
        $.ajax({
            url: '/api/admin/reject-user',
            method: 'POST',
            data: { user_id: userId },
            success: function(response) {
                if (response.success) {
                    showSuccess('User rejected');
                    location.reload();
                } else {
                    showError(response.message || 'Failed to reject user');
                }
            },
            error: function() {
                showError('Failed to reject user. Please try again.');
            }
        });
    }
}
</script>

<style>
.dashboard-card {
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.dashboard-stat .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.dashboard-stat .stat-label {
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.activity-timeline {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-content h6 {
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.system-status {
    font-size: 0.9rem;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.status-item:last-child {
    border-bottom: none;
}

.status-label {
    font-weight: 500;
}

.status-value {
    font-weight: 600;
}

.pending-approvals {
    max-height: 300px;
    overflow-y: auto;
}

.approval-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.approval-item:last-child {
    border-bottom: none;
}

.approval-info {
    flex: 1;
}

.approval-actions {
    display: flex;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .admin-actions {
        margin-top: 1rem;
    }
    
    .dashboard-stat .stat-number {
        font-size: 2rem;
    }
}
</style>
