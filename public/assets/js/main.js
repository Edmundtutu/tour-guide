/**
 * Uganda Tour Guide - Main JavaScript
 * Interactive functionality and user experience enhancements
 */

$(document).ready(function() {
    // Initialize all components
    initializeComponents();
    
    // Setup event listeners
    setupEventListeners();
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize booking flow
    initializeBooking();
    
    // Initialize file uploads
    initializeFileUploads();
    
    // Initialize navigation
    initializeNavigation();
});

/**
 * Initialize all interactive components
 */
function initializeComponents() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-bs-toggle="popover"]').popover();
    
    // Initialize modals
    $('.modal').modal({
        backdrop: 'static',
        keyboard: false
    });
    
    // Initialize date pickers
    initializeDatePickers();
    
    // Initialize image galleries
    initializeImageGalleries();
    
    // Initialize maps
    initializeMaps();
}

/**
 * Setup global event listeners
 */
function setupEventListeners() {
    // Form validation
    $('form').on('submit', function(e) {
        if (!validateForm(this)) {
            e.preventDefault();
            return false;
        }
    });
    
    // AJAX form submissions
    $('form[data-ajax="true"]').on('submit', function(e) {
        e.preventDefault();
        submitFormAjax(this);
    });
    
    // Search functionality
    $('#search-input').on('input', debounce(function() {
        performSearch($(this).val());
    }, 300));
    
    // Filter functionality
    $('.filter-btn').on('click', function() {
        toggleFilter($(this));
    });
    
    // Booking date changes
    $('input[name="check_in"], input[name="check_out"]').on('change', function() {
        updateBookingPrice();
    });
    
    // Room selection
    $('input[name="room_id"]').on('change', function() {
        updateBookingPrice();
    });
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    // Advanced search toggle
    $('#advanced-search-toggle').on('click', function() {
        $('#advanced-search').slideToggle();
    });
    
    // Search filters
    $('.search-filter').on('change', function() {
        performSearch();
    });
    
    // Search suggestions
    $('#location-search').on('input', function() {
        const query = $(this).val();
        if (query.length > 2) {
            getLocationSuggestions(query);
        }
    });
}

/**
 * Perform search with filters
 */
function performSearch(query = null) {
    const searchData = {
        query: query || $('#search-input').val(),
        location: $('#location-filter').val(),
        price_min: $('#price-min').val(),
        price_max: $('#price-max').val(),
        amenities: $('.amenity-filter:checked').map(function() {
            return $(this).val();
        }).get()
    };
    
    showLoading();
    
    $.ajax({
        url: '/api/search',
        method: 'POST',
        data: searchData,
        success: function(response) {
            displaySearchResults(response.results);
            hideLoading();
        },
        error: function() {
            showError('Search failed. Please try again.');
            hideLoading();
        }
    });
}

/**
 * Display search results
 */
function displaySearchResults(results) {
    const container = $('#search-results');
    container.empty();
    
    if (results.length === 0) {
        container.html('<div class="text-center py-5"><h4>No results found</h4><p>Try adjusting your search criteria.</p></div>');
        return;
    }
    
    results.forEach(function(hotel) {
        const hotelCard = createHotelCard(hotel);
        container.append(hotelCard);
    });
    
    // Animate results
    container.find('.hotel-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('fade-in');
    });
}

/**
 * Create hotel card HTML
 */
