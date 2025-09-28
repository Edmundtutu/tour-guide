<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php require_once __DIR__ . '/../../app/core/View.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-5 mb-0">
                        <i class="fas fa-crown me-3 text-primary"></i>Choose Your Plan
                    </h1>
                    <a href="<?= BASE_URL ?>/host/subscription" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Subscription
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
            <div class="col-lg-10">
                <form action="<?= BASE_URL ?>/host/subscribe" method="POST" id="subscribe-form">
                    <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                    
                    <div class="row">
                        <!-- Monthly Plan -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-sm h-100 subscription-card" data-plan="monthly">
                                <div class="card-header bg-primary text-white text-center">
                                    <h4 class="mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>Monthly Plan
                                    </h4>
                                </div>
                                <div class="card-body text-center">
                                    <div class="pricing mb-4">
                                        <h2 class="display-4 text-primary mb-0">UGX <?= number_format(MONTHLY_SUBSCRIPTION_FEE) ?></h2>
                                        <p class="text-muted">per month</p>
                                    </div>
                                    
                                    <ul class="list-unstyled mb-4">
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Unlimited hotels
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Unlimited rooms
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Booking management
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Analytics dashboard
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Email support
                                        </li>
                                    </ul>
                                    
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="radio" name="plan" id="plan_monthly" value="monthly" required>
                                        <label class="form-check-label ms-2" for="plan_monthly">
                                            <strong>Choose Monthly</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Annual Plan -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-sm h-100 subscription-card border-warning" data-plan="annual">
                                <div class="card-header bg-warning text-dark text-center position-relative">
                                    <span class="badge bg-danger position-absolute top-0 start-50 translate-middle px-3 py-2">
                                        Save 17%
                                    </span>
                                    <h4 class="mb-0">
                                        <i class="fas fa-calendar-check me-2"></i>Annual Plan
                                    </h4>
                                </div>
                                <div class="card-body text-center">
                                    <div class="pricing mb-4">
                                        <h2 class="display-4 text-warning mb-0">UGX <?= number_format(ANNUAL_SUBSCRIPTION_FEE) ?></h2>
                                        <p class="text-muted">per year</p>
                                        <small class="text-success">
                                            <i class="fas fa-save me-1"></i>
                                            Save UGX <?= number_format((MONTHLY_SUBSCRIPTION_FEE * 12) - ANNUAL_SUBSCRIPTION_FEE) ?> annually
                                        </small>
                                    </div>
                                    
                                    <ul class="list-unstyled mb-4">
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Everything in Monthly
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Priority support
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Advanced analytics
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Custom branding
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            API access
                                        </li>
                                    </ul>
                                    
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="radio" name="plan" id="plan_annual" value="annual" required>
                                        <label class="form-check-label ms-2" for="plan_annual">
                                            <strong>Choose Annual</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="mb-3">
                                        <i class="fas fa-shield-alt me-2"></i>Payment Information
                                    </h5>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Note:</strong> Payment processing will be implemented in the next phase. 
                                        For now, your subscription will be activated immediately.
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a> 
                                            and <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                                        </label>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-credit-card me-2"></i>Subscribe Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms of Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Subscription Terms</h6>
                <p>By subscribing to our service, you agree to pay the subscription fee for the selected plan.</p>
                
                <h6>2. Cancellation Policy</h6>
                <p>You may cancel your subscription at any time. Cancellation will take effect at the end of your current billing period.</p>
                
                <h6>3. Service Availability</h6>
                <p>We strive to maintain 99.9% uptime but cannot guarantee uninterrupted service.</p>
                
                <h6>4. Data Usage</h6>
                <p>We respect your privacy and will not share your data with third parties without consent.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Privacy Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Information Collection</h6>
                <p>We collect information you provide directly to us, such as when you create an account or subscribe to our service.</p>
                
                <h6>2. Use of Information</h6>
                <p>We use the information we collect to provide, maintain, and improve our services.</p>
                
                <h6>3. Information Sharing</h6>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties.</p>
                
                <h6>4. Data Security</h6>
                <p>We implement appropriate security measures to protect your personal information.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Plan selection styling
    const subscriptionCards = document.querySelectorAll('.subscription-card');
    const planInputs = document.querySelectorAll('input[name="plan"]');
    
    planInputs.forEach(input => {
        input.addEventListener('change', function() {
            subscriptionCards.forEach(card => {
                card.classList.remove('border-primary', 'border-warning');
                if (card.dataset.plan === this.value) {
                    card.classList.add('border-primary');
                }
            });
        });
    });
    
    // Form validation
    const form = document.getElementById('subscribe-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateSubscriptionForm()) {
                e.preventDefault();
            }
        });
    }
});

function validateSubscriptionForm() {
    let isValid = true;
    
    // Clear previous errors
    const invalidFields = document.querySelectorAll('.is-invalid');
    invalidFields.forEach(field => field.classList.remove('is-invalid'));
    
    const invalidFeedbacks = document.querySelectorAll('.invalid-feedback');
    invalidFeedbacks.forEach(feedback => feedback.remove());
    
    // Plan validation
    const planInputs = document.querySelectorAll('input[name="plan"]');
    const selectedPlan = Array.from(planInputs).find(input => input.checked);
    if (!selectedPlan) {
        const firstCard = document.querySelector('.subscription-card');
        showFieldError(firstCard, 'Please select a subscription plan');
        isValid = false;
    }
    
    // Terms validation
    const termsField = document.getElementById('terms');
    if (!termsField.checked) {
        showFieldError(termsField, 'You must agree to the terms and conditions');
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
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.subscription-card {
    cursor: pointer;
}

.subscription-card.border-primary {
    border: 2px solid var(--primary-color) !important;
}

.subscription-card.border-warning {
    border: 2px solid var(--warning-color) !important;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
    border: none;
}

.pricing h2 {
    font-weight: 700;
}

.badge {
    font-size: 0.8em;
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
</style>
