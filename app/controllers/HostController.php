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
            
            $hotels = $this->hotelService->getMyHotels();
            $pendingBookings = $this->bookingService->getPendingBookings();
            $subscription = $this->subscriptionService->getMySubscription();
            
            $this->renderDashboard([
                'hotels' => $hotels,
                'pendingBookings' => $pendingBookings,
                'subscription' => $subscription
            ]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function hotels() {
        try {
            Auth::requireRole('host');
            $hotels = $this->hotelService->getMyHotels();
            
            $this->renderHotels(['hotels' => $hotels]);
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
            
            $this->renderBookings(['bookings' => $bookings]);
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
    
    private function renderError($message) {
        echo "<h1>Error</h1>";
        echo "<p style='color: red;'>{$message}</p>";
        echo "<a href='" . BASE_URL . "/host/dashboard'>Back to Dashboard</a>";
    }
}