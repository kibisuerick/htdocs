<?php
session_start();

require_once 'dbconnect.php'; // Ensure database connection is available
require_once 'login_model.inc.php';
require_once 'login_contr.inc.php';

// Function to check for empty input
function is_input_empty(...$inputs) {
    foreach ($inputs as $input) {
        if (empty($input)) {
            return true;
        }
    }
    return false;
}

// Initialize errors array before any checks
$errors = [];

// Ensure form data is retrieved before being used
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

// Process form submission and Error handlers
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if fields are empty
    if (is_input_empty($email, $password)) {
        $errors["empty_input"] = "Fill in all fields";
    }

    if (!empty($errors)) {
        echo "<script>alert('{$errors["empty_input"]}'); window.location.href='login.html';</script>";
        exit();
    }

    // Verify user credentials
    $result = verify_user($email, $password, $pdo);

    if (!$result || is_email_wrong($result)) {
        $errors["login_incorrect"] = "Incorrect login info!";
    }

    if (!empty($errors)) {
        echo "<script>alert('{$errors["login_incorrect"]}'); window.location.href='login.html';</script>";
        exit();
    }

    require_once 'configsession.php';

    $newsessionid = session_create_id();
    $sessionid = $newsessionid . "_" . $result["id"];
    session_id($sessionid);

    $_SESSION["user_id"] = $result["id"];
    $_SESSION["user_email"] = htmlspecialchars($result["email"]);
    $_SESSION["last_regeneration"] = time();

    header("Location: .../login.html?login=success");
    exit();
}
?>
