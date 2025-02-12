<!-- Save as index.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Centralised Property Reservation Platform</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Example CSS for styling the login form */
    .account__form {
      max-width: 400px;
      margin: 50px auto;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .account__form label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .account__form input[type="email"],
    .account__form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .account__form button {
      width: 100%;
      padding: 10px;
      background-color: #000929;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      display: block;
      margin: 20px auto;
    }
    .account__form button:hover {
      background-color: #001244;
    }
    .message-box {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="account__form">
        <h2>Login</h2>
        <!-- Display error or success messages stored in session -->
            <div id="message" class="message-box">
                <?php
                if (isset($_SESSION["errors_login"])) {
                foreach ($_SESSION["errors_login"] as $error) {
                echo "<p style='color: red;'>" . htmlspecialchars($error) . "</p>";
                }
                unset($_SESSION["errors_login"]);
            }
                ?>
    </div>
    <form action="login.inc.php" method="POST">
      <label for="email">Email Address</label>
      <input type="email" name="email" id="email" placeholder="Enter Email Address" required>
      
      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="Enter Password" required>
      
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
