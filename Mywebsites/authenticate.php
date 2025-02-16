<?php
session_start();
require_once 'db.php'; // Include the PDO database connection

// ✅ Handle email verification if a token is provided
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $stmt = $pdo->prepare("SELECT id FROM signup WHERE verification_token = ? AND verified = 0");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Mark user as verified
        $stmt = $pdo->prepare("UPDATE signup SET verified = 1, verification_token = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);

        echo "<script>
                alert('✅ Email verified successfully! You can now log in.');
                window.location.href = 'login.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('❌ Invalid or expired verification link.');
                window.location.href = 'signup.php';
              </script>";
        exit();
    }
}

// ✅ Handle login authentication
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Sanitize and trim the input data
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ Check if the email exists
    $stmt = $pdo->prepare("SELECT * FROM signup WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        // ❌ No account found with this email
        echo "<script>
                alert('❌ No account found with this email. Please sign up first.');
                window.location.href = 'signup.php';
              </script>";
        exit();
    }

    // Ensure the user is verified before allowing login
    if ($user['verified'] == 0) {
        echo "<script>
                alert('❌ Please verify your email before logging in.');
                window.location.href = 'login.php';
              </script>";
        exit();
    }

    // ✅ Check if the password is correct
    if (password_verify($password, $user['password'])) {
        // Successful login: set session variable and redirect
        $_SESSION['email'] = $email;
        header("Location: admin/dashboard.php");
        exit();
    }

    // ❌ Incorrect password
    header("Location: login.php?error=1");
    exit();
} else {
    // ❌ If form data is missing, redirect back to login.php
    header("Location: login.php?error=1");
    exit();
}
?>
