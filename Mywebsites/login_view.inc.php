<?php
declare(strict_types=1);

function check_login_errors()
{
   if (isset($_SESSION["error_login"])) { // ✅ Checks if session error exists
        $errors = $_SESSION["error_login"];

        echo "<br>";

        foreach ($errors as $error) {
            echo '<p class="form-error">' . htmlspecialchars($error) . '</p>'; // ✅ Prevents XSS
        }

        unset($_SESSION["error_login"]);
   } 
   
   elseif (!empty($_GET['login']) && $_GET['login'] === "success") { // ✅ Fixed syntax
      echo '<br>';
      echo '<p class="form-success">Login success!</p>';
   }
}
?>
