<?php
require_once __DIR__ . '/../models/Destination.php';

class DestinationService {
    private $destinationModel;
    
    public function __construct() {
        $this->destinationModel = new Destination();
    }
    
    public function getAllDestinations() {
        return $this->destinationModel->findAll([], 'name ASC');
    }
    
    public function getDestinationById($id) {
        return $this->destinationModel->findById($id);
    }
    
    public function searchDestinations($query) {
        if (empty($query)) {
            return $this->getAllDestinations();
        }
        
        return $this->destinationModel->searchDestinations($query);
    }
    
    public function getPopularDestinations($limit = 10) {
        return $this->destinationModel->getPopularDestinations($limit);
    }
    
    public function getDestinationsNearby($latitude, $longitude, $radius = 100) {
        return $this->destinationModel->getDestinationsNearby($latitude, $longitude, $radius);
    }
    
    public function searchDestinationsByLocation($location = null, $latitude = null, $longitude = null, $radius = 100) {
        return $this->destinationModel->searchDestinationsByLocation($location, $latitude, $longitude, $radius);
    }
    
    public function createDestination($data) {
        return $this->destinationModel->createDestination($data);
    }
    
    public function updateDestination($id, $data) {
        return $this->destinationModel->update($id, $data);
    }
    
    public function deleteDestination($id) {
        return $this->destinationModel->delete($id);
    }
}
?>
