<?php
include '../db.php'; // Database connection

$uploadDir = __DIR__ . "/uploads/"; // Adjusted for admin folder
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
$allowedVideoTypes = ['video/mp4', 'video/avi', 'video/mov'];
$maxFileSize = 10 * 1024 * 1024; // 10MB limit

$response = ['success' => false, 'filePath' => '', 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['image'])) {
        $uploadedFiles = [];

        foreach ($_FILES['image']['name'] as $key => $fileName) {
            $fileType = $_FILES['image']['type'][$key];
            $fileSize = $_FILES['image']['size'][$key];

            // Validate file type
            if (!in_array($fileType, array_merge($allowedImageTypes, $allowedVideoTypes))) {
                $response['error'] = "Invalid file type. Only JPG, PNG, GIF, MP4, AVI, and MOV are allowed.";
                echo json_encode($response);
                exit();
            }

            // Validate file size
            if ($fileSize > $maxFileSize) {
                $response['error'] = "File too large. Max 10MB allowed.";
                echo json_encode($response);
                exit();
            }

            // Rename file to avoid conflicts
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
            $uploadFilePath = $uploadDir . $newFileName;

            // Move file to uploads directory
            if (move_uploaded_file($_FILES['image']['tmp_name'][$key], $uploadFilePath)) {
                $uploadedFiles[] = $newFileName;
            }
        }

        if (!empty($uploadedFiles)) {
            $imagePaths = implode(",", $uploadedFiles);

            // Store file paths in the database
            $sql = "INSERT INTO properties (image) VALUES (:image)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([':image' => $imagePaths])) {
                $response['success'] = true;
                $response['filePath'] = $imagePaths;
            } else {
                $response['error'] = "Database error: Could not save image paths.";
            }
        }
    } else {
        $response['error'] = "No files uploaded.";
    }
}

echo json_encode($response);
