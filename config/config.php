<?php
// Load environment variables from .env file if it exists
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Set as environment variable if not already set
            if (!getenv($key)) {
                putenv("$key=$value");
            }
        }
    }
}

// Environment configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'tour_guide');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Application settings
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/tour-guide');
define('APP_NAME', getenv('APP_NAME') ?: 'Tour Guide');

// Security settings
define('SESSION_TIMEOUT', getenv('SESSION_TIMEOUT') ?: 3600); // 1 hour
define('PASSWORD_MIN_LENGTH', getenv('PASSWORD_MIN_LENGTH') ?: 6);

// Subscription settings
define('MONTHLY_SUBSCRIPTION_FEE', getenv('MONTHLY_SUBSCRIPTION_FEE') ?: 50000); // UGX
define('ANNUAL_SUBSCRIPTION_FEE', getenv('ANNUAL_SUBSCRIPTION_FEE') ?: 500000); // UGX
