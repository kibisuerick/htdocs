<?php
declare(strict_types=1);

require_once 'dbconnect.php'; // Ensure database connection is available

function is_input_empty(string $email, string $password): bool {
    if (empty($email) || empty($password)) { 
        return true;
    } else {
        return false;
    }
}

function is_email_wrong(bool|array $result): bool {    
    if (is_array($result)) {
        return empty($result); // If array is empty, return true (email is wrong)
    }
    return !$result;
}

function is_password_wrong(string $password, string $hashedpassword): bool {
    // Check if the password matches the hashed version
    if (!password_verify($password, $hashedpassword)) {
        return true; // Password is incorrect
    } else {
        return false;
    }
}
?>
