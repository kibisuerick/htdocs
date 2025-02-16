<?php
session_start();
require_once 'db.php'; // Include database connection

// Ensure CSRF token is set for security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Strengthen session security
session_regenerate_id(true); // Prevent session fixation attacks
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session cookies
ini_set('session.cookie_secure', 1); // Ensure cookies are sent only over HTTPS

// ✅ CSRF Protection: Verify token before processing the form
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("<script>
            alert('❌ CSRF attack detected! Form submission blocked.');
            window.location.href = 'signup.php';
        </script>");
}

// ✅ Process form submission when method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Password will be hashed before storage
    $terms = isset($_POST['terms']) ? 1 : 0; // Ensure terms checkbox is checked

    // ✅ Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('❌ Invalid email format.');
                window.location.href='signup.php';
              </script>";
        exit();
    }

    // ✅ Check for too many failed signup attempts (rate limiting)
    $stmt = $pdo->prepare("SELECT attempts, last_attempt FROM signup_attempts WHERE email = ?");
    $stmt->execute([$email]);
    $attempt = $stmt->fetch();

    if ($attempt && $attempt['attempts'] >= 5 && strtotime($attempt['last_attempt']) > time() - 900) { // 15-minute cooldown
        echo "<script>
                alert('❌ Too many signup attempts. Please try again later.');
                window.location.href = 'signup.php';
              </script>";
        exit();
    }

    // ✅ Validate that all required fields are filled
    if (!empty($name) && !empty($email) && !empty($password) && $terms) {
        try {
            // ✅ Check if email is already registered
            $stmt = $pdo->prepare("SELECT id FROM signup WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                echo "<script>
                        if (confirm('Email already exists. Proceed to login?')) {
                            window.location.href = 'login.php';
                        } else {
                            window.location.href = 'signup.php';
                        }
                      </script>";
                exit();
            }

            // ✅ Securely hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ✅ Insert new user into the database
            $stmt = $pdo->prepare("INSERT INTO signup (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hashed_password
            ]);

            // ✅ Insert or update signup attempt records
            if ($attempt) {
                $stmt = $pdo->prepare("UPDATE signup_attempts SET attempts = attempts + 1, last_attempt = NOW() WHERE email = ?");
                $stmt->execute([$email]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO signup_attempts (email, attempts) VALUES (?, 1)");
                $stmt->execute([$email]);
            }

            // ✅ Signup successful
            echo "<script>
                    alert('✅ Signup successful!');
                    window.location.href = 'signup.php';
                  </script>";
            exit();
            
        } catch (PDOException $e) {
            // Handle database errors
            echo "<script>
                    alert('❌ Signup failed: " . addslashes($e->getMessage()) . "');
                    window.location.href = 'signup.php';
                  </script>";
        }
    } else {
        // Handle missing required fields
        echo "<script>
                alert('❌ Please fill all fields and accept Terms & Conditions.');
                window.location.href = 'signup.php';
              </script>";
    }
}
?>
