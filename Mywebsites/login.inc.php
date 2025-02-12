<?php
// login.inc.php
session_start();
require_once 'dbconnect.php';
require_once 'login_model.inc.php';
require_once 'login_contr.inc.php';
require_once 'configsession.php';

$errors = [];

// Retrieve form inputs
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for empty input fields
    if (is_input_empty($email, $password)) {
        $errors["empty_input"] = "Please fill in all fields.";
    }

    if (!empty($errors)) {
        $_SESSION["errors_login"] = $errors;
        header("Location: index.php");
        exit();
    }

    // Verify user credentials using the model function
    $user = verify_user($email, $password, $pdo);
    if (!$user) {
        $errors["login_incorrect"] = "Incorrect login information!";
    }

    if (!empty($errors)) {
        $_SESSION["errors_login"] = $errors;
        header("Location: index.php");
        exit();
    }

    // Successful login: set session variables
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = $user["name"];
    $_SESSION["user_email"] = htmlspecialchars($user["email"]);
    $_SESSION["last_regeneration"] = time();

    // Redirect to dashboard upon successful login
    header("Location: dashboard.html");
    exit();
}
?>
