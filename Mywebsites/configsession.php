<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

// Set session cookie parameters
$cookieParams = [
    'lifetime' => 0,        // Session expires when the browser is closed
    'path' => '/',          // Available across the entire domain
    'domain' => '',         // Empty string ensures compatibility with localhost
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', // Proper HTTPS detection
    'httponly' => true,     // Prevent JavaScript access to session cookie
    'samesite' => 'Strict'  // Prevent cross-site request forgery (CSRF)
];

// Ensure compatibility with PHP versions below 7.3
if (PHP_VERSION_ID >= 70300) {
    session_set_cookie_params($cookieParams);
} else {
    session_set_cookie_params(
        $cookieParams['lifetime'],
        $cookieParams['path'],
        $cookieParams['domain'],
        $cookieParams['secure'],
        $cookieParams['httponly']
    );
}

session_start();

function regenerate_session_id_loggedin()
{
    session_regenerate_id(true);

    $userid = $_SESSION["user_id"] ?? 'guest'; // ✅ Prevents undefined index error
    $newsessionid = session_create_id();
    $sessionid = $newsessionid . "_" . $userid;
    
    session_commit();   // Save current session data
    session_id($sessionid);
    session_start();    // Restart session with new ID

    $_SESSION["last_regeneration"] = time();
}

function regenerate_session_id()
{
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}

// Define session regeneration interval
$interval = 60 * 30;

if (isset($_SESSION["user_id"])) {
    if (!isset($_SESSION["last_regeneration"]) || time() - $_SESSION["last_regeneration"] >= $interval) {
        regenerate_session_id_loggedin();
    }
} else {
    if (!isset($_SESSION["last_regeneration"]) || time() - $_SESSION["last_regeneration"] >= $interval) {
        regenerate_session_id();
    }
}
?>
