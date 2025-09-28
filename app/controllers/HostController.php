<?php
require_once __DIR__ . '/../services/HotelService.php';
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../services/SubscriptionService.php';
require_once __DIR__ . '/../services/UserService.php';

class HostController {
    private $hotelService;
    private $bookingService;
    private $subscriptionService;
    private $userService;
    
    public function __construct() {
        $this->hotelService = new HotelService();
        $this->bookingService = new BookingService();
        $this->subscriptionService = new SubscriptionService();
        $this->userService = new UserService();
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
                Auth::requireRole('host');
                
                // CSRF validation
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== View::csrfToken()) {
                    throw new Exception("Invalid CSRF token");
                }
                
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'location' => $_POST['location'] ?? '',
                    'price_per_night' => $_POST['price_per_night'] ?? 0,
                    'image_url' => $_POST['image_url'] ?? ''
                ];
                
                $hotelId = $this->hotelService->createHotel($data);
                
                $_SESSION['success'] = 'Hotel created successfully!';
                header('Location: ' . BASE_URL . '/host/hotels');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['old_input'] = $_POST;
                header('Location: ' . BASE_URL . '/host/create-hotel');
                exit;
            }
        } else {
            try {
                Auth::requireRole('host');
                
                $data = [
                    'title' => 'Create Hotel'
                ];
                
                echo View::renderWithLayout('host/create-hotel', $data);
            } catch (Exception $e) {
                $this->renderError($e->getMessage());
            }
        }
    }
    
    public function editHotel() {
        try {
            Auth::requireRole('host');
            
            $hotelId = $_GET['id'] ?? null;
            if (!$hotelId) {
                throw new Exception("Hotel ID required");
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // CSRF validation
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== View::csrfToken()) {
                    throw new Exception("Invalid CSRF token");
                }
                
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'location' => $_POST['location'] ?? '',
                    'price_per_night' => $_POST['price_per_night'] ?? 0,
                    'image_url' => $_POST['image_url'] ?? ''
                ];
                
                $this->hotelService->updateHotel($hotelId, $data);
                
                $_SESSION['success'] = 'Hotel updated successfully!';
                header('Location: ' . BASE_URL . '/host/hotels');
                exit;
            } else {
                $hotel = $this->hotelService->getHotelById($hotelId);
                
                $data = [
                    'title' => 'Edit Hotel',
                    'hotel' => $hotel
                ];
                
                echo View::renderWithLayout('host/edit-hotel', $data);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/hotels');
            exit;
        }
    }
    
    public function rooms() {
        try {
            Auth::requireRole('host');
            $hotelId = $_GET['hotel_id'] ?? null;
            
            if (!$hotelId) {
                throw new Exception("Hotel ID required");
            }
            
            $hotel = $this->hotelService->getHotelById($hotelId);
            $rooms = $this->hotelService->getHotelRooms($hotelId);
            
            $data = [
                'title' => 'Hotel Rooms',
                'hotel' => $hotel,
                'rooms' => $rooms
            ];
            
            echo View::renderWithLayout('host/rooms', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/hotels');
            exit;
        }
    }
    
    public function createRoom() {
        try {
            Auth::requireRole('host');
            
            $hotelId = $_GET['hotel_id'] ?? null;
            if (!$hotelId) {
                throw new Exception("Hotel ID required");
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // CSRF validation
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== View::csrfToken()) {
                    throw new Exception("Invalid CSRF token");
                }
                
                $data = [
                    'hotel_id' => $hotelId,
                    'room_type' => $_POST['room_type'] ?? '',
                    'price' => $_POST['price'] ?? 0,
                    'capacity' => $_POST['capacity'] ?? 1,
                    'availability_status' => $_POST['availability_status'] ?? 'available',
                    'description' => $_POST['description'] ?? ''
                ];
                
                $roomId = $this->hotelService->createRoom($data);
                
                $_SESSION['success'] = 'Room added successfully!';
                header('Location: ' . BASE_URL . '/host/rooms?hotel_id=' . $hotelId);
                exit;
            } else {
                $hotel = $this->hotelService->getHotelById($hotelId);
                
                $data = [
                    'title' => 'Add Room',
                    'hotel' => $hotel
                ];
                
                echo View::renderWithLayout('host/create-room', $data);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/host/create-room?hotel_id=' . ($hotelId ?? ''));
            exit;
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
            Auth::requireRole('host');
            
            $bookingId = $_POST['booking_id'] ?? null;
            if (!$bookingId) {
                throw new Exception("Booking ID required");
            }
            
            $this->bookingService->approveBooking($bookingId);
            
            $_SESSION['success'] = 'Booking approved successfully!';
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        }
    }
    
    public function rejectBooking() {
        try {
            Auth::requireRole('host');
            
            $bookingId = $_POST['booking_id'] ?? null;
            if (!$bookingId) {
                throw new Exception("Booking ID required");
            }
            
            $this->bookingService->rejectBooking($bookingId);
            
            $_SESSION['success'] = 'Booking rejected successfully!';
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        }
    }
    
    public function cancelBooking() {
        try {
            Auth::requireRole('host');
            
            $bookingId = $_POST['booking_id'] ?? null;
            if (!$bookingId) {
                throw new Exception("Booking ID required");
            }
            
            $this->bookingService->cancelBooking($bookingId);
            
            $_SESSION['success'] = 'Booking cancelled successfully!';
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        }
    }
    
    public function viewBooking() {
        try {
            Auth::requireRole('host');
            
            $bookingId = $_GET['booking_id'] ?? null;
            if (!$bookingId) {
                throw new Exception("Booking ID required");
            }
            
            $booking = $this->bookingService->getBookingDetails($bookingId);
            if (!$booking) {
                throw new Exception("Booking not found");
            }
            
            // Check if the booking belongs to this host
            $currentUser = Auth::getCurrentUser();
            $hotel = $this->hotelService->getHotelById($booking['hotel_id']);
            if (!$hotel || $hotel['host_id'] != $currentUser['id']) {
                throw new Exception("Access denied");
            }
            
            $data = [
                'title' => 'Booking Details',
                'booking' => $booking,
                'hotel' => $hotel
            ];
            
            echo View::renderWithLayout('host/view-booking', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/bookings');
            exit;
        }
    }
    
    
    public function subscription() {
        try {
            Auth::requireRole('host');
            $subscription = $this->subscriptionService->getMySubscription();
            $history = $this->subscriptionService->getMySubscriptionHistory();
            
            $data = [
                'title' => 'Subscription Management',
                'subscription' => $subscription,
                'history' => $history
            ];
            
            echo View::renderWithLayout('host/subscription', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/host/dashboard');
            exit;
        }
    }
    
    public function subscribe() {
        try {
            Auth::requireRole('host');
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // CSRF validation
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== View::csrfToken()) {
                    throw new Exception("Invalid CSRF token");
                }
                
                $currentUser = Auth::getCurrentUser();
                $plan = $_POST['plan'] ?? '';
                
                $subscriptionId = $this->subscriptionService->createSubscription($currentUser['id'], $plan);
                
                $_SESSION['success'] = 'Subscription activated successfully!';
                header('Location: ' . BASE_URL . '/host/subscription');
                exit;
            } else {
                $data = [
                    'title' => 'Subscribe to Premium'
                ];
                
                echo View::renderWithLayout('host/subscribe', $data);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . '/host/subscribe');
            exit;
        }
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