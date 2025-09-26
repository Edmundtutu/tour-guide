<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Booking extends BaseModel {
    protected $table = 'bookings';
    
    public function findByTourist($touristId) {
        return $this->findAll(['tourist_id' => $touristId]);
    }
    
    public function findByHotel($hotelId) {
        return $this->findAll(['hotel_id' => $hotelId]);
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
        $sql = "SELECT COUNT(*) FROM bookings 
                WHERE hotel_id = :hotel_id 
                AND (:room_id IS NULL OR room_id = :room_id)
                AND status IN ('pending', 'approved')
                AND (
                    (check_in <= :check_in AND check_out > :check_in) OR
                    (check_in < :check_out AND check_out >= :check_out) OR
                    (check_in >= :check_in AND check_out <= :check_out)
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':hotel_id', $hotelId);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':check_in', $checkIn);
        $stmt->bindParam(':check_out', $checkOut);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }
    
    public function getBookingDetails($bookingId) {
        $sql = "SELECT b.*, h.name as hotel_name, h.location, 
                       r.room_type, u.name as tourist_name, u.email as tourist_email
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
?>