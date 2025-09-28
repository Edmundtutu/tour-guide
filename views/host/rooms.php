<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php require_once __DIR__ . '/../../app/core/View.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-5 mb-0">
                        <i class="fas fa-bed me-3 text-primary"></i>Rooms - <?= htmlspecialchars($hotel['name']) ?>
                    </h1>
                    <div>
                        <a href="<?= BASE_URL ?>/host/create-room?hotel_id=<?= htmlspecialchars($hotel['id']) ?>" class="btn btn-primary me-2">
                            <i class="fas fa-plus me-2"></i>Add Room
                        </a>
                        <a href="<?= BASE_URL ?>/host/hotels" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Hotels
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hotel Info Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1"><?= htmlspecialchars($hotel['name']) ?></h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($hotel['location']) ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-<?= $hotel['status'] === 'approved' ? 'success' : ($hotel['status'] === 'pending' ? 'warning' : 'danger') ?> fs-6">
                            <?= ucfirst($hotel['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rooms List -->
        <?php if (empty($rooms)): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Rooms Added Yet</h4>
                <p class="text-muted">Start by adding your first room to this hotel.</p>
                <a href="<?= BASE_URL ?>/host/create-room?hotel_id=<?= htmlspecialchars($hotel['id']) ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Your First Room
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($rooms as $room): ?>
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($room['room_type']) ?></h5>
                            <span class="badge bg-<?= $room['availability_status'] === 'available' ? 'success' : 'danger' ?>">
                                <?= ucfirst($room['availability_status']) ?>
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-dollar-sign text-primary me-2"></i>
                                <span class="fw-bold">UGX <?= number_format($room['price']) ?>/night</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-users text-primary me-2"></i>
                                <span><?= $room['capacity'] ?> <?= $room['capacity'] == 1 ? 'guest' : 'guests' ?></span>
                            </div>
                        </div>
                        
                        <?php if (!empty($room['description'])): ?>
                        <p class="card-text text-muted small"><?= htmlspecialchars($room['description']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Added <?= date('M j, Y', strtotime($room['created_at'])) ?>
                            </small>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editRoom(<?= $room['id'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteRoom(<?= $room['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
function editRoom(roomId) {
    // TODO: Implement room editing
    alert('Room editing functionality will be implemented soon!');
}

function deleteRoom(roomId) {
    if (confirm('Are you sure you want to delete this room? This action cannot be undone.')) {
        // TODO: Implement room deletion
        alert('Room deletion functionality will be implemented soon!');
    }
}
</script>

<style>
.card {
    border: none;
    border-radius: 1rem;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.card-footer {
    border-top: 1px solid rgba(0,0,0,.125);
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
