<?php
require_once __DIR__ . '/../services/HotelService.php';
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../models/Destination.php';

class TouristController {
    private $hotelService;
    private $bookingService;
    private $destinationModel;
    
    public function __construct() {
        $this->hotelService = new HotelService();
        $this->bookingService = new BookingService();
        $this->destinationModel = new Destination();
    }
    
    public function home() {
        try {
            $hotels = $this->hotelService->getAvailableHotels();
            $destinations = $this->destinationModel->getPopularDestinations(6);
            
            $this->renderHome(['hotels' => $hotels, 'destinations' => $destinations]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function hotels() {
        try {
            $location = $_GET['location'] ?? null;
            $hotels = $this->hotelService->searchHotels($location);
            
            $this->renderHotels(['hotels' => $hotels, 'location' => $location]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function hotelDetails() {
        try {
            $hotelId = $_GET['id'] ?? null;
            if (!$hotelId) {
                throw new Exception("Hotel ID required");
            }
            
            $hotel = $this->hotelService->getHotelById($hotelId);
            $rooms = $this->hotelService->getHotelRooms($hotelId);
            
            $this->renderHotelDetails(['hotel' => $hotel, 'rooms' => $rooms]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function book() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'hotel_id' => $_POST['hotel_id'] ?? '',
                    'room_id' => $_POST['room_id'] ?? null,
                    'check_in' => $_POST['check_in'] ?? '',
                    'check_out' => $_POST['check_out'] ?? '',
                    'guests' => $_POST['guests'] ?? 1
                ];
                
                $bookingId = $this->bookingService->createBooking($data);
                
                header('Location: ' . BASE_URL . '/my-bookings');
                exit;
            } catch (Exception $e) {
                $this->renderError($e->getMessage());
            }
        } else {
            $hotelId = $_GET['hotel_id'] ?? null;
            if (!$hotelId) {
                $this->renderError("Hotel ID required");
                return;
            }
            
            try {
                $hotel = $this->hotelService->getHotelById($hotelId);
                $rooms = $this->hotelService->getHotelRooms($hotelId);
                
                $this->renderBookingForm(['hotel' => $hotel, 'rooms' => $rooms]);
            } catch (Exception $e) {
                $this->renderError($e->getMessage());
            }
        }
    }
    
    public function myBookings() {
        try {
            Auth::requireRole('tourist');
            $bookings = $this->bookingService->getMyBookings();
            
            $this->renderMyBookings(['bookings' => $bookings]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function destinations() {
        try {
            $query = $_GET['search'] ?? null;
            
            if ($query) {
                $destinations = $this->destinationModel->searchDestinations($query);
            } else {
                $destinations = $this->destinationModel->findAll();
            }
            
            $this->renderDestinations(['destinations' => $destinations, 'search' => $query]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    // Simple rendering methods (will be replaced with proper views)
    private function renderHome($data) {
        echo "<h1>Welcome to Uganda Tour Guide</h1>";
        echo "<h2>Featured Hotels</h2>";
        foreach ($data['hotels'] as $hotel) {
            echo "<div>";
            echo "<h3>{$hotel['name']}</h3>";
            echo "<p>{$hotel['location']}</p>";
            echo "<p>UGX {$hotel['price_per_night']}/night</p>";
            echo "<a href='" . BASE_URL . "/hotel-details?id={$hotel['id']}'>View Details</a>";
            echo "</div>";
        }
        
        echo "<h2>Popular Destinations</h2>";
        foreach ($data['destinations'] as $destination) {
            echo "<div>";
            echo "<h3>{$destination['name']}</h3>";
            echo "<p>{$destination['location']}</p>";
            echo "</div>";
        }
    }
    
    private function renderHotels($data) {
        echo "<h1>Hotels</h1>";
        if ($data['location']) {
            echo "<p>Showing results for: {$data['location']}</p>";
        }
        
        foreach ($data['hotels'] as $hotel) {
            echo "<div>";
            echo "<h3>{$hotel['name']}</h3>";
            echo "<p>{$hotel['location']}</p>";
            echo "<p>UGX {$hotel['price_per_night']}/night</p>";
            echo "<a href='" . BASE_URL . "/hotel-details?id={$hotel['id']}'>View Details</a>";
            echo "<a href='" . BASE_URL . "/book?hotel_id={$hotel['id']}'>Book Now</a>";
            echo "</div>";
        }
    }
    
    private function renderHotelDetails($data) {
        $hotel = $data['hotel'];
        echo "<h1>{$hotel['name']}</h1>";
        echo "<p>{$hotel['description']}</p>";
        echo "<p>Location: {$hotel['location']}</p>";
        echo "<p>Price: UGX {$hotel['price_per_night']}/night</p>";
        
        echo "<h2>Available Rooms</h2>";
        foreach ($data['rooms'] as $room) {
            echo "<div>";
            echo "<h3>{$room['room_type']}</h3>";
            echo "<p>Capacity: {$room['capacity']} guests</p>";
            echo "<p>Price: UGX {$room['price']}/night</p>";
            echo "<p>Status: {$room['availability_status']}</p>";
            echo "</div>";
        }
        
        echo "<a href='" . BASE_URL . "/book?hotel_id={$hotel['id']}'>Book This Hotel</a>";
    }
    
    private function renderBookingForm($data) {
        $hotel = $data['hotel'];
        echo "<h1>Book {$hotel['name']}</h1>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='hotel_id' value='{$hotel['id']}'>";
        
        if (!empty($data['rooms'])) {
            echo "<div>";
            echo "<label>Room:</label>";
            echo "<select name='room_id'>";
            echo "<option value=''>Any Available Room</option>";
            foreach ($data['rooms'] as $room) {
                echo "<option value='{$room['id']}'>{$room['room_type']} - UGX {$room['price']}</option>";
            }
            echo "</select>";
            echo "</div>";
        }
        
        echo "<div>";
        echo "<label>Check-in:</label>";
        echo "<input type='date' name='check_in' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Check-out:</label>";
        echo "<input type='date' name='check_out' required>";
        echo "</div>";
        
        echo "<div>";
        echo "<label>Guests:</label>";
        echo "<input type='number' name='guests' min='1' value='1'>";
        echo "</div>";
        
        echo "<button type='submit'>Book Now</button>";
        echo "</form>";
    }
    
    private function renderMyBookings($data) {
        echo "<h1>My Bookings</h1>";
        
        if (empty($data['bookings'])) {
            echo "<p>No bookings found.</p>";
            return;
        }
        
        foreach ($data['bookings'] as $booking) {
            echo "<div>";
            echo "<h3>Booking #{$booking['id']}</h3>";
            echo "<p>Hotel ID: {$booking['hotel_id']}</p>";
            echo "<p>Check-in: {$booking['check_in']}</p>";
            echo "<p>Check-out: {$booking['check_out']}</p>";
            echo "<p>Guests: {$booking['guests']}</p>";
            echo "<p>Total: UGX {$booking['total_price']}</p>";
            echo "<p>Status: {$booking['status']}</p>";
            echo "<p>Payment: {$booking['payment_status']}</p>";
            echo "</div>";
        }
    }
    
    private function renderDestinations($data) {
        echo "<h1>Destinations</h1>";
        
        echo "<form method='GET'>";
        echo "<input type='text' name='search' placeholder='Search destinations...' value='{$data['search']}'>";
        echo "<button type='submit'>Search</button>";
        echo "</form>";
        
        foreach ($data['destinations'] as $destination) {
            echo "<div>";
            echo "<h3>{$destination['name']}</h3>";
            echo "<p>{$destination['description']}</p>";
            echo "<p>Location: {$destination['location']}</p>";
            if ($destination['entry_fee'] > 0) {
                echo "<p>Entry Fee: UGX {$destination['entry_fee']}</p>";
            }
            echo "</div>";
        }
    }
    
    private function renderError($message) {
        echo "<h1>Error</h1>";
        echo "<p style='color: red;'>{$message}</p>";
        echo "<a href='" . BASE_URL . "'>Go Home</a>";
    }
}