<?php
require_once __DIR__ . '/../services/HotelService.php';
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../services/DestinationService.php';

class TouristController {
    private $hotelService;
    private $bookingService;
    private $destinationService;
    
    public function __construct() {
        $this->hotelService = new HotelService();
        $this->bookingService = new BookingService();
        $this->destinationService = new DestinationService();
    }
    
    public function home() {
        try {
            $hotels = $this->hotelService->getAvailableHotels();
            $destinations = $this->destinationService->getPopularDestinations(6);
            
            $data = [
                'title' => 'Home',
                'hotels' => $hotels,
                'destinations' => $destinations
            ];
            
            echo View::renderWithLayout('tourist/home', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function hotels() {
        try {
            $location = $_GET['location'] ?? null;
            $latitude = $_GET['lat'] ?? null;
            $longitude = $_GET['lng'] ?? null;
            $radius = $_GET['radius'] ?? 50;
            
            // Search hotels by location or coordinates
            if ($latitude && $longitude) {
                $hotels = $this->hotelService->searchHotels($location, null, $latitude, $longitude, $radius);
            } else {
                $hotels = $this->hotelService->searchHotels($location);
            }
            
            // Get all destinations for map markers
            $destinations = $this->destinationService->getAllDestinations();
            
            $data = [
                'title' => 'Hotels',
                'hotels' => $hotels,
                'destinations' => $destinations,
                'location' => $location,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ];
            
            echo View::renderWithLayout('tourist/hotels', $data);
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
            
            $data = [
                'title' => $hotel['name'],
                'hotel' => $hotel,
                'rooms' => $rooms
            ];
            
            echo View::renderWithLayout('tourist/hotel-details', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    
    public function myBookings() {
        try {
            Auth::requireRole('tourist');
            $bookings = $this->bookingService->getMyBookings();
            
            $data = [
                'title' => 'My Bookings',
                'bookings' => $bookings
            ];
            
            echo View::renderWithLayout('tourist/my-bookings', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function destinations() {
        try {
            $query = $_GET['search'] ?? null;
            $latitude = $_GET['lat'] ?? null;
            $longitude = $_GET['lng'] ?? null;
            $radius = $_GET['radius'] ?? 100;
            
            // Search destinations by query or coordinates
            if ($query) {
                $destinations = $this->destinationService->searchDestinations($query);
            } elseif ($latitude && $longitude) {
                $destinations = $this->destinationService->searchDestinationsByLocation(null, $latitude, $longitude, $radius);
            } else {
                $destinations = $this->destinationService->getAllDestinations();
            }
            
            // Get nearby hotels for map
            $hotels = $this->hotelService->getAvailableHotels();
            
            $data = [
                'title' => 'Destinations',
                'destinations' => $destinations,
                'hotels' => $hotels,
                'search' => $query,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ];
            
            echo View::renderWithLayout('tourist/destinations', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
   
    public function book() {
        // Clear any previous output
        if (ob_get_level()) {
            ob_clean();
        }
        
        try {
            Auth::requireLogin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Display booking form with pre-filled data from hotel-details
                $hotelId = $_GET['hotel_id'] ?? null;
                
                if (!$hotelId) {
                    throw new Exception("Hotel ID is required");
                }
                
                // Get hotel details
                $hotel = $this->hotelService->getHotelById($hotelId);
                if (!$hotel) {
                    throw new Exception("Hotel not found");
                }
                
                // Get available rooms for this hotel
                $rooms = $this->hotelService->getHotelRooms($hotelId);
                
                // Pre-fill form data from GET parameters (from hotel-details sidebar)
                $formData = [
                    'check_in' => $_GET['check_in'] ?? '',
                    'check_out' => $_GET['check_out'] ?? '',
                    'guests' => $_GET['guests'] ?? '',
                    'room_id' => $_GET['room_id'] ?? ''
                ];
                
                $data = [
                    'title' => 'Book Hotel',
                    'hotel' => $hotel,
                    'rooms' => $rooms,
                    'form_data' => $formData
                ];
                
                echo View::renderWithLayout('tourist/booking', $data);
                
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Process booking submission
                Auth::requireRole('tourist');
                
                    // CSRF validation
                    $submittedToken = $_POST['csrf_token'] ?? '';
                    $sessionToken = View::csrfToken();
                    
                    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $sessionToken) {
                        throw new Exception("Invalid CSRF token");
                    }
                
                $bookingData = [
                    'hotel_id' => $_POST['hotel_id'] ?? null,
                    'room_id' => !empty($_POST['room_id']) ? $_POST['room_id'] : null,
                    'check_in' => $_POST['check_in'] ?? '',
                    'check_out' => $_POST['check_out'] ?? '',
                    'guests' => $_POST['guests'] ?? 1
                ];
                
                // Validate required fields with detailed error messages
                $missingFields = [];
                if (empty($bookingData['hotel_id'])) $missingFields[] = 'hotel_id';
                if (empty($bookingData['check_in'])) $missingFields[] = 'check_in';
                if (empty($bookingData['check_out'])) $missingFields[] = 'check_out';
                if (empty($bookingData['guests'])) $missingFields[] = 'guests';
                
                if (!empty($missingFields)) {
                    throw new Exception("Missing required fields: " . implode(', ', $missingFields));
                }
                
                // Validate dates
                $checkIn = new DateTime($bookingData['check_in']);
                $checkOut = new DateTime($bookingData['check_out']);
                if ($checkOut <= $checkIn) {
                    throw new Exception("Check-out date must be after check-in date");
                }
                
                // Calculate total price
                $hotel = $this->hotelService->getHotelById($bookingData['hotel_id']);
                $nights = $checkIn->diff($checkOut)->days;
                $pricePerNight = $hotel['price_per_night'];
                $totalPrice = $pricePerNight * $nights;
                
                $bookingData['total_price'] = $totalPrice;
                $bookingData['tourist_id'] = Auth::getCurrentUser()['id'];
                
                // Create booking
                $bookingId = $this->bookingService->createBooking($bookingData);
                
                // Always return JSON for AJAX requests
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Booking created successfully!',
                    'booking_id' => $bookingId,
                    'redirect' => BASE_URL . '/my-bookings'
                ]);
                exit;
            }
        } catch (Exception $e) {
            // Always return JSON for booking requests
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
    
    private function renderError($message) {
        $data = [
            'title' => 'Error',
            'error_message' => $message
        ];
        
        echo View::renderWithLayout('error', $data);
    }
    
    // API methods for AJAX requests
    public function apiSearch() {
        try {
            $query = $_POST['query'] ?? '';
            $location = $_POST['location'] ?? null;
            $priceMin = $_POST['price_min'] ?? null;
            $priceMax = $_POST['price_max'] ?? null;
            $amenities = $_POST['amenities'] ?? [];
            
            $hotels = $this->hotelService->searchHotels($location, $priceMin, $priceMax);
            
            // Filter by amenities if provided
            if (!empty($amenities)) {
                $hotels = array_filter($hotels, function($hotel) use ($amenities) {
                    // This would need to be implemented based on your hotel amenities structure
                    return true; // Placeholder
                });
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'results' => $hotels,
                'count' => count($hotels)
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function apiCalculatePrice() {
        try {
            $roomId = $_POST['room_id'] ?? null;
            $checkIn = $_POST['check_in'] ?? '';
            $checkOut = $_POST['check_out'] ?? '';
            $guests = $_POST['guests'] ?? 1;
            
            if (!$roomId || !$checkIn || !$checkOut) {
                throw new Exception("Missing required parameters");
            }
            
            // Calculate nights
            $start = new DateTime($checkIn);
            $end = new DateTime($checkOut);
            $nights = $start->diff($end)->days;
            
            // Get room price
            $room = $this->hotelService->getRoomById($roomId);
            $pricePerNight = $room['price'] ?? 0;
            $totalPrice = $pricePerNight * $nights;
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'price' => $totalPrice,
                'nights' => $nights,
                'price_per_night' => $pricePerNight
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function apiCancelBooking() {
        try {
            Auth::requireRole('tourist');
            
            $bookingId = $_POST['booking_id'] ?? null;
            if (!$bookingId) {
                throw new Exception("Booking ID required");
            }
            
            $result = $this->bookingService->cancelBooking($bookingId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Booking cancelled successfully'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function apiDownloadReceipt() {
        try {
            Auth::requireRole('tourist');
            
            $bookingId = $_GET['booking_id'] ?? null;
            if (!$bookingId) {
                throw new Exception("Booking ID required");
            }
            
            $booking = $this->bookingService->getBookingById($bookingId);
            if (!$booking) {
                throw new Exception("Booking not found");
            }
            
            // Generate receipt (simplified version)
            $receipt = $this->generateReceipt($booking);
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="booking-receipt-' . $bookingId . '.pdf"');
            echo $receipt;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    private function generateReceipt($booking) {
        // This is a simplified receipt generation
        // In a real application, you would use a PDF library like TCPDF or FPDF
        $html = "
        <html>
        <head><title>Booking Receipt</title></head>
        <body>
            <h1>Booking Receipt</h1>
            <p>Booking ID: {$booking['id']}</p>
            <p>Hotel: {$booking['hotel_name']}</p>
            <p>Check-in: {$booking['check_in']}</p>
            <p>Check-out: {$booking['check_out']}</p>
            <p>Total: UGX " . number_format($booking['total_price']) . "</p>
        </body>
        </html>";
        
        return $html;
    }
    
    public function itinerary() {
        try {
            Auth::requireRole('tourist');
            
            $currentUser = Auth::getCurrentUser();
            $bookings = $this->bookingService->getMyBookings();
            
            // Mock itinerary data (in real app, this would come from a database)
            $itineraries = [
                [
                    'id' => 1,
                    'title' => 'Uganda Wildlife Safari',
                    'start_date' => '2024-03-01',
                    'end_date' => '2024-03-07',
                    'status' => 'active',
                    'progress' => 60,
                    'estimated_cost' => 2500000,
                    'destinations' => [
                        ['name' => 'Murchison Falls National Park'],
                        ['name' => 'Bwindi Impenetrable Forest'],
                        ['name' => 'Queen Elizabeth National Park']
                    ]
                ],
                [
                    'id' => 2,
                    'title' => 'Kampala City Tour',
                    'start_date' => '2024-02-15',
                    'end_date' => '2024-02-17',
                    'status' => 'completed',
                    'progress' => 100,
                    'estimated_cost' => 800000,
                    'destinations' => [
                        ['name' => 'Kampala City Center'],
                        ['name' => 'Kasubi Tombs'],
                        ['name' => 'Uganda Museum']
                    ]
                ]
            ];
            
            // Get recent bookings
            $recent_bookings = array_slice($bookings, 0, 5);
            
            // Calculate stats
            $stats = [
                'total_itineraries' => count($itineraries),
                'total_bookings' => count($bookings),
                'destinations_visited' => 8, // Mock data
                'total_spent' => array_sum(array_column($bookings, 'total_price'))
            ];
            
            // Mock popular destinations
            $popular_destinations = [
                [
                    'id' => 1,
                    'name' => 'Murchison Falls National Park',
                    'location' => 'Northern Uganda',
                    'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=100&h=100&fit=crop'
                ],
                [
                    'id' => 2,
                    'name' => 'Bwindi Impenetrable Forest',
                    'location' => 'Southwestern Uganda',
                    'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=100&h=100&fit=crop'
                ],
                [
                    'id' => 3,
                    'name' => 'Queen Elizabeth National Park',
                    'location' => 'Western Uganda',
                    'image_url' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=100&h=100&fit=crop'
                ]
            ];
            
            $data = [
                'title' => 'My Itinerary',
                'itineraries' => $itineraries,
                'recent_bookings' => $recent_bookings,
                'stats' => $stats,
                'popular_destinations' => $popular_destinations
            ];
            
            echo View::renderWithLayout('tourist/itinerary', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function createItinerary() {
        try {
            Auth::requireRole('tourist');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== View::csrfToken()) {
                throw new Exception("Invalid CSRF token");
            }
            
            $data = [
                'title' => $_POST['title'] ?? '',
                'start_date' => $_POST['start_date'] ?? '',
                'end_date' => $_POST['end_date'] ?? '',
                'budget' => $_POST['budget'] ?? 0,
                'travel_style' => $_POST['travel_style'] ?? 'mid-range',
                'interests' => $_POST['interests'] ?? [],
                'description' => $_POST['description'] ?? ''
            ];
            
            // Validate required fields
            if (empty($data['title']) || empty($data['start_date']) || empty($data['end_date'])) {
                throw new Exception("Title, start date, and end date are required");
            }
            
            // Validate dates
            $startDate = new DateTime($data['start_date']);
            $endDate = new DateTime($data['end_date']);
            if ($endDate <= $startDate) {
                throw new Exception("End date must be after start date");
            }
            
            // In a real application, save to database here
            // For now, just redirect with success message
            
            $_SESSION['success'] = 'Itinerary created successfully!';
            header('Location: ' . BASE_URL . '/tourist/itinerary');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/tourist/itinerary');
            exit;
        }
    }
    
    public function addDestinationToItinerary() {
        try {
            Auth::requireRole('tourist');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            // CSRF validation
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== View::csrfToken()) {
                throw new Exception("Invalid CSRF token");
            }
            
            $destinationId = $_POST['destination_id'] ?? null;
            $itineraryId = $_POST['itinerary_id'] ?? null;
            
            if (!$destinationId) {
                throw new Exception("Destination ID is required");
            }
            
            // Get destination details
            $destination = $this->destinationService->getDestinationById($destinationId);
            if (!$destination) {
                throw new Exception("Destination not found");
            }
            
            // In a real application, you would save this to a database table like:
            // itinerary_destinations (itinerary_id, destination_id, added_date, notes)
            
            // For now, just return success
            if ($_SERVER['HTTP_ACCEPT'] === 'application/json' || isset($_POST['ajax'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Destination added to itinerary successfully!',
                    'destination' => $destination
                ]);
            } else {
                $_SESSION['success'] = 'Destination "' . $destination['name'] . '" added to your itinerary!';
                header('Location: ' . BASE_URL . '/itinerary');
                exit;
            }
        } catch (Exception $e) {
            if ($_SERVER['HTTP_ACCEPT'] === 'application/json' || isset($_POST['ajax'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ' . BASE_URL . '/destinations');
                exit;
            }
        }
    }
}