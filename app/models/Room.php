<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Room extends BaseModel {
    protected $table = 'rooms';
    
    public function findByHotel($hotelId) {
        return $this->findAll(['hotel_id' => $hotelId]);
    }
    
    public function findAvailableRooms($hotelId, $checkIn = null, $checkOut = null) {
        if (!$checkIn || !$checkOut) {
            return $this->findAll(['hotel_id' => $hotelId, 'availability_status' => 'available']);
        }
        
        // Check for booking conflicts
        $sql = "SELECT r.* FROM rooms r 
                WHERE r.hotel_id = :hotel_id 
                AND r.availability_status = 'available'
                AND r.id NOT IN (
                    SELECT DISTINCT room_id FROM bookings 
                    WHERE room_id IS NOT NULL 
                    AND status IN ('pending', 'approved')
                    AND (
                        (check_in <= :check_in AND check_out > :check_in) OR
                        (check_in < :check_out AND check_out >= :check_out) OR
                        (check_in >= :check_in AND check_out <= :check_out)
                    )
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':hotel_id', $hotelId);
        $stmt->bindParam(':check_in', $checkIn);
        $stmt->bindParam(':check_out', $checkOut);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function createRoom($data) {
        $required = ['hotel_id', 'room_type', 'price'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field {$field} is required");
            }
        }
        
        return $this->create($data);
    }
    
    public function updateAvailability($roomId, $status) {
        $validStatuses = ['available', 'unavailable'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid availability status");
        }
        
        return $this->update($roomId, ['availability_status' => $status]);
    }
}
?>