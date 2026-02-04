<?php
/**
 * Checkout - The Transaction Engine
 * Process payment and create order
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Require login
require_login();

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    $_SESSION['error'] = 'Your cart is empty.';
    header('Location: ' . BASE_URL . '/marketplace/index.php');
    exit();
}

// Get current user
$current_user = $_SESSION['user'];
$user_id = $current_user['id'];

// Calculate cart total
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}

// Load all users
$users = get_json_data('users.json');

// Find current user in users array
$user_index = null;
$user_balance = 0;
foreach ($users as $index => $user) {
    if ($user['id'] === $user_id) {
        $user_index = $index;
        $user_balance = $user['balance'];
        break;
    }
}

// Check if user was found
if ($user_index === null) {
    $_SESSION['error'] = 'User not found.';
    header('Location: ' . BASE_URL . '/marketplace/shop/cart.php');
    exit();
}

// CRITICAL CHECK: Affordability
if ($user_balance < $cart_total) {
    $_SESSION['error'] = 'Insufficient Funds! Your balance (₦' . number_format($user_balance) . ') is less than the cart total (₦' . number_format($cart_total) . ').';
    header('Location: ' . BASE_URL . '/marketplace/shop/cart.php');
    exit();
}

// DEDUCT MONEY: Calculate new balance
$new_balance = $user_balance - $cart_total;

// UPDATE USER: Save new balance to users.json
$users[$user_index]['balance'] = $new_balance;
if (!save_json_data('users.json', $users)) {
    $_SESSION['error'] = 'Failed to update balance. Transaction cancelled.';
    header('Location: ' . BASE_URL . '/marketplace/shop/cart.php');
    exit();
}

// Update session user balance
$_SESSION['user']['balance'] = $new_balance;

// SAVE ORDER: Create order record
$orders = get_json_data('orders.json');

$order_id = empty($orders) ? 1 : max(array_column($orders, 'id')) + 1;

$new_order = [
    'id' => $order_id,
    'user_id' => $user_id,
    'user_name' => $current_user['name'],
    'items' => $_SESSION['cart'],
    'total' => $cart_total,
    'date' => date('Y-m-d H:i:s'),
    'status' => 'completed'
];

$orders[] = $new_order;

if (!save_json_data('orders.json', $orders)) {
    // Order save failed but money was deducted - log this critical error
    error_log("CRITICAL: Order save failed for user {$user_id}, but balance was deducted. Amount: {$cart_total}");
}

// Update product stock
$products = get_json_data('products.json');
foreach ($_SESSION['cart'] as $cart_item) {
    foreach ($products as $index => $product) {
        if ($product['id'] === $cart_item['id']) {
            $products[$index]['stock'] -= $cart_item['quantity'];
            if ($products[$index]['stock'] < 0) {
                $products[$index]['stock'] = 0;
            }
            break;
        }
    }
}
save_json_data('products.json', $products);

// CLEAR CART
$purchased_items = $_SESSION['cart'];
unset($_SESSION['cart']);
$_SESSION['cart'] = []; // Reinitialize

// Set success message
$_SESSION['success'] = 'Order completed successfully! Order #' . $order_id;
$_SESSION['last_order'] = [
    'id' => $order_id,
    'total' => $cart_total,
    'items' => $purchased_items,
    'new_balance' => $new_balance
];

// Redirect to success page
header('Location: ' . BASE_URL . '/marketplace/shop/success.php');
exit();
