<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Force PDO to throw exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitizeInput($_POST['name'] ?? '');
    $email   = sanitizeInput($_POST['email'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    try {
        // ✅ OPTIONAL: prevent duplicate emails (uncomment if needed)
        /*
        $check = $pdo->prepare("SELECT COUNT(*) FROM contacts WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'This email already submitted a message']);
            exit;
        }
        */

        // Insert new record
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);

        echo json_encode(['status' => 'success', 'message' => 'Message submitted successfully!']);
    } catch (PDOException $e) {
        // ⚠️ Debug mode - log the exact error
        echo json_encode([
            'status'  => 'error',
            'message' => 'Failed to submit message',
            'error'   => $e->getMessage()  // 🔍 shows actual error
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
