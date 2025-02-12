<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login1.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        a { color: blue; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong>! You are now logged in.</p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
