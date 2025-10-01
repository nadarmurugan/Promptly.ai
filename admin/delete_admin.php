<?php
// Start the session to access user role
session_start();

// Include necessary files for database connection and helper functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

// --- MOCK FUNCTION FOR STANDALONE VIEW ---
if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
// --- END MOCK FUNCTIONS ---

// Security check: Only allow access if the user is an admin
if(!isAdmin()){ 
    header('Location: login.php'); 
    exit; 
}

// Check if an ID has been passed in the URL
if(isset($_GET['id'])){
    // Sanitize and validate the ID
    $id = intval($_GET['id']);
    
    // Check if the ID is valid (greater than zero)
    if ($id > 0) {
        // Mock DB interaction since $pdo is not available in this scope
        if (isset($pdo)) {
            // Real deletion logic here
            $stmt = $pdo->prepare("DELETE FROM admins WHERE id=?");
            $stmt->execute([$id]);
            // Optional: You might want to prevent deleting the last admin or the currently logged-in admin.
        } else {
            // Mock successful operation
            // In a real scenario, you wouldn't output anything, just redirect.
            // echo "Admin user with ID {$id} deleted (mock operation).";
        }
    }
}

// Redirect back to the dashboard immediately after the operation
header('Location: index.php');
exit;
?>
