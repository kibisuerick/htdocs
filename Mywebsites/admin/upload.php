<?php
if (!empty($_FILES['file'])) {
    $uploadDir = "uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $uploadDir . $fileName;

    // Allowed file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            echo "File uploaded successfully.";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid file type.";
    }
}
?>
