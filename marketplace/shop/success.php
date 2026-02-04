<?php
/**
 * Order Success Page
 * Displays order confirmation
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Require login
require_login();

// Check if there's a last order
if (!isset($_SESSION['last_order'])) {
    header('Location: ' . BASE_URL . '/marketplace/index.php');
    exit();
}

$order = $_SESSION['last_order'];
$current_user = $_SESSION['user'];

// Set page title
$page_title = 'Order Success';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success Icon -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-4">
            <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Successful!</h1>
        <p class="text-gray-600">Thank you for your purchase</p>
    </div>
    
    <!-- Order Details -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-90">Order Number</p>
                    <p class="text-2xl font-bold">#<?php echo $order['id']; ?></p>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">Order Date</p>
                    <p class="font-semibold"><?php echo date('M d, Y'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Purchased Items -->
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Items Purchased</h3>
            <div class="space-y-3 mb-6">
                <?php foreach ($order['items'] as $item): ?>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium text-gray-900"><?php echo sanitize_output($item['name']); ?></p>
                            <p class="text-sm text-gray-600">Quantity: <?php echo $item['quantity']; ?> × ₦<?php echo number_format($item['price']); ?></p>
                        </div>
                        <p class="font-semibold text-gray-900">
                            ₦<?php echo number_format($item['price'] * $item['quantity']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Order Total -->
            <div class="border-t pt-4">
                <div class="flex justify-between items-center text-xl font-bold">
                    <span>Total Paid:</span>
                    <span class="text-green-600">₦<?php echo number_format($order['total']); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Balance Update -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Wallet Update</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-700">Previous Balance:</span>
                <span class="font-semibold">₦<?php echo number_format($order['new_balance'] + $order['total']); ?></span>
            </div>
            <div class="flex justify-between text-red-600">
                <span>Amount Paid:</span>
                <span class="font-semibold">-₦<?php echo number_format($order['total']); ?></span>
            </div>
            <div class="border-t border-blue-300 pt-2 mt-2">
                <div class="flex justify-between text-lg font-bold text-blue-900">
                    <span>New Balance:</span>
                    <span>₦<?php echo number_format($order['new_balance']); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="<?php echo BASE_URL; ?>/marketplace/index.php" 
           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-3 px-6 rounded-lg transition">
            Continue Shopping
        </a>
        <a href="<?php echo BASE_URL; ?>/index.php" 
           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 text-center font-semibold py-3 px-6 rounded-lg transition">
            Go to Home
        </a>
    </div>
</div>

<?php 
// Clear the last order from session after displaying
unset($_SESSION['last_order']);
require_once __DIR__ . '/../../includes/footer.php'; 
?>
