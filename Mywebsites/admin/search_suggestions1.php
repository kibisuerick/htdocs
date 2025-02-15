<?php
require_once "../db.php"; // Ensure correct path

if (isset($_GET['query'])) {
    $query = "%" . $_GET['query'] . "%";

    try {
        $stmt = $pdo->prepare("SELECT DISTINCT location FROM properties WHERE location LIKE ? LIMIT 10");
        $stmt->execute([$query]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database query failed: " . $e->getMessage()]);
    }
}
?>
