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
    
    public function searchHotels($location = null, $priceRange = null, $latitude = null, $longitude = null, $radius = 50) {
        $conditions = ['status' => 'approved'];
        
        if ($location) {
            // For simplicity, using LIKE. In production, consider full-text search
            $sql = "SELECT * FROM hotels WHERE status = 'approved' AND location LIKE :location";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':location', "%{$location}%");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        // Search by coordinates (within radius)
        if ($latitude && $longitude) {
            $sql = "SELECT *, 
                    (6371 * acos(cos(radians(:lat)) * cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(:lng)) + sin(radians(:lat)) * 
                    sin(radians(latitude)))) AS distance 
                    FROM hotels 
                    WHERE status = 'approved' 
                    AND latitude IS NOT NULL 
                    AND longitude IS NOT NULL
                    HAVING distance < :radius 
                    ORDER BY distance";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':lat', $latitude);
            $stmt->bindValue(':lng', $longitude);
            $stmt->bindValue(':radius', $radius);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        return $this->findAll($conditions);
    }
    
    public function getHotelsNearby($latitude, $longitude, $radius = 50) {
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(:lat)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(:lng)) + sin(radians(:lat)) * 
                sin(radians(latitude)))) AS distance 
                FROM hotels 
                WHERE status = 'approved' 
                AND latitude IS NOT NULL 
                AND longitude IS NOT NULL
                HAVING distance < :radius 
                ORDER BY distance 
                LIMIT 20";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lat', $latitude);
        $stmt->bindValue(':lng', $longitude);
        $stmt->bindValue(':radius', $radius);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>