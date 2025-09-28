<!-- Admin Users Management -->
<section class="py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-5 mb-2">
                            <i class="fas fa-users me-3 text-primary"></i>User Management
                        </h1>
                        <p class="text-muted">Manage system users and their permissions</p>
                    </div>
                    <div class="user-actions">
                        <button class="btn btn-outline-primary" onclick="exportUsers()">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3" id="user-filters">
                            <div class="col-md-3">
                                <label for="role_filter" class="form-label">Role</label>
                                <select class="form-select" id="role_filter" name="role">
                                    <option value="">All Roles</option>
                                    <option value="tourist">Tourist</option>
                                    <option value="host">Host</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status_filter" class="form-label">Status</label>
                                <select class="form-select" id="status_filter" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="search_query" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search_query" name="search" placeholder="Search users...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary" onclick="filterUsers()">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Users List
                        </h5>
                        <div class="user-stats">
                            <span class="badge bg-primary me-2">Total: <?php echo count($users)?></span>
                            <span class="badge bg-success me-2">Active: <?php echo $stats['active'] ?? 0?></span>
                            <span class="badge bg-warning">Blocked: <?php echo $stats['blocked'] ?? 0?></span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (! empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Last Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr class="user-row" data-status="<?php echo $user['status']?>">
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                <img src="<?php echo $user['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? 'Unknown') . '&background=007bff&color=fff'?>"
                                                    alt="<?php echo htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES)?>"
                                                    class="rounded-circle"
                                                    width="40"
                                                    height="40">

                                                </div>
                                                <div class="user-details">
                                                    <strong><?php echo htmlspecialchars($user['name'])?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope me-1"></i>
                                                        <?php echo htmlspecialchars($user['email'])?>
                                                    </small>
                                                    <?php if ($user['phone']): ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone me-1"></i>
                                                        <?php echo htmlspecialchars($user['phone'])?>
                                                    </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge user-role-<?php echo $user['role']?>">
                                                <i class="fas fa-<?php echo $user['role'] === 'admin' ? 'crown' : ($user['role'] === 'host' ? 'hotel' : 'user')?> me-1"></i>
                                                <?php echo ucfirst($user['role'])?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge user-status-<?php echo $user['status']?>">
                                                <?php echo ucfirst($user['status'])?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?php echo date('M j, Y', strtotime($user['created_at']))?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo date('g:i A', strtotime($user['created_at']))?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo date('M j, Y', strtotime($user['last_login'] ?? $user['created_at']))?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo date('g:i A', strtotime($user['last_login'] ?? $user['created_at']))?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary"
                                                        onclick="viewUser(<?php echo $user['id']?>)"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-warning"
                                                        onclick="editUser(<?php echo $user['id']?>)"
                                                        title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <?php if ($user['status'] === 'active'): ?>
                                                <button class="btn btn-outline-danger"
                                                        onclick="blockUser(<?php echo $user['id']?>)"
                                                        title="Block User">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                                <?php else: ?>
                                                <button class="btn btn-outline-success"
                                                        onclick="unblockUser(<?php echo $user['id']?>)"
                                                        title="Unblock User">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <?php endif; ?>
                                                <button class="btn btn-outline-danger"
                                                        onclick="deleteUser(<?php echo $user['id']?>)"
                                                        title="Delete User">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-4"></i>
                            <h3>No Users Found</h3>
                            <p class="text-muted">No users match your current filters.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="user-details">
                <!-- User details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="edit-user-form">
                    <input type="hidden" id="edit_user_id" name="user_id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone">
                    </div>

                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="tourist">Tourist</option>
                            <option value="host">Host</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
// Pure vanilla JavaScript - no jQuery dependency
document.addEventListener('DOMContentLoaded', function() {
    initializeUserManagement();
});

function initializeUserManagement() {
    // Add hover effects to user rows
    const userRows = document.querySelectorAll('.user-row');
    userRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('table-active');
        });
        row.addEventListener('mouseleave', function() {
            this.classList.remove('table-active');
        });
    });
}

function filterUsers() {
    const role = document.getElementById('role_filter').value;
    const status = document.getElementById('status_filter').value;
    const search = document.getElementById('search_query').value;

    const userRows = document.querySelectorAll('.user-row');
    userRows.forEach(row => {
        let show = true;

        if (role && !row.querySelector('.user-role-' + role)) {
            show = false;
        }

        if (status && row.dataset.status !== status) {
            show = false;
        }

        if (search && !row.textContent.toLowerCase().includes(search.toLowerCase())) {
            show = false;
        }

        row.style.display = show ? '' : 'none';
    });
}

