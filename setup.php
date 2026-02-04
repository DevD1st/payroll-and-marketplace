<?php
/**
 * Setup & Database Seeder
 * Run this file ONCE to initialize the data files
 * Access: http://localhost/payroll-and-marketplace/setup.php
 */

// Include functions
require_once __DIR__ . '/includes/functions.php';

// Set page title
$page_title = 'System Setup';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - PayMarket Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full">
            <h1 class="text-3xl font-bold text-center mb-6 text-blue-600">🚀 System Setup</h1>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-sm text-blue-700">
                    <strong>Note:</strong> This will create/overwrite all data files. Run this only once during initial setup.
                </p>
            </div>
            
            <div class="space-y-4">
                <?php
                // Run the seed function
                seed_data();
                ?>
            </div>
            
            <div class="mt-8 p-4 bg-green-50 border border-green-200 rounded-lg">
                <h3 class="font-semibold text-green-800 mb-2">✅ Setup Complete!</h3>
                <p class="text-sm text-gray-700 mb-4">Your system is now ready to use.</p>
                
                <div class="space-y-2">
                    <a href="index.php" class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
                        Go to Home Page
                    </a>
                    <a href="auth/login.php" class="block w-full text-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition">
                        Go to Login
                    </a>
                </div>
            </div>
            
            <div class="mt-6 text-center text-xs text-gray-500">
                <p>⚠️ For security, delete this file after setup is complete.</p>
            </div>
        </div>
    </div>
</body>
</html>
