<?php
// configsession.php
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

$cookieParams = [
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Strict'
];

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

// Optional: session regeneration functions (if needed)
// function regenerate_session_id_loggedin() { ... }
// function regenerate_session_id() { ... }
?>
