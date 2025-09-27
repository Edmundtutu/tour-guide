<?php
// Debug Login Process
// Access this at: http://localhost/tour-guide/public/debug-login.php

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load core classes
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/BaseModel.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/View.php';

// Load services and models
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/services/UserService.php';

echo "<h1>Login Debug Process</h1>";

// Test 1: Session Status
echo "<h2>1. Session Status</h2>";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Session Variables:</strong></p>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Test 2: CSRF Token
echo "<h2>2. CSRF Token Test</h2>";
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
echo "<p><strong>CSRF Token:</strong> " . $_SESSION['csrf_token'] . "</p>";

// Test 3: Database Connection
echo "<h2>3. Database Connection</h2>";
try {
    $db = Database::getInstance();
    echo "<p><strong>Database Connection:</strong> ✅ Success</p>";
} catch (Exception $e) {
    echo "<p><strong>Database Connection:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
}

// Test 4: User Model
echo "<h2>4. User Model Test</h2>";
try {
    $userModel = new User();
    echo "<p><strong>User Model:</strong> ✅ Created successfully</p>";
    
    // Test finding a user
    $testUser = $userModel->findByEmail('test@example.com');
    if ($testUser) {
        echo "<p><strong>Test User Found:</strong> ✅ " . $testUser['name'] . "</p>";
    } else {
        echo "<p><strong>Test User Found:</strong> ❌ No test user found</p>";
    }
} catch (Exception $e) {
    echo "<p><strong>User Model:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
}

// Test 5: UserService
echo "<h2>5. UserService Test</h2>";
try {
    $userService = new UserService();
    echo "<p><strong>UserService:</strong> ✅ Created successfully</p>";
} catch (Exception $e) {
    echo "<p><strong>UserService:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
}

// Test 6: Auth Class
echo "<h2>6. Auth Class Test</h2>";
try {
    echo "<p><strong>Auth::startSession():</strong> ";
    Auth::startSession();
    echo "✅ Called successfully</p>";
    
    echo "<p><strong>Auth::isLoggedIn():</strong> ";
    $isLoggedIn = Auth::isLoggedIn();
    echo ($isLoggedIn ? "✅ Yes" : "❌ No") . "</p>";
    
    echo "<p><strong>Auth::getCurrentUser():</strong> ";
    $currentUser = Auth::getCurrentUser();
    if ($currentUser) {
        echo "✅ " . $currentUser['name'] . " (" . $currentUser['role'] . ")</p>";
    } else {
        echo "❌ No current user</p>";
    }
} catch (Exception $e) {
    echo "<p><strong>Auth Class:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
}

// Test 7: Login Process Simulation
echo "<h2>7. Login Process Simulation</h2>";
if (isset($_POST['test_login'])) {
    echo "<h3>Testing Login Process...</h3>";
    
    try {
        // Simulate POST data
        $email = $_POST['email'] ?? 'test@example.com';
        $password = $_POST['password'] ?? 'password';
        
        echo "<p><strong>Email:</strong> " . $email . "</p>";
        echo "<p><strong>Password:</strong> " . str_repeat('*', strlen($password)) . "</p>";
        
        // Test CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("Invalid CSRF token");
        }
        echo "<p><strong>CSRF Token:</strong> ✅ Valid</p>";
        
        // Test UserService login
        $userService = new UserService();
        $user = $userService->login($email, $password);
        
        echo "<p><strong>Login Result:</strong> ✅ Success - " . $user['name'] . "</p>";
        echo "<p><strong>User Role:</strong> " . $user['role'] . "</p>";
        
    } catch (Exception $e) {
        echo "<p><strong>Login Result:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
    }
}

// Test Form
echo "<h2>8. Test Login Form</h2>";
echo "<form method='POST'>";
echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>";
echo "<div class='mb-3'>";
echo "<label for='email' class='form-label'>Email:</label>";
echo "<input type='email' class='form-control' id='email' name='email' value='test@example.com' required>";
echo "</div>";
echo "<div class='mb-3'>";
echo "<label for='password' class='form-label'>Password:</label>";
echo "<input type='password' class='form-control' id='password' name='password' value='password' required>";
echo "</div>";
echo "<button type='submit' name='test_login' class='btn btn-primary'>Test Login</button>";
echo "</form>";

// Test 9: Create Test User
echo "<h2>9. Create Test User</h2>";
if (isset($_POST['create_test_user'])) {
    try {
        $userService = new UserService();
        $testData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'tourist'
        ];
        
        $user = $userService->register($testData);
        echo "<p><strong>Test User Created:</strong> ✅ " . $user['name'] . "</p>";
    } catch (Exception $e) {
        echo "<p><strong>Test User Creation:</strong> ❌ Failed - " . $e->getMessage() . "</p>";
    }
}

echo "<form method='POST'>";
echo "<button type='submit' name='create_test_user' class='btn btn-success'>Create Test User</button>";
echo "</form>";

echo "<hr>";
echo "<p><a href='debug-login.php'>Refresh</a> | <a href='index.php'>Go to Main App</a></p>";
?>
