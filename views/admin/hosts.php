<?php
$title = 'Host Management';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Host Management</h1>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createHostModal">
                        <i class="fas fa-plus"></i> Add Host
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
                            <h4 class="mb-0"><?= $stats['total_hosts'] ?? 0 ?></h4>
                            <p class="mb-0">Total Hosts</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
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
                            <h4 class="mb-0"><?= $stats['active_hosts'] ?? 0 ?></h4>
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
                            <h4 class="mb-0"><?= $stats['pending_approval'] ?? 0 ?></h4>
                            <p class="mb-0">Pending Approval</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
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
                            <h4 class="mb-0"><?= $stats['total_hotels'] ?? 0 ?></h4>
                            <p class="mb-0">Total Hotels</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hotel fa-2x"></i>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="subscriptionFilter" class="form-label">Subscription</label>
                                <select class="form-select" id="subscriptionFilter">
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                    <option value="none">No Subscription</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="verificationFilter" class="form-label">Verification</label>
                                <select class="form-select" id="verificationFilter">
                                    <option value="">All</option>
                                    <option value="verified">Verified</option>
                                    <option value="unverified">Unverified</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="searchInput" class="form-label">Search</label>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search by name, email, or business...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="filterHosts()">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hosts Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list"></i> All Hosts
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="hostsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Host</th>
                                    <th>Business</th>
                                    <th>Status</th>
                                    <th>Subscription</th>
                                    <th>Hotels</th>
                                    <th>Bookings</th>
                                    <th>Revenue</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($hosts) && !empty($hosts)): ?>
                                    <?php foreach ($hosts as $host): ?>
                                        <tr data-host-id="<?= $host['id'] ?>">
                                            <td>
                                                <input type="checkbox" class="host-checkbox" value="<?= $host['id'] ?>">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face" 
                                                             alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($host['name']) ?></h6>
                                                        <small class="text-muted"><?= htmlspecialchars($host['email']) ?></small>
                                                        <?php if ($host['phone']): ?>
                                                            <br><small class="text-muted"><i class="fas fa-phone"></i> <?= htmlspecialchars($host['phone']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if (!empty($host['business_name'])): ?>
                                                    <div>
                                                        <strong><?= htmlspecialchars($host['business_name']) ?></strong>
                                                        <?php if (!empty($host['business_type'])): ?>
                                                            <br><small class="text-muted"><?= ucfirst($host['business_type']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Not set</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $host['status'] === 'active' ? 'success' : ($host['status'] === 'blocked' ? 'danger' : 'secondary') ?>">
                                                    <?= ucfirst($host['status']) ?>
                                                </span>
                                                <?php if (!empty($host['is_verified'])): ?>
                                                    <br><small class="text-success"><i class="fas fa-check-circle"></i> Verified</small>
                                                <?php else: ?>
                                                    <br><small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Unverified</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($host['subscription'])): ?>
                                                    <div>
                                                        <span class="badge bg-<?= $host['subscription']['status'] === 'active' ? 'success' : 'danger' ?>">
                                                            <?= ucfirst($host['subscription']['plan']) ?>
                                                        </span>
                                                        <br><small class="text-muted">
                                                            <?= date('M j, Y', strtotime($host['subscription']['end_date'])) ?>
                                                        </small>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">No Subscription</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?= $host['hotel_count'] ?? 0 ?></span>
                                                <?php if ($host['pending_hotels'] > 0): ?>
                                                    <br><small class="text-warning"><?= $host['pending_hotels'] ?> pending</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= $host['booking_count'] ?? 0 ?></span>
                                                <?php if ($host['pending_bookings'] > 0): ?>
                                                    <br><small class="text-warning"><?= $host['pending_bookings'] ?> pending</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong class="text-success">UGX <?= number_format($host['total_revenue'] ?? 0) ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M j, Y', strtotime($host['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" onclick="viewHost(<?= $host['id'] ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success" onclick="verifyHost(<?= $host['id'] ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning" onclick="editHost(<?= $host['id'] ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#" onclick="sendMessage(<?= $host['id'] ?>)">
                                                                <i class="fas fa-envelope"></i> Send Message
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="viewHotels(<?= $host['id'] ?>)">
                                                                <i class="fas fa-hotel"></i> View Hotels
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="viewBookings(<?= $host['id'] ?>)">
                                                                <i class="fas fa-calendar"></i> View Bookings
                                                            </a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#" onclick="blockHost(<?= $host['id'] ?>)">
                                                                <i class="fas fa-ban"></i> Block Host
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No hosts found</h5>
                                            <p class="text-muted">Add the first host to get started.</p>
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
                <nav aria-label="Hosts pagination">
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

<!-- Create Host Modal -->
<div class="modal fade" id="createHostModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Host</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/hosts/create" method="POST" id="createHostForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="business_type" class="form-label">Business Type</label>
                                <select class="form-select" id="business_type" name="business_type">
                                    <option value="">Select Type</option>
                                    <option value="hotel">Hotel</option>
                                    <option value="lodge">Lodge</option>
                                    <option value="resort">Resort</option>
                                    <option value="guesthouse">Guesthouse</option>
                                    <option value="hostel">Hostel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="license_number" class="form-label">License Number</label>
                                <input type="text" class="form-control" id="license_number" name="license_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tax_id" class="form-label">Tax ID</label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1">
                            <label class="form-check-label" for="is_verified">
                                Mark as verified
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Host</button>
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
                        <option value="verify">Verify Selected</option>
                        <option value="block">Block Selected</option>
                        <option value="unblock">Unblock Selected</option>
                        <option value="send_message">Send Message</option>
                        <option value="export">Export Selected</option>
                    </select>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> This will affect all selected hosts.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Execute Action</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeHostTable();
});

function initializeHostTable() {
    // Add search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const subscriptionFilter = document.getElementById('subscriptionFilter');
    const verificationFilter = document.getElementById('verificationFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterHosts);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', filterHosts);
    }
    if (subscriptionFilter) {
        subscriptionFilter.addEventListener('change', filterHosts);
    }
    if (verificationFilter) {
        verificationFilter.addEventListener('change', filterHosts);
    }
}

function filterHosts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const subscriptionFilter = document.getElementById('subscriptionFilter').value;
    const verificationFilter = document.getElementById('verificationFilter').value;
    
    const rows = document.querySelectorAll('#hostsTable tbody tr');
    
    rows.forEach(row => {
        const hostName = row.cells[1].textContent.toLowerCase();
        const hostEmail = row.cells[1].textContent.toLowerCase();
        const businessName = row.cells[2].textContent.toLowerCase();
        const status = row.cells[3].textContent.toLowerCase();
        const subscription = row.cells[4].textContent.toLowerCase();
        const verification = row.cells[3].textContent.toLowerCase();
        
        const matchesSearch = hostName.includes(searchTerm) || hostEmail.includes(searchTerm) || businessName.includes(searchTerm);
        const matchesStatus = !statusFilter || status.includes(statusFilter);
        const matchesSubscription = !subscriptionFilter || subscription.includes(subscriptionFilter);
        const matchesVerification = !verificationFilter || verification.includes(verificationFilter);
        
        if (matchesSearch && matchesStatus && matchesSubscription && matchesVerification) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.host-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function viewHost(hostId) {
    window.location.href = `/admin/hosts/${hostId}`;
}

function verifyHost(hostId) {
    if (confirm('Are you sure you want to verify this host?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/hosts/verify';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= View::csrfToken() ?>';
        
        const hostIdInput = document.createElement('input');
        hostIdInput.type = 'hidden';
        hostIdInput.name = 'host_id';
        hostIdInput.value = hostId;
        
        form.appendChild(csrfToken);
        form.appendChild(hostIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function editHost(hostId) {
    window.location.href = `<?= BASE_URL ?>/admin/edit-host?host_id=${hostId}`;
}

function blockHost(hostId) {
    if (confirm('Are you sure you want to block this host? This will prevent them from accessing their account.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/hosts/block';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= View::csrfToken() ?>';
        
        const hostIdInput = document.createElement('input');
        hostIdInput.type = 'hidden';
        hostIdInput.name = 'host_id';
        hostIdInput.value = hostId;
        
        form.appendChild(csrfToken);
        form.appendChild(hostIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function sendMessage(hostId) {
    // Open message modal or redirect to messaging system
    alert(`Send message to host ${hostId}`);
}

function viewHotels(hostId) {
    window.location.href = `<?= BASE_URL ?>/admin/hotels?host_id=${hostId}`;
}

function viewBookings(hostId) {
    window.location.href = `<?= BASE_URL ?>/admin/bookings?host_id=${hostId}`;
}

function executeBulkAction() {
    const selectedCheckboxes = document.querySelectorAll('.host-checkbox:checked');
    const action = document.getElementById('bulkAction').value;
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one host');
        return;
    }
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    const hostIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (confirm(`Are you sure you want to ${action} ${hostIds.length} host(s)?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/hosts/bulk-action';
        
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
        idsInput.name = 'host_ids';
        idsInput.value = JSON.stringify(hostIds);
        
        form.appendChild(csrfToken);
        form.appendChild(actionInput);
        form.appendChild(idsInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
