<?php
/**
 * Admin - Log Time Entry (The Money Engine)
 * Process hours worked and update employee balance
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
foreach ($users as $user) {
    if ($user['id'] === $employee_id && $user['role'] === 'employee') {
        $employee = $user;
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
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = $_POST['month'] ?? '';
    $year = $_POST['year'] ?? '';
    $hours = $_POST['hours'] ?? '';
    
    // Validate inputs
    if (empty($month) || empty($year) || empty($hours)) {
        $error_message = 'All fields are required.';
    } elseif (!is_numeric($hours) || $hours <= 0) {
        $error_message = 'Hours must be a positive number.';
    } else {
        $hours = (float)$hours;
        $hourly_rate = $employee['hourly_rate'];
        
        // Calculate pay
        $gross_pay = $hours * $hourly_rate;
        $tax = $gross_pay * TAX_RATE; // 10% tax
        $net_pay = $gross_pay - $tax;
        
        // Create time entry record
        $time_entry = [
            'id' => time() . rand(1000, 9999), // Simple unique ID
            'user_id' => $employee['id'],
            'user_name' => $employee['name'],
            'month' => $month,
            'year' => (int)$year,
            'hours' => $hours,
            'hourly_rate' => $hourly_rate,
            'gross_pay' => $gross_pay,
            'tax' => $tax,
            'net_pay' => $net_pay,
            'date_processed' => date('Y-m-d H:i:s')
        ];
        
        // Load time entries
        $time_entries = get_json_data('time_entries.json');
        
        // Add new entry
        $time_entries[] = $time_entry;
        
        // Save time entries
        if (save_json_data('time_entries.json', $time_entries)) {
            // UPDATE USER BALANCE (Critical Step)
            $user_index = null;
            foreach ($users as $index => $user) {
                if ($user['id'] === $employee_id) {
                    $user_index = $index;
                    break;
                }
            }
            
            if ($user_index !== null) {
                // Add net pay to user's balance
                $users[$user_index]['balance'] += $net_pay;
                
                // Save updated users
                if (save_json_data('users.json', $users)) {
                    $_SESSION['success'] = "Pay processed successfully! Net Pay: ₦" . number_format($net_pay) . " added to balance.";
                    header('Location: ' . BASE_URL . '/payroll/index.php');
                    exit();
                } else {
                    $error_message = 'Failed to update user balance.';
                }
            } else {
                $error_message = 'User not found for balance update.';
            }
        } else {
            $error_message = 'Failed to save time entry.';
        }
    }
}

// Set page title
$page_title = 'Log Hours - ' . $employee['name'];
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
        <h1 class="text-3xl font-bold text-gray-900">Log Time Entry</h1>
        <p class="mt-2 text-gray-600">Process hours worked for <?php echo sanitize_output($employee['name']); ?></p>
    </div>
    
    <!-- Employee Info Card -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6 border border-blue-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Employee Name</p>
                <p class="text-lg font-semibold text-gray-900"><?php echo sanitize_output($employee['name']); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Hourly Rate</p>
                <p class="text-lg font-semibold text-gray-900">₦<?php echo number_format($employee['hourly_rate']); ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Current Balance</p>
                <p class="text-lg font-semibold text-green-600">₦<?php echo number_format($employee['balance']); ?></p>
            </div>
        </div>
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
    
    <!-- Time Entry Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="">
            <!-- Month Dropdown -->
            <div class="mb-6">
                <label for="month" class="block text-sm font-medium text-gray-700 mb-2">
                    Month
                </label>
                <select id="month" name="month" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>
            
            <!-- Year Input -->
            <div class="mb-6">
                <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                    Year
                </label>
                <input type="number" id="year" name="year" required min="2020" max="2030" 
                       value="<?php echo date('Y'); ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="2026">
            </div>
            
            <!-- Hours Worked Input -->
            <div class="mb-6">
                <label for="hours" class="block text-sm font-medium text-gray-700 mb-2">
                    Hours Worked
                </label>
                <input type="number" id="hours" name="hours" required min="1" step="any"
                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="160">
                <p class="mt-1 text-sm text-gray-500">Enter the total hours worked for the selected month</p>
            </div>
            
            <!-- Calculation Preview -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Payment Calculation Preview</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Hourly Rate:</span>
                        <span class="font-medium">₦<?php echo number_format($employee['hourly_rate']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax Rate:</span>
                        <span class="font-medium"><?php echo (TAX_RATE * 100); ?>%</span>
                    </div>
                    <div class="border-t pt-2 mt-2">
                        <p class="text-xs text-gray-500 italic">
                            Net Pay = (Hours × Rate) - Tax<br>
                            The calculated amount will be added to employee's balance
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex items-center justify-between">
                <a href="<?php echo BASE_URL; ?>/payroll/index.php" 
                   class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md shadow-lg transition transform hover:scale-105">
                    💰 Process Pay & Update Balance
                </button>
            </div>
        </form>
    </div>
    
    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Note:</strong> When you submit this form, the system will:
                    <br>1. Calculate Gross Pay (Hours × Hourly Rate)
                    <br>2. Deduct <?php echo (TAX_RATE * 100); ?>% tax
                    <br>3. Add the Net Pay to the employee's balance
                    <br>4. Save the transaction record for future reference
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
