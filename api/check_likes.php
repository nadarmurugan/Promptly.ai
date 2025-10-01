<?php
// /api/check_likes.php
// Returns an array of prompt IDs the current anonymous client has liked.

// Assuming your PDO database connection ($pdo) is available via db.php
require_once '../includes/db.php'; 

header('Content-Type: application/json');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$client_id = $input['client_id'] ?? ''; // Anonymous visitor ID

// --- 1. Validation ---
if (empty($client_id)) {
    http_response_code(400); 
    echo json_encode(['status' => 'error', 'message' => 'Missing client identifier.']);
    exit;
}

// --- 2. Query Liked Prompts ---
try {
    // SQL: Select the prompt_id for all likes associated with this client_id
    $stmt = $pdo->prepare("
        SELECT prompt_id 
        FROM prompt_likes 
        WHERE client_id = :client_id
    ");
    $stmt->bindParam(':client_id', $client_id);
    $stmt->execute();
    
    // Fetch all prompt_ids as a simple indexed array
    $liked_prompt_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // --- 3. Success Response ---
    echo json_encode([
        'status' => 'success', 
        'liked_ids' => $liked_prompt_ids 
    ]);

} catch (\PDOException $e) {
    http_response_code(500); 
    error_log("Check likes error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error during lookup.']);
}
?>