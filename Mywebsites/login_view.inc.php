<?php
// login_view.inc.php
declare(strict_types=1);

function check_login_errors() {
    if (isset($_SESSION["errors_login"])) {
        foreach ($_SESSION["errors_login"] as $error) {
            echo '<p class="form-error">' . htmlspecialchars($error) . '</p>';
        }
        unset($_SESSION["errors_login"]);
    } elseif (!empty($_GET['login']) && $_GET['login'] === "success") {
        echo '<p class="form-success">Login success!</p>';
    }
}
?>
