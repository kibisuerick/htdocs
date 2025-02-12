<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "centralised_property_reservation_platform";
$username = "root"; // Default XAMPP username
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Process form submission and Error handlers
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        // ✅ Login successful, create session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        
        header("Location: dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        echo "<script>alert('Invalid email or password'); window.location.href='login.html';</script>";
    }
}
?>
