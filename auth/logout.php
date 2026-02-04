<?php
/**
 * Logout Handler
 * Destroys session and redirects to landing page
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destroy the session
session_destroy();

// Redirect to landing page
header('Location: ' . BASE_URL . '/index.php?logged_out=1');
exit();