function createHotelCard(hotel) {
    return `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card hotel-card h-100">
                <img src="${hotel.image_url || '/assets/images/default-hotel.jpg'}" 
                     class="card-img-top" alt="${hotel.name}">
                <div class="price-badge">UGX ${formatPrice(hotel.price_per_night)}/night</div>
                <div class="rating">
                    <i class="fas fa-star"></i> ${hotel.rating || '4.5'}
                </div>
                <div class="card-body">
                    <h5 class="card-title">${hotel.name}</h5>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt text-muted me-1"></i>
                        ${hotel.location}
                    </p>
                    <p class="card-text">${hotel.description.substring(0, 100)}...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="/hotel-details?id=${hotel.id}" class="btn btn-outline-primary btn-sm">
                            View Details
                        </a>
                        <a href="/book?hotel_id=${hotel.id}" class="btn btn-primary btn-sm">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Initialize booking flow
 */
function initializeBooking() {
    // Booking step navigation
    $('.booking-next').on('click', function() {
        const currentStep = $('.booking-step.active');
        const nextStep = currentStep.next('.booking-step');
        
        if (validateBookingStep(currentStep)) {
            currentStep.removeClass('active');
            nextStep.addClass('active');
            updateBookingProgress();
        }
    });
    
    $('.booking-prev').on('click', function() {
        const currentStep = $('.booking-step.active');
        const prevStep = currentStep.prev('.booking-step');
        
        currentStep.removeClass('active');
        prevStep.addClass('active');
        updateBookingProgress();
    });
    
    // Room selection
    $('.room-option').on('click', function() {
        $('.room-option').removeClass('selected');
        $(this).addClass('selected');
        $('input[name="room_id"]').val($(this).data('room-id'));
        updateBookingPrice();
    });
    
    // Date validation
    $('input[name="check_in"]').on('change', function() {
        const checkIn = new Date($(this).val());
        const checkOut = $('input[name="check_out"]');
        const minCheckOut = new Date(checkIn);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        
        checkOut.attr('min', minCheckOut.toISOString().split('T')[0]);
        
        if (new Date(checkOut.val()) <= checkIn) {
            checkOut.val('');
        }
    });
}

/**
 * Update booking price calculation
 */
function updateBookingPrice() {
    const checkIn = $('input[name="check_in"]').val();
    const checkOut = $('input[name="check_out"]').val();
    const roomId = $('input[name="room_id"]:checked').val();
    const guests = $('input[name="guests"]').val() || 1;
    
    if (!checkIn || !checkOut || !roomId) {
        return;
    }
    
    const nights = calculateNights(checkIn, checkOut);
    
    $.ajax({
        url: '/api/calculate-price',
        method: 'POST',
        data: {
            room_id: roomId,
            check_in: checkIn,
            check_out: checkOut,
            guests: guests
        },
        success: function(response) {
            updatePriceDisplay(response.price, nights);
        }
    });
}

/**
 * Initialize file uploads
 */
function initializeFileUploads() {
    // Image upload with preview
    $('input[type="file"][accept*="image"]').on('change', function() {
        const file = this.files[0];
        if (file) {
            previewImage(file, $(this));
        }
    });
    
    // Drag and drop functionality
    $('.upload-area').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('drag-over');
    });
    
    $('.upload-area').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
    });
    
    $('.upload-area').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            handleFileUpload(files[0]);
        }
    });
}

/**
 * Preview uploaded image
 */
function previewImage(file, input) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = input.siblings('.image-preview');
        if (preview.length === 0) {
            input.after('<div class="image-preview mt-2"><img src="" class="img-thumbnail" style="max-width: 200px;"></div>');
        }
        input.siblings('.image-preview').find('img').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
}

/**
 * Initialize image galleries
 */
function initializeImageGalleries() {
    // Lightbox functionality
    $('.gallery-item').on('click', function() {
        const src = $(this).find('img').attr('src');
        const title = $(this).find('img').attr('alt');
        showLightbox(src, title);
    });
    
    // Gallery navigation
    $('.gallery-nav').on('click', function() {
        const direction = $(this).data('direction');
        navigateGallery(direction);
    });
}

/**
 * Show lightbox modal
 */
function showLightbox(src, title) {
    const lightbox = `
        <div class="modal fade" id="imageLightbox" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${src}" class="img-fluid" alt="${title}">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(lightbox);
    $('#imageLightbox').modal('show');
    
    $('#imageLightbox').on('hidden.bs.modal', function() {
        $(this).remove();
    });
}

/**
 * Initialize date pickers
 */
function initializeDatePickers() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    $('input[type="date"]').attr('min', today);
    
    // Initialize date range picker for booking
    if ($('#booking-date-range').length) {
        initializeDateRangePicker();
    }
}

/**
 * Initialize maps
 */
function initializeMaps() {
    if (typeof google !== 'undefined' && $('.map-container').length) {
        $('.map-container').each(function() {
            const lat = parseFloat($(this).data('lat'));
            const lng = parseFloat($(this).data('lng'));
            const title = $(this).data('title');
            
            if (lat && lng) {
                initGoogleMap($(this)[0], lat, lng, title);
            }
        });
    }
}

/**
 * Initialize Google Map
 */
function initGoogleMap(container, lat, lng, title) {
    const map = new google.maps.Map(container, {
        zoom: 15,
        center: { lat: lat, lng: lng },
        mapTypeId: 'roadmap'
    });
    
    const marker = new google.maps.Marker({
        position: { lat: lat, lng: lng },
        map: map,
        title: title
    });
    
    const infoWindow = new google.maps.InfoWindow({
        content: `<h6>${title}</h6>`
    });
    
    marker.addListener('click', function() {
        infoWindow.open(map, marker);
    });
}

/**
 * Form validation
 */
