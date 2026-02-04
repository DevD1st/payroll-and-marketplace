<?php
/**
 * Admin - Edit Employee Hourly Rate
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Require admin access
require_admin();

// Get employee ID from URL
$employee_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Load users
$users = get_json_data('users.json');

// Find the employee
$employee = null;
$employee_index = null;
foreach ($users as $index => $user) {
    if ($user['id'] === $employee_id && $user['role'] === 'employee') {
        $employee = $user;
        $employee_index = $index;
        break;
    }
}

// If employee not found, redirect back
if (!$employee) {
    $_SESSION['error'] = 'Employee not found.';
    header('Location: ' . BASE_URL . '/payroll/index.php');
    exit();
}

// Initialize variables
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_rate = $_POST['hourly_rate'] ?? '';
    
    // Validate input
    if (empty($new_rate)) {
        $error_message = 'Hourly rate is required.';
    } elseif (!is_numeric($new_rate) || $new_rate < 0) {
        $error_message = 'Hourly rate must be a positive number.';
    } else {
        // Update the rate
        $users[$employee_index]['hourly_rate'] = (float)$new_rate;
        
        // Save to file
        if (save_json_data('users.json', $users)) {
            $_SESSION['success'] = "Hourly rate updated successfully for " . $employee['name'];
            header('Location: ' . BASE_URL . '/payroll/index.php');
            exit();
        } else {
            $error_message = 'Failed to update hourly rate.';
        }
    }
}

// Set page title
$page_title = 'Edit Rate - ' . $employee['name'];
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?php echo BASE_URL; ?>/payroll/index.php" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
    </div>
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Update Hourly Rate</h1>
        <p class="mt-2 text-gray-600">Edit hourly rate for <?php echo sanitize_output($employee['name']); ?></p>
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
    
    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Employee Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-bold text-2xl">
                        <?php echo strtoupper(substr($employee['name'], 0, 2)); ?>
                    </span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900"><?php echo sanitize_output($employee['name']); ?></h3>
                    <p class="text-sm text-gray-600"><?php echo sanitize_output($employee['email']); ?></p>
                    <p class="text-sm text-gray-500 mt-1">
                        Current Rate: <span class="font-semibold text-gray-900">₦<?php echo number_format($employee['hourly_rate']); ?></span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Form -->
        <form method="POST" action="">
            <div class="mb-6">
                <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">
                    New Hourly Rate (₦)
                </label>
                <input type="number" id="hourly_rate" name="hourly_rate" required min="0" step="1000"
                       value="<?php echo $employee['hourly_rate']; ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                       placeholder="50000">
                <p class="mt-2 text-sm text-gray-500">Enter the new hourly rate in Naira (₦)</p>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-4">
                <a href="<?php echo BASE_URL; ?>/payroll/index.php" 
                   class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-lg transition transform hover:scale-105">
                    Update Hourly Rate
                </button>
            </div>
        </form>
    </div>
    
    <!-- Info Box -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Important:</strong> Changing the hourly rate will only affect future time entries. Past payslips will not be recalculated.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
