<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php require_once __DIR__ . '/../../app/core/View.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-5 mb-0">
                        <i class="fas fa-crown me-3 text-primary"></i>Subscription Management
                    </h1>
                    <?php if (!$subscription): ?>
                    <a href="<?= BASE_URL ?>/host/subscribe" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Subscribe Now
                    </a>
                    <?php endif; ?>
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

        <div class="row">
            <!-- Current Subscription -->
            <div class="col-lg-8">
                <?php if ($subscription): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>Active Subscription
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Plan</h6>
                                <h4 class="mb-3"><?= ucfirst($subscription['plan']) ?> Plan</h4>
                                
                                <h6 class="text-muted mb-1">Amount</h6>
                                <h5 class="text-success mb-3">UGX <?= number_format($subscription['amount']) ?></h5>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Status</h6>
                                <span class="badge bg-success fs-6 mb-3"><?= ucfirst($subscription['status']) ?></span>
                                
                                <h6 class="text-muted mb-1">Next Billing</h6>
                                <p class="mb-0"><?= date('M j, Y', strtotime($subscription['end_date'])) ?></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Start Date</h6>
                                <p class="mb-0"><?= date('M j, Y', strtotime($subscription['start_date'])) ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">End Date</h6>
                                <p class="mb-0"><?= date('M j, Y', strtotime($subscription['end_date'])) ?></p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button class="btn btn-outline-danger me-2" onclick="cancelSubscription()">
                                <i class="fas fa-times me-2"></i>Cancel Subscription
                            </button>
                            <button class="btn btn-outline-primary" onclick="changePlan()">
                                <i class="fas fa-edit me-2"></i>Change Plan
                            </button>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>No Active Subscription
                        </h5>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Subscribe to Continue</h4>
                        <p class="text-muted">You need an active subscription to manage your hotels and bookings.</p>
                        <a href="<?= BASE_URL ?>/host/subscribe" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Subscribe Now
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Subscription History -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Subscription History
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($history)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No subscription history found.</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Period</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($history as $sub): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-<?= $sub['plan'] === 'monthly' ? 'primary' : 'info' ?>">
                                                <?= ucfirst($sub['plan']) ?>
                                            </span>
                                        </td>
                                        <td>UGX <?= number_format($sub['amount']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $sub['status'] === 'active' ? 'success' : ($sub['status'] === 'expired' ? 'secondary' : 'danger') ?>">
                                                <?= ucfirst($sub['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= date('M j', strtotime($sub['start_date'])) ?> - 
                                            <?= date('M j, Y', strtotime($sub['end_date'])) ?>
                                        </td>
                                        <td><?= date('M j, Y', strtotime($sub['created_at'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Subscription Benefits -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>Subscription Benefits
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Unlimited Hotels</strong>
                                <br><small class="text-muted">Add as many hotels as you want</small>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Unlimited Rooms</strong>
                                <br><small class="text-muted">Manage unlimited rooms per hotel</small>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Booking Management</strong>
                                <br><small class="text-muted">Full booking approval and management</small>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Analytics Dashboard</strong>
                                <br><small class="text-muted">Revenue and booking analytics</small>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>Priority Support</strong>
                                <br><small class="text-muted">24/7 customer support</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function cancelSubscription() {
    if (confirm('Are you sure you want to cancel your subscription? You will lose access to premium features.')) {
        // TODO: Implement subscription cancellation
        alert('Subscription cancellation functionality will be implemented soon!');
    }
}

function changePlan() {
    // TODO: Implement plan change
    alert('Plan change functionality will be implemented soon!');
}
</script>

<style>
.card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
    border: none;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
}

.badge {
    font-size: 0.75em;
}
</style>
