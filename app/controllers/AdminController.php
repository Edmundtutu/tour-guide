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
            
            $users = $this->userService->getAllUsers();
            $hotels = $this->hotelService->getAllHotels();
            $bookings = $this->bookingService->getAllBookings();
            $subscriptions = $this->subscriptionService->getAllSubscriptions();
            
            // Calculate statistics
            $stats = [
                'total_users' => count($users),
                'total_hotels' => count($hotels),
                'total_bookings' => count($bookings),
                'total_revenue' => array_sum(array_column($bookings, 'total_price')),
                'pending_hotels' => count(array_filter($hotels, function($h) { return $h['status'] === 'pending'; })),
                'active_subscriptions' => count(array_filter($subscriptions, function($s) { return $s['status'] === 'active'; })),
                'new_users_today' => count(array_filter($users, function($u) { return date('Y-m-d', strtotime($u['created_at'])) === date('Y-m-d'); })),
                'active_users' => count(array_filter($users, function($u) { return ($u['status'] ?? 'active') === 'active'; })),
                'blocked_users' => count(array_filter($users, function($u) { return ($u['status'] ?? 'active') === 'blocked'; })),
                'system_health' => 'Good'
            ];
            
            $data = [
                'title' => 'Admin Dashboard',
                'stats' => $stats
            ];
            
            echo View::renderWithLayout('admin/dashboard', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function users() {
        try {
            Auth::requireRole('admin');
            $users = $this->userService->getAllUsers();
            
            // Calculate user statistics
            $stats = [
                'active' => count(array_filter($users, function($u) { return ($u['status'] ?? 'active') === 'active'; })),
                'blocked' => count(array_filter($users, function($u) { return ($u['status'] ?? 'active') === 'blocked'; })),
                'inactive' => count(array_filter($users, function($u) { return ($u['status'] ?? 'active') === 'inactive'; })),
                'tourists' => count(array_filter($users, function($u) { return $u['role'] === 'tourist'; })),
                'hosts' => count(array_filter($users, function($u) { return $u['role'] === 'host'; })),
                'admins' => count(array_filter($users, function($u) { return $u['role'] === 'admin'; }))
            ];
            
            $data = [
                'title' => 'User Management',
                'users' => $users,
                'stats' => $stats
            ];
            
            echo View::renderWithLayout('admin/users', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function getUser() {
        try {
            Auth::requireRole('admin');
            
            $userId = $_GET['user_id'] ?? null;
            if (!$userId) {
                throw new Exception("User ID required");
            }
            
            $user = $this->userService->getUserById($userId);
            if (!$user) {
                throw new Exception("User not found");
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'user' => $user
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function viewUser() {
        try {
            Auth::requireRole('admin');
            
            $userId = $_GET['user_id'] ?? null;
            if (!$userId) {
                throw new Exception("User ID required");
            }
            
            $user = $this->userService->getUserById($userId);
            if (!$user) {
                throw new Exception("User not found");
            }
            
            $data = [
                'title' => 'User Details',
                'user' => $user
            ];
            
            echo View::renderWithLayout('admin/view-user', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }
    }
    
    public function editUser() {
        try {
            Auth::requireRole('admin');
            
            $userId = $_GET['user_id'] ?? null;
            if (!$userId) {
                throw new Exception("User ID required");
            }
            
            $user = $this->userService->getUserById($userId);
            if (!$user) {
                throw new Exception("User not found");
            }
            
            $data = [
                'title' => 'Edit User',
                'user' => $user
            ];
            
            echo View::renderWithLayout('admin/edit-user', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }
    }
    
    public function updateUser() {
        try {
            Auth::requireRole('admin');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $userId = $_POST['user_id'] ?? null;
            if (!$userId) {
                throw new Exception("User ID required");
            }
            
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'role' => $_POST['role'] ?? '',
                'status' => $_POST['status'] ?? ''
            ];
            
            $this->userService->updateProfile($userId, $data);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'User updated successfully!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function blockUser() {
        try {
            Auth::requireRole('admin');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $userId = $_POST['user_id'] ?? null;
            if (!$userId) {
                throw new Exception("User ID required");
            }
            
            $this->userService->blockUser($userId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'User blocked successfully!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function unblockUser() {
        try {
            Auth::requireRole('admin');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $userId = $_POST['user_id'] ?? null;
            if (!$userId) {
                throw new Exception("User ID required");
            }
            
            $this->userService->unblockUser($userId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'User unblocked successfully!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function deleteUser() {
        try {
            Auth::requireRole('admin');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $userId = $_POST['user_id'] ?? null;
            if (!$userId) {
                throw new Exception("User ID required");
            }
            
            // Prevent admin from deleting themselves
            $currentUser = Auth::getCurrentUser();
            if ($currentUser['id'] == $userId) {
                throw new Exception("You cannot delete your own account");
            }
            
            $this->userService->deleteUser($userId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function exportUsers() {
        try {
            Auth::requireRole('admin');
            
            $users = $this->userService->getAllUsers();
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="users_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Created At', 'Last Login']);
            
            // CSV data
            foreach ($users as $user) {
                fputcsv($output, [
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['phone'] ?? '',
                    $user['role'],
                    $user['status'] ?? 'active',
                    $user['created_at'],
                    $user['last_login'] ?? ''
                ]);
            }
            
            fclose($output);
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
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
    
    public function subscriptions() {
        try {
            Auth::requireRole('admin');
            
            $subscriptions = $this->subscriptionService->getAllSubscriptions();
            $hosts = $this->userService->getUsersByRole('host');
            
            // Calculate statistics
            $stats = [
                'total_subscriptions' => count($subscriptions),
                'active_subscriptions' => count(array_filter($subscriptions, function($s) { return $s['status'] === 'active'; })),
                'expiring_soon' => count(array_filter($subscriptions, function($s) {
                    $endDate = new DateTime($s['end_date']);
                    $today = new DateTime();
                    $daysLeft = $today->diff($endDate)->days;
                    return $s['status'] === 'active' && $daysLeft <= 7;
                })),
                'monthly_revenue' => array_sum(array_filter(array_column($subscriptions, 'amount'), function($amount, $key) use ($subscriptions) {
                    return $subscriptions[$key]['status'] === 'active' && $subscriptions[$key]['plan'] === 'monthly';
                }, ARRAY_FILTER_USE_BOTH))
            ];
            
            // Add host information to subscriptions
            foreach ($subscriptions as &$subscription) {
                $host = array_filter($hosts, function($h) use ($subscription) {
                    return $h['id'] == $subscription['host_id'];
                });
                $host = reset($host);
                $subscription['host_name'] = $host['name'] ?? 'Unknown';
                $subscription['host_email'] = $host['email'] ?? 'Unknown';
            }
            
            $data = [
                'title' => 'Subscription Management',
                'subscriptions' => $subscriptions,
                'hosts' => $hosts,
                'stats' => $stats
            ];
            
            echo View::renderWithLayout('admin/subscriptions', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function reports() {
        try {
            Auth::requireRole('admin');
            
            $users = $this->userService->getAllUsers();
            $hotels = $this->hotelService->getAllHotels();
            $bookings = $this->bookingService->getAllBookings();
            $subscriptions = $this->subscriptionService->getAllSubscriptions();
            
            // Calculate key metrics
            $metrics = [
                'total_revenue' => array_sum(array_column($bookings, 'total_price')),
                'total_bookings' => count($bookings),
                'total_users' => count($users),
                'total_hotels' => count($hotels),
                'revenue_change' => 15.5, // Mock data
                'bookings_change' => 8.2, // Mock data
                'users_change' => 12.1, // Mock data
                'hotels_change' => 5.7 // Mock data
            ];
            
            // Mock chart data
            $chart_data = [
                'revenue' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [1500000, 1800000, 2200000, 1900000, 2500000, 2800000]
                ],
                'booking_status' => [
                    'labels' => ['Approved', 'Pending', 'Cancelled', 'Rejected'],
                    'data' => [45, 15, 8, 2]
                ],
                'user_growth' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [12, 19, 15, 25, 22, 30]
                ]
            ];
            
            // Mock top hotels data
            $top_hotels = [
                [
                    'name' => 'Kampala Hotel',
                    'location' => 'Kampala, Uganda',
                    'image_url' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=100&h=100&fit=crop',
                    'bookings' => 45,
                    'revenue' => 2500000,
                    'rating' => 4.5
                ],
                [
                    'name' => 'Entebbe Resort',
                    'location' => 'Entebbe, Uganda',
                    'image_url' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=100&h=100&fit=crop',
                    'bookings' => 38,
                    'revenue' => 2200000,
                    'rating' => 4.3
                ],
                [
                    'name' => 'Jinja Lodge',
                    'location' => 'Jinja, Uganda',
                    'image_url' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=100&h=100&fit=crop',
                    'bookings' => 32,
                    'revenue' => 1800000,
                    'rating' => 4.1
                ]
            ];
            
            // Mock monthly data
            $monthly_data = [
                [
                    'month' => 'January 2024',
                    'revenue' => 1500000,
                    'bookings' => 25,
                    'new_users' => 12,
                    'new_hotels' => 3,
                    'avg_booking_value' => 60000
                ],
                [
                    'month' => 'February 2024',
                    'revenue' => 1800000,
                    'bookings' => 30,
                    'new_users' => 19,
                    'new_hotels' => 2,
                    'avg_booking_value' => 60000
                ],
                [
                    'month' => 'March 2024',
                    'revenue' => 2200000,
                    'bookings' => 35,
                    'new_users' => 15,
                    'new_hotels' => 4,
                    'avg_booking_value' => 62857
                ]
            ];
            
            $data = [
                'title' => 'Reports & Analytics',
                'metrics' => $metrics,
                'chart_data' => $chart_data,
                'top_hotels' => $top_hotels,
                'monthly_data' => $monthly_data
            ];
            
            echo View::renderWithLayout('admin/reports', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function hosts() {
        try {
            Auth::requireRole('admin');
            
            $hosts = $this->userService->getUsersByRole('host');
            $subscriptions = $this->subscriptionService->getAllSubscriptions();
            $hotels = $this->hotelService->getAllHotels();
            $bookings = $this->bookingService->getAllBookings();
            
            // Calculate statistics
            $stats = [
                'total_hosts' => count($hosts),
                'active_hosts' => count(array_filter($hosts, function($h) { return ($h['status'] ?? 'active') === 'active'; })),
                'pending_approval' => count(array_filter($hotels, function($h) { return $h['status'] === 'pending'; })),
                'total_hotels' => count($hotels)
            ];
            
            // Add additional data to hosts
            foreach ($hosts as &$host) {
                // Find subscription
                $hostSubscription = array_filter($subscriptions, function($s) use ($host) {
                    return $s['host_id'] == $host['id'] && $s['status'] === 'active';
                });
                $host['subscription'] = !empty($hostSubscription) ? reset($hostSubscription) : null;
                
                // Count hotels
                $hostHotels = array_filter($hotels, function($h) use ($host) {
                    return $h['host_id'] == $host['id'];
                });
                $host['hotel_count'] = count($hostHotels);
                $host['pending_hotels'] = count(array_filter($hostHotels, function($h) {
                    return $h['status'] === 'pending';
                }));
                
                // Count bookings and revenue
                $hostBookings = array_filter($bookings, function($b) use ($hostHotels) {
                    return in_array($b['hotel_id'], array_column($hostHotels, 'id'));
                });
                $host['booking_count'] = count($hostBookings);
                $host['pending_bookings'] = count(array_filter($hostBookings, function($b) {
                    return $b['status'] === 'pending';
                }));
                $host['total_revenue'] = array_sum(array_column($hostBookings, 'total_price'));
                
                // Mock verification status
                $host['is_verified'] = rand(0, 1) == 1;
            }
            
            $data = [
                'title' => 'Host Management',
                'hosts' => $hosts,
                'stats' => $stats
            ];
            
            echo View::renderWithLayout('admin/hosts', $data);
        } catch (Exception $e) {
            $this->renderError($e->getMessage());
        }
    }
    
    public function verifyHost() {
        try {
            Auth::requireRole('admin');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $hostId = $_POST['host_id'] ?? null;
            if (!$hostId) {
                throw new Exception("Host ID required");
            }
            
            // Update host verification status
            $this->userService->updateProfile($hostId, ['is_verified' => 1]);
            
            $_SESSION['success'] = 'Host verified successfully!';
            header('Location: ' . BASE_URL . '/admin/hosts');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/hosts');
            exit;
        }
    }
    
    public function blockHost() {
        try {
            Auth::requireRole('admin');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $hostId = $_POST['host_id'] ?? null;
            if (!$hostId) {
                throw new Exception("Host ID required");
            }
            
            $this->userService->blockUser($hostId);
            
            $_SESSION['success'] = 'Host blocked successfully!';
            header('Location: ' . BASE_URL . '/admin/hosts');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/hosts');
            exit;
        }
    }
    
    public function editHost() {
        try {
            Auth::requireRole('admin');
            
            $hostId = $_GET['host_id'] ?? null;
            if (!$hostId) {
                throw new Exception("Host ID required");
            }
            
            $host = $this->userService->getUserById($hostId);
            if (!$host) {
                throw new Exception("Host not found");
            }
            
            $data = [
                'title' => 'Edit Host',
                'host' => $host
            ];
            
            echo View::renderWithLayout('admin/edit-host', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/hosts');
            exit;
        }
    }
    
    public function bulkActionHosts() {
        try {
            Auth::requireRole('admin');
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Method not allowed");
            }
            
            $hostIds = json_decode($_POST['host_ids'] ?? '[]', true);
            $action = $_POST['action'] ?? '';
            
            if (empty($hostIds)) {
                throw new Exception("No hosts selected");
            }
            
            $count = 0;
            foreach ($hostIds as $hostId) {
                switch ($action) {
                    case 'verify':
                        $this->userService->updateProfile($hostId, ['is_verified' => 1]);
                        break;
                    case 'block':
                        $this->userService->blockUser($hostId);
                        break;
                    case 'unblock':
                        $this->userService->unblockUser($hostId);
                        break;
                }
                $count++;
            }
            
            $_SESSION['success'] = "Bulk action '{$action}' completed for {$count} host(s)!";
            header('Location: ' . BASE_URL . '/admin/hosts');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/hosts');
            exit;
        }
    }

    private function renderError($message) {
        echo "<h1>Error</h1>";
        echo "<p style='color: red;'>{$message}</p>";
        echo "<a href='" . BASE_URL . "/admin/dashboard'>Back to Dashboard</a>";
    }
}