<?php
require_once __DIR__ . '/../services/HotelService.php';
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../services/SubscriptionService.php';

class HostController {
    private $hotelService;
    private $bookingService;
    private $subscriptionService;
    
    public function __construct() {
        $this->hotelService = new HotelService();
        $this->bookingService = new BookingService();
        $this->subscriptionService = new SubscriptionService();
    }
    
    public function dashboard() {
        try {
            Auth::requireRole('host');
            
            $user = Auth::getCurrentUser();
            $hotels = $this->hotelService->getMyHotels();
            $recent_bookings = $this->bookingService->getMyBookings();
            $subscription = $this->subscriptionService->getMySubscription();
            
            // Calculate statistics
            $stats = [
                'total_hotels' => count($hotels),
                'total_bookings' => count($recent_bookings),
                'pending_bookings' => count(array_filter($recent_bookings, function($b) { return $b['status'] === 'pending'; })),
                'total_revenue' => array_sum(array_column($recent_bookings, 'total_price'))
            ];
            
            $data = [
                'title' => 'Host Dashboard',
                'user' => $user,
                'hotels' => $hotels,
                'recent_bookings' => array_slice($recent_bookings, 0, 5),
                'subscription' => $subscription,
                'stats' => $stats
            ];
            
            echo View::renderWithLayout('host/dashboard', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function hotels() {
        try {
            Auth::requireRole('host');
            $hotels = $this->hotelService->getMyHotels();
            
            $data = [
                'title' => 'My Hotels',
                'hotels' => $hotels
            ];
            
            echo View::renderWithLayout('host/hotels', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function createHotel() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'location' => $_POST['location'] ?? '',
                    'price_per_night' => $_POST['price_per_night'] ?? 0,
                    'image_url' => $_POST['image_url'] ?? ''
                ];
                
                $hotelId = $this->hotelService->createHotel($data);
                
                header('Location: ' . BASE_URL . '/host/hotels');
                exit;
            } catch (Exception $e) {
                $this->renderCreateHotelForm(['error' => $e->getMessage()]);
            }
        } else {
            $this->renderCreateHotelForm();
        }
    }
    
    public function editHotel() {
        $hotelId = $_GET['id'] ?? null;
        if (!$hotelId) {
            $this->renderError("Hotel ID required");
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'location' => $_POST['location'] ?? '',
                    'price_per_night' => $_POST['price_per_night'] ?? 0,
                    'image_url' => $_POST['image_url'] ?? ''
                ];
                
                $this->hotelService->updateHotel($hotelId, $data);
                
                header('Location: ' . BASE_URL . '/host/hotels');
                exit;
            } catch (Exception $e) {
                $hotel = $this->hotelService->getHotelById($hotelId);
                $this->renderEditHotelForm(['hotel' => $hotel, 'error' => $e->getMessage()]);
            }
        } else {
            try {
                $hotel = $this->hotelService->getHotelById($hotelId);
                $this->renderEditHotelForm(['hotel' => $hotel]);
            } catch (Exception $e) {
                $this->renderError($e->getMessage());
            }
        }
    }
    
