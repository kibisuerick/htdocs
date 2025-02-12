<?php
// login_model.inc.php
declare(strict_types=1);
require_once 'dbconnect.php';

function verify_user(string $email, string $password, PDO $pdo): mixed {
    try {
        // Select the full user record from the signup table
        $stmt = $pdo->prepare("SELECT * FROM signup WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify the password if a user record is found
        if ($user && !empty($user["password"]) && password_verify($password, $user["password"])) {
            return $user; // Return the user record
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
    return false;
}
?>
