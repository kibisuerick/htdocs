<?php

// Database connection
$host = "localhost";
$dbname = "centralised_property_reservation_platform";
$username = "root"; // Default XAMPP username
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Successfully connected"; // Display success message
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>
