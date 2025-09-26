<?php
require_once __DIR__ . '/../core/BaseModel.php';

class Subscription extends BaseModel {
    protected $table = 'subscriptions';
    
    public function findByHost($hostId) {
        return $this->findAll(['host_id' => $hostId], 'created_at DESC');
    }
    
    public function findActiveSubscription($hostId) {
        $conditions = ['host_id' => $hostId, 'status' => 'active'];
        $subscriptions = $this->findAll($conditions, 'end_date DESC', 1);
        
        if (empty($subscriptions)) {
            return null;
        }
        
        $subscription = $subscriptions[0];
        
        // Check if subscription is actually still valid
        if (strtotime($subscription['end_date']) < time()) {
            $this->update($subscription['id'], ['status' => 'expired']);
            return null;
        }
        
        return $subscription;
    }
    
    public function createSubscription($data) {
        $required = ['host_id', 'plan', 'amount', 'start_date', 'end_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field {$field} is required");
            }
        }
        
        // Validate plan
        if (!in_array($data['plan'], ['monthly', 'annual'])) {
            throw new Exception("Invalid subscription plan");
        }
        
        return $this->create($data);
    }
    
    public function expireSubscription($subscriptionId) {
        return $this->update($subscriptionId, ['status' => 'expired']);
    }
    
    public function cancelSubscription($subscriptionId) {
        return $this->update($subscriptionId, ['status' => 'cancelled']);
    }
    
    public function getExpiringSubscriptions($days = 7) {
        $expireDate = date('Y-m-d', strtotime("+{$days} days"));
        
        $sql = "SELECT s.*, u.name as host_name, u.email as host_email
                FROM subscriptions s
                LEFT JOIN users u ON s.host_id = u.id
                WHERE s.status = 'active' AND s.end_date <= :expire_date";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':expire_date', $expireDate);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>