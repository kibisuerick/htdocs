<?php
if (!empty($_FILES)) {
    $targetDir = "../admin/uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . '_' . basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
    $maxFileSize = 5 * 1024 * 1024;

    if (in_array($_FILES["file"]["type"], $allowedTypes) && $_FILES["file"]["size"] <= $maxFileSize) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            echo json_encode(["status" => "success", "file" => $fileName]);
        } else {
            echo json_encode(["status" => "error", "message" => "File upload failed."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid file type or size."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded."]);
}
?>
