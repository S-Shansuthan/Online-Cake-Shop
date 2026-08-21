<?php
// config/database.php - Shared Configuration (Member 4)

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'cake_shop');

function getDBConnection() {
    $dbConnection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($dbConnection->connect_error) {
        die("Database Connection Failed: " . $dbConnection->connect_error);
    }
    
    // Set charset for security
    $dbConnection->set_charset("utf8mb4");
    
    return $dbConnection;
}
?>
