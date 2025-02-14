<?php
require_once '../db.php'; // Ensure correct path to db.php

try {
    // Fetch the last balance update timestamp
    $stmt = $pdo->prepare("
        SELECT created_at FROM transactions 
        WHERE status = 'completed' 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->execute();
    $lastTransactionTime = $stmt->fetchColumn();

    if ($lastTransactionTime) {
        $timeAgo = strtotime($lastTransactionTime);
        $currentTime = time();
        $timeDifference = $currentTime - $timeAgo;

        if ($timeDifference < 60) {
            $lastBalanceUpdate = "Just now";
        } elseif ($timeDifference < 3600) {
            $minutes = floor($timeDifference / 60);
            $lastBalanceUpdate = "$minutes minute(s) ago";
        } elseif ($timeDifference < 86400) {
            $hours = floor($timeDifference / 3600);
            $lastBalanceUpdate = "$hours hour(s) ago";
        } else {
            $days = floor($timeDifference / 86400);
            $lastBalanceUpdate = "$days day(s) ago";
        }
    } else {
        $lastBalanceUpdate = "No recent transactions";
    }

    // Return JSON response
    echo json_encode(["lastUpdate" => $lastBalanceUpdate]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["lastUpdate" => "Error fetching data"]);
}
?>
