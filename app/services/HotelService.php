<?php
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../models/Room.php';
require_once __DIR__ . '/../models/Subscription.php';

class HotelService {
    private $hotelModel;
    private $roomModel;
    private $subscriptionModel;
    
    public function __construct() {
        $this->hotelModel = new Hotel();
        $this->roomModel = new Room();
        $this->subscriptionModel = new Subscription();
    }
    
    public function createHotel($data) {
        Auth::requireRole('host');
        
        $currentUser = Auth::getCurrentUser();
        
        // Check if host has active subscription
        $subscription = $this->subscriptionModel->findActiveSubscription($currentUser['id']);
        if (!$subscription) {
            throw new Exception("Active subscription required to create hotels");
        }
        
        $data['host_id'] = $currentUser['id'];
        return $this->hotelModel->createHotel($data);
    }
    
    public function getMyHotels() {
        Auth::requireRole('host');
        
        $currentUser = Auth::getCurrentUser();
        return $this->hotelModel->findByHost($currentUser['id']);
    }
    
    public function getHotelById($hotelId) {
        $hotel = $this->hotelModel->findById($hotelId);
        if (!$hotel) {
            throw new Exception("Hotel not found");
        }
        
        return $hotel;
    }
    
    public function updateHotel($hotelId, $data) {
        Auth::requireLogin();
        
        $hotel = $this->hotelModel->findById($hotelId);
        if (!$hotel) {
            throw new Exception("Hotel not found");
        }
        
        $currentUser = Auth::getCurrentUser();
        
        // Only hotel owner or admin can update
        if ($currentUser['role'] !== 'admin' && $hotel['host_id'] != $currentUser['id']) {
            throw new Exception("Access denied");
        }
        
        // Hosts cannot change status - only admins can
        if ($currentUser['role'] !== 'admin') {
            unset($data['status']);
        }
        
        return $this->hotelModel->update($hotelId, $data);
    }
    
    public function approveHotel($hotelId) {
        Auth::requireRole('admin');
        return $this->hotelModel->updateStatus($hotelId, 'approved');
    }
    
    public function blockHotel($hotelId) {
        Auth::requireRole('admin');
        return $this->hotelModel->updateStatus($hotelId, 'blocked');
    }
    
    public function searchHotels($location = null, $priceRange = null) {
        return $this->hotelModel->searchHotels($location, $priceRange);
    }
    
    public function getAvailableHotels() {
        return $this->hotelModel->findAvailableHotels();
    }
    
    public function getPendingHotels() {
        Auth::requireRole('admin');
        return $this->hotelModel->findByStatus('pending');
    }
    
    // Room management
    public function createRoom($data) {
        Auth::requireRole('host');
        
        // Verify hotel ownership
        $hotel = $this->hotelModel->findById($data['hotel_id']);
        if (!$hotel) {
            throw new Exception("Hotel not found");
        }
        
        $currentUser = Auth::getCurrentUser();
        if ($hotel['host_id'] != $currentUser['id']) {
            throw new Exception("Access denied");
        }
        
        return $this->roomModel->createRoom($data);
    }
    
    public function getHotelRooms($hotelId) {
        return $this->roomModel->findByHotel($hotelId);
    }
    
    public function getAvailableRooms($hotelId, $checkIn = null, $checkOut = null) {
        return $this->roomModel->findAvailableRooms($hotelId, $checkIn, $checkOut);
    }
    
    public function updateRoom($roomId, $data) {
        Auth::requireRole('host');
        
        $room = $this->roomModel->findById($roomId);
        if (!$room) {
            throw new Exception("Room not found");
        }
        
        // Verify hotel ownership
        $hotel = $this->hotelModel->findById($room['hotel_id']);
        $currentUser = Auth::getCurrentUser();
        if ($hotel['host_id'] != $currentUser['id']) {
            throw new Exception("Access denied");
        }
        
        return $this->roomModel->update($roomId, $data);
    }
    
    public function updateRoomAvailability($roomId, $status) {
        Auth::requireRole('host');
        
        $room = $this->roomModel->findById($roomId);
        if (!$room) {
            throw new Exception("Room not found");
        }
        
        // Verify hotel ownership
        $hotel = $this->hotelModel->findById($room['hotel_id']);
        $currentUser = Auth::getCurrentUser();
        if ($hotel['host_id'] != $currentUser['id']) {
            throw new Exception("Access denied");
        }
        
        return $this->roomModel->updateAvailability($roomId, $status);
    }
}