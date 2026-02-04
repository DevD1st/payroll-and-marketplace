<?php
/**
 * Employee Payslip View
 * Individual payslip with print functionality
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Require employee login
require_login();

// Get entry ID from URL
$entry_id = $_GET['id'] ?? '';

if (empty($entry_id)) {
    $_SESSION['error'] = 'Invalid payslip ID.';
    header('Location: ' . BASE_URL . '/payroll/index.php');
    exit();
}

// Load time entries
$time_entries = get_json_data('time_entries.json');

// Find the specific entry
$entry = null;
foreach ($time_entries as $e) {
    if ($e['id'] == $entry_id && $e['user_id'] === $_SESSION['user']['id']) {
        $entry = $e;
        break;
    }
}

// If not found or not owned by user, redirect
if (!$entry) {
    $_SESSION['error'] = 'Payslip not found or access denied.';
    header('Location: ' . BASE_URL . '/payroll/index.php');
    exit();
}

// Set page title
$page_title = 'Payslip - ' . $entry['month'] . ' ' . $entry['year'];
require_once __DIR__ . '/../../includes/header.php';
?>

<style>
    @media print {
        /* Hide everything */
        body * {
            visibility: hidden;
        }
        
        /* Show only the payslip card and its children */
        .payslip-card,
        .payslip-card * {
            visibility: visible;
        }
        
        /* Position the payslip card */
        .payslip-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
            box-shadow: none !important;
            border: none !important;
        }
        
        /* Hide the print button when printing */
        .no-print {
            display: none !important;
        }
        
        /* Hide navigation */
        nav,
        footer {
            display: none !important;
        }
    }
</style>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back and Print Buttons -->
    <div class="mb-6 flex justify-between items-center no-print">
        <a href="<?php echo BASE_URL; ?>/payroll/index.php" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
        
        <button onclick="window.print()" 
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Payslip
        </button>
    </div>
    
    <!-- Payslip Card -->
    <div class="payslip-card bg-white rounded-lg shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2"><?php echo APP_NAME; ?></h1>
                    <p class="text-blue-100">Employee Payslip</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100">Payslip #<?php echo $entry['id']; ?></p>
                    <p class="text-sm text-blue-100">Generated: <?php echo date('M d, Y'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Employee & Period Info -->
        <div class="px-8 py-6 border-b border-gray-200">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Employee Details</h3>
                    <p class="text-lg font-bold text-gray-900"><?php echo sanitize_output($entry['user_name']); ?></p>
                    <p class="text-sm text-gray-600">ID: <?php echo $entry['user_id']; ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Pay Period</h3>
                    <p class="text-lg font-bold text-gray-900"><?php echo $entry['month']; ?> <?php echo $entry['year']; ?></p>
                    <p class="text-sm text-gray-600">Processed: <?php echo date('M d, Y', strtotime($entry['date_processed'])); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Earnings Breakdown -->
        <div class="px-8 py-6 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Earnings Breakdown</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Hours Worked:</span>
                    <span class="font-semibold text-gray-900"><?php echo number_format($entry['hours'], 2); ?> hours</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Hourly Rate:</span>
                    <span class="font-semibold text-gray-900">₦<?php echo number_format($entry['hourly_rate'], 2); ?></span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t">
                    <span class="text-gray-900 font-medium">Gross Pay:</span>
                    <span class="font-bold text-gray-900 text-lg">₦<?php echo number_format($entry['gross_pay'], 2); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Deductions -->
        <div class="px-8 py-6 border-b border-gray-200 bg-red-50">
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Deductions</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tax (<?php echo (TAX_RATE * 100); ?>%):</span>
                    <span class="font-semibold text-red-600">-₦<?php echo number_format($entry['tax'], 2); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Net Pay -->
        <div class="px-8 py-8 bg-gradient-to-br from-green-50 to-emerald-50">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Net Pay (Take Home)</p>
                    <p class="text-4xl font-bold text-green-600">₦<?php echo number_format($entry['net_pay'], 2); ?></p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-50 border-t">
            <p class="text-xs text-gray-500 text-center">
                This is a computer-generated payslip and does not require a signature.
                <br>
                For queries, please contact the HR department.
            </p>
        </div>
    </div>
    
    <!-- Additional Info -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 no-print">
        <p class="text-sm text-blue-700">
            <strong>💡 Tip:</strong> Use the "Print Payslip" button above to save or print this document. 
            Only the payslip content will appear on the printed page.
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
