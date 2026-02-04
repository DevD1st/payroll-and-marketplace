<?php
/**
 * Employee Payroll Dashboard
 * View balance and payslips
 */

// This file is included from payroll/index.php
$page_title = 'My Payroll';
require_once __DIR__ . '/../../includes/header.php';

// Get current user data
$current_user = $_SESSION['user'];

// Load time entries
$all_time_entries = get_json_data('time_entries.json');

// Filter entries for this user only
$my_entries = array_filter($all_time_entries, function($entry) use ($current_user) {
    return $entry['user_id'] === $current_user['id'];
});

// Sort by most recent first
usort($my_entries, function($a, $b) {
    return strtotime($b['date_processed']) - strtotime($a['date_processed']);
});

// Calculate total earnings
$total_gross = 0;
$total_tax = 0;
$total_net = 0;
foreach ($my_entries as $entry) {
    $total_gross += $entry['gross_pay'];
    $total_tax += $entry['tax'];
    $total_net += $entry['net_pay'];
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Payroll Dashboard</h1>
        <p class="mt-2 text-gray-600">Welcome, <?php echo sanitize_output($current_user['name']); ?></p>
    </div>
    
    <!-- Balance Card -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-2xl p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-green-100 text-sm uppercase tracking-wide font-semibold mb-2">Your Current Balance</p>
                <h2 class="text-5xl font-bold mb-2">
                    ₦<?php echo number_format($current_user['balance']); ?>
                </h2>
                <p class="text-green-100 text-sm">Available for marketplace purchases</p>
            </div>
            <div class="mt-6 md:mt-0">
                <a href="<?php echo BASE_URL; ?>/marketplace/index.php" 
                   class="inline-block bg-white text-green-600 px-8 py-4 rounded-lg font-semibold hover:bg-green-50 transition shadow-lg">
                    🛒 Shop in Marketplace
                </a>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Hourly Rate</p>
                    <p class="text-lg font-bold text-gray-900">₦<?php echo number_format($current_user['hourly_rate']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Total Earnings</p>
                    <p class="text-lg font-bold text-gray-900">₦<?php echo number_format($total_net); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Payslips</p>
                    <p class="text-lg font-bold text-gray-900"><?php echo count($my_entries); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-medium text-gray-600">Total Tax</p>
                    <p class="text-lg font-bold text-gray-900">₦<?php echo number_format($total_tax); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Payslips -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Recent Payslips</h2>
            <span class="text-sm text-gray-500"><?php echo count($my_entries); ?> total records</span>
        </div>
        
        <?php if (empty($my_entries)): ?>
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-2 text-gray-500">No payslips yet</p>
                <p class="text-sm text-gray-400">Your payment records will appear here once processed by admin</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Pay</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax (10%)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Pay</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Processed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($my_entries as $entry): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo sanitize_output($entry['month']); ?> <?php echo $entry['year']; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo number_format($entry['hours'], 1); ?> hrs</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₦<?php echo number_format($entry['hourly_rate']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">₦<?php echo number_format($entry['gross_pay']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-red-600">-₦<?php echo number_format($entry['tax']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-green-600">₦<?php echo number_format($entry['net_pay']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($entry['date_processed'])); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="<?php echo BASE_URL; ?>/payroll/employee/payslip.php?id=<?php echo $entry['id']; ?>" 
                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        View/Print
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Summary Box -->
    <?php if (!empty($my_entries)): ?>
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Payment Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-blue-700">Total Gross Pay:</p>
                    <p class="text-2xl font-bold text-blue-900">₦<?php echo number_format($total_gross); ?></p>
                </div>
                <div>
                    <p class="text-blue-700">Total Tax Deducted:</p>
                    <p class="text-2xl font-bold text-red-600">-₦<?php echo number_format($total_tax); ?></p>
                </div>
                <div>
                    <p class="text-blue-700">Total Net Earnings:</p>
                    <p class="text-2xl font-bold text-green-600">₦<?php echo number_format($total_net); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
