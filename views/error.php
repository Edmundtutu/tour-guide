<!-- Error Page -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-4"></i>
                    <h1 class="display-4 text-danger mb-4">Oops!</h1>
                    <h2 class="mb-4">Something went wrong</h2>
                    <p class="lead text-muted mb-4">
                        <?= htmlspecialchars($error_message ?? 'An unexpected error occurred.') ?>
                    </p>
                    <div class="error-actions">
                        <a href="<?= View::url('/') ?>" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Go Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Help Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">Need Help?</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-question-circle fa-3x text-primary mb-3"></i>
                        <h5>FAQ</h5>
                        <p class="text-muted">Find answers to common questions</p>
                        <a href="#" class="btn btn-outline-primary">View FAQ</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <h5>Contact Support</h5>
                        <p class="text-muted">Get help from our support team</p>
                        <a href="mailto:support@ugandatourguide.com" class="btn btn-outline-primary">Contact Us</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                        <h5>Call Us</h5>
                        <p class="text-muted">Speak with our team directly</p>
                        <a href="tel:+256700000000" class="btn btn-outline-primary">+256 700 000 000</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
