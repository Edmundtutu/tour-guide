<?php
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../models/Room.php';

class BookingService {
    private $bookingModel;
    private $hotelModel;
    private $roomModel;
    
    public function __construct() {
        $this->bookingModel = new Booking();
        $this->hotelModel = new Hotel();
        $this->roomModel = new Room();
    }
    
    public function createBooking($data) {
        Auth::requireRole('tourist');
        
        $currentUser = Auth::getCurrentUser();
        $data['tourist_id'] = $currentUser['id'];
        
        // Validate hotel exists and is approved
        $hotel = $this->hotelModel->findById($data['hotel_id']);
        if (!$hotel || $hotel['status'] !== 'approved') {
            throw new Exception("Hotel not available");
        }
        
        // If room specified, validate it exists and is available
        if (!empty($data['room_id'])) {
            $room = $this->roomModel->findById($data['room_id']);
            if (!$room || $room['hotel_id'] != $data['hotel_id']) {
                throw new Exception("Room not found");
            }
            
            if ($room['availability_status'] !== 'available') {
                throw new Exception("Room not available");
            }
        }
        
        // Calculate total price
        $data['total_price'] = $this->calculateBookingPrice($data);
        
        // Set default values
        if (!isset($data['guests'])) {
            $data['guests'] = 1;
        }
        
        return $this->bookingModel->createBooking($data);
    }
    
    private function calculateBookingPrice($data) {
        $checkIn = new DateTime($data['check_in']);
        $checkOut = new DateTime($data['check_out']);
        $nights = $checkOut->diff($checkIn)->days;
        
        if (isset($data['room_id']) && !empty($data['room_id'])) {
            // Room-specific pricing
            $room = $this->roomModel->findById($data['room_id']);
            return $room['price'] * $nights;
        } else {
            // Hotel-level pricing
            $hotel = $this->hotelModel->findById($data['hotel_id']);
            return $hotel['price_per_night'] * $nights;
        }
    }
    
    public function getMyBookings() {
        Auth::requireLogin();
        
        $currentUser = Auth::getCurrentUser();
        
        if ($currentUser['role'] === 'tourist') {
            return $this->bookingModel->findByTourist($currentUser['id']);
        } elseif ($currentUser['role'] === 'host') {
            // Get bookings for all host's hotels
            $hotels = $this->hotelModel->findByHost($currentUser['id']);
            $bookings = [];
            
            foreach ($hotels as $hotel) {
                $hotelBookings = $this->bookingModel->findByHotel($hotel['id']);
                $bookings = array_merge($bookings, $hotelBookings);
            }
            
            return $bookings;
        } else {
            // Admin can see all bookings
            return $this->bookingModel->findAll();
        }
    }
    
    public function getBookingById($bookingId) {
        $booking = $this->bookingModel->getBookingDetails($bookingId);
        if (!$booking) {
            throw new Exception("Booking not found");
        }
        
        // Check access permissions
        $currentUser = Auth::getCurrentUser();
        
        if ($currentUser['role'] === 'tourist' && $booking['tourist_id'] != $currentUser['id']) {
            throw new Exception("Access denied");
        } elseif ($currentUser['role'] === 'host') {
            $hotel = $this->hotelModel->findById($booking['hotel_id']);
            if ($hotel['host_id'] != $currentUser['id']) {
                throw new Exception("Access denied");
            }
        }
        
        return $booking;
    }
    
    public function approveBooking($bookingId) {
        Auth::requireLogin();
        
        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking) {
            throw new Exception("Booking not found");
        }
        
        $currentUser = Auth::getCurrentUser();
        
        // Only hotel host or admin can approve
        if ($currentUser['role'] === 'host') {
            $hotel = $this->hotelModel->findById($booking['hotel_id']);
            if ($hotel['host_id'] != $currentUser['id']) {
                throw new Exception("Access denied");
            }
        } elseif ($currentUser['role'] !== 'admin') {
            throw new Exception("Access denied");
        }
        
        return $this->bookingModel->updateStatus($bookingId, 'approved');
    }
    
    public function rejectBooking($bookingId) {
        Auth::requireLogin();
        
        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking) {
            throw new Exception("Booking not found");
        }
        
        $currentUser = Auth::getCurrentUser();
        
        // Only hotel host or admin can reject
        if ($currentUser['role'] === 'host') {
            $hotel = $this->hotelModel->findById($booking['hotel_id']);
            if ($hotel['host_id'] != $currentUser['id']) {
                throw new Exception("Access denied");
            }
        } elseif ($currentUser['role'] !== 'admin') {
            throw new Exception("Access denied");
        }
        
        return $this->bookingModel->updateStatus($bookingId, 'rejected');
    }
    
    public function cancelBooking($bookingId) {
        Auth::requireLogin();
        
        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking) {
            throw new Exception("Booking not found");
        }
        
        $currentUser = Auth::getCurrentUser();
        
        // Only booking owner or admin can cancel
        if ($currentUser['role'] !== 'admin' && $booking['tourist_id'] != $currentUser['id']) {
            throw new Exception("Access denied");
        }
        
        // Can only cancel pending or approved bookings
        if (!in_array($booking['status'], ['pending', 'approved'])) {
            throw new Exception("Cannot cancel this booking");
        }
        
        return $this->bookingModel->updateStatus($bookingId, 'cancelled');
    }
    
    public function markAsPaid($bookingId) {
        Auth::requireLogin();
        
        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking) {
            throw new Exception("Booking not found");
        }
        
        $currentUser = Auth::getCurrentUser();
        
        // Only admin or hotel host can mark as paid
        if ($currentUser['role'] === 'host') {
            $hotel = $this->hotelModel->findById($booking['hotel_id']);
            if ($hotel['host_id'] != $currentUser['id']) {
                throw new Exception("Access denied");
            }
        } elseif ($currentUser['role'] !== 'admin') {
            throw new Exception("Access denied");
        }
        
        return $this->bookingModel->updatePaymentStatus($bookingId, 'paid');
    }
    
    public function getPendingBookings() {
        Auth::requireLogin();
        
        $currentUser = Auth::getCurrentUser();
        
        if ($currentUser['role'] === 'host') {
            // Get pending bookings for host's hotels
            $hotels = $this->hotelModel->findByHost($currentUser['id']);
            $pendingBookings = [];
            
            foreach ($hotels as $hotel) {
                $hotelBookings = $this->bookingModel->findAll([
                    'hotel_id' => $hotel['id'],
                    'status' => 'pending'
                ]);
                $pendingBookings = array_merge($pendingBookings, $hotelBookings);
            }
            
            return $pendingBookings;
        } else {
            // Admin can see all pending bookings
            Auth::requireRole('admin');
            return $this->bookingModel->findByStatus('pending');
        }
    }
}
