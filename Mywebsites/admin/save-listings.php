<?php
include '../db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $county = filter_input(INPUT_POST, 'county', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $region = filter_input(INPUT_POST, 'region', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $po_box = filter_input(INPUT_POST, 'po_box', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $year_built = filter_input(INPUT_POST, 'year_built', FILTER_VALIDATE_INT);
    $rooms = filter_input(INPUT_POST, 'rooms', FILTER_VALIDATE_INT);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $landmark = filter_input(INPUT_POST, 'landmark', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
    $sql = "INSERT INTO properties (title, description, price, type, status, image, created_at, rooms, address, landmark, county, region, po_box, country, year_built) 
            VALUES (:title, :description, :price, :type, :status, :image, :created_at, :rooms, :address, :landmark, :county, :region, :po_box, :country, :year_built)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':type' => $type,
            ':status' => $status,
            ':image' => $imagePaths, // Stores multiple images as comma-separated values
            ':created_at' => $created_at,
            ':rooms' => $rooms,
            ':address' => $address,
            ':landmark' => $landmark,
            ':county' => $county,
            ':region' => $region,
            ':po_box' => $po_box,
            ':country' => $country,
            ':year_built' => $year_built,
        ]);

        // Redirect with success message
        header("Location: create-listing.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    // Redirect if the form is not submitted
    header("Location: create-listing.php");
    exit();
}
?>