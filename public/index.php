<?php
// Load configuration
require_once __DIR__ . '/../config/config.php';

// Start session early
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load core classes
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/BaseModel.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/View.php';
    
// Load controllers
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/TouristController.php';
require_once __DIR__ . '/../app/controllers/HostController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

try {
    $router = new Router();
    
    // Authentication routes
    $router->addRoute('GET', '/login', 'AuthController', 'login');
    $router->addRoute('POST', '/login', 'AuthController', 'login');
    $router->addRoute('GET', '/register', 'AuthController', 'register');
    $router->addRoute('POST', '/register', 'AuthController', 'register');
    $router->addRoute('GET', '/logout', 'AuthController', 'logout');
    
    // Tourist routes
    $router->addRoute('GET', '/', 'TouristController', 'home');
    $router->addRoute('GET', '/home', 'TouristController', 'home');
    $router->addRoute('GET', '/hotels', 'TouristController', 'hotels');
    $router->addRoute('GET', '/hotel-details', 'TouristController', 'hotelDetails');
    $router->addRoute('GET', '/book', 'TouristController', 'book');
    $router->addRoute('POST', '/book', 'TouristController', 'book');
    $router->addRoute('GET', '/my-bookings', 'TouristController', 'myBookings');
    $router->addRoute('GET', '/destinations', 'TouristController', 'destinations');
    $router->addRoute('GET', '/itinerary', 'TouristController', 'itinerary');
    $router->addRoute('POST', '/itinerary/create', 'TouristController', 'createItinerary');
    $router->addRoute('POST', '/itinerary/add-destination', 'TouristController', 'addDestinationToItinerary');
    
    // API routes for AJAX
    $router->addRoute('POST', '/api/search', 'TouristController', 'apiSearch');
    $router->addRoute('POST', '/api/calculate-price', 'TouristController', 'apiCalculatePrice');
    $router->addRoute('POST', '/api/cancel-booking', 'TouristController', 'apiCancelBooking');
    $router->addRoute('GET', '/api/download-receipt', 'TouristController', 'apiDownloadReceipt');
    
    // Host routes
    $router->addRoute('GET', '/host/dashboard', 'HostController', 'dashboard');
    $router->addRoute('GET', '/host/hotels', 'HostController', 'hotels');
    $router->addRoute('GET', '/host/create-hotel', 'HostController', 'createHotel');
    $router->addRoute('POST', '/host/create-hotel', 'HostController', 'createHotel');
    $router->addRoute('GET', '/host/edit-hotel', 'HostController', 'editHotel');
    $router->addRoute('POST', '/host/edit-hotel', 'HostController', 'editHotel');
    $router->addRoute('GET', '/host/rooms', 'HostController', 'rooms');
    $router->addRoute('GET', '/host/create-room', 'HostController', 'createRoom');
    $router->addRoute('POST', '/host/create-room', 'HostController', 'createRoom');
    $router->addRoute('GET', '/host/bookings', 'HostController', 'bookings');
    $router->addRoute('POST', '/host/approve-booking', 'HostController', 'approveBooking');
    $router->addRoute('POST', '/host/reject-booking', 'HostController', 'rejectBooking');
    $router->addRoute('GET', '/host/subscription', 'HostController', 'subscription');
    $router->addRoute('GET', '/host/subscribe', 'HostController', 'subscribe');
    $router->addRoute('POST', '/host/subscribe', 'HostController', 'subscribe');
    $router->addRoute('GET', '/host/profile', 'HostController', 'profile');
    $router->addRoute('POST', '/host/profile/update', 'HostController', 'updateProfile');
    $router->addRoute('POST', '/host/profile/change-password', 'HostController', 'changePassword');
    $router->addRoute('GET', '/host/calendar', 'HostController', 'calendar');
    
    // Admin routes
    $router->addRoute('GET', '/admin/dashboard', 'AdminController', 'dashboard');
    $router->addRoute('GET', '/admin/users', 'AdminController', 'users');
    $router->addRoute('GET', '/admin/hotels', 'AdminController', 'hotels');
    $router->addRoute('POST', '/admin/approve-hotel', 'AdminController', 'approveHotel');
    $router->addRoute('POST', '/admin/block-hotel', 'AdminController', 'blockHotel');
    $router->addRoute('GET', '/admin/subscriptions', 'AdminController', 'subscriptions');
    $router->addRoute('GET', '/admin/bookings', 'AdminController', 'bookings');
    $router->addRoute('GET', '/admin/reports', 'AdminController', 'reports');
    $router->addRoute('GET', '/admin/hosts', 'AdminController', 'hosts');
    
    // Dispatch the request
    $router->dispatch();
    
} catch (Exception $e) {
    // Log the error (in production, use proper logging)
    error_log("Tour Guide Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    
    // Check if this is an AJAX request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred. Please try again.',
            'error' => $e->getMessage()
        ]);
        exit;
    }
    
    // Show user-friendly error page
    $data = [
        'title' => 'Application Error',
        'error_message' => $e->getMessage(),
        'show_details' => isset($_GET['debug']) && $_GET['debug'] === 'true'
    ];
    
    // Try to render error page, fallback to simple HTML if View class fails
    try {
        echo View::renderWithLayout('error', $data);
    } catch (Exception $viewError) {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Application Error</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .error { background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; }
                .details { background: #f8f9fa; padding: 15px; margin-top: 20px; border-radius: 5px; }
            </style>
        </head>
        <body>
            <h1>Application Error</h1>
            <div class='error'>
                <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            </div>";
        
        if (isset($_GET['debug']) && $_GET['debug'] === 'true') {
            echo "<div class='details'>
                <h3>Debug Information:</h3>
                <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
            </div>";
        }
        
        echo "<p><a href='" . BASE_URL . "'>Go Home</a></p>
        </body>
        </html>";
    }
}