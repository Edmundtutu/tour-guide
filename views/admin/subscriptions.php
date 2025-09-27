<?php
$title = 'Subscription Management';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Subscription Management</h1>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createSubscriptionModal">
                        <i class="fas fa-plus"></i> Create Subscription
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                        <i class="fas fa-tasks"></i> Bulk Actions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_subscriptions'] ?? 0 ?></h4>
                            <p class="mb-0">Total Subscriptions</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-crown fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['active_subscriptions'] ?? 0 ?></h4>
                            <p class="mb-0">Active</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['expiring_soon'] ?? 0 ?></h4>
                            <p class="mb-0">Expiring Soon</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">UGX <?= number_format($stats['monthly_revenue'] ?? 0) ?></h4>
                            <p class="mb-0">Monthly Revenue</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="planFilter" class="form-label">Plan</label>
                                <select class="form-select" id="planFilter">
                                    <option value="">All Plans</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="searchInput" class="form-label">Search</label>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search by host name or email...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="filterSubscriptions()">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list"></i> All Subscriptions
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="subscriptionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Host</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days Left</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($subscriptions) && !empty($subscriptions)): ?>
                                    <?php foreach ($subscriptions as $subscription): ?>
                                        <tr data-subscription-id="<?= $subscription['id'] ?>">
                                            <td>
                                                <input type="checkbox" class="subscription-checkbox" value="<?= $subscription['id'] ?>">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" 
                                                             alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($subscription['host_name']) ?></h6>
                                                        <small class="text-muted"><?= htmlspecialchars($subscription['host_email']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $subscription['plan'] === 'annual' ? 'success' : 'primary' ?>">
                                                    <?= ucfirst($subscription['plan']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong>UGX <?= number_format($subscription['amount']) ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $subscription['status'] === 'active' ? 'success' : ($subscription['status'] === 'expired' ? 'danger' : 'secondary') ?>">
                                                    <?= ucfirst($subscription['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('M j, Y', strtotime($subscription['start_date'])) ?></td>
                                            <td><?= date('M j, Y', strtotime($subscription['end_date'])) ?></td>
                                            <td>
                                                <?php
                                                $endDate = new DateTime($subscription['end_date']);
                                                $today = new DateTime();
                                                $daysLeft = $today->diff($endDate)->days;
                                                
                                                if ($subscription['status'] === 'active') {
                                                    if ($daysLeft <= 7) {
                                                        echo '<span class="text-danger"><strong>' . $daysLeft . '</strong> days</span>';
                                                    } elseif ($daysLeft <= 30) {
                                                        echo '<span class="text-warning"><strong>' . $daysLeft . '</strong> days</span>';
                                                    } else {
                                                        echo '<span class="text-success"><strong>' . $daysLeft . '</strong> days</span>';
                                                    }
                                                } else {
                                                    echo '<span class="text-muted">-</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewSubscription(<?= $subscription['id'] ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" onclick="renewSubscription(<?= $subscription['id'] ?>)">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning" onclick="editSubscription(<?= $subscription['id'] ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" onclick="cancelSubscription(<?= $subscription['id'] ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No subscriptions found</h5>
                                            <p class="text-muted">Create the first subscription to get started.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Subscriptions pagination">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Create Subscription Modal -->
<div class="modal fade" id="createSubscriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/subscriptions/create" method="POST" id="createSubscriptionForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="host_id" class="form-label">Host *</label>
                        <select class="form-select" id="host_id" name="host_id" required>
                            <option value="">Select Host</option>
                            <?php if (isset($hosts) && !empty($hosts)): ?>
                                <?php foreach ($hosts as $host): ?>
                                    <option value="<?= $host['id'] ?>"><?= htmlspecialchars($host['name']) ?> (<?= htmlspecialchars($host['email']) ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="plan" class="form-label">Plan *</label>
                        <select class="form-select" id="plan" name="plan" required>
                            <option value="">Select Plan</option>
                            <option value="monthly">Monthly - UGX <?= number_format(MONTHLY_SUBSCRIPTION_FEE) ?></option>
                            <option value="annual">Annual - UGX <?= number_format(ANNUAL_SUBSCRIPTION_FEE) ?></option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date *</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Subscription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="bulkAction" class="form-label">Action</label>
                    <select class="form-select" id="bulkAction">
                        <option value="">Select Action</option>
                        <option value="renew">Renew Selected</option>
                        <option value="cancel">Cancel Selected</option>
                        <option value="export">Export Selected</option>
                    </select>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> This will affect all selected subscriptions.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Execute Action</button>
            </div>
        </div>
    </div>
</div>

<!-- Subscription Details Modal -->
<div class="modal fade" id="subscriptionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="subscriptionDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize data tables or custom filtering
    initializeSubscriptionTable();
});

function initializeSubscriptionTable() {
    // Add search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const planFilter = document.getElementById('planFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterSubscriptions);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', filterSubscriptions);
    }
    if (planFilter) {
        planFilter.addEventListener('change', filterSubscriptions);
    }
}

function filterSubscriptions() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const planFilter = document.getElementById('planFilter').value;
    
    const rows = document.querySelectorAll('#subscriptionsTable tbody tr');
    
    rows.forEach(row => {
        const hostName = row.cells[1].textContent.toLowerCase();
        const hostEmail = row.cells[1].textContent.toLowerCase();
        const plan = row.cells[2].textContent.toLowerCase();
        const status = row.cells[4].textContent.toLowerCase();
        
        const matchesSearch = hostName.includes(searchTerm) || hostEmail.includes(searchTerm);
        const matchesStatus = !statusFilter || status.includes(statusFilter);
        const matchesPlan = !planFilter || plan.includes(planFilter);
        
        if (matchesSearch && matchesStatus && matchesPlan) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.subscription-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function viewSubscription(subscriptionId) {
    // Load subscription details via AJAX
    fetch(`/admin/subscriptions/${subscriptionId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('subscriptionDetailsContent').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('subscriptionDetailsModal')).show();
        })
        .catch(error => {
            console.error('Error loading subscription details:', error);
            alert('Error loading subscription details');
        });
}

function renewSubscription(subscriptionId) {
    if (confirm('Are you sure you want to renew this subscription?')) {
        // Submit renewal request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/subscriptions/renew';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= View::csrfToken() ?>';
        
        const subscriptionIdInput = document.createElement('input');
        subscriptionIdInput.type = 'hidden';
        subscriptionIdInput.name = 'subscription_id';
        subscriptionIdInput.value = subscriptionId;
        
        form.appendChild(csrfToken);
        form.appendChild(subscriptionIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function editSubscription(subscriptionId) {
    // Redirect to edit page or show edit modal
    window.location.href = `/admin/subscriptions/${subscriptionId}/edit`;
}

function cancelSubscription(subscriptionId) {
    if (confirm('Are you sure you want to cancel this subscription? This action cannot be undone.')) {
        // Submit cancellation request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/subscriptions/cancel';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= View::csrfToken() ?>';
        
        const subscriptionIdInput = document.createElement('input');
        subscriptionIdInput.type = 'hidden';
        subscriptionIdInput.name = 'subscription_id';
        subscriptionIdInput.value = subscriptionId;
        
        form.appendChild(csrfToken);
        form.appendChild(subscriptionIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function executeBulkAction() {
    const selectedCheckboxes = document.querySelectorAll('.subscription-checkbox:checked');
    const action = document.getElementById('bulkAction').value;
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one subscription');
        return;
    }
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    const subscriptionIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (confirm(`Are you sure you want to ${action} ${subscriptionIds.length} subscription(s)?`)) {
        // Submit bulk action
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/subscriptions/bulk-action';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= View::csrfToken() ?>';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        
        const idsInput = document.createElement('input');
        idsInput.type = 'hidden';
        idsInput.name = 'subscription_ids';
        idsInput.value = JSON.stringify(subscriptionIds);
        
        form.appendChild(csrfToken);
        form.appendChild(actionInput);
        form.appendChild(idsInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
