<?php
// Ensure session and config are loaded
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}
if (session_status() === PHP_SESSION_NONE) {
    require_once __DIR__ . '/session.php';
}
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? sanitize_output($page_title) . ' - ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#6366f1',
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Custom styles */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left side - Brand -->
                <div class="flex items-center">
                    <a href="<?php echo BASE_URL; ?>/index.php" class="flex items-center">
                        <span class="text-2xl font-bold text-primary">💼 <?php echo APP_NAME; ?></span>
                    </a>
                </div>
                
                <!-- Right side - Navigation Links -->
                <div class="flex items-center space-x-4">
                    <a href="<?php echo BASE_URL; ?>/index.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                        Home
                    </a>
                    
                    <?php if (is_logged_in()): ?>
                        <!-- Payroll Link -->
                        <a href="<?php echo BASE_URL; ?>/payroll/index.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                            Payroll
                        </a>
                        
                        <!-- Marketplace Link -->
                        <a href="<?php echo BASE_URL; ?>/marketplace/index.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">
                            Marketplace
                        </a>
                        
                        <!-- User Info & Logout -->
                        <div class="flex items-center space-x-3 border-l pl-4">
                            <span class="text-sm text-gray-600">
                                Welcome, <strong><?php echo sanitize_output($_SESSION['user']['name']); ?></strong>
                                <?php if (is_admin()): ?>
                                    <span class="ml-1 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs rounded-full">Admin</span>
                                <?php endif; ?>
                            </span>
                            <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                                Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Login Link (hide if already on login page) -->
                        <?php if (basename($_SERVER['PHP_SELF']) !== 'login.php'): ?>
                            <a href="<?php echo BASE_URL; ?>/auth/login.php" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                                Login
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="flex-1">
