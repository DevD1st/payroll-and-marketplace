<?php
/**
 * Add to Cart Handler
 * Adds products to session cart
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Require login
require_login();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if ($product_id > 0) {
        // Load products
        $products = get_json_data('products.json');
        
        // Find the product
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] === $product_id) {
                $product = $p;
                break;
            }
        }
        
        if ($product && $product['stock'] > 0) {
            // Check if product already in cart
            $found = false;
            foreach ($_SESSION['cart'] as $index => $cart_item) {
                if ($cart_item['id'] === $product_id) {
                    // Increment quantity (but don't exceed stock)
                    if ($_SESSION['cart'][$index]['quantity'] < $product['stock']) {
                        $_SESSION['cart'][$index]['quantity']++;
                        $_SESSION['success'] = 'Product quantity updated in cart!';
                    } else {
                        $_SESSION['error'] = 'Cannot add more - stock limit reached.';
                    }
                    $found = true;
                    break;
                }
            }
            
            // If not found, add new item to cart
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => 1,
                    'max_stock' => $product['stock']
                ];
                $_SESSION['success'] = 'Product added to cart!';
            }
        } else {
            $_SESSION['error'] = 'Product not available.';
        }
    }
}

// Redirect back to marketplace
header('Location: ' . BASE_URL . '/marketplace/index.php');
exit();
