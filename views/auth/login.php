<!-- Login Page -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-sign-in-alt me-2"></i>Welcome Back
                        </h3>
                        <p class="mb-0">Sign in to your account</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= View::url('/login') ?>" method="POST" id="login-form">
                            <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       required
                                       value="<?= View::old('email') ?>"
                                       placeholder="Enter your email">
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required
                                           placeholder="Enter your password">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="toggle-password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="remember" 
                                       name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-2">Don't have an account?</p>
                            <a href="<?= View::url('/register') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Login for Demo -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Demo Accounts</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-sm btn-outline-success w-100 mb-2" 
                                        onclick="demoLogin('tourist@demo.com', 'password')">
                                    <i class="fas fa-user me-1"></i>Tourist
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-sm btn-outline-warning w-100 mb-2" 
                                        onclick="demoLogin('host@demo.com', 'password')">
                                    <i class="fas fa-hotel me-1"></i>Host
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-sm btn-outline-danger w-100" 
                                        onclick="demoLogin('admin@demo.com', 'password')">
                                    <i class="fas fa-crown me-1"></i>Admin
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-sm btn-outline-info w-100" 
                                        onclick="demoLogin('guest@demo.com', 'password')">
                                    <i class="fas fa-eye me-1"></i>Guest
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Password toggle
    $('#toggle-password').on('click', function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Form validation
    $('#login-form').on('submit', function(e) {
        if (!validateLoginForm()) {
            e.preventDefault();
        }
    });
});

function validateLoginForm() {
    let isValid = true;
    
    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Email validation
    const email = $('#email').val();
    if (!email || !isValidEmail(email)) {
        showFieldError($('#email'), 'Please enter a valid email address');
        isValid = false;
    }
    
    // Password validation
    const password = $('#password').val();
    if (!password || password.length < 6) {
        showFieldError($('#password'), 'Password must be at least 6 characters');
        isValid = false;
    }
    
    return isValid;
}

function demoLogin(email, password) {
    $('#email').val(email);
    $('#password').val(password);
    $('#login-form').submit();
}

function showFieldError($field, message) {
    $field.addClass('is-invalid');
    $field.after(`<div class="invalid-feedback">${message}</div>`);
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
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

.input-group .btn {
    border-left: none;
}

.demo-accounts {
    font-size: 0.9rem;
}

.demo-accounts .btn {
    font-size: 0.8rem;
    padding: 0.5rem;
}
</style>
