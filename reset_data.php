<?php
/**
 * Data Reset Utility
 * Wipes all JSON files and restores default data
 * ⚠️ USE WITH CAUTION - This permanently deletes all data
 */

require_once __DIR__ . '/includes/functions.php';

// Define data directory
if (!defined('DATA_DIR')) {
    define('DATA_DIR', __DIR__ . '/data/');
}

$reset_complete = false;
$error_message = '';

// Process reset if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_reset'])) {
    try {
        // Default users data
        $default_users = [
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@company.com',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role' => 'admin',
                'hourly_rate' => 5000,
                'balance' => 0
            ],
            [
                'id' => 2,
                'name' => 'John Doe',
                'email' => 'john@company.com',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role' => 'employee',
                'hourly_rate' => 2500,
                'balance' => 50000
            ]
        ];

        // Default products data
        $default_products = [
            [
                'id' => 1,
                'name' => 'Office Chair',
                'price' => 15000,
                'stock' => 10,
                'description' => 'Ergonomic office chair with lumbar support'
            ],
            [
                'id' => 2,
                'name' => 'Laptop Bag',
                'price' => 5000,
                'stock' => 25,
                'description' => 'Water-resistant laptop bag with multiple compartments'
            ],
            [
                'id' => 3,
                'name' => 'USB Mouse',
                'price' => 2000,
                'stock' => 50,
                'description' => 'Wireless optical mouse with USB receiver'
            ],
            [
                'id' => 4,
                'name' => 'Keyboard',
                'price' => 8000,
                'stock' => 20,
                'description' => 'Mechanical keyboard with RGB backlighting'
            ],
            [
                'id' => 5,
                'name' => 'Monitor Stand',
                'price' => 6500,
                'stock' => 15,
                'description' => 'Adjustable monitor stand with storage drawer'
            ]
        ];

        // Save default data to JSON files
        save_json_data('users.json', $default_users);
        save_json_data('products.json', $default_products);
        save_json_data('time_entries.json', []);
        save_json_data('orders.json', []);

        $reset_complete = true;
    } catch (Exception $e) {
        $error_message = 'Reset failed: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Data - PayMarket Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 min-h-screen">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-2xl mx-auto">
            
            <?php if ($reset_complete): ?>
                <!-- Success Message -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-6 text-white">
                        <div class="flex items-center">
                            <svg class="h-12 w-12 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h1 class="text-3xl font-bold">Reset Complete!</h1>
                                <p class="text-green-100 mt-1">All data has been restored to defaults</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="space-y-4 mb-8">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <p class="text-gray-700"><strong>users.json</strong> - 2 default accounts created</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <p class="text-gray-700"><strong>products.json</strong> - 5 sample products added</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <p class="text-gray-700"><strong>time_entries.json</strong> - Cleared (no payslips)</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <p class="text-gray-700"><strong>orders.json</strong> - Cleared (no orders)</p>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-blue-800">
                                <strong>🔐 Default Login Credentials:</strong>
                            </p>
                            <ul class="mt-2 text-sm text-blue-700 space-y-1">
                                <li>• Admin: <code class="bg-blue-100 px-2 py-0.5 rounded">admin@company.com</code> / <code class="bg-blue-100 px-2 py-0.5 rounded">password123</code></li>
                                <li>• Employee: <code class="bg-blue-100 px-2 py-0.5 rounded">john@company.com</code> / <code class="bg-blue-100 px-2 py-0.5 rounded">password123</code></li>
                            </ul>
                            <p class="mt-2 text-xs text-blue-600">
                                Emergency logins (admin@portal.com / user@portal.com) are always available via self-healing authentication.
                            </p>
                        </div>
                        
                        <div class="flex justify-center">
                            <a href="index.php" 
                               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-700 hover:to-indigo-700 transition">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Go to Home Page
                            </a>
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <!-- Warning & Confirmation Form -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-orange-600 px-8 py-6 text-white">
                        <div class="flex items-center">
                            <svg class="h-12 w-12 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <h1 class="text-3xl font-bold">Data Reset Utility</h1>
                                <p class="text-red-100 mt-1">Restore system to factory defaults</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <!-- Critical Warning -->
                        <div class="bg-red-50 border-2 border-red-300 rounded-lg p-6 mb-8">
                            <div class="flex items-start">
                                <svg class="h-8 w-8 text-red-600 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                <div>
                                    <h2 class="text-xl font-bold text-red-900 mb-2">⚠️ Warning: Destructive Operation</h2>
                                    <p class="text-red-800 mb-4">
                                        This will <strong>permanently erase</strong> all current data and restore the system to its default state.
                                    </p>
                                    <div class="space-y-2 text-sm text-red-700">
                                        <p>✖ All user accounts (except defaults) will be deleted</p>
                                        <p>✖ All custom products will be removed</p>
                                        <p>✖ All payroll records and payslips will be lost</p>
                                        <p>✖ All marketplace orders will be erased</p>
                                        <p>✖ Employee wallet balances will be reset</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($error_message): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- What Will Happen -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-semibold text-blue-900 mb-3">What happens after reset:</h3>
                            <ul class="space-y-2 text-sm text-blue-800">
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>2 default user accounts will be created (1 admin, 1 employee)</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>5 sample products will be added to marketplace</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Emergency logins will remain functional (admin@portal.com / user@portal.com)</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>System will be ready for immediate use</span>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Confirmation Form -->
                        <form method="POST" onsubmit="return confirm('Are you absolutely sure you want to reset all data? This action CANNOT be undone.');">
                            <div class="flex justify-center space-x-4">
                                <a href="index.php" 
                                   class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        name="confirm_reset"
                                        class="px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white font-bold rounded-lg hover:from-red-700 hover:to-orange-700 shadow-lg transition">
                                    Reset All Data
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-xs text-gray-500 text-center mt-6">
                            💡 Tip: If you only want to test the system, use the existing demo accounts instead of resetting.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</body>
</html>
