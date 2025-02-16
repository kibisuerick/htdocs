<?php
// Enable error reporting for debugging (recommended to disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Start session to store user login state
require_once 'db.php'; // Ensure correct path to db.php

// Process form submission and Error handlers
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $terms = isset($_POST['terms']) ? 1 : 0; // Ensure terms checkbox is checked

    // Validate that all required fields are filled
    if (!empty($name) && !empty($email) && !empty($_POST['password']) && $terms) {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM signup WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                // JavaScript confirm() message with redirect on "OK" and return to signup on "Cancel"
                echo "<script>
                        if (confirm('Email already exists. Proceed to login?')) {
                            window.location.href = '../login.php';
                        } else {
                            window.location.href = 'signup.php';
                        }
                      </script>";
                exit();
            }

            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO signup (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]);

            // ✅ If signup is successful, automatically log in the user
            $_SESSION['email'] = $email;

            // ✅ Redirect the user to dashboard.php
            echo "<script>
                    alert('✅ Signup successful! Redirecting to your dashboard...');
                    window.location.href = '../admin/dashboard.php';
                  </script>";
            exit();
            
        } catch (PDOException $e) {
            // ❌ If database insertion fails, alert the user with the error message
            echo "<script>
                    alert('❌ Signup failed: " . addslashes($e->getMessage()) . "');
                    window.location.href = 'signup.php';
                  </script>";
        }
    } else {
        // ❌ If validation fails, alert the user to fill in all fields
        echo "<script>
                alert('❌ Please fill all fields and accept Terms & Conditions.');
                window.location.href = 'signup.php';
              </script>";
    }
}
?>
