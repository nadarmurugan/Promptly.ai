<?php
// admins/delete_contact.php
// Handles the deletion of a contact message using PDO.

// 1. Start the session and include core files
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// 2. Security check: Redirect non-admins
if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

// 3. Input Validation: Only allow GET method for simplicity here (since it's a link action), 
// and ensure a valid integer ID is present.
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: index.php?error=' . urlencode('Invalid request or contact ID provided for deletion.'));
    exit;
}

$contact_id = (int)$_GET['id'];

// 4. Execute Deletion using the helper function
if (deleteContact($contact_id)) {
    // Redirect with success message
    header('Location: index.php?success=' . urlencode("Contact message #{$contact_id} has been successfully deleted."));
    exit;
} else {
    // Redirect with error message (e.g., failed to find ID, or database error)
    header('Location: index.php?error=' . urlencode("Failed to delete contact message #{$contact_id}. It may not exist or a database error occurred."));
    exit;
}
?>
