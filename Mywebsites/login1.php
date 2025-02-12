<?php
session_start();

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { margin: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="email"], input[type="password"] { padding: 5px; width: 200px; }
        input[type="submit"] { margin-top: 10px; padding: 5px 10px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form action="authenticate.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
    
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
    
        <input type="submit" value="Login">
    </form>
    <?php
    // Display error message if login failed
    if (isset($_GET['error'])) {
        echo '<p class="error">Invalid email or password.</p>';
    }
    ?>
</body>
</html>
