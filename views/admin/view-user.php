<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php require_once __DIR__ . '/../../app/core/View.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-5 mb-0">
                        <i class="fas fa-user me-3 text-primary"></i>User Details
                    </h1>
                    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
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
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>User Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="<?= $user['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=007bff&color=fff' ?>"
                                     alt="<?= htmlspecialchars($user['name']) ?>"
                                     class="rounded-circle mb-3"
                                     width="100"
                                     height="100">
                                <h5><?= htmlspecialchars($user['name']) ?></h5>
                                <span class="badge user-role-<?= $user['role'] ?> fs-6">
                                    <i class="fas fa-<?= $user['role'] === 'admin' ? 'crown' : ($user['role'] === 'host' ? 'hotel' : 'user') ?> me-1"></i>
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-primary mb-3">Contact Information</h6>
                                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></p>

                                <h6 class="text-primary mb-3 mt-4">Account Information</h6>
                                <p><strong>Status:</strong> 
                                    <span class="badge user-status-<?= $user['status'] ?? 'active' ?>">
                                        <?= ucfirst($user['status'] ?? 'active') ?>
                                    </span>
                                </p>
                                <p><strong>Joined:</strong> <?= date('M j, Y g:i A', strtotime($user['created_at'])) ?></p>
                                <p><strong>Last Active:</strong> <?= $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= BASE_URL ?>/admin/edit-user?user_id=<?= $user['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Edit User
                            </a>
                            
                            <?php if (($user['status'] ?? 'active') === 'active'): ?>
                            <button class="btn btn-warning" onclick="blockUser(<?= $user['id'] ?>)">
                                <i class="fas fa-ban me-2"></i>Block User
                            </button>
                            <?php else: ?>
                            <button class="btn btn-success" onclick="unblockUser(<?= $user['id'] ?>)">
                                <i class="fas fa-check me-2"></i>Unblock User
                            </button>
                            <?php endif; ?>
                            
                            <button class="btn btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">
                                <i class="fas fa-trash me-2"></i>Delete User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function blockUser(userId) {
    if (confirm('Are you sure you want to block this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/block-user';
        
        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user_id';
        userIdInput.value = userId;
        
        form.appendChild(userIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function unblockUser(userId) {
    if (confirm('Are you sure you want to unblock this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/unblock-user';
        
        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user_id';
        userIdInput.value = userId;
        
        form.appendChild(userIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/delete-user';
        
        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user_id';
        userIdInput.value = userId;
        
        form.appendChild(userIdInput);
        document.body.appendChild(form);
        form.submit();
    }
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
</style>
