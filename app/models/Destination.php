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
}
?>