<?php
// Database Setup Script
// Run this to set up the database with demo data

// Database configuration
$host = 'localhost';
$dbname = 'tour_guide';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Database Setup for Tour Guide</h1>";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "<p>✅ Database '$dbname' created/verified</p>";
    
    // Use the database
    $pdo->exec("USE $dbname");
    
    // Read and execute schema
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $statements = explode(';', $schema);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^(CREATE DATABASE|USE)/i', $statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Ignore errors for existing tables
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "<p>⚠️ Warning: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
    echo "<p>✅ Database schema created/updated</p>";
    
    // Run migration if needed
    $migration = file_get_contents(__DIR__ . '/migration_add_user_status.sql');
    $migrationStatements = explode(';', $migration);
    
    foreach ($migrationStatements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^(USE|SELECT)/i', $statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Ignore errors for existing data
                if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                    echo "<p>⚠️ Migration Warning: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
    echo "<p>✅ Migration completed</p>";
    
    // Verify tables exist
    $tables = ['users', 'hotels', 'bookings', 'subscriptions', 'destinations'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p>✅ Table '$table' exists</p>";
        } else {
            echo "<p>❌ Table '$table' missing</p>";
        }
    }
    
    // Check if demo users exist
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE email LIKE '%@demo.com'");
    $demoUsers = $stmt->fetch()['count'];
    echo "<p>✅ Demo users: $demoUsers found</p>";
    
    // Check if demo hotels exist
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM hotels");
    $hotels = $stmt->fetch()['count'];
    echo "<p>✅ Hotels: $hotels found</p>";
    
    // Check if demo bookings exist
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM bookings");
    $bookings = $stmt->fetch()['count'];
    echo "<p>✅ Bookings: $bookings found</p>";
    
    echo "<hr>";
    echo "<h2>Demo Accounts</h2>";
    echo "<p><strong>Tourist:</strong> tourist@demo.com / password</p>";
    echo "<p><strong>Host:</strong> host@demo.com / password</p>";
    echo "<p><strong>Admin:</strong> admin@demo.com / password</p>";
    echo "<p><strong>Blocked User:</strong> blocked@demo.com / password</p>";
    echo "<p><strong>Inactive User:</strong> inactive@demo.com / password</p>";
    
    echo "<hr>";
    echo "<p><strong>✅ Database setup completed successfully!</strong></p>";
    echo "<p><a href='../public/index.php'>Go to Application</a></p>";
    
} catch (PDOException $e) {
    echo "<h1>Database Setup Error</h1>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in config/config.php</p>";
}
?>
