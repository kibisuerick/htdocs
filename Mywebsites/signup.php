<?php
// Enable error reporting for debugging (recommended to disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection credentials
$host = 'localhost';
$dbname = 'centralised_property_reservation_platform';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO to throw exceptions on errors for debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If database connection fails, stop execution and display an error message
    die("❌ Database connection failed: " . $e->getMessage());
}

// Check if the request is a POST request (form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $terms = isset($_POST['terms']) ? 1 : 0; // Ensure terms checkbox is checked

    // Validate that all required fields are filled
    if (!empty($name) && !empty($email) && !empty($_POST['password']) && $terms) {
        try {
            // Prepare an SQL statement to insert user data into the signup table
            $stmt = $pdo->prepare("INSERT INTO signup (name, email, password) VALUES (:name, :email, :password)");
            
            // Execute the SQL statement with provided user input
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]);

            // ✅ If signup is successful, alert the user and redirect to signup.html
            echo "<script>alert('✅ Signup successful!'); window.location.replace('signup.html');</script>";
            exit();
            
        } catch (PDOException $e) {
            // ❌ If database insertion fails, alert the user with the error message
            echo "<script>alert('❌ Signup failed: " . addslashes($e->getMessage()) . "'); window.location.href='signup.html';</script>";
        }
    } else {
        // ❌ If validation fails, alert the user to fill in all fields
        echo "<script>alert('❌ Please fill all fields and accept Terms & Conditions.'); window.location.href='signup.html';</script>";
    }
}
?>
