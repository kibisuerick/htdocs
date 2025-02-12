<?php
// dbconnect.php
$host = "localhost";
$dbname = "centralised_property_reservation_platform";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally, echo a message for debugging (remove in production)
    // echo "Successfully connected";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
