<?php
require_once __DIR__ . '/../models/Subscription.php';

class SubscriptionService {
    private $subscriptionModel;
    
    public function __construct() {
        $this->subscriptionModel = new Subscription();
    }
    
    public function createSubscription($hostId, $plan) {
        Auth::requireLogin();
        
        $currentUser = Auth::getCurrentUser();
        
        // Only admin can create subscriptions for others, hosts can create for themselves
        if ($currentUser['role'] !== 'admin' && $currentUser['id'] != $hostId) {
            throw new Exception("Access denied");
        }
        
        // Validate plan
        if (!in_array($plan, ['monthly', 'annual'])) {
            throw new Exception("Invalid subscription plan");
        }
        
        // Calculate amount and dates
        $amount = ($plan === 'monthly') ? MONTHLY_SUBSCRIPTION_FEE : ANNUAL_SUBSCRIPTION_FEE;
        $startDate = date('Y-m-d');
        $endDate = ($plan === 'monthly') 
            ? date('Y-m-d', strtotime('+1 month'))
            : date('Y-m-d', strtotime('+1 year'));
        
        $data = [
            'host_id' => $hostId,
            'plan' => $plan,
            'amount' => $amount,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active'
        ];
        
        return $this->subscriptionModel->createSubscription($data);
    }
    
    public function getMySubscription() {
        Auth::requireRole('host');
        
        $currentUser = Auth::getCurrentUser();
        return $this->subscriptionModel->findActiveSubscription($currentUser['id']);
    }
    
    public function getMySubscriptionHistory() {
        Auth::requireRole('host');
        
        $currentUser = Auth::getCurrentUser();
        return $this->subscriptionModel->findByHost($currentUser['id']);
    }
    
    public function getHostSubscription($hostId) {
        Auth::requireRole('admin');
        return $this->subscriptionModel->findActiveSubscription($hostId);
    }
    
    public function getAllSubscriptions() {
        Auth::requireRole('admin');
        return $this->subscriptionModel->findAll();
    }
    
    public function renewSubscription($hostId, $plan) {
        Auth::requireLogin();
        
        $currentUser = Auth::getCurrentUser();
        
        // Only admin can renew for others, hosts can renew for themselves
        if ($currentUser['role'] !== 'admin' && $currentUser['id'] != $hostId) {
            throw new Exception("Access denied");
        }
        
        return $this->createSubscription($hostId, $plan);
    }
    
    public function cancelSubscription($subscriptionId) {
        Auth::requireLogin();
        
        $subscription = $this->subscriptionModel->findById($subscriptionId);
        if (!$subscription) {
            throw new Exception("Subscription not found");
        }
        
        $currentUser = Auth::getCurrentUser();
        
        // Only admin or subscription owner can cancel
        if ($currentUser['role'] !== 'admin' && $subscription['host_id'] != $currentUser['id']) {
            throw new Exception("Access denied");
        }
        
        return $this->subscriptionModel->cancelSubscription($subscriptionId);
    }
    
    public function getExpiringSubscriptions($days = 7) {
        Auth::requireRole('admin');
        return $this->subscriptionModel->getExpiringSubscriptions($days);
    }
    
    public function expireSubscriptions() {
        Auth::requireRole('admin');
        
        // Find all subscriptions that should be expired
        $expiredSubscriptions = $this->subscriptionModel->getExpiringSubscriptions(0);
        
        $expiredCount = 0;
        foreach ($expiredSubscriptions as $subscription) {
            if ($this->subscriptionModel->expireSubscription($subscription['id'])) {
                $expiredCount++;
            }
        }
        
        return $expiredCount;
    }
    
    public function isSubscriptionActive($hostId) {
        $subscription = $this->subscriptionModel->findActiveSubscription($hostId);
        return $subscription !== null;
    }
    
    public function getSubscriptionSummary() {
        Auth::requireRole('admin');
        
        $allSubscriptions = $this->subscriptionModel->findAll();
        
        $summary = [
            'total' => count($allSubscriptions),
            'active' => 0,
            'expired' => 0,
            'cancelled' => 0,
            'monthly_revenue' => 0,
            'annual_revenue' => 0
        ];
        
        foreach ($allSubscriptions as $subscription) {
            $summary[$subscription['status']]++;
            
            if ($subscription['status'] === 'active') {
                if ($subscription['plan'] === 'monthly') {
                    $summary['monthly_revenue'] += $subscription['amount'];
                } else {
                    $summary['annual_revenue'] += $subscription['amount'];
                }
            }
        }
        
        return $summary;
    }
}
