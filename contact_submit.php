<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');

    if(empty($name) || empty($email) || empty($message)){
        echo json_encode(['status'=>'error','message'=>'All fields are required']);
        exit;
    }

    try {
        // Prepare statement
        $stmt = $pdo->prepare("INSERT INTO contacts (name,email,message) VALUES (:name, :email, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

        if($stmt->execute()){
            echo json_encode(['status'=>'success','message'=>'Message submitted successfully!']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Submission failed. Please try again later.']);
        }
    } catch(PDOException $e){
        error_log("DB Insert Error: ".$e->getMessage());
        echo json_encode(['status'=>'error','message'=>'Submission failed. Please try again later.']);
    }
} else {
    echo json_encode(['status'=>'error','message'=>'Invalid request']);
}
