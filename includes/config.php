<?php
/**
 * Configuration File
 * Contains application-wide constants and settings
 */

// Application Settings
define('APP_NAME', 'PayMarket Pro');
define('APP_VERSION', '1.0.0');

// Tax Configuration
define('TAX_RATE', 0.10); // 10% tax rate

// File Paths (already defined in functions.php, but keeping for reference)
if (!defined('DATA_DIR')) {
    define('DATA_DIR', __DIR__ . '/../data/');
}

// URL Base Path (adjust if your project is in a subfolder)
define('BASE_URL', '/payroll-and-marketplace');

// Date Format
define('DATE_FORMAT', 'F j, Y'); // e.g., "January 1, 2026"
define('DATETIME_FORMAT', 'F j, Y g:i A'); // e.g., "January 1, 2026 3:30 PM"

// Pagination
define('ITEMS_PER_PAGE', 10);

// Session Settings
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
