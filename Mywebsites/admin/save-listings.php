<?php
include '../db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $property_type = filter_input(INPUT_POST, 'property_type', FILTER_SANITIZE_STRING);
    $bedrooms = filter_input(INPUT_POST, 'bedrooms', FILTER_SANITIZE_NUMBER_INT);
    $amenities = isset($_POST['amenities']) ? implode(", ", $_POST['amenities']) : "";
    $created_at = date('Y-m-d H:i:s');

    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../admin/uploads/';
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $uploadFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFilePath)) {
            $imagePath = $fileName;
        } else {
            die("File upload failed.");
        }
    }

    $sql = "INSERT INTO properties (city, state, property_type, bedrooms, amenities, image, created_at) 
            VALUES (:city, :state, :property_type, :bedrooms, :amenities, :image, :created_at)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':city' => $city,
            ':state' => $state,
            ':property_type' => $property_type,
            ':bedrooms' => $bedrooms,
            ':amenities' => $amenities,
            ':image' => $imagePath,
            ':created_at' => $created_at,
        ]);

        header("Location: create-listing1.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: create-listing1.php");
    exit();
}
?>
