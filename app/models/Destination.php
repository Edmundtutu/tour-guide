<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Destination extends BaseModel {
    protected $table = 'destinations';
    
    public function createDestination($data) {
        $required = ['name', 'location'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field {$field} is required");
            }
        }
        
        return $this->create($data);
    }
    
    public function searchDestinations($query) {
        $sql = "SELECT * FROM destinations 
                WHERE name LIKE :query OR location LIKE :query OR description LIKE :query
                ORDER BY name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', "%{$query}%");
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function getPopularDestinations($limit = 10) {
        // For now, just return all destinations ordered by name
        // In future, this could be based on booking frequency or ratings
        return $this->findAll([], 'name ASC', $limit);
    }
    
    public function getDestinationsNearby($latitude, $longitude, $radius = 100) {
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(:lat)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(:lng)) + sin(radians(:lat)) * 
                sin(radians(latitude)))) AS distance 
                FROM destinations 
                WHERE latitude IS NOT NULL 
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
    
    public function searchDestinationsByLocation($location = null, $latitude = null, $longitude = null, $radius = 100) {
        if ($location) {
            return $this->searchDestinations($location);
        }
        
        if ($latitude && $longitude) {
            return $this->getDestinationsNearby($latitude, $longitude, $radius);
        }
        
        return $this->findAll([], 'name ASC');
    }
}
?>