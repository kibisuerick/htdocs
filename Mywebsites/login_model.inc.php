<?php
declare(strict_types=1);

require_once 'dbconnect.php'; // Ensure database connection is available

function verify_user(string $email, string $password, PDO $pdo): bool {
    try {
        // Prepare the query to check if the user exists
        $stmt = $pdo->prepare("SELECT password FROM signup WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ensure a password exists before verifying
        if ($user && !empty($user["password"]) && password_verify($password, $user["password"])) {
            return true; // Password matches
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log errors instead of exposing them
    }
    
    return false; // User does not exist or password incorrect
}
?>
