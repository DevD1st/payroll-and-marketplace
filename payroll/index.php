<?php
/**
 * Payroll Module - Dashboard Router
 * Routes to admin or employee dashboard based on role
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

// Require user to be logged in
require_login();

// Route based on user role
if ($_SESSION['role'] === 'admin') {
    // Admin dashboard
    require_once __DIR__ . '/admin/dashboard.php';
} elseif ($_SESSION['role'] === 'employee') {
    // Employee dashboard
    require_once __DIR__ . '/employee/dashboard.php';
} else {
    // Unknown role - redirect to home
    $_SESSION['error'] = 'Invalid user role.';
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}
