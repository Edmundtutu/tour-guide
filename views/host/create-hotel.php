<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php require_once __DIR__ . '/../../app/core/View.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-5 mb-0">
                        <i class="fas fa-plus-circle me-3 text-primary"></i>Create New Hotel
                    </h1>
                    <a href="<?= BASE_URL ?>/host/hotels" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Hotels
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
                            <i class="fas fa-hotel me-2"></i>Hotel Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>/host/create-hotel" method="POST" id="create-hotel-form">
                            <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-hotel me-1"></i>Hotel Name *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="name" 
                                           name="name" 
                                           required
                                           value="<?= View::old('name') ?>"
                                           placeholder="Enter hotel name">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>Location *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="location" 
                                           name="location" 
                                           required
                                           value="<?= View::old('location') ?>"
                                           placeholder="Enter hotel location">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Description
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Describe your hotel..."><?= View::old('description') ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price_per_night" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i>Price per Night (UGX) *
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="price_per_night" 
                                           name="price_per_night" 
                                           min="0" 
                                           step="1000" 
                                           required
                                           value="<?= View::old('price_per_night') ?>"
                                           placeholder="Enter price per night">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="image_url" class="form-label">
                                        <i class="fas fa-image me-1"></i>Image URL
                                    </label>
                                    <input type="url" 
                                           class="form-control" 
                                           id="image_url" 
                                           name="image_url"
                                           value="<?= View::old('image_url') ?>"
                                           placeholder="https://example.com/image.jpg">
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <a href="<?= BASE_URL ?>/host/hotels" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Hotel
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
    const form = document.getElementById('create-hotel-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateHotelForm()) {
                e.preventDefault();
            }
        });
    }
});

function validateHotelForm() {
    let isValid = true;
    
    // Clear previous errors
    const invalidFields = document.querySelectorAll('.is-invalid');
    invalidFields.forEach(field => field.classList.remove('is-invalid'));
    
    const invalidFeedbacks = document.querySelectorAll('.invalid-feedback');
    invalidFeedbacks.forEach(feedback => feedback.remove());
    
    // Name validation
    const nameField = document.getElementById('name');
    const name = nameField.value.trim();
    if (!name || name.length < 2) {
        showFieldError(nameField, 'Hotel name must be at least 2 characters');
        isValid = false;
    }
    
    // Location validation
    const locationField = document.getElementById('location');
    const location = locationField.value.trim();
    if (!location || location.length < 2) {
        showFieldError(locationField, 'Location must be at least 2 characters');
        isValid = false;
    }
    
    // Price validation
    const priceField = document.getElementById('price_per_night');
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

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
