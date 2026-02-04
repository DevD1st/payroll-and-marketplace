<?php
/**
 * Admin - Add New User
 * Create new admin or employee users
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
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $hourly_rate = $_POST['hourly_rate'] ?? '';
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error_message = 'All fields except Hourly Rate are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } elseif (!in_array($role, ['admin', 'employee'])) {
        $error_message = 'Invalid role selected.';
    } elseif ($role === 'employee' && (empty($hourly_rate) || !is_numeric($hourly_rate) || $hourly_rate < 0)) {
        $error_message = 'Please enter a valid hourly rate for employees.';
    } else {
        // Load existing users
        $users = get_json_data('users.json');
        
        // Check if email already exists
        $email_exists = false;
        foreach ($users as $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                $email_exists = true;
                break;
            }
        }
        
        if ($email_exists) {
            $error_message = 'Email address already exists. Please use a different email.';
        } else {
            // Generate unique ID
            $new_id = empty($users) ? 1 : max(array_column($users, 'id')) + 1;
            
            // Create new user
            $new_user = [
                'id' => $new_id,
                'name' => $name,
                'email' => $email,
                'password' => $password, // Plain text as per requirements
                'role' => $role,
                'hourly_rate' => $role === 'employee' ? (float)$hourly_rate : 0,
                'balance' => 0
            ];
            
            // Add to users array
            $users[] = $new_user;
            
            // Save to file
            if (save_json_data('users.json', $users)) {
                $_SESSION['success'] = "User '{$name}' created successfully!";
                header('Location: ' . BASE_URL . '/payroll/index.php');
                exit();
            } else {
                $error_message = 'Failed to save user data.';
            }
        }
    }
}

// Set page title
$page_title = 'Add New User';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?php echo BASE_URL; ?>/payroll/index.php" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
    </div>
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add New User</h1>
        <p class="mt-2 text-gray-600">Create a new admin or employee account</p>
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
    
    <!-- User Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="" id="userForm">
            <!-- Full Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" required
                       value="<?php echo isset($_POST['name']) ? sanitize_output($_POST['name']) : ''; ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="e.g., John Doe">
            </div>
            
            <!-- Email Address -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" name="email" required
                       value="<?php echo isset($_POST['email']) ? sanitize_output($_POST['email']) : ''; ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="john.doe@company.com">
                <p class="mt-1 text-sm text-gray-500">This will be used for login</p>
            </div>
            
            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="text" id="password" name="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter password">
                <p class="mt-1 text-sm text-gray-500">User will use this password to log in</p>
            </div>
            
            <!-- Role -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role" name="role" required onchange="toggleHourlyRate()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Role</option>
                    <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>
                        Admin
                    </option>
                    <option value="employee" <?php echo (isset($_POST['role']) && $_POST['role'] === 'employee') ? 'selected' : ''; ?>>
                        Employee
                    </option>
                </select>
                <p class="mt-1 text-sm text-gray-500">Admin: Full system access | Employee: Limited access</p>
            </div>
            
            <!-- Hourly Rate (conditional for employees) -->
            <div class="mb-6" id="hourlyRateField" style="display: none;">
                <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">
                    Hourly Rate (₦) <span class="text-red-500" id="requiredStar">*</span>
                </label>
                <input type="number" id="hourly_rate" name="hourly_rate" min="0" step="0.01"
                       value="<?php echo isset($_POST['hourly_rate']) ? $_POST['hourly_rate'] : ''; ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="50000.00">
                <p class="mt-1 text-sm text-gray-500">Required for employees. Enter 0 or leave blank for admins.</p>
            </div>
            
            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Note:</strong> The user will be created with:
                            <br>• Initial balance of ₦0
                            <br>• Immediate login access using the provided credentials
                            <br>• Role-specific dashboard access
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-4">
                <a href="<?php echo BASE_URL; ?>/payroll/index.php" 
                   class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow-lg transition transform hover:scale-105">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Show/hide hourly rate field based on role selection
function toggleHourlyRate() {
    const role = document.getElementById('role').value;
    const hourlyRateField = document.getElementById('hourlyRateField');
    const hourlyRateInput = document.getElementById('hourly_rate');
    
    if (role === 'employee') {
        hourlyRateField.style.display = 'block';
        hourlyRateInput.required = true;
    } else {
        hourlyRateField.style.display = 'none';
        hourlyRateInput.required = false;
        hourlyRateInput.value = '0';
    }
}

// Initialize on page load if role is already selected
document.addEventListener('DOMContentLoaded', function() {
    toggleHourlyRate();
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
