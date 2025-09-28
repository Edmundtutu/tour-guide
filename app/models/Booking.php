<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Booking extends BaseModel {
    protected $table = 'bookings';
    
    public function findByTourist($touristId) {
        return $this->findAll(['tourist_id' => $touristId]);
    }
    
    public function findByTouristWithHotel($touristId) {
        $sql = "SELECT b.*, 
                       h.name as hotel_name, 
                       h.location as hotel_location, 
                       h.image_url as hotel_image,
                       r.room_type as room_type
                FROM bookings b
                LEFT JOIN hotels h ON b.hotel_id = h.id
                LEFT JOIN rooms r ON b.room_id = r.id
                WHERE b.tourist_id = :tourist_id
                ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tourist_id', $touristId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findByHotel($hotelId) {
        return $this->findAll(['hotel_id' => $hotelId]);
    }
    
    public function findByHotelWithDetails($hotelId) {
        $sql = "SELECT b.*, 
                       h.name as hotel_name, 
                       h.location as hotel_location, 
                       h.image_url as hotel_image,
                       r.room_type as room_type,
                       u.name as guest_name,
                       u.email as guest_email,
                       u.phone as guest_phone
                FROM bookings b
                LEFT JOIN hotels h ON b.hotel_id = h.id
                LEFT JOIN rooms r ON b.room_id = r.id
                LEFT JOIN users u ON b.tourist_id = u.id
                WHERE b.hotel_id = :hotel_id
                ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':hotel_id', $hotelId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findByStatus($status) {
        return $this->findAll(['status' => $status]);
    }
    
    public function createBooking($data) {
        $required = ['hotel_id', 'tourist_id', 'check_in', 'check_out'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field {$field} is required");
            }
        }
        
        // Validate dates
        $checkIn = new DateTime($data['check_in']);
        $checkOut = new DateTime($data['check_out']);
        $today = new DateTime();
        
        if ($checkIn <= $today) {
            throw new Exception("Check-in date must be in the future");
        }
        
        if ($checkOut <= $checkIn) {
            throw new Exception("Check-out date must be after check-in date");
        }
        
        // Check for conflicts
        if ($this->hasBookingConflict($data['hotel_id'], $data['room_id'], $data['check_in'], $data['check_out'])) {
            throw new Exception("Booking conflict detected");
        }
        
        return $this->create($data);
    }
    
    public function updateStatus($bookingId, $status) {
        $validStatuses = ['pending', 'approved', 'rejected', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid booking status");
        }
        
        return $this->update($bookingId, ['status' => $status]);
    }
    
    public function updatePaymentStatus($bookingId, $paymentStatus) {
        $validStatuses = ['unpaid', 'paid'];
        if (!in_array($paymentStatus, $validStatuses)) {
            throw new Exception("Invalid payment status");
        }
        
        return $this->update($bookingId, ['payment_status' => $paymentStatus]);
    }
    
    private function hasBookingConflict($hotelId, $roomId, $checkIn, $checkOut) {
        if ($roomId === null || $roomId === '') {
            // Check for any room conflicts in the hotel
            $sql = "SELECT COUNT(*) FROM bookings WHERE hotel_id = :hotel_id AND status IN ('pending', 'approved') AND ((check_in <= :check_in1 AND check_out > :check_in2) OR (check_in < :check_out1 AND check_out >= :check_out2) OR (check_in >= :check_in3 AND check_out <= :check_out3))";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':hotel_id', $hotelId);
            $stmt->bindValue(':check_in1', $checkIn);
            $stmt->bindValue(':check_in2', $checkIn);
            $stmt->bindValue(':check_in3', $checkIn);
            $stmt->bindValue(':check_out1', $checkOut);
            $stmt->bindValue(':check_out2', $checkOut);
            $stmt->bindValue(':check_out3', $checkOut);
        } else {
            // Check for specific room conflicts
            $sql = "SELECT COUNT(*) FROM bookings WHERE hotel_id = :hotel_id AND room_id = :room_id AND status IN ('pending', 'approved') AND ((check_in <= :check_in1 AND check_out > :check_in2) OR (check_in < :check_out1 AND check_out >= :check_out2) OR (check_in >= :check_in3 AND check_out <= :check_out3))";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':hotel_id', $hotelId);
            $stmt->bindValue(':room_id', $roomId);
            $stmt->bindValue(':check_in1', $checkIn);
            $stmt->bindValue(':check_in2', $checkIn);
            $stmt->bindValue(':check_in3', $checkIn);
            $stmt->bindValue(':check_out1', $checkOut);
            $stmt->bindValue(':check_out2', $checkOut);
            $stmt->bindValue(':check_out3', $checkOut);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    public function getBookingDetails($bookingId) {
        $sql = "SELECT b.*, h.name as hotel_name, h.location, 
                       r.room_type, u.name as guest_name, u.email as guest_email, u.phone as guest_phone
                FROM bookings b
                LEFT JOIN hotels h ON b.hotel_id = h.id
                LEFT JOIN rooms r ON b.room_id = r.id
                LEFT JOIN users u ON b.tourist_id = u.id
                WHERE b.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $bookingId);
        $stmt->execute();
        
        return $stmt->fetch();
    }
}
