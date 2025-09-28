<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php require_once __DIR__ . '/../../app/core/View.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-5 mb-0">
                        <i class="fas fa-plus-circle me-3 text-primary"></i>Add Room to <?= htmlspecialchars($hotel['name']) ?>
                    </h1>
                    <a href="<?= BASE_URL ?>/host/rooms?hotel_id=<?= htmlspecialchars($hotel['id']) ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Rooms
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

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-bed me-2"></i>Room Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>/host/create-room?hotel_id=<?= htmlspecialchars($hotel['id']) ?>" method="POST" id="create-room-form">
                            <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="room_type" class="form-label">
                                        <i class="fas fa-bed me-1"></i>Room Type *
                                    </label>
                                    <select class="form-select" id="room_type" name="room_type" required>
                                        <option value="">Select room type</option>
                                        <option value="Single" <?= View::old('room_type') === 'Single' ? 'selected' : '' ?>>Single Room</option>
                                        <option value="Double" <?= View::old('room_type') === 'Double' ? 'selected' : '' ?>>Double Room</option>
                                        <option value="Twin" <?= View::old('room_type') === 'Twin' ? 'selected' : '' ?>>Twin Room</option>
                                        <option value="Triple" <?= View::old('room_type') === 'Triple' ? 'selected' : '' ?>>Triple Room</option>
                                        <option value="Family" <?= View::old('room_type') === 'Family' ? 'selected' : '' ?>>Family Room</option>
                                        <option value="Suite" <?= View::old('room_type') === 'Suite' ? 'selected' : '' ?>>Suite</option>
                                        <option value="Deluxe" <?= View::old('room_type') === 'Deluxe' ? 'selected' : '' ?>>Deluxe Room</option>
                                        <option value="Executive" <?= View::old('room_type') === 'Executive' ? 'selected' : '' ?>>Executive Room</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="capacity" class="form-label">
                                        <i class="fas fa-users me-1"></i>Capacity *
                                    </label>
                                    <select class="form-select" id="capacity" name="capacity" required>
                                        <option value="">Select capacity</option>
                                        <option value="1" <?= View::old('capacity') === '1' ? 'selected' : '' ?>>1 Guest</option>
                                        <option value="2" <?= View::old('capacity') === '2' ? 'selected' : '' ?>>2 Guests</option>
                                        <option value="3" <?= View::old('capacity') === '3' ? 'selected' : '' ?>>3 Guests</option>
                                        <option value="4" <?= View::old('capacity') === '4' ? 'selected' : '' ?>>4 Guests</option>
                                        <option value="5" <?= View::old('capacity') === '5' ? 'selected' : '' ?>>5 Guests</option>
                                        <option value="6" <?= View::old('capacity') === '6' ? 'selected' : '' ?>>6+ Guests</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i>Price per Night (UGX) *
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="price" 
                                           name="price" 
                                           min="0" 
                                           step="1000" 
                                           required
                                           value="<?= View::old('price') ?>"
                                           placeholder="Enter price per night">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="availability_status" class="form-label">
                                        <i class="fas fa-check-circle me-1"></i>Availability Status
                                    </label>
                                    <select class="form-select" id="availability_status" name="availability_status">
                                        <option value="available" <?= View::old('availability_status') === 'available' ? 'selected' : '' ?>>Available</option>
                                        <option value="unavailable" <?= View::old('availability_status') === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                                        <option value="maintenance" <?= View::old('availability_status') === 'maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Room Description
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Describe the room features, amenities, etc..."><?= View::old('description') ?></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <a href="<?= BASE_URL ?>/host/rooms?hotel_id=<?= htmlspecialchars($hotel['id']) ?>" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Add Room
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-room-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateRoomForm()) {
                e.preventDefault();
            }
        });
    }
});

function validateRoomForm() {
    let isValid = true;
    
    // Clear previous errors
    const invalidFields = document.querySelectorAll('.is-invalid');
    invalidFields.forEach(field => field.classList.remove('is-invalid'));
    
    const invalidFeedbacks = document.querySelectorAll('.invalid-feedback');
    invalidFeedbacks.forEach(feedback => feedback.remove());
    
    // Room type validation
    const roomTypeField = document.getElementById('room_type');
    const roomType = roomTypeField.value;
    if (!roomType) {
        showFieldError(roomTypeField, 'Please select a room type');
        isValid = false;
    }
    
    // Capacity validation
    const capacityField = document.getElementById('capacity');
    const capacity = capacityField.value;
    if (!capacity) {
        showFieldError(capacityField, 'Please select room capacity');
        isValid = false;
    }
    
    // Price validation
    const priceField = document.getElementById('price');
    const price = parseFloat(priceField.value);
    if (!price || price < 0) {
        showFieldError(priceField, 'Please enter a valid price');
        isValid = false;
    }
    
    return isValid;
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
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

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
