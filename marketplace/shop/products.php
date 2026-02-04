<?php
/**
 * Marketplace Storefront
 * Browse and shop products
 */

// This file can be included from marketplace/index.php or accessed directly
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../includes/config.php';
    require_once __DIR__ . '/../../includes/session.php';
    require_once __DIR__ . '/../../includes/functions.php';
    require_login();
}

// Load products
$products = get_json_data('products.json');

// Get current user
$current_user = $_SESSION['user'];

// Check for success messages
$success_message = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

// Set page title
$page_title = 'Marketplace';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Employee Marketplace</h1>
            <p class="mt-2 text-gray-600">Shop products using your wallet balance</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-4">
            <!-- Wallet Balance -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg">
                <p class="text-xs uppercase tracking-wide">Your Balance</p>
                <p class="text-2xl font-bold">₦<?php echo number_format($current_user['balance']); ?></p>
            </div>
            
            <!-- Cart Button -->
            <a href="<?php echo BASE_URL; ?>/marketplace/shop/cart.php" 
               class="relative inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                View Cart
                <?php if (!empty($_SESSION['cart'])): ?>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                        <?php echo count($_SESSION['cart']); ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <?php if (is_admin()): ?>
                <!-- Admin Link -->
                <a href="<?php echo BASE_URL; ?>/marketplace/admin/manage_products.php" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Manage
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Success Message -->
    <?php if ($success_message): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">
                        <?php echo sanitize_output($success_message); ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="mt-4 text-lg text-gray-500">No products available at the moment</p>
            <p class="text-sm text-gray-400">Check back soon for new items!</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Product Image -->
                    <div class="h-48 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                        <span class="text-6xl">📦</span>
                    </div>
                    
                    <!-- Product Details -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            <?php echo sanitize_output($product['name']); ?>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            <?php echo sanitize_output($product['description']); ?>
                        </p>
                        
                        <!-- Price and Stock -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-2xl font-bold text-green-600">
                                    ₦<?php echo number_format($product['price']); ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <?php if ($product['stock'] > 0): ?>
                                    <span class="text-xs text-green-600 font-medium">
                                        <?php echo $product['stock']; ?> in stock
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-red-600 font-medium">
                                        Out of stock
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <?php if ($product['stock'] > 0): ?>
                            <form method="POST" action="<?php echo BASE_URL; ?>/marketplace/shop/add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Add to Cart
                                </button>
                            </form>
                        <?php else: ?>
                            <button disabled 
                                    class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed">
                                Out of Stock
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
