<?php
// admins/logout.php
// Securely destroys the session and redirects the user to the login page.

// 1. Start the session
session_start();

// 2. Clear all session variables
$_SESSION = [];

// 3. Destroy the session itself
session_destroy();

// 4. Redirect the user to the login page
header('Location: login.php');
exit;
?>
