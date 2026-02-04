<?php
/**
 * Admin - Add New Product
 * Form to add products to the marketplace
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Require admin access
require_admin();

// Initialize variables
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $stock = $_POST['stock'] ?? '';
    
    // Validate inputs
    if (empty($name) || empty($price) || empty($description) || empty($stock)) {
        $error_message = 'All fields are required.';
    } elseif (!is_numeric($price) || $price < 0) {
        $error_message = 'Price must be a valid number.';
    } elseif (!is_numeric($stock) || $stock < 0) {
        $error_message = 'Stock must be a valid number.';
    } else {
        // Load existing products
        $products = get_json_data('products.json');
        
        // Generate new ID
        $new_id = empty($products) ? 1 : max(array_column($products, 'id')) + 1;
        
        // Create new product
        $new_product = [
            'id' => $new_id,
            'name' => $name,
            'price' => (float)$price,
            'description' => $description,
            'image' => !empty($image) ? $image : 'default.jpg',
            'stock' => (int)$stock
        ];
        
        // Add to products array
        $products[] = $new_product;
        
        // Save to file
        if (save_json_data('products.json', $products)) {
            $_SESSION['success'] = "Product '{$name}' added successfully!";
            header('Location: ' . BASE_URL . '/marketplace/admin/manage_products.php');
            exit();
        } else {
            $error_message = 'Failed to save product.';
        }
    }
}

// Set page title
$page_title = 'Add Product';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?php echo BASE_URL; ?>/marketplace/admin/manage_products.php" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Products
        </a>
    </div>
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
        <p class="mt-2 text-gray-600">Add a new item to the marketplace inventory</p>
    </div>
    
    <!-- Error Message -->
    <?php if ($error_message): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">
                        <?php echo sanitize_output($error_message); ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Product Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="">
            <!-- Product Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" required
                       value="<?php echo isset($_POST['name']) ? sanitize_output($_POST['name']) : ''; ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="e.g., Company T-Shirt">
            </div>
            
            <!-- Price -->
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                    Price (₦) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="price" name="price" required min="0" step="100"
                       value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="50000">
                <p class="mt-1 text-sm text-gray-500">Enter price in Naira (₦)</p>
            </div>
            
            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea id="description" name="description" required rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter a detailed description of the product..."><?php echo isset($_POST['description']) ? sanitize_output($_POST['description']) : ''; ?></textarea>
            </div>
            
            <!-- Image URL -->
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Image Filename (Optional)
                </label>
                <input type="text" id="image" name="image"
                       value="<?php echo isset($_POST['image']) ? sanitize_output($_POST['image']) : ''; ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="product.jpg">
                <p class="mt-1 text-sm text-gray-500">Leave blank to use default image</p>
            </div>
            
            <!-- Stock -->
            <div class="mb-6">
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                    Stock Quantity <span class="text-red-500">*</span>
                </label>
                <input type="number" id="stock" name="stock" required min="0" step="1"
                       value="<?php echo isset($_POST['stock']) ? $_POST['stock'] : ''; ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="100">
                <p class="mt-1 text-sm text-gray-500">Number of units available</p>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-4">
                <a href="<?php echo BASE_URL; ?>/marketplace/admin/manage_products.php" 
                   class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow-lg transition transform hover:scale-105">
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
