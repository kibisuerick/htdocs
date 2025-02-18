<?php
require '../db.php'; // Ensure the database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["salesperson_image"])) {
    $salespersonId = $_POST['salesperson_id'];

    // Folder where images will be saved
    $targetDir = "uploads/";
    $fileName = basename($_FILES["salesperson_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Move uploaded file
    if (move_uploaded_file($_FILES["salesperson_image"]["tmp_name"], $targetFilePath)) {
        // Save file path in database
        $stmt = $pdo->prepare("UPDATE sales_report SET image_path = ? WHERE id = ?");
        $stmt->execute([$targetFilePath, $salespersonId]);

        // Redirect back to dashboard after a successful upload
        header("Location: dashboard.php");
        exit();
    } else {
        // Return JSON response for error
        echo json_encode(["status" => "error", "message" => "Error uploading image"]);
    }
}
?>
