<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prompt_id'])) {
    $prompt_id = intval($_POST['prompt_id']);
    // Optional: log copy event
    try {
        $stmt = $pdo->prepare("UPDATE prompts SET copied_count = IFNULL(copied_count,0)+1 WHERE id=?");
        $stmt->execute([$prompt_id]);
        echo json_encode(['status' => 'success', 'message' => 'Prompt copied']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
