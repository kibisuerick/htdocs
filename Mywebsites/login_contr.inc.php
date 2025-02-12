<?php
// login_contr.inc.php
declare(strict_types=1);
require_once 'dbconnect.php';

function is_input_empty(string $email, string $password): bool {
    return empty($email) || empty($password);
}

function is_password_wrong(string $password, string $hashedpassword): bool {
    return !password_verify($password, $hashedpassword);
}
?>
