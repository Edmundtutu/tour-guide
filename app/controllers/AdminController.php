<?php
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/HotelService.php';
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../services/SubscriptionService.php';

class AdminController {
    private $userService;
    private $hotelService;
    private $bookingService;
    private $subscriptionService;
    
    public function __construct() {
        $this->userService = new UserService();
        $this->hotelService = new HotelService();
        $this->bookingService = new BookingService();
        $this->subscriptionService = new SubscriptionService();
    }
    
    public function dashboard() {
        try {
            Auth::requireRole('admin');
            
            $totalUsers = count($this->userService->getAllUsers());
            $totalHotels = count($this->hotelService->getAvailableHotels());
            $pendingHotels = count($this->hotelService->getPendingHotels());
            $subscriptionSummary = $this->subscriptionService->getSubscriptionSummary();
            
            $this->renderDashboard([
                'totalUsers' => $totalUsers,
                'totalHotels' => $totalHotels,
                'pendingHotels' => $pendingHotels,
                'subscriptions' => $subscriptionSummary
            ]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function users() {
        try {
            Auth::requireRole('admin');
            $users = $this->userService->getAllUsers();
            
            $this->renderUsers(['users' => $users]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function hotels() {
        try {
            Auth::requireRole('admin');
            $status = $_GET['status'] ?? 'all';
            
            if ($status === 'all') {
                $hotels = $this->hotelService->getAllHotels();
            } else {
                $hotels = $this->hotelService->getHotelsByStatus($status);
            }
            
            $this->renderHotels(['hotels' => $hotels, 'status' => $status]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function approveHotel() {
        try {
            $hotelId = $_POST['hotel_id'] ?? null;
            if (!$hotelId) {
                throw new Exception("Hotel ID required");
            }
            
            $this->hotelService->approveHotel($hotelId);
            
            header('Location: ' . BASE_URL . '/admin/hotels');
            exit;
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function blockHotel() {
        try {
            $hotelId = $_POST['hotel_id'] ?? null;
            if (!$hotelId) {
                throw new Exception("Hotel ID required");
            }
            
            $this->hotelService->blockHotel($hotelId);
            
            header('Location: ' . BASE_URL . '/admin/hotels');
            exit;
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function subscriptions() {
        try {
            Auth::requireRole('admin');
            $subscriptions = $this->subscriptionService->getAllSubscriptions();
            $expiringSubscriptions = $this->subscriptionService->getExpiringSubscriptions();
            
            $this->renderSubscriptions([
                'subscriptions' => $subscriptions,
                'expiring' => $expiringSubscriptions
            ]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function bookings() {
        try {
            Auth::requireRole('admin');
            $bookings = $this->bookingService->getAllBookings();
            
            $this->renderBookings(['bookings' => $bookings]);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    // Rendering methods
    private function renderDashboard($data) {
        echo "<h1>Admin Dashboard</h1>";
        
        echo "<div>";
        echo "<h2>System Overview</h2>";
        echo "<div>";
        echo "<div>Total Users: {$data['totalUsers']}</div>";
        echo "<div>Total Hotels: {$data['totalHotels']}</div>";
        echo "<div>Pending Hotels: {$data['pendingHotels']}</div>";
        echo "</div>";
        echo "</div>";
        
        echo "<div>";
        echo "<h2>Subscriptions</h2>";
        $subs = $data['subscriptions'];
        echo "<div>";
        echo "<div>Total: {$subs['total']}</div>";
        echo "<div>Active: {$subs['active']}</div>";
        echo "<div>Expired: {$subs['expired']}</div>";
        echo "<div>Cancelled: {$subs['cancelled']}</div>";
        echo "</div>";
        echo "<div>";
        echo "<div>Monthly Revenue: UGX " . number_format($subs['monthly_revenue']) . "</div>";
        echo "<div>Annual Revenue: UGX " . number_format($subs['annual_revenue']) . "</div>";
        echo "</div>";
        echo "</div>";
        
        echo "<div>";
        echo "<h2>Quick Actions</h2>";
        echo "<a href='" . BASE_URL . "/admin/users'>Manage Users</a> | ";
        echo "<a href='" . BASE_URL . "/admin/hotels?status=pending'>Review Pending Hotels</a> | ";
        echo "<a href='" . BASE_URL . "/admin/subscriptions'>Manage Subscriptions</a> | ";
        echo "<a href='" . BASE_URL . "/admin/bookings'>View All Bookings</a>";
        echo "</div>";
    }
    
    private function renderUsers($data) {
        echo "<h1>Users Management</h1>";
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr>";
        foreach ($data['users'] as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['name']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>{$user['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    private function renderHotels($data) {
        echo "<h1>Hotels Management</h1>";
        
        echo "<div>";
        echo "<a href='" . BASE_URL . "/admin/hotels?status=all'>All</a> | ";
        echo "<a href='" . BASE_URL . "/admin/hotels?status=pending'>Pending</a> | ";
        echo "<a href='" . BASE_URL . "/admin/hotels?status=approved'>Approved</a> | ";
        echo "<a href='" . BASE_URL . "/admin/hotels?status=blocked'>Blocked</a>";
        echo "</div>";
        
        foreach ($data['hotels'] as $hotel) {
            echo "<div>";
            echo "<h3>{$hotel['name']}</h3>";
            echo "<p>Location: {$hotel['location']}</p>";
            echo "<p>Price: UGX {$hotel['price_per_night']}/night</p>";
            echo "<p>Status: {$hotel['status']}</p>";
            echo "<p>Host ID: {$hotel['host_id']}</p>";
            
            if ($hotel['status'] === 'pending') {
                echo "<form method='POST' action='" . BASE_URL . "/admin/approve-hotel' style='display:inline;'>";
                echo "<input type='hidden' name='hotel_id' value='{$hotel['id']}'>";
                echo "<button type='submit'>Approve</button>";
                echo "</form>";
                
                echo "<form method='POST' action='" . BASE_URL . "/admin/block-hotel' style='display:inline;'>";
                echo "<input type='hidden' name='hotel_id' value='{$hotel['id']}'>";
                echo "<button type='submit'>Block</button>";
                echo "</form>";
            } elseif ($hotel['status'] === 'approved') {
                echo "<form method='POST' action='" . BASE_URL . "/admin/block-hotel' style='display:inline;'>";
                echo "<input type='hidden' name='hotel_id' value='{$hotel['id']}'>";
                echo "<button type='submit'>Block</button>";
                echo "</form>";
            } elseif ($hotel['status'] === 'blocked') {
                echo "<form method='POST' action='" . BASE_URL . "/admin/approve-hotel' style='display:inline;'>";
                echo "<input type='hidden' name='hotel_id' value='{$hotel['id']}'>";
                echo "<button type='submit'>Approve</button>";
                echo "</form>";
            }
            echo "</div>";
        }
    }
    
    private function renderSubscriptions($data) {
        echo "<h1>Subscriptions Management</h1>";
        
        echo "<div>";
        echo "<h2>Expiring Soon</h2>";
        foreach ($data['expiring'] as $sub) {
            echo "<div>";
            echo "<p>Host: {$sub['host_name']} ({$sub['host_email']})</p>";
            echo "<p>Plan: {$sub['plan']} | Expires: {$sub['end_date']}</p>";
            echo "</div>";
        }
        echo "</div>";
        
        echo "<div>";
        echo "<h2>All Subscriptions</h2>";
        foreach ($data['subscriptions'] as $sub) {
            echo "<div>";
            echo "<p>Host ID: {$sub['host_id']}</p>";
            echo "<p>Plan: {$sub['plan']} | Amount: UGX {$sub['amount']}</p>";
            echo "<p>Status: {$sub['status']}</p>";
            echo "<p>Period: {$sub['start_date']} to {$sub['end_date']}</p>";
            echo "</div>";
        }
        echo "</div>";
    }
    
    private function renderBookings($data) {
        echo "<h1>All Bookings</h1>";
        
        foreach ($data['bookings'] as $booking) {
            echo "<div>";
            echo "<h3>Booking #{$booking['id']}</h3>";
            echo "<p>Hotel ID: {$booking['hotel_id']} | Tourist ID: {$booking['tourist_id']}</p>";
            echo "<p>Dates: {$booking['check_in']} to {$booking['check_out']}</p>";
            echo "<p>Guests: {$booking['guests']}</p>";
            echo "<p>Total: UGX {$booking['total_price']}</p>";
            echo "<p>Status: {$booking['status']} | Payment: {$booking['payment_status']}</p>";
            echo "</div>";
        }
    }
    
    private function renderError($message) {
        echo "<h1>Error</h1>";
        echo "<p style='color: red;'>{$message}</p>";
        echo "<a href='" . BASE_URL . "/admin/dashboard'>Back to Dashboard</a>";
    }
}