<?php
include '../db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $property_type = filter_input(INPUT_POST, 'property_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $bedrooms = filter_input(INPUT_POST, 'bedrooms', FILTER_VALIDATE_INT);
    $bedrooms = $bedrooms !== false ? $bedrooms : 0; // Default to 0 if invalid

    $amenities = isset($_POST['amenities']) ? implode(", ", $_POST['amenities']) : "";
    $created_at = date('Y-m-d H:i:s');

    // Image Upload Handling
    $imagePaths = ""; // Stores paths of uploaded images
    if (!empty($_FILES['image']['name'][0])) {
        $uploadDir = '../admin/uploads/';
        $uploadedImages = [];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB limit

        foreach ($_FILES['image']['name'] as $key => $imageName) {
            $fileType = $_FILES['image']['type'][$key];
            $fileSize = $_FILES['image']['size'][$key];

            // Validate file type and size BEFORE uploading
            if (!in_array($fileType, $allowedTypes)) {
                die("Invalid file type: Only JPG, PNG, and GIF allowed.");
            }
            if ($fileSize > $maxFileSize) {
                die("File too large: Max 5MB allowed.");
            }

            $fileName = time() . '_' . basename($imageName);
            $uploadFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'][$key], $uploadFilePath)) {
                $uploadedImages[] = $fileName;
            }
        }

        // Merge new images with existing ones
        if (!empty($imagePaths)) {
            $existingImages = explode(",", $imagePaths);
            $imagePaths = implode(",", array_merge($existingImages, $uploadedImages));
        } else {
            $imagePaths = implode(",", $uploadedImages);
        }
    }

    // Insert into database
    $sql = "INSERT INTO properties (title, description, location, price, type, status, city, state, property_type, bedrooms, amenities, image, created_at) 
            VALUES (:title, :description, :location, :price, :type, :status, :city, :state, :property_type, :bedrooms, :amenities, :image, :created_at)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':location' => $location,
            ':price' => $price,
            ':type' => $type,
            ':status' => $status,
            ':city' => $city,
            ':state' => $state,
            ':property_type' => $property_type,
            ':bedrooms' => $bedrooms,
            ':amenities' => $amenities,
            ':image' => $imagePaths, // Stores multiple images as comma-separated values
            ':created_at' => $created_at,
        ]);

        // Redirect with success message
        header("Location: create-listing1.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    // Redirect if the form is not submitted
    header("Location: create-listing1.php");
    exit();
}
?>
