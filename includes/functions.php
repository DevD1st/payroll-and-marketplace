<?php
/**
 * Core Functions for Payroll & Marketplace System
 * Handles file-based JSON storage and data operations
 */

// Define data directory constant if not already defined
if (!defined('DATA_DIR')) {
    define('DATA_DIR', __DIR__ . '/../data/');
}

/**
 * Read JSON data from a file
 * @param string $filename - Name of the file (e.g., 'users.json')
 * @return array - Decoded JSON data or empty array if file doesn't exist
 */
function get_json_data($filename) {
    $filepath = DATA_DIR . $filename;
    
    // Check if file exists
    if (!file_exists($filepath)) {
        return [];
    }
    
    // Read file contents
    $json_content = file_get_contents($filepath);
    
    // Decode JSON to associative array
    $data = json_decode($json_content, true);
    
    // Return empty array if decode fails
    return $data !== null ? $data : [];
}

/**
 * Save data to a JSON file
 * @param string $filename - Name of the file (e.g., 'users.json')
 * @param array $data - Data to save
 * @return bool - True on success, false on failure
 */
function save_json_data($filename, $data) {
    $filepath = DATA_DIR . $filename;
    
    // Create data directory if it doesn't exist
    if (!file_exists(DATA_DIR)) {
        mkdir(DATA_DIR, 0777, true);
    }
    
    // Encode data to pretty JSON
    $json_content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
    // Write to file
    $result = file_put_contents($filepath, $json_content);
    
    return $result !== false;
}

/**
 * Generate a unique ID for new records
 * @param array $data - Existing data array
 * @return int - Next available ID
 */
function generate_id($data) {
    if (empty($data)) {
        return 1;
    }
    
    // Get the highest ID and add 1
    $ids = array_column($data, 'id');
    return max($ids) + 1;
}

/**
 * Find a record by ID
 * @param array $data - Array of records
 * @param int $id - ID to search for
 * @return array|null - Found record or null
 */
function find_by_id($data, $id) {
    foreach ($data as $record) {
        if ($record['id'] == $id) {
            return $record;
        }
    }
    return null;
}

/**
 * Find a user by email
 * @param string $email - Email to search for
 * @return array|null - Found user or null
 */
function find_user_by_email($email) {
    $users = get_json_data('users.json');
    
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    
    return null;
}

/**
 * Update a user's balance
 * @param int $user_id - User ID
 * @param float $new_balance - New balance amount
 * @return bool - Success status
 */
function update_user_balance($user_id, $new_balance) {
    $users = get_json_data('users.json');
    
    foreach ($users as &$user) {
        if ($user['id'] == $user_id) {
            $user['balance'] = $new_balance;
            return save_json_data('users.json', $users);
        }
    }
    
    return false;
}

/**
 * Seed initial data for the system
 * Creates users.json with 1 admin and 2 employees
 * Creates products.json with 5 sample products
 * Creates time_entries.json as empty array
 * Creates orders.json as empty array
 */
function seed_data() {
    // Create data directory if it doesn't exist
    if (!file_exists(DATA_DIR)) {
        mkdir(DATA_DIR, 0777, true);
    }
    
    // Seed Users (1 Admin + 2 Employees)
    $users = [
        [
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'admin@company.com',
            'password' => 'admin123', // Plaintext for simplicity
            'role' => 'admin',
            'hourly_rate' => 0.00, // Admins don't have hourly rates
            'balance' => 0.00
        ],
        [
            'id' => 2,
            'name' => 'John Doe',
            'email' => 'john@company.com',
            'password' => 'john123',
            'role' => 'employee',
            'hourly_rate' => 37500.00,
            'balance' => 3000000.00 // Starting balance
        ],
        [
            'id' => 3,
            'name' => 'Jane Smith',
            'email' => 'jane@company.com',
            'password' => 'jane123',
            'role' => 'employee',
            'hourly_rate' => 45000.00,
            'balance' => 3750000.00 // Starting balance
        ]
    ];
    
    save_json_data('users.json', $users);
    
    // Seed Products (5 Sample Items)
    $products = [
        [
            'id' => 1,
            'name' => 'Company Hoodie',
            'price' => 67500.00,
            'description' => 'Premium cotton hoodie with company logo. Available in multiple sizes.',
            'image' => 'hoodie.jpg',
            'stock' => 50
        ],
        [
            'id' => 2,
            'name' => 'Branded Coffee Mug',
            'price' => 18000.00,
            'description' => 'Ceramic mug with company branding. Perfect for your morning coffee.',
            'image' => 'mug.jpg',
            'stock' => 100
        ],
        [
            'id' => 3,
            'name' => 'Wireless Mouse',
            'price' => 52500.00,
            'description' => 'Ergonomic wireless mouse with USB receiver. 2.4GHz connection.',
            'image' => 'mouse.jpg',
            'stock' => 30
        ],
        [
            'id' => 4,
            'name' => 'Laptop Backpack',
            'price' => 97500.00,
            'description' => 'Water-resistant backpack with padded laptop compartment up to 15.6".',
            'image' => 'backpack.jpg',
            'stock' => 25
        ],
        [
            'id' => 5,
            'name' => 'USB Flash Drive 64GB',
            'price' => 27000.00,
            'description' => 'High-speed USB 3.0 flash drive with company logo engraving.',
            'image' => 'usb.jpg',
            'stock' => 75
        ]
    ];
    
    save_json_data('products.json', $products);
    
    // Create empty time_entries.json
    save_json_data('time_entries.json', []);
    
    // Create empty orders.json
    save_json_data('orders.json', []);
    
    echo "✓ Data seeded successfully!<br>";
    echo "✓ Created users.json with 1 admin and 2 employees<br>";
    echo "✓ Created products.json with 5 products<br>";
    echo "✓ Created time_entries.json<br>";
    echo "✓ Created orders.json<br><br>";
    echo "<strong>Login Credentials:</strong><br>";
    echo "Admin: admin@company.com / admin123<br>";
    echo "Employee 1: john@company.com / john123<br>";
    echo "Employee 2: jane@company.com / jane123<br>";
}

/**
 * Format currency
 * @param float $amount - Amount to format
 * @return string - Formatted currency string
 */
function format_currency($amount) {
    return '₦' . number_format($amount, 2);
}

/**
 * Sanitize output for HTML display
 * @param string $string - String to sanitize
 * @return string - Sanitized string
 */
function sanitize_output($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a specific page
 * @param string $path - Path to redirect to
 */
function redirect($path) {
    header("Location: $path");
    exit();
}

/**
 * Display error message
 * @param string $message - Error message
 * @return string - HTML formatted error
 */
function show_error($message) {
    return '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">' . sanitize_output($message) . '</span>
            </div>';
}

/**
 * Display success message
 * @param string $message - Success message
 * @return string - HTML formatted success message
 */
function show_success($message) {
    return '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">' . sanitize_output($message) . '</span>
            </div>';
}
