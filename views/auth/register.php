<!-- Registration Page -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>Join Uganda Tour Guide
                        </h3>
                        <p class="mb-0">Create your account and start exploring</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= View::url('/register') ?>" method="POST" id="register-form">
                            <input type="hidden" name="csrf_token" value="<?= View::csrfToken() ?>">
                            
                            <!-- User Type Selection -->
                            <div class="mb-4">
                                <label class="form-label">I want to:</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="role" 
                                                   id="role_tourist" 
                                                   value="tourist" 
                                                   checked>
                                            <label class="form-check-label" for="role_tourist">
                                                <i class="fas fa-user me-1"></i>Explore & Book
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="role" 
                                                   id="role_host" 
                                                   value="host">
                                            <label class="form-check-label" for="role_host">
                                                <i class="fas fa-hotel me-1"></i>List My Hotel
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="role" 
                                                   id="role_admin" 
                                                   value="admin">
                                            <label class="form-check-label" for="role_admin">
                                                <i class="fas fa-crown me-1"></i>Manage System
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-1"></i>Full Name
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="name" 
                                           name="name" 
                                           required
                                           value="<?= View::old('name') ?>"
                                           placeholder="Enter your full name">
                                </div>
                                
                                <div class="col-md-6 mb-3">
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
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Phone Number
                                    </label>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="phone" 
                                           name="phone"
                                           value="<?= View::old('phone') ?>"
                                           placeholder="Enter your phone number">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password" 
                                               name="password" 
                                               required
                                               placeholder="Create a password">
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                id="toggle-password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        Password must be at least 6 characters long
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Confirm Password
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       required
                                       placeholder="Confirm your password">
                            </div>
                            
                            <!-- Host-specific fields -->
                            <div id="host-fields" class="mb-4" style="display: none;">
                                <h5 class="mb-3">Hotel Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="hotel_name" class="form-label">Hotel Name</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="hotel_name" 
                                               name="hotel_name"
                                               placeholder="Enter your hotel name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="hotel_location" class="form-label">Hotel Location</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="hotel_location" 
                                               name="hotel_location"
                                               placeholder="Enter hotel location">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="hotel_description" class="form-label">Hotel Description</label>
                                    <textarea class="form-control" 
                                              id="hotel_description" 
                                              name="hotel_description" 
                                              rows="3"
                                              placeholder="Describe your hotel..."></textarea>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="terms" 
                                       name="terms" 
                                       required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a> 
                                    and <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-2">Already have an account?</p>
                            <a href="<?= View::url('/login') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </a>
                        </div>
                    </div>
                </div>
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
                <h6>1. Acceptance of Terms</h6>
                <p>By using Uganda Tour Guide, you agree to be bound by these terms and conditions.</p>
                
                <h6>2. User Responsibilities</h6>
                <p>Users are responsible for maintaining the confidentiality of their account information.</p>
                
                <h6>3. Prohibited Activities</h6>
                <p>Users may not use the service for illegal activities or to violate any laws.</p>
                
                <h6>4. Service Availability</h6>
                <p>We strive to maintain service availability but cannot guarantee uninterrupted access.</p>
                
                <h6>5. Limitation of Liability</h6>
                <p>Uganda Tour Guide shall not be liable for any indirect, incidental, or consequential damages.</p>
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
                <p>We collect information you provide directly to us, such as when you create an account.</p>
                
                <h6>2. Use of Information</h6>
                <p>We use the information we collect to provide, maintain, and improve our services.</p>
                
                <h6>3. Information Sharing</h6>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties.</p>
                
                <h6>4. Data Security</h6>
                <p>We implement appropriate security measures to protect your personal information.</p>
                
                <h6>5. Your Rights</h6>
                <p>You have the right to access, update, or delete your personal information.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Role selection handler
    $('input[name="role"]').on('change', function() {
        const role = $(this).val();
        if (role === 'host') {
            $('#host-fields').slideDown();
            $('#hotel_name, #hotel_location, #hotel_description').prop('required', true);
        } else {
            $('#host-fields').slideUp();
            $('#hotel_name, #hotel_location, #hotel_description').prop('required', false);
        }
    });
    
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
    $('#register-form').on('submit', function(e) {
        if (!validateRegisterForm()) {
            e.preventDefault();
        }
    });
    
    // Real-time password confirmation
    $('#confirm_password').on('input', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        
        if (confirmPassword && password !== confirmPassword) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Passwords do not match</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
});

function validateRegisterForm() {
    let isValid = true;
    
    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Name validation
    const name = $('#name').val();
    if (!name || name.length < 2) {
        showFieldError($('#name'), 'Name must be at least 2 characters');
        isValid = false;
    }
    
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
    
    // Confirm password validation
    const confirmPassword = $('#confirm_password').val();
    if (!confirmPassword || password !== confirmPassword) {
        showFieldError($('#confirm_password'), 'Passwords do not match');
        isValid = false;
    }
    
    // Terms validation
    if (!$('#terms').is(':checked')) {
        showFieldError($('#terms'), 'You must agree to the terms and conditions');
        isValid = false;
    }
    
    return isValid;
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

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

#host-fields {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
}

.input-group .btn {
    border-left: none;
}

.role-selection .form-check {
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.role-selection .form-check:hover {
    border-color: var(--primary-color);
    background-color: #f8f9fa;
}

.role-selection .form-check-input:checked + .form-check-label {
    color: var(--primary-color);
    font-weight: 600;
}
</style>
