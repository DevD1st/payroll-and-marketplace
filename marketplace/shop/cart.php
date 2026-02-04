<?php
/**
 * Shopping Cart
 * View and manage cart items
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Require login
require_login();

// Get current user
$current_user = $_SESSION['user'];

// Handle cart updates (remove item or update quantity)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'remove' && isset($_POST['cart_index'])) {
            $index = (int)$_POST['cart_index'];
            if (isset($_SESSION['cart'][$index])) {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
                $_SESSION['success'] = 'Item removed from cart.';
            }
        } elseif ($_POST['action'] === 'update' && isset($_POST['cart_index']) && isset($_POST['quantity'])) {
            $index = (int)$_POST['cart_index'];
            $quantity = (int)$_POST['quantity'];
            if (isset($_SESSION['cart'][$index]) && $quantity > 0 && $quantity <= $_SESSION['cart'][$index]['max_stock']) {
                $_SESSION['cart'][$index]['quantity'] = $quantity;
                $_SESSION['success'] = 'Cart updated.';
            }
        }
    }
    header('Location: ' . BASE_URL . '/marketplace/shop/cart.php');
    exit();
}

// Calculate cart totals
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}

// Check for messages
$success_message = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

// Set page title
$page_title = 'Shopping Cart';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?php echo BASE_URL; ?>/marketplace/index.php" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Continue Shopping
        </a>
    </div>
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
        <p class="mt-2 text-gray-600">Review your items before checkout</p>
    </div>
    
    <!-- Success Message -->
    <?php if ($success_message): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <p class="text-sm text-green-700 font-medium"><?php echo sanitize_output($success_message); ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Error Message -->
    <?php if ($error_message): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <p class="text-sm text-red-700 font-medium"><?php echo sanitize_output($error_message); ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <!-- Empty Cart -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="mt-4 text-lg text-gray-500">Your cart is empty</p>
            <a href="<?php echo BASE_URL; ?>/marketplace/index.php" 
               class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Cart Items (<?php echo count($_SESSION['cart']); ?>)</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                            <div class="p-6 flex items-center space-x-4">
                                <!-- Product Icon -->
                                <div class="flex-shrink-0 h-20 w-20 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-3xl">📦</span>
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <?php echo sanitize_output($item['name']); ?>
                                    </h3>
                                    <p class="text-gray-600">₦<?php echo number_format($item['price']); ?> each</p>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="mt-2 flex items-center space-x-2">
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                            <label class="text-sm text-gray-600 mr-2">Qty:</label>
                                            <select name="quantity" onchange="this.form.submit()" 
                                                    class="border border-gray-300 rounded px-2 py-1 text-sm">
                                                <?php for ($i = 1; $i <= $item['max_stock']; $i++): ?>
                                                    <option value="<?php echo $i; ?>" <?php echo $i === $item['quantity'] ? 'selected' : ''; ?>>
                                                        <?php echo $i; ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </form>
                                        
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Subtotal -->
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">
                                        ₦<?php echo number_format($item['price'] * $item['quantity']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                    
                    <!-- Cart Total -->
                    <div class="border-t border-b border-gray-200 py-4 mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">₦<?php echo number_format($cart_total); ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Tax:</span>
                            <span class="font-semibold">₦0</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold mt-4">
                            <span>Total:</span>
                            <span class="text-green-600">₦<?php echo number_format($cart_total); ?></span>
                        </div>
                    </div>
                    
                    <!-- Wallet Balance -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-600 mb-1">Your Wallet Balance</p>
                        <p class="text-2xl font-bold text-blue-600">₦<?php echo number_format($current_user['balance']); ?></p>
                        <?php if ($current_user['balance'] < $cart_total): ?>
                            <p class="text-xs text-red-600 mt-2">⚠️ Insufficient balance</p>
                        <?php else: ?>
                            <p class="text-xs text-green-600 mt-2">✓ Sufficient balance</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Checkout Button -->
                    <?php if ($current_user['balance'] >= $cart_total): ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/marketplace/shop/checkout.php">
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                                💳 Pay with Wallet Balance
                            </button>
                        </form>
                        <p class="text-xs text-center text-gray-500 mt-3">
                            Balance after purchase: ₦<?php echo number_format($current_user['balance'] - $cart_total); ?>
                        </p>
                    <?php else: ?>
                        <button disabled 
                                class="w-full bg-gray-300 text-gray-500 font-bold py-4 px-6 rounded-lg cursor-not-allowed">
                            Insufficient Balance
                        </button>
                        <p class="text-xs text-center text-red-600 mt-3">
                            You need ₦<?php echo number_format($cart_total - $current_user['balance']); ?> more to complete this purchase
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