function validateForm(form) {
    let isValid = true;
    const $form = $(form);
    
    // Clear previous errors
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').remove();
    
    // Required field validation
    $form.find('[required]').each(function() {
        if (!$(this).val().trim()) {
            showFieldError($(this), 'This field is required');
            isValid = false;
        }
    });
    
    // Email validation
    $form.find('input[type="email"]').each(function() {
        const email = $(this).val();
        if (email && !isValidEmail(email)) {
            showFieldError($(this), 'Please enter a valid email address');
            isValid = false;
        }
    });
    
    // Password validation
    $form.find('input[name="password"]').each(function() {
        const password = $(this).val();
        if (password && password.length < 6) {
            showFieldError($(this), 'Password must be at least 6 characters');
            isValid = false;
        }
    });
    
    // Confirm password validation
    $form.find('input[name="confirm_password"]').each(function() {
        const password = $form.find('input[name="password"]').val();
        const confirmPassword = $(this).val();
        if (confirmPassword && password !== confirmPassword) {
            showFieldError($(this), 'Passwords do not match');
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Show field error
 */
function showFieldError($field, message) {
    $field.addClass('is-invalid');
    $field.after(`<div class="invalid-feedback">${message}</div>`);
}

/**
 * Submit form via AJAX
 */
function submitFormAjax(form) {
    const $form = $(form);
    const formData = new FormData(form);
    
    showLoading();
    
    $.ajax({
        url: $form.attr('action'),
        method: $form.attr('method') || 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showSuccess(response.message || 'Operation completed successfully');
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1500);
                }
            } else {
                showError(response.message || 'An error occurred');
            }
            hideLoading();
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                displayFormErrors(response.errors);
            } else {
                showError('An error occurred. Please try again.');
            }
            hideLoading();
        }
    });
}

/**
 * Display form errors
 */
function displayFormErrors(errors) {
    Object.keys(errors).forEach(field => {
        const $field = $(`[name="${field}"]`);
        if ($field.length) {
            showFieldError($field, errors[field][0]);
        }
    });
}

/**
 * Utility functions
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function formatPrice(price) {
    return new Intl.NumberFormat('en-UG').format(price);
}

function calculateNights(checkIn, checkOut) {
    const start = new Date(checkIn);
    const end = new Date(checkOut);
    const diffTime = Math.abs(end - start);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

function showLoading() {
    $('body').addClass('loading');
    if ($('.loading-spinner').length === 0) {
        $('body').append('<div class="loading-spinner position-fixed top-50 start-50 translate-middle"><div class="spinner-border text-primary" role="status"></div></div>');
    }
}

function hideLoading() {
    $('body').removeClass('loading');
    $('.loading-spinner').remove();
}

function showSuccess(message) {
    showAlert(message, 'success');
}

function showError(message) {
    showAlert(message, 'danger');
}

function showAlert(message, type) {
    const alert = `
        <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
             style="top: 100px; right: 20px; z-index: 9999;" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('body').append(alert);
    
    setTimeout(() => {
        $('.alert').fadeOut(() => {
            $('.alert').remove();
        });
    }, 5000);
}

/**
 * Initialize Navigation System
 */
function initializeNavigation() {
    // Set active navigation state based on current URL
    setActiveNavigation();
    
    // Handle mobile navigation
    handleMobileNavigation();
    
    // Add navigation animations
    addNavigationAnimations();
    
    // Handle dropdown interactions
    handleDropdownInteractions();
}

/**
 * Set active navigation state
 */
function setActiveNavigation() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href.replace(BASE_URL, ''))) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
    
    // Special handling for dashboard routes
    if (currentPath.includes('/dashboard')) {
        const dashboardLink = document.querySelector('a[href*="/dashboard"]');
        if (dashboardLink) {
            dashboardLink.classList.add('active');
        }
    }
}

/**
 * Handle mobile navigation
 */
function handleMobileNavigation() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navbarToggler.contains(e.target) && !navbarCollapse.contains(e.target)) {
                if (navbarCollapse.classList.contains('show')) {
                    navbarToggler.click();
                }
            }
        });
        
        // Close mobile menu when clicking on a link
        const mobileLinks = navbarCollapse.querySelectorAll('.nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (navbarCollapse.classList.contains('show')) {
                    navbarToggler.click();
                }
            });
        });
    }
}

/**
 * Add navigation animations
 */
function addNavigationAnimations() {
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            link.style.transform = 'translateY(-2px)';
        });
        
        link.addEventListener('mouseleave', () => {
            if (!link.classList.contains('active')) {
                link.style.transform = 'translateY(0)';
            }
        });
    });
}

/**
 * Handle dropdown interactions
 */
function handleDropdownInteractions() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (toggle && menu) {
            // Add smooth show/hide animations
            toggle.addEventListener('show.bs.dropdown', () => {
                menu.style.opacity = '0';
                menu.style.transform = 'translateY(-10px)';
            });
            
            toggle.addEventListener('shown.bs.dropdown', () => {
                menu.style.transition = 'all 0.3s ease';
                menu.style.opacity = '1';
                menu.style.transform = 'translateY(0)';
            });
            
            toggle.addEventListener('hide.bs.dropdown', () => {
                menu.style.transition = 'all 0.2s ease';
                menu.style.opacity = '0';
                menu.style.transform = 'translateY(-10px)';
            });
        }
    });
}

/**
 * Update navigation based on user role
 */
function updateNavigationForRole(role) {
    const navbar = document.querySelector('.navbar-nav');
    if (!navbar) return;
    
    // Add role-specific classes
    navbar.classList.add(`nav-role-${role}`);
    
    // Update active states based on role
    setActiveNavigation();
}

// Export functions for global use
window.TourGuide = {
    showLoading,
    hideLoading,
    showSuccess,
    showError,
    formatPrice,
    validateForm,
    setActiveNavigation,
    updateNavigationForRole
};
