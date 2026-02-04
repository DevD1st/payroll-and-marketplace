<?php
/**
 * Session Management
 * Handles session initialization and authentication helpers
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/**
 * Check if user is logged in
 * @return bool - True if logged in, false otherwise
 */
function is_logged_in() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

/**
 * Check if user is admin
 * @return bool - True if admin, false otherwise
 */
function is_admin() {
    return is_logged_in() && $_SESSION['user']['role'] === 'admin';
}

/**
 * Check if user is employee
 * @return bool - True if employee, false otherwise
 */
function is_employee() {
    return is_logged_in() && $_SESSION['user']['role'] === 'employee';
}

/**
 * Require user to be logged in
 * Redirects to login page if not authenticated
 * @param string $redirect_back - URL to return to after login
 */
function require_login($redirect_back = '') {
    if (!is_logged_in()) {
        if ($redirect_back) {
            $_SESSION['redirect_after_login'] = $redirect_back;
        }
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit();
    }
}

/**
 * Require user to be admin
 * Redirects to home if not admin
 */
function require_admin() {
    require_login(); // Must be logged in first
    
    if (!is_admin()) {
        $_SESSION['error'] = 'Access denied. Admin privileges required.';
        header('Location: ' . BASE_URL . '/index.php');
        exit();
    }
}

/**
 * Require user to be employee
 * Redirects to home if not employee
 */
function require_employee() {
    require_login(); // Must be logged in first
    
    if (!is_employee()) {
        $_SESSION['error'] = 'Access denied. Employee privileges required.';
        header('Location: ' . BASE_URL . '/index.php');
        exit();
    }
}

/**
 * Get current logged-in user
 * @return array|null - User data or null if not logged in
 */
function get_logged_in_user() {
    return $_SESSION['user'] ?? null;
}

/**
 * Login user and set session
 * @param array $user - User data
 */
function login_user($user) {
    // Don't store password in session
    unset($user['password']);
    $_SESSION['user'] = $user;
    $_SESSION['login_time'] = time();
}

/**
 * Logout user and destroy session
 */
function logout_user() {
    $_SESSION = [];
    session_destroy();
}
