<?php
// db.php - Database connection using PDO

$host     = 'localhost';
$dbname   = 'centralised_property_reservation_platform'; // Updated database name
$username = 'root';
$password = ''; // Default XAMPP password

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
