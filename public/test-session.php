<?php
// Session Test File
// Access this at: http://localhost/tour-guide/public/test-session.php

echo "<h1>Session Test</h1>";

// Check if session is started
echo "<h2>Session Status</h2>";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "<p><strong>Session Started:</strong> Yes</p>";
} else {
    echo "<p><strong>Session Started:</strong> Already started</p>";
}

// Test session variables
echo "<h2>Session Variables</h2>";
if (isset($_SESSION)) {
    echo "<p><strong>Session Variables:</strong></p>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    echo "<p><strong>Session Variables:</strong> None</p>";
}

// Test setting a session variable
if (isset($_GET['test'])) {
    $_SESSION['test_var'] = 'Session is working!';
    echo "<p><strong>Test Variable Set:</strong> " . $_SESSION['test_var'] . "</p>";
}

// Test CSRF token generation
echo "<h2>CSRF Token Test</h2>";
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
echo "<p><strong>CSRF Token:</strong> " . $_SESSION['csrf_token'] . "</p>";

// Test form
echo "<h2>Test Form</h2>";
echo "<form method='GET'>";
echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>";
echo "<button type='submit' name='test' value='1'>Test Session</button>";
echo "</form>";

// Test old input
echo "<h2>Old Input Test</h2>";
if (isset($_GET['old_test'])) {
    $_SESSION['old_input'] = ['test_field' => 'Old input test value'];
    echo "<p><strong>Old Input Set:</strong> " . $_SESSION['old_input']['test_field'] . "</p>";
}

echo "<form method='GET'>";
echo "<input type='text' name='test_field' placeholder='Enter test value'>";
echo "<button type='submit' name='old_test' value='1'>Test Old Input</button>";
echo "</form>";

echo "<h2>Session Configuration</h2>";
echo "<p><strong>Session Name:</strong> " . session_name() . "</p>";
echo "<p><strong>Session Save Path:</strong> " . session_save_path() . "</p>";
echo "<p><strong>Session Cookie Lifetime:</strong> " . ini_get('session.cookie_lifetime') . "</p>";
echo "<p><strong>Session Cookie Path:</strong> " . ini_get('session.cookie_path') . "</p>";
echo "<p><strong>Session Cookie Domain:</strong> " . ini_get('session.cookie_domain') . "</p>";
echo "<p><strong>Session Cookie Secure:</strong> " . (ini_get('session.cookie_secure') ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Session Cookie HttpOnly:</strong> " . (ini_get('session.cookie_httponly') ? 'Yes' : 'No') . "</p>";

echo "<h2>PHP Configuration</h2>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Session Auto Start:</strong> " . (ini_get('session.auto_start') ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Session Use Cookies:</strong> " . (ini_get('session.use_cookies') ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Session Use Only Cookies:</strong> " . (ini_get('session.use_only_cookies') ? 'Yes' : 'No') . "</p>";

echo "<hr>";
echo "<p><a href='test-session.php'>Refresh Page</a></p>";
echo "<p><a href='index.php'>Go to Main App</a></p>";
?>
