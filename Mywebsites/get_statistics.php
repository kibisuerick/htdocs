<?php
header('Content-Type: application/json');
require_once 'db.php'; // Use the existing PDO connection

try {
    $stmt = $pdo->query("SELECT property_name, views FROM properties ORDER BY views DESC LIMIT 5");
    $data = $stmt->fetchAll();
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
