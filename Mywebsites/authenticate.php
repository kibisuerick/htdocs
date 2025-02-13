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
        // If passwords are stored hashed, use password_verify()
        if (password_verify($password, $user['password'])) {
            // Successful login: set session variable and redirect
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            exit;
        }
    }
    // If authentication fails, redirect back to login.php with an error flag
    header("Location: login.php?error=1");
    exit;
} else {
    // If form data is missing, redirect back to login.php with an error flag
    header("Location: login.php?error=1");
    exit;
}
