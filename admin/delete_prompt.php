<?php
// Start the session to access user role
session_start();

// Include necessary files for database connection and helper functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

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
        // Prepare and execute the DELETE statement
        // Note: The $pdo object is assumed to be available from '../includes/db.php'
        $stmt = $pdo->prepare("DELETE FROM prompts WHERE id=?");
        $stmt->execute([$id]);
        
        // Optional: You could check $stmt->rowCount() to see if a row was actually deleted
    }
}

// Redirect back to the dashboard immediately after the operation
header('Location: index.php');
exit;
?>
