<?php
/**
 * Login Page
 * Handles user authentication
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    $redirect = is_admin() ? BASE_URL . '/payroll/index.php' : BASE_URL . '/index.php';
    header('Location: ' . $redirect);
    exit();
}

// Initialize variables
$error_message = '';

// Hardcoded credentials
$hardcoded_credentials = [
    'admin' => [
        'email' => 'admin@portal.com',
        'password' => 'admin123',
        'name' => 'System Admin',
        'role' => 'admin',
        'hourly_rate' => 0
    ],
    'employee' => [
        'email' => 'user@portal.com',
        'password' => 'user123',
        'name' => 'Standard User',
        'role' => 'employee',
        'hourly_rate' => 50000
    ]
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $error_message = 'Please enter both email and password.';
    } else {
        // Load users from JSON
        $users = get_json_data('users.json');
        
        // Initialize authentication variables
        $authenticated = false;
        $user_data = null;
        $is_hardcoded = false;
        
        // STEP 1: Check if credentials match hardcoded accounts
        foreach ($hardcoded_credentials as $hardcoded_user) {
            if ($email === $hardcoded_user['email'] && $password === $hardcoded_user['password']) {
                $is_hardcoded = true;
                
                // Check if this hardcoded user exists in users.json
                $found_in_db = false;
                foreach ($users as $user) {
                    if ($user['email'] === $hardcoded_user['email']) {
                        $found_in_db = true;
                        $user_data = $user;
                        $authenticated = true;
                        break;
                    }
                }
                
                // SELF-HEALING: If hardcoded user doesn't exist in DB, create it
                if (!$found_in_db) {
                    // Generate new ID
                    $new_id = empty($users) ? 1 : max(array_column($users, 'id')) + 1;
                    
                    // Create new user
                    $user_data = [
                        'id' => $new_id,
                        'name' => $hardcoded_user['name'],
                        'email' => $hardcoded_user['email'],
                        'password' => $hardcoded_user['password'],
                        'role' => $hardcoded_user['role'],
                        'hourly_rate' => $hardcoded_user['hourly_rate'],
                        'balance' => 0
                    ];
                    
                    // Add to users array and save
                    $users[] = $user_data;
                    save_json_data('users.json', $users);
                    
                    $authenticated = true;
                }
                
                break;
            }
        }
        
        // STEP 2: If not hardcoded, check normal users in database
        if (!$is_hardcoded) {
            foreach ($users as $user) {
                if ($user['email'] === $email && $user['password'] === $password) {
                    $authenticated = true;
                    $user_data = $user;
                    break;
                }
            }
        }
        
        // STEP 3: Process authentication result
        if ($authenticated && $user_data) {
            // Set session data
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['role'] = $user_data['role'];
            $_SESSION['user'] = [
                'id' => $user_data['id'],
                'name' => $user_data['name'],
                'email' => $user_data['email'],
                'role' => $user_data['role'],
                'hourly_rate' => $user_data['hourly_rate'] ?? 0,
                'balance' => $user_data['balance'] ?? 0
            ];
            
            // Check for redirect after login
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect_url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $redirect_url);
                exit();
            }
            
            // Redirect employees to payroll page, admins to payroll dashboard
            header('Location: ' . BASE_URL . '/payroll/index.php');
            exit();
        } else {
            $error_message = 'Invalid credentials. Please try again.';
        }
    }
}

// Set page title
$page_title = 'Login';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Login Section -->
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div>
            <h2 class="mt-6 text-center text-4xl font-extrabold text-gray-900">
                Welcome Back
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sign in to access your portal
            </p>
        </div>
        
        <!-- Login Form -->
        <div class="mt-8 bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
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
            
            <form class="space-y-6" method="POST" action="">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="<?php echo isset($_POST['email']) ? sanitize_output($_POST['email']) : ''; ?>"
                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                            placeholder="your.email@example.com"
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-150"
                    >
                        Sign In
                    </button>
                </div>
            </form>
            
            <!-- Demo Credentials -->
            <div class="mt-6 border-t pt-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Demo Credentials (Self-Healing)</h3>
                <div class="space-y-2 text-xs">
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="font-medium text-gray-700">Admin Account:</p>
                        <p class="text-gray-600">Email: <code class="bg-white px-1 py-0.5 rounded">admin@portal.com</code></p>
                        <p class="text-gray-600">Password: <code class="bg-white px-1 py-0.5 rounded">admin123</code></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="font-medium text-gray-700">Employee Account:</p>
                        <p class="text-gray-600">Email: <code class="bg-white px-1 py-0.5 rounded">user@portal.com</code></p>
                        <p class="text-gray-600">Password: <code class="bg-white px-1 py-0.5 rounded">user123</code></p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded border border-blue-200">
                        <p class="text-blue-700">
                            <strong>✨ Auto-creates users if they don't exist in database</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
