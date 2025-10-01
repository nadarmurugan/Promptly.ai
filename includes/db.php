<?php
// includes/db.php
// Database connection using PDO

$host = 'localhost';
$dbname = 'promptly_db';
$user = 'root';
$pass = '';

try {
    // Create PDO instance
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Fetch results as associative arrays
            PDO::ATTR_EMULATE_PREPARES => false,                 // Use real prepared statements
        ]
    );
} catch (PDOException $e) {
    // Log detailed error for debugging (server-side)
    error_log("Database connection failed: " . $e->getMessage());

    // Display generic message to the user
    die("Database connection failed. Please try again later.");
}
