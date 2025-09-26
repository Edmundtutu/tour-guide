<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Hotel extends BaseModel {
    protected $table = 'hotels';
    
    public function findByHost($hostId) {
        return $this->findAll(['host_id' => $hostId]);
    }
    
    public function findByStatus($status) {
        return $this->findAll(['status' => $status]);
    }
    
    public function findAvailableHotels() {
        return $this->findByStatus('approved');
    }
    
    public function createHotel($data) {
        $required = ['host_id', 'name', 'location'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field {$field} is required");
            }
        }
        
        // Set default status
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }
        
        return $this->create($data);
    }
    
    public function updateStatus($hotelId, $status) {
        $validStatuses = ['pending', 'approved', 'blocked'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid status");
        }
        
        return $this->update($hotelId, ['status' => $status]);
    }
    
    public function searchHotels($location = null, $priceRange = null) {
        $conditions = ['status' => 'approved'];
        
        if ($location) {
            // For simplicity, using LIKE. In production, consider full-text search
            $sql = "SELECT * FROM hotels WHERE status = 'approved' AND location LIKE :location";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':location', "%{$location}%");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        return $this->findAll($conditions);
    }
}
?>