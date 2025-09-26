<?php
// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load core classes
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/BaseModel.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Router.php';

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
    $router->addRoute('GET', '/hotels', 'TouristController', 'hotels');
    $router->addRoute('GET', '/hotel-details', 'TouristController', 'hotelDetails');
    $router->addRoute('GET', '/book', 'TouristController', 'book');
    $router->addRoute('POST', '/book', 'TouristController', 'book');
    $router->addRoute('GET', '/my-bookings', 'TouristController', 'myBookings');
    $router->addRoute('GET', '/destinations', 'TouristController', 'destinations');
    
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
    
    // Admin routes
    $router->addRoute('GET', '/admin/dashboard', 'AdminController', 'dashboard');
    $router->addRoute('GET', '/admin/users', 'AdminController', 'users');
    $router->addRoute('GET', '/admin/hotels', 'AdminController', 'hotels');
    $router->addRoute('POST', '/admin/approve-hotel', 'AdminController', 'approveHotel');
    $router->addRoute('POST', '/admin/block-hotel', 'AdminController', 'blockHotel');
    $router->addRoute('GET', '/admin/subscriptions', 'AdminController', 'subscriptions');
    $router->addRoute('GET', '/admin/bookings', 'AdminController', 'bookings');
    
    // Dispatch the request
    $router->dispatch();
    
} catch (Exception $e) {
    // Simple error handling - in production, log errors and show user-friendly message
    echo "<h1>Application Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    
    // For development only - remove in production
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}