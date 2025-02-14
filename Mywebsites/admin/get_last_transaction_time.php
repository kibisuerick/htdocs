<?php
session_start();
require_once '../db.php'; // Adjust the path if needed

if (!isset($_SESSION['email'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

try {
    // Get the latest transaction timestamp
    $stmt = $pdo->prepare("
        SELECT created_at 
        FROM transactions 
        WHERE status = 'completed' 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->execute();
    $lastTransaction = $stmt->fetchColumn();

    if ($lastTransaction) {
        echo json_encode(["last_transaction" => $lastTransaction]);
    } else {
        echo json_encode(["last_transaction" => null]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error"]);
}
?>