    public function rooms() {
        try {
            Auth::requireRole('host');
            $hotelId = $_GET['hotel_id'] ?? null;
            
            if (!$hotelId) {
                $this->renderError("Hotel ID required");
                return;
            }
            
            $hotel = $this->hotelService->getHotelById($hotelId);
            $rooms = $this->hotelService->getHotelRooms($hotelId);
            
            $this->renderRooms(['hotel' => $hotel, 'rooms' => $rooms]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function createRoom() {
        $hotelId = $_GET['hotel_id'] ?? null;
        if (!$hotelId) {
            $this->renderError("Hotel ID required");
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'hotel_id' => $hotelId,
                    'room_type' => $_POST['room_type'] ?? '',
                    'price' => $_POST['price'] ?? 0,
                    'capacity' => $_POST['capacity'] ?? 1
                ];
                
                $roomId = $this->hotelService->createRoom($data);
                
                header('Location: ' . BASE_URL . '/host/rooms?hotel_id=' . $hotelId);
                exit;
            } catch (Exception $e) {
                $hotel = $this->hotelService->getHotelById($hotelId);
                $this->renderCreateRoomForm(['hotel' => $hotel, 'error' => $e->getMessage()]);
            }
        } else {
            try {
                $hotel = $this->hotelService->getHotelById($hotelId);
                $this->renderCreateRoomForm(['hotel' => $hotel]);
            } catch (Exception $e) {
                $this->renderError($e->getMessage());
            }
        }
    }
    
    public function bookings() {
        try {
            Auth::requireRole('host');
            $bookings = $this->bookingService->getMyBookings();
            $hotels = $this->hotelService->getMyHotels();
            
            // Calculate booking statistics
            $stats = [
                'pending' => count(array_filter($bookings, function($b) { return $b['status'] === 'pending'; })),
                'approved' => count(array_filter($bookings, function($b) { return $b['status'] === 'approved'; })),
                'rejected' => count(array_filter($bookings, function($b) { return $b['status'] === 'rejected'; })),
                'cancelled' => count(array_filter($bookings, function($b) { return $b['status'] === 'cancelled'; }))
            ];
            
            $data = [
                'title' => 'Hotel Bookings',
                'bookings' => $bookings,
                'hotels' => $hotels,
                'stats' => $stats
            ];
            
            echo View::renderWithLayout('host/bookings', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function approveBooking() {
        try {
            $bookingId = $_POST['booking_id'] ?? null;
            if (!$bookingId) {
                throw new Exception("Booking ID required");
            }
            
            $this->bookingService->approveBooking($bookingId);
            
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function rejectBooking() {
        try {
            $bookingId = $_POST['booking_id'] ?? null;
            if (!$bookingId) {
                throw new Exception("Booking ID required");
            }
            
            $this->bookingService->rejectBooking($bookingId);
            
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function subscription() {
        try {
            Auth::requireRole('host');
            $subscription = $this->subscriptionService->getMySubscription();
            $history = $this->subscriptionService->getMySubscriptionHistory();
            
            $this->renderSubscription(['subscription' => $subscription, 'history' => $history]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function subscribe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $currentUser = Auth::getCurrentUser();
                $plan = $_POST['plan'] ?? '';
                
                $subscriptionId = $this->subscriptionService->createSubscription($currentUser['id'], $plan);
                
                header('Location: ' . BASE_URL . '/host/subscription');
                exit;
            } catch (Exception $e) {
                $this->renderSubscribeForm(['error' => $e->getMessage()]);
            }
        } else {
            $this->renderSubscribeForm();
        }
    }
    
    // Rendering methods
    private function renderDashboard($data) {
        echo "<h1>Host Dashboard</h1>";
        
        echo "<div>";
        echo "<h2>Subscription Status</h2>";
        if ($data['subscription']) {
            $sub = $data['subscription'];
            echo "<p>Plan: {$sub['plan']}</p>";
            echo "<p>Status: {$sub['status']}</p>";
            echo "<p>Expires: {$sub['end_date']}</p>";
        } else {
            echo "<p style='color: red;'>No active subscription</p>";
            echo "<a href='" . BASE_URL . "/host/subscribe'>Subscribe Now</a>";
        }
        echo "</div>";
        
        echo "<div>";
        echo "<h2>My Hotels (" . count($data['hotels']) . ")</h2>";
        foreach ($data['hotels'] as $hotel) {
            echo "<div>";
            echo "<h3>{$hotel['name']}</h3>";
            echo "<p>Status: {$hotel['status']}</p>";
            echo "<a href='" . BASE_URL . "/host/rooms?hotel_id={$hotel['id']}'>Manage Rooms</a>";
            echo "</div>";
        }
        echo "<a href='" . BASE_URL . "/host/create-hotel'>Add New Hotel</a>";
        echo "</div>";
        
        echo "<div>";
        echo "<h2>Pending Bookings (" . count($data['pendingBookings']) . ")</h2>";
        foreach ($data['pendingBookings'] as $booking) {
            echo "<div>";
            echo "<p>Booking #{$booking['id']}</p>";
            echo "<p>Dates: {$booking['check_in']} to {$booking['check_out']}</p>";
            echo "<p>Total: UGX {$booking['total_price']}</p>";
            echo "</div>";
        }
        echo "<a href='" . BASE_URL . "/host/bookings'>View All Bookings</a>";
        echo "</div>";
    }
    
    private function renderHotels($data) {
        echo "<h1>My Hotels</h1>";
        echo "<a href='" . BASE_URL . "/host/create-hotel'>Add New Hotel</a>";
        
        foreach ($data['hotels'] as $hotel) {
            echo "<div>";
            echo "<h3>{$hotel['name']}</h3>";
            echo "<p>{$hotel['location']}</p>";
            echo "<p>Price: UGX {$hotel['price_per_night']}/night</p>";
            echo "<p>Status: {$hotel['status']}</p>";
            echo "<a href='" . BASE_URL . "/host/edit-hotel?id={$hotel['id']}'>Edit</a>";
            echo "<a href='" . BASE_URL . "/host/rooms?hotel_id={$hotel['id']}'>Manage Rooms</a>";
            echo "</div>";
        }
    }
    
    private function renderCreateHotelForm($data = []) {
        echo "<h1>Create Hotel</h1>";
        if (isset($data['error'])) {
            echo "<p style='color: red;'>{$data['error']}</p>";
        }
        
        echo "<form method='POST'>";
        echo "<div>";
        echo "<label>Name:</label>";
        echo "<input type='text' name='name' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Description:</label>";
        echo "<textarea name='description'></textarea>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Location:</label>";
        echo "<input type='text' name='location' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Price per Night (UGX):</label>";
        echo "<input type='number' name='price_per_night' min='0' step='1000' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Image URL:</label>";
        echo "<input type='url' name='image_url'>";
        echo "</div>";
        
        echo "<button type='submit'>Create Hotel</button>";
        echo "</form>";
        
        echo "<a href='" . BASE_URL . "/host/hotels'>Back to Hotels</a>";
    }
    
    private function renderEditHotelForm($data) {
        $hotel = $data['hotel'];
        echo "<h1>Edit Hotel</h1>";
        if (isset($data['error'])) {
            echo "<p style='color: red;'>{$data['error']}</p>";
        }
        
        echo "<form method='POST'>";
        echo "<div>";
        echo "<label>Name:</label>";
        echo "<input type='text' name='name' value='{$hotel['name']}' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Description:</label>";
        echo "<textarea name='description'>{$hotel['description']}</textarea>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Location:</label>";
        echo "<input type='text' name='location' value='{$hotel['location']}' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Price per Night (UGX):</label>";
        echo "<input type='number' name='price_per_night' value='{$hotel['price_per_night']}' min='0' step='1000' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Image URL:</label>";
        echo "<input type='url' name='image_url' value='{$hotel['image_url']}'>";
        echo "</div>";
        
        echo "<button type='submit'>Update Hotel</button>";
        echo "</form>";
        
        echo "<a href='" . BASE_URL . "/host/hotels'>Back to Hotels</a>";
    }
    
    private function renderRooms($data) {
        $hotel = $data['hotel'];
        echo "<h1>Rooms for {$hotel['name']}</h1>";
        echo "<a href='" . BASE_URL . "/host/create-room?hotel_id={$hotel['id']}'>Add New Room</a>";
        
        foreach ($data['rooms'] as $room) {
            echo "<div>";
            echo "<h3>{$room['room_type']}</h3>";
            echo "<p>Price: UGX {$room['price']}/night</p>";
            echo "<p>Capacity: {$room['capacity']} guests</p>";
            echo "<p>Status: {$room['availability_status']}</p>";
            echo "</div>";
        }
        
        echo "<a href='" . BASE_URL . "/host/hotels'>Back to Hotels</a>";
    }
    
    private function renderCreateRoomForm($data) {
        $hotel = $data['hotel'];
        echo "<h1>Add Room to {$hotel['name']}</h1>";
        if (isset($data['error'])) {
            echo "<p style='color: red;'>{$data['error']}</p>";
        }
        
        echo "<form method='POST'>";
        echo "<div>";
        echo "<label>Room Type:</label>";
        echo "<input type='text' name='room_type' placeholder='e.g., Single, Double, Deluxe' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Price per Night (UGX):</label>";
        echo "<input type='number' name='price' min='0' step='1000' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Capacity:</label>";
        echo "<input type='number' name='capacity' min='1' value='1' required>";
        echo "</div>";
        
        echo "<button type='submit'>Add Room</button>";
        echo "</form>";
        
        echo "<a href='" . BASE_URL . "/host/rooms?hotel_id={$hotel['id']}'>Back to Rooms</a>";
    }
    
    private function renderBookings($data) {
        echo "<h1>My Bookings</h1>";
        
        if (empty($data['bookings'])) {
            echo "<p>No bookings found.</p>";
            return;
        }
        
        foreach ($data['bookings'] as $booking) {
            echo "<div>";
            echo "<h3>Booking #{$booking['id']}</h3>";
            echo "<p>Hotel ID: {$booking['hotel_id']}</p>";
            echo "<p>Dates: {$booking['check_in']} to {$booking['check_out']}</p>";
            echo "<p>Guests: {$booking['guests']}</p>";
            echo "<p>Total: UGX {$booking['total_price']}</p>";
            echo "<p>Status: {$booking['status']}</p>";
            echo "<p>Payment: {$booking['payment_status']}</p>";
            
            if ($booking['status'] === 'pending') {
                echo "<form method='POST' action='" . BASE_URL . "/host/approve-booking' style='display:inline;'>";
                echo "<input type='hidden' name='booking_id' value='{$booking['id']}'>";
                echo "<button type='submit'>Approve</button>";
                echo "</form>";
                
                echo "<form method='POST' action='" . BASE_URL . "/host/reject-booking' style='display:inline;'>";
                echo "<input type='hidden' name='booking_id' value='{$booking['id']}'>";
                echo "<button type='submit'>Reject</button>";
                echo "</form>";
            }
            echo "</div>";
        }
    }
    
    private function renderSubscription($data) {
        echo "<h1>Subscription</h1>";
        
        if ($data['subscription']) {
            $sub = $data['subscription'];
            echo "<div>";
            echo "<h2>Current Subscription</h2>";
            echo "<p>Plan: {$sub['plan']}</p>";
            echo "<p>Amount: UGX {$sub['amount']}</p>";
            echo "<p>Status: {$sub['status']}</p>";
            echo "<p>Start Date: {$sub['start_date']}</p>";
            echo "<p>End Date: {$sub['end_date']}</p>";
            echo "</div>";
        } else {
            echo "<p style='color: red;'>No active subscription</p>";
            echo "<a href='" . BASE_URL . "/host/subscribe'>Subscribe Now</a>";
        }
        
        echo "<div>";
        echo "<h2>Subscription History</h2>";
        foreach ($data['history'] as $sub) {
            echo "<div>";
            echo "<p>Plan: {$sub['plan']} | Amount: UGX {$sub['amount']} | Status: {$sub['status']}</p>";
            echo "<p>Period: {$sub['start_date']} to {$sub['end_date']}</p>";
            echo "</div>";
        }
        echo "</div>";
    }
    
    private function renderSubscribeForm($data = []) {
        echo "<h1>Subscribe</h1>";
        if (isset($data['error'])) {
            echo "<p style='color: red;'>{$data['error']}</p>";
        }
        
        echo "<form method='POST'>";
        echo "<div>";
        echo "<label>";
        echo "<input type='radio' name='plan' value='monthly' required>";
        echo "Monthly - UGX " . number_format(MONTHLY_SUBSCRIPTION_FEE) . "/month";
        echo "</label>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>";
        echo "<input type='radio' name='plan' value='annual' required>";
        echo "Annual - UGX " . number_format(ANNUAL_SUBSCRIPTION_FEE) . "/year (Save 17%)";
        echo "</label>";
        echo "</div>";
        
        echo "<button type='submit'>Subscribe</button>";
        echo "</form>";
    }
    
    public function profile() {
        try {
            Auth::requireRole('host');
            
            $currentUser = Auth::getCurrentUser();
            $subscription = $this->subscriptionService->getMySubscription();
            
            // Get user stats
            $hotels = $this->hotelService->getMyHotels();
            $bookings = $this->bookingService->getMyBookings();
            
            $stats = [
                'total_hotels' => count($hotels),
                'total_bookings' => count($bookings),
                'pending_bookings' => count(array_filter($bookings, function($b) { return $b['status'] === 'pending'; })),
                'total_revenue' => array_sum(array_column($bookings, 'total_price'))
            ];
            
            // Mock recent activity
            $recent_activity = [
                [
                    'icon' => 'hotel',
                    'color' => 'primary',
                    'description' => 'Added new hotel: ' . ($hotels[0]['name'] ?? 'New Hotel'),
                    'time' => '2 hours ago'
                ],
                [
                    'icon' => 'calendar-check',
                    'color' => 'success',
                    'description' => 'Approved booking for ' . ($hotels[0]['name'] ?? 'Hotel'),
                    'time' => '1 day ago'
                ],
                [
                    'icon' => 'user-plus',
                    'color' => 'info',
                    'description' => 'New guest registered',
                    'time' => '3 days ago'
                ]
            ];
            
            $data = [
                'title' => 'My Profile',
                'user' => $currentUser,
                'subscription' => $subscription,
                'stats' => $stats,
                'recent_activity' => $recent_activity
            ];
            
            echo View::renderWithLayout('host/profile', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function calendar() {
        try {
            Auth::requireRole('host');
            
            $currentUser = Auth::getCurrentUser();
            $hotels = $this->hotelService->getMyHotels();
            $bookings = $this->bookingService->getMyBookings();
            
            // Get all rooms for all hotels
            $rooms = [];
            foreach ($hotels as $hotel) {
                $hotelRooms = $this->hotelService->getHotelRooms($hotel['id']);
                foreach ($hotelRooms as $room) {
                    $room['hotel_name'] = $hotel['name'];
                    $rooms[] = $room;
                }
            }
            
            // Get today's bookings
            $today = date('Y-m-d');
            $today_bookings = array_filter($bookings, function($b) use ($today) {
                return $b['check_in'] === $today || $b['check_out'] === $today;
            });
            
            // Calculate month stats
            $currentMonth = date('Y-m');
            $month_bookings = array_filter($bookings, function($b) use ($currentMonth) {
                return strpos($b['check_in'], $currentMonth) === 0;
            });
            
            $month_stats = [
                'total_bookings' => count($month_bookings),
                'occupancy_rate' => min(100, (count($month_bookings) / max(1, count($rooms) * 30)) * 100),
                'pending_bookings' => count(array_filter($month_bookings, function($b) { return $b['status'] === 'pending'; })),
                'revenue' => array_sum(array_column($month_bookings, 'total_price'))
            ];
            
            // Mock calendar bookings data
            $calendar_bookings = [];
            foreach ($bookings as $booking) {
                $checkIn = new DateTime($booking['check_in']);
                $checkOut = new DateTime($booking['check_out']);
                
                while ($checkIn < $checkOut) {
                    $calendar_bookings[] = [
                        'date' => $checkIn->format('Y-m-d'),
                        'title' => $booking['hotel_name'] . ' - ' . $booking['room_type'],
                        'status' => $booking['status'] === 'approved' ? 'booked' : 'partial',
                        'id' => $booking['id']
                    ];
                    $checkIn->add(new DateInterval('P1D'));
                }
            }
            
            $data = [
                'title' => 'Booking Calendar',
                'hotels' => $hotels,
                'rooms' => $rooms,
                'bookings' => $calendar_bookings,
                'today_bookings' => $today_bookings,
                'month_stats' => $month_stats
            ];
            
            echo View::renderWithLayout('host/calendar', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function updateProfile() {
        try {
            Auth::requireRole('host');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== View::csrfToken()) {
                throw new Exception("Invalid CSRF token");
            }
            
            $currentUser = Auth::getCurrentUser();
            $data = [
                'name' => $_POST['name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'business_name' => $_POST['business_name'] ?? '',
                'business_type' => $_POST['business_type'] ?? '',
                'license_number' => $_POST['license_number'] ?? '',
                'tax_id' => $_POST['tax_id'] ?? ''
            ];
            
            $this->userService->updateProfile($currentUser['id'], $data);
            
            $_SESSION['success'] = 'Profile updated successfully!';
            header('Location: ' . BASE_URL . '/host/profile');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/profile');
            exit;
        }
    }
    
    public function changePassword() {
        try {
            Auth::requireRole('host');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== View::csrfToken()) {
                throw new Exception("Invalid CSRF token");
            }
            
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception("New passwords do not match");
            }
            
            $currentUser = Auth::getCurrentUser();
            $this->userService->changePassword($currentUser['id'], $currentPassword, $newPassword);
            
            $_SESSION['success'] = 'Password changed successfully!';
            header('Location: ' . BASE_URL . '/host/profile');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/profile');
            exit;
        }
    }

    private function renderError($message) {
        echo "<h1>Error</h1>";
        echo "<p style='color: red;'>{$message}</p>";
        echo "<a href='" . BASE_URL . "/host/dashboard'>Back to Dashboard</a>";
    }
}