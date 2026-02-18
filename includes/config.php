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

// URL Base Path (Dynamic - works in any folder structure)
// Automatically detect the base URL from the current request
$scriptName = $_SERVER['SCRIPT_NAME'];

// Normalize path separators and split into parts
$parts = explode('/', str_replace('\\', '/', $scriptName));

// Find the project root folder by looking for common markers
// This works regardless of what the folder is named (payroll-and-marketplace, payroll-and-marketplace-main, etc.)
$projectRoot = null;
foreach ($parts as $index => $part) {
    // Look for the includes folder to identify project root
    if ($index > 0 && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . implode('/', array_slice($parts, 1, $index)) . '/includes/config.php')) {
        $projectRoot = '/' . implode('/', array_slice($parts, 1, $index));
        break;
    }
}

// Fallback: use the directory two levels up from this config file
if ($projectRoot === null) {
    $configPath = str_replace('\\', '/', __DIR__);
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $projectRoot = str_replace($docRoot, '', dirname($configPath));
}

define('BASE_URL', $projectRoot);

// Date Format
define('DATE_FORMAT', 'F j, Y'); // e.g., "January 1, 2026"
define('DATETIME_FORMAT', 'F j, Y g:i A'); // e.g., "January 1, 2026 3:30 PM"

// Pagination
define('ITEMS_PER_PAGE', 10);

// Session Settings
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
