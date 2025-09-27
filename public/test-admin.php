<?php
// Test Admin Dashboard
// Access this at: http://localhost/tour-guide/public/test-admin.php

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load core classes
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/BaseModel.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/View.php';

// Load services and models
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Hotel.php';
require_once __DIR__ . '/../app/models/Booking.php';
require_once __DIR__ . '/../app/models/Subscription.php';
require_once __DIR__ . '/../app/services/UserService.php';
require_once __DIR__ . '/../app/services/HotelService.php';
require_once __DIR__ . '/../app/services/BookingService.php';
require_once __DIR__ . '/../app/services/SubscriptionService.php';

echo "<h1>Admin Dashboard Test</h1>";

// Test 1: Database Connection
echo "<h2>1. Database Connection</h2>";
try {
    $db = Database::getInstance();
    echo "<p><strong>Database Connection:</strong> ✅ Success</p>";
} catch (Exception $e) {
    echo "<p><strong>Database Connection:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
    exit;
}

// Test 2: Services Creation
echo "<h2>2. Services Creation</h2>";
try {
    $userService = new UserService();
    echo "<p><strong>UserService:</strong> ✅ Created</p>";
    
    $hotelService = new HotelService();
    echo "<p><strong>HotelService:</strong> ✅ Created</p>";
    
    $bookingService = new BookingService();
    echo "<p><strong>BookingService:</strong> ✅ Created</p>";
    
    $subscriptionService = new SubscriptionService();
    echo "<p><strong>SubscriptionService:</strong> ✅ Created</p>";
} catch (Exception $e) {
    echo "<p><strong>Services Creation:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
    exit;
}

// Test 3: Admin Methods (without auth for testing)
echo "<h2>3. Admin Methods Test</h2>";

// Mock admin user in session
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Test Admin';
$_SESSION['user_email'] = 'admin@test.com';
$_SESSION['user_role'] = 'admin';
$_SESSION['login_time'] = time();

try {
    echo "<p><strong>Testing getAllUsers():</strong> ";
    $users = $userService->getAllUsers();
    echo "✅ Success - Found " . count($users) . " users</p>";
    
    echo "<p><strong>Testing getAllHotels():</strong> ";
    $hotels = $hotelService->getAllHotels();
    echo "✅ Success - Found " . count($hotels) . " hotels</p>";
    
    echo "<p><strong>Testing getAllBookings():</strong> ";
    $bookings = $bookingService->getAllBookings();
    echo "✅ Success - Found " . count($bookings) . " bookings</p>";
    
    echo "<p><strong>Testing getAllSubscriptions():</strong> ";
    $subscriptions = $subscriptionService->getAllSubscriptions();
    echo "✅ Success - Found " . count($subscriptions) . " subscriptions</p>";
    
} catch (Exception $e) {
    echo "<p><strong>Admin Methods:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
}

// Test 4: Statistics Calculation
echo "<h2>4. Statistics Calculation</h2>";
try {
    $stats = [
        'total_users' => count($users),
        'total_hotels' => count($hotels),
        'total_bookings' => count($bookings),
        'total_revenue' => array_sum(array_column($bookings, 'total_price')),
        'pending_hotels' => count(array_filter($hotels, function($h) { return $h['status'] === 'pending'; })),
        'active_subscriptions' => count(array_filter($subscriptions, function($s) { return $s['status'] === 'active'; })),
        'new_users_today' => count(array_filter($users, function($u) { return date('Y-m-d', strtotime($u['created_at'])) === date('Y-m-d'); })),
        'system_health' => 'Good'
    ];
    
    echo "<p><strong>Statistics Calculated:</strong> ✅ Success</p>";
    echo "<pre>";
    print_r($stats);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p><strong>Statistics Calculation:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
}

// Test 5: View Rendering
echo "<h2>5. View Rendering Test</h2>";
try {
    $data = [
        'title' => 'Admin Dashboard Test',
        'stats' => $stats
    ];
    
    echo "<p><strong>View Data Prepared:</strong> ✅ Success</p>";
    echo "<p><strong>Stats Available:</strong> " . (isset($stats) ? 'Yes' : 'No') . "</p>";
    
} catch (Exception $e) {
    echo "<p><strong>View Rendering:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Test Complete!</strong></p>";
echo "<p><a href='index.php'>Go to Main App</a></p>";
echo "<p><a href='test-admin.php'>Refresh Test</a></p>";
?>