function viewUser(userId) {
    // Use fetch to get user details and show in modal
    fetch('<?= BASE_URL ?>/admin/get-user?user_id=' + userId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayUserDetails(data.user);
                showModal('userModal');
            } else {
                showError(data.message || 'Failed to load user details');
            }
        })
        .catch(error => {
            showError('Failed to load user details. Please try again.');
        });
}

function editUser(userId) {
    // Use fetch to get user details and show in edit modal
    fetch('<?= BASE_URL ?>/admin/get-user?user_id=' + userId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateEditForm(data.user);
                showModal('editUserModal');
            } else {
                showError(data.message || 'Failed to load user data');
            }
        })
        .catch(error => {
            showError('Failed to load user data. Please try again.');
        });
}

function blockUser(userId) {
    if (confirm('Are you sure you want to block this user?')) {
        const formData = new FormData();
        formData.append('user_id', userId);
        
        fetch('<?= BASE_URL ?>/admin/block-user', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('User blocked successfully');
                location.reload();
            } else {
                showError(data.message || 'Failed to block user');
            }
        })
        .catch(error => {
            showError('Failed to block user. Please try again.');
        });
    }
}

function unblockUser(userId) {
    if (confirm('Are you sure you want to unblock this user?')) {
        const formData = new FormData();
        formData.append('user_id', userId);
        
        fetch('<?= BASE_URL ?>/admin/unblock-user', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('User unblocked successfully');
                location.reload();
            } else {
                showError(data.message || 'Failed to unblock user');
            }
        })
        .catch(error => {
            showError('Failed to unblock user. Please try again.');
        });
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('user_id', userId);
        
        fetch('<?= BASE_URL ?>/admin/delete-user', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('User deleted successfully');
                location.reload();
            } else {
                showError(data.message || 'Failed to delete user');
            }
        })
        .catch(error => {
            showError('Failed to delete user. Please try again.');
        });
    }
}

function exportUsers() {
    window.open('<?= BASE_URL ?>/admin/export-users', '_blank');
}

function displayUserDetails(user) {
    const details = `
        <div class="user-details-view">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center">
                        <img src="${user.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&background=007bff&color=fff'}"
                             alt="${user.name}"
                             class="rounded-circle mb-3"
                             width="100"
                             height="100">
                        <h5>${user.name}</h5>
                        <span class="badge user-role-${user.role}">${user.role}</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <h6>Contact Information</h6>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Phone:</strong> ${user.phone || 'Not provided'}</p>

                    <h6>Account Information</h6>
                    <p><strong>Status:</strong> <span class="badge user-status-${user.status}">${user.status}</span></p>
                    <p><strong>Joined:</strong> ${user.created_at}</p>
                    <p><strong>Last Active:</strong> ${user.last_login || 'Never'}</p>
                </div>
            </div>
        </div>
    `;

    document.getElementById('user-details').innerHTML = details;
}

function populateEditForm(user) {
    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_phone').value = user.phone || '';
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_status').value = user.status;
}

function saveUser() {
    const formData = new FormData(document.getElementById('edit-user-form'));

    fetch('<?= BASE_URL ?>/admin/update-user', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('User updated successfully');
            hideModal('editUserModal');
            location.reload();
        } else {
            showError(data.message || 'Failed to update user');
        }
    })
    .catch(error => {
        showError('Failed to update user. Please try again.');
    });
}

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        const bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) {
            bsModal.hide();
        }
    }
}

function showSuccess(message) {
    showAlert(message, 'success');
}

function showError(message) {
    showAlert(message, 'danger');
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

<style>
.user-info {
    display: flex;
    align-items: center;
}

.user-avatar {
    margin-right: 1rem;
}

.user-details {
    flex: 1;
}

.user-role-admin {
    background-color: var(--danger-color);
    color: white;
}

.user-role-host {
    background-color: var(--warning-color);
    color: var(--dark-color);
}

.user-role-tourist {
    background-color: var(--info-color);
    color: white;
}

.user-status-active {
    background-color: var(--success-color);
    color: white;
}

.user-status-inactive {
    background-color: var(--secondary-color);
    color: white;
}

.user-status-blocked {
    background-color: var(--danger-color);
    color: white;
}

.user-details-view {
    font-size: 0.9rem;
}

.user-details-view h6 {
    color: var(--primary-color);
    margin-top: 1rem;
    margin-bottom: 0.5rem;
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

.user-stats .badge {
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .user-actions {
        margin-top: 1rem;
    }

    .table-responsive {
        font-size: 0.9rem;
    }

    .user-info {
        flex-direction: column;
        align-items: flex-start;
    }

    .user-avatar {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
}
</style>
