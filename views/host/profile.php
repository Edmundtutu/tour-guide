<?php
$title = 'My Profile';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">My Profile</h1>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-avatar">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" 
                                     alt="Profile Picture" class="rounded-circle img-fluid" style="width: 120px; height: 120px; object-fit: cover;">
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changeAvatarModal">
                                        <i class="fas fa-camera"></i> Change Photo
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="profile-details">
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Full Name:</strong></div>
                                    <div class="col-sm-8"><?= htmlspecialchars($user['name'] ?? 'Not set') ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8"><?= htmlspecialchars($user['email'] ?? 'Not set') ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Phone:</strong></div>
                                    <div class="col-sm-8"><?= htmlspecialchars($user['phone'] ?? 'Not set') ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Role:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-success"><?= ucfirst($user['role'] ?? 'host') ?></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Status:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-<?= ($user['status'] ?? 'active') === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($user['status'] ?? 'active') ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Member Since:</strong></div>
                                    <div class="col-sm-8"><?= date('F j, Y', strtotime($user['created_at'] ?? 'now')) ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Last Login:</strong></div>
                                    <div class="col-sm-8"><?= $user['last_login'] ? date('F j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building"></i> Business Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-5"><strong>Business Name:</strong></div>
                                <div class="col-sm-7"><?= htmlspecialchars($user['business_name'] ?? 'Not set') ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-5"><strong>Business Type:</strong></div>
                                <div class="col-sm-7"><?= htmlspecialchars($user['business_type'] ?? 'Not set') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-5"><strong>License Number:</strong></div>
                                <div class="col-sm-7"><?= htmlspecialchars($user['license_number'] ?? 'Not set') ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-5"><strong>Tax ID:</strong></div>
                                <div class="col-sm-7"><?= htmlspecialchars($user['tax_id'] ?? 'Not set') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Subscription Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-crown"></i> Subscription Status
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($subscription) && $subscription): ?>
                        <div class="text-center">
                            <div class="subscription-status mb-3">
                                <span class="badge bg-<?= $subscription['status'] === 'active' ? 'success' : 'danger' ?> fs-6">
                                    <?= ucfirst($subscription['status']) ?>
                                </span>
                            </div>
                            <p class="mb-2"><strong>Plan:</strong> <?= ucfirst($subscription['plan']) ?></p>
                            <p class="mb-2"><strong>Amount:</strong> UGX <?= number_format($subscription['amount']) ?></p>
                            <p class="mb-2"><strong>Start Date:</strong> <?= date('M j, Y', strtotime($subscription['start_date'])) ?></p>
                            <p class="mb-0"><strong>End Date:</strong> <?= date('M j, Y', strtotime($subscription['end_date'])) ?></p>
                            
                            <?php if ($subscription['status'] === 'active'): ?>
                                <div class="mt-3">
                                    <div class="progress" style="height: 8px;">
                                        <?php
                                        $start = strtotime($subscription['start_date']);
                                        $end = strtotime($subscription['end_date']);
                                        $now = time();
                                        $progress = min(100, max(0, (($now - $start) / ($end - $start)) * 100));
                                        ?>
                                        <div class="progress-bar bg-success" style="width: <?= $progress ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?= round($progress) ?>% used</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-muted mb-3">No active subscription</p>
                            <a href="/host/subscription" class="btn btn-warning btn-sm">
                                <i class="fas fa-crown"></i> Subscribe Now
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h4 class="text-primary mb-1"><?= $stats['total_hotels'] ?? 0 ?></h4>
                                <small class="text-muted">Hotels</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h4 class="text-success mb-1"><?= $stats['total_bookings'] ?? 0 ?></h4>
                                <small class="text-muted">Bookings</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-warning mb-1"><?= $stats['pending_bookings'] ?? 0 ?></h4>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h4 class="text-info mb-1">UGX <?= number_format($stats['total_revenue'] ?? 0) ?></h4>
                                <small class="text-muted">Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-clock"></i> Recent Activity
                    </h6>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        <?php if (isset($recent_activity) && !empty($recent_activity)): ?>
                            <?php foreach ($recent_activity as $activity): ?>
                                <div class="activity-item d-flex align-items-start mb-3">
                                    <div class="activity-icon me-3">
                                        <i class="fas fa-<?= $activity['icon'] ?? 'circle' ?> text-<?= $activity['color'] ?? 'primary' ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p class="mb-1 small"><?= htmlspecialchars($activity['description']) ?></p>
                                        <small class="text-muted"><?= $activity['time'] ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">No recent activity</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/host/profile/update" method="POST" id="editProfileForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" 
                                       value="<?= htmlspecialchars($user['business_name'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="business_type" class="form-label">Business Type</label>
                                <select class="form-select" id="business_type" name="business_type">
                                    <option value="">Select Type</option>
                                    <option value="hotel" <?= ($user['business_type'] ?? '') === 'hotel' ? 'selected' : '' ?>>Hotel</option>
                                    <option value="lodge" <?= ($user['business_type'] ?? '') === 'lodge' ? 'selected' : '' ?>>Lodge</option>
                                    <option value="resort" <?= ($user['business_type'] ?? '') === 'resort' ? 'selected' : '' ?>>Resort</option>
                                    <option value="guesthouse" <?= ($user['business_type'] ?? '') === 'guesthouse' ? 'selected' : '' ?>>Guesthouse</option>
                                    <option value="hostel" <?= ($user['business_type'] ?? '') === 'hostel' ? 'selected' : '' ?>>Hostel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="license_number" class="form-label">License Number</label>
                                <input type="text" class="form-control" id="license_number" name="license_number" 
                                       value="<?= htmlspecialchars($user['license_number'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tax_id" class="form-label">Tax ID</label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                       value="<?= htmlspecialchars($user['tax_id'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/host/profile/change-password" method="POST" id="changePasswordForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password *</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="form-text">Password must be at least 8 characters long.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password *</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Avatar Modal -->
<div class="modal fade" id="changeAvatarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/host/profile/change-avatar" method="POST" enctype="multipart/form-data" id="changeAvatarForm">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                    
                    <div class="text-center mb-3">
                        <img id="avatarPreview" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" 
                             alt="Preview" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Choose New Profile Picture</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                        <div class="form-text">Supported formats: JPG, PNG, GIF. Max size: 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Picture</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Form validation
    const changePasswordForm = document.getElementById('changePasswordForm');
    changePasswordForm.addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New passwords do not match!');
            return false;
        }
        
        if (newPassword.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long!');
            return false;
        }
    });
});
</script>
