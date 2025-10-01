<?php
// /api/toggle_like.php
// Handles liking and unliking a prompt using an Anonymous Client ID.

// Assuming your PDO database connection ($pdo) is available via db.php
require_once '../includes/db.php'; 

header('Content-Type: application/json');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$prompt_id = $input['prompt_id'] ?? 0;
$action    = $input['action'] ?? ''; // 'like' or 'unlike'
$client_id = $input['client_id'] ?? ''; // Anonymous visitor ID from local storage

// --- 1. Validation ---
if (!is_numeric($prompt_id) || (int)$prompt_id <= 0 || !in_array($action, ['like', 'unlike']) || empty($client_id)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request data.']);
    exit;
}

$prompt_id = (int) $prompt_id;

// --- 2. Execute Transaction ---
try {
    $pdo->beginTransaction();

    if ($action === 'like') {
        // SQL: Insert a new like. The UNIQUE index prevents duplicates.
        $stmt = $pdo->prepare("
            INSERT INTO prompt_likes (client_id, prompt_id, liked_at) 
            VALUES (:client_id, :prompt_id, NOW())
        ");
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':prompt_id', $prompt_id);
        $stmt->execute();
        $message = "Prompt liked.";
        
    } elseif ($action === 'unlike') {
        // SQL: Delete the existing like record.
        $stmt = $pdo->prepare("
            DELETE FROM prompt_likes 
            WHERE client_id = :client_id AND prompt_id = :prompt_id
        ");
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':prompt_id', $prompt_id);
        $stmt->execute();
        $message = "Prompt unliked.";
    }

    // --- 3. Get New Total Like Count ---
    $count_stmt = $pdo->prepare("
        SELECT COUNT(id) AS new_likes 
        FROM prompt_likes 
        WHERE prompt_id = :prompt_id
    ");
    $count_stmt->bindParam(':prompt_id', $prompt_id);
    $count_stmt->execute();
    $new_likes_count = $count_stmt->fetchColumn();

    $pdo->commit();

    // --- 4. Success Response ---
    echo json_encode([
        'status' => 'success', 
        'message' => $message,
        'new_count' => (int) $new_likes_count,
        'prompt_id' => $prompt_id
    ]);

} catch (\PDOException $e) {
    $pdo->rollBack();

    // Specific check for UNIQUE constraint violation (Error code 23000)
    if ($e->getCode() == '23000' && $action === 'like') {
        http_response_code(409); // Conflict
        echo json_encode(['status' => 'error', 'message' => 'Already liked.']);
    } else {
        http_response_code(500); // Internal Server Error
        error_log("Toggle like error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'A database error occurred.']);
    }
}
?>