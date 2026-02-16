<?php
/**
 * Landing Page (Public Home)
 * Displays welcome message and navigation based on auth status
 */

$page_title = 'Home';
require_once __DIR__ . '/includes/header.php';

// Check for logout message
$show_logout_message = isset($_GET['logged_out']);
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-6">
            Welcome to <?php echo APP_NAME; ?>
        </h1>
        <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Your all-in-one Employee Welfare Portal for Payroll Management and Marketplace Shopping
        </p>
        
        <?php if ($show_logout_message): ?>
            <div class="mb-8 inline-block bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                ✓ You have been successfully logged out
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Navigation Cards (if logged in) -->
<?php if (is_logged_in()): ?>
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
            Quick Access
        </h2>
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Payroll Card -->
            <a href="<?php echo BASE_URL; ?>/payroll/index.php" class="block group">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 border-2 border-transparent hover:border-blue-500">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 text-blue-600 rounded-full mb-6 text-4xl">
                            💰
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            Payroll System
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Track your time, view earnings, and manage payroll information
                        </p>
                        <span class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold group-hover:bg-blue-700 transition">
                            Go to Payroll →
                        </span>
                    </div>
                </div>
            </a>
            
            <!-- Marketplace Card -->
            <a href="<?php echo BASE_URL; ?>/marketplace/index.php" class="block group">
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 border-2 border-transparent hover:border-indigo-500">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full mb-6 text-4xl">
                            🛒
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            Marketplace
                        </h3>
                        <p class="text-gray-600 mb-6">
                            Browse products, shop with your balance, and manage orders
                        </p>
                        <span class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold group-hover:bg-indigo-700 transition">
                            Go to Marketplace →
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<?php else: ?>
<!-- Login Call-to-Action (if not logged in) -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">
                Ready to Get Started?
            </h2>
            <p class="text-lg text-gray-600 mb-8">
                Log in to access your payroll information and start shopping in the marketplace
            </p>
            <a href="<?php echo BASE_URL; ?>/auth/login.php" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-12 py-5 rounded-xl text-xl font-bold shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                🔐 Login to Portal
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Group Members Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">
            Meet Our Team
        </h2>
        <p class="text-center text-gray-600 mb-12">
            The brilliant minds behind <?php echo APP_NAME; ?>
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Member 1 -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        OY
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Olanrewaju Yusuf Damola
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        19/2382
                    </p>
                </div>
            </div>
            
            <!-- Member 2 -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        DS
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Damilola Sofowora
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        11/2490
                    </p>
                </div>
            </div>
            
            <!-- Member 3 -->
            <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-teal-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        OA
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Oluwatimilehin Adelowo
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        PG/25/0069
                    </p>
                </div>
            </div>
            
            <!-- Member 4 -->
            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        OO
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Oshundiya Oluwapelumi
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        19/2255
                    </p>
                </div>
            </div>
            
            <!-- Member 5 -->
            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        KH
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Kwasau Henry
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        11/0992
                    </p>
                </div>
            </div>
            
            <!-- Member 6 -->
            <div class="bg-gradient-to-br from-cyan-50 to-sky-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-cyan-500 to-sky-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        OT
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Olarewaju Tobiloba
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        14/1636
                    </p>
                </div>
            </div>
            
            <!-- Member 7 -->
            <div class="bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-rose-500 to-pink-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        OA
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Onyeacholem Anita
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        PG/25/0016
                    </p>
                </div>
            </div>
            
            <!-- Member 8 -->
            <div class="bg-gradient-to-br from-violet-50 to-purple-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-violet-500 to-purple-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        FA
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Fasanya Azeez Adedeji
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        PG/25/0018
                    </p>
                </div>
            </div>
            
            <!-- Member 9 -->
            <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        AA
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Atolagbe Ademola
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        PG/22/0563
                    </p>
                </div>
            </div>
            
            <!-- Member 10 -->
            <div class="bg-gradient-to-br from-fuchsia-50 to-pink-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-fuchsia-500 to-pink-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        FB
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Fabiyi Blessing Grace
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        PG/25/0683
                    </p>
                </div>
            </div>
            
            <!-- Member 11 -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-2xl font-bold">
                        CM
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                        Christopher Micah
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Developer
                    </p>
                    <p class="text-xs text-gray-500 font-mono">
                        PG/25/0017
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Overview -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
            Platform Features
        </h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4 text-3xl">
                    ⏱️
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                    Time Tracking
                </h3>
                <p class="text-gray-600">
                    Log work hours easily and track your earnings in real-time
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full mb-4 text-3xl">
                    🛍️
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                    Employee Marketplace
                </h3>
                <p class="text-gray-600">
                    Shop from a curated selection of products using your earned balance
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-4 text-3xl">
                    📊
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">
                    Admin Dashboard
                </h3>
                <p class="text-gray-600">
                    Complete management tools for payroll and inventory oversight
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
