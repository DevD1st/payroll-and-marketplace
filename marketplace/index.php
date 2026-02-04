<?php
/**
 * Marketplace Module - Router
 * Routes to shop or admin based on role
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

// Require user to be logged in
require_login();

// Default to shop for all users
require_once __DIR__ . '/shop/products.php';
