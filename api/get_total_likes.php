<?php
// /api/check_likes.php
// Returns an array of prompt IDs the current anonymous client has liked.

// Assuming your PDO database connection ($pdo) is available via db.php
require_once '../includes/db.php'; 

header('Content-Type: application/json');

$stmt = $pdo->query("SELECT prompt_id, COUNT(*) as total_likes FROM likes_table GROUP BY prompt_id");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$like_data = [];
foreach ($results as $row) {
    $like_data[(int)$row['prompt_id']] = (int)$row['total_likes'];
}

header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'likes' => $like_data]);
?>