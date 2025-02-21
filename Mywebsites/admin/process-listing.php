<?php
// Include the database connection file
include '../db.php'; // Ensure this file correctly connects to your database

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    echo "Debug 1: Form received.<br>";

    // Validate and sanitize input data
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $created_at = date('Y-m-d H:i:s');

    echo "Debug 2: Data collected.<br>";

    // Handle Image Upload
    $targetDir = "../admin/uploads/"; // Ensure this directory exists and is writable
    $imageName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $imageName;

    // Validate file upload
    if (!empty($_FILES["image"]["tmp_name"])) {
        // Check if the file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }

        // Check file size (e.g., 5MB limit)
        if ($_FILES["image"]["size"] > 5000000) {
            die("File is too large. Maximum size allowed is 5MB.");
        }

        // Allow only specific file formats
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, $allowedFormats)) {
            die("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            echo "Debug 3: Image uploaded.<br>";

            // Insert into database using PDO
            $sql = "INSERT INTO properties (title, description, location, price, type, status, image, created_at) 
                    VALUES (:title, :description, :location, :price, :type, :status, :image, :created_at)";

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':location', $location);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':image', $imageName);
                $stmt->bindParam(':created_at', $created_at);

                if ($stmt->execute()) {
                    echo "Debug 4: Data inserted successfully.<br>";

                    // Redirect with success message
                    header("Location: Create-listing.php?success=1");
                    exit();
                } else {
                    die("Database error: Unable to execute the query.");
                }
            } catch (PDOException $e) {
                die("Database error: " . $e->getMessage());
            }
        } else {
            die("File upload failed. Please try again.");
        }
    } else {
        die("No file uploaded.");
    }
} else {
    // Redirect if the form is not submitted
    header("Location: Create-listing.php");
    exit();
}

// Properly close PDO connection
$pdo = null;
?>