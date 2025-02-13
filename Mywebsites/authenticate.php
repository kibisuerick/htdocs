<?php 
session_start();
require_once 'db.php'; // Include the PDO database connection

if (isset($_POST['email']) && isset($_POST['password'])) {
    // Sanitize and trim the input data
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare SQL to fetch the user by email
    $stmt = $pdo->prepare("SELECT * FROM signup WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Check if a user was found and verify the password
    if ($user) {
        // Ensure passwords are hashed in the database
        if (password_verify($password, $user['password'])) {
            // Successful login: set session variable and redirect
            $_SESSION['email'] = $user['email']; // Ensure consistency
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: login.php?error=invalid_credentials"); // More specific error message
            exit;
        }
    } else {
        header("Location: login.php?error=invalid_credentials");
        exit;
    }
} else {
    header("Location: login.php?error=missing_data");
    exit;
}
