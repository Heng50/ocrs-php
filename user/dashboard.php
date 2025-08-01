<?php
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    session_start();
    require_once '../header.php';

    
    // Debug information
    echo "<!-- Debug: Session data: " . print_r($_SESSION, true) . " -->";
    
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        echo "<!-- Debug: No user session found -->";
        header('Location: ../index.php');
        exit();
    }
    
    // If profile is not completed, redirect to profile completion page
    if ($_SESSION['user']['profile_completed'] == 0) {
        // Use JavaScript to redirect instead of PHP header
        // header('Location: profile_completed.php');
        echo '<script type="text/javascript">window.location.href = "profile_completed.php";</script>';
        // Fallback for if JavaScript is disabled
        echo '<noscript><meta http-equiv="refresh" content="0;url=profile_completed.php"></noscript>';
        exit();
    }
    
    echo "<!-- Debug: Dashboard should display now -->";
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-black">OCRS</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                    </span>
                    <a href="../backend/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600 transition-colors duration-200">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm border-r border-gray-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-black mb-6">Navigation</h2>
                <nav class="space-y-2">
                    <a href="course.php" class="flex items-center px-4 py-3 text-black rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Browse Courses
                    </a>
                    <a href="schedule.php" class="flex items-center px-4 py-3 text-black rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        My Schedule
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Dashboard Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black mb-2">Welcome back, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</h1>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-black mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="course.php" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-[#FFD700] rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-black font-medium">Browse Available Courses</p>
                                    <p class="text-sm text-gray-500">Search and enroll in courses</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-black transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        
                        <a href="schedule.php" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-[#FFD700] rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-black font-medium">View My Schedule</p>
                                    <p class="text-sm text-gray-500">Check your class timings</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-black transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require_once '../footer.php';
?>