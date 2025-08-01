<?php
// Admin Navigation Component
// Usage: include this file in admin pages and pass the current page name to highlight the active menu item

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo '<script type="text/javascript">window.location.href = "../index.php";</script>';
    // Fallback for if JavaScript is disabled
    echo '<noscript><meta http-equiv="refresh" content="0;url=../index.php"></noscript>';
    exit();
}

function getCurrentPage() {
    $current_file = basename($_SERVER['PHP_SELF']);
    return $current_file;
}

$current_page = getCurrentPage();
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-black">OCRS Admin</h1>
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
                <h2 class="text-lg font-semibold text-black mb-6">Admin Dashboard</h2>
                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'dashboard.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'dashboard.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="semester.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'semester.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'semester.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Semester Management
                    </a>
                    <a href="category.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'category.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'category.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Category Management
                    </a>
                    <a href="programme.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'programme.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'programme.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Programme Management
                    </a>
                    <a href="course.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'course.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'course.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Course Management
                    </a>
                    <a href="class_session.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'class_session.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'class_session.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Class Sessions
                    </a>
                    <a href="enrollment.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'enrollment.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'enrollment.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Enrollment Management
                    </a>
                    <a href="student.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'student.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'student.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Student Management
                    </a>
                    <a href="student_approval.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'student_approval.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'student_approval.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Student Approval
                    </a>
                    <a href="grades.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'grades.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'grades.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Grades Management
                    </a>
                    <a href="reports.php" class="flex items-center px-4 py-3 <?php echo ($current_page == 'reports.php') ? 'text-black bg-[#FFD700]' : 'text-gray-600 hover:bg-[#FFD700] hover:text-black'; ?> rounded-lg transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 <?php echo ($current_page == 'reports.php') ? 'text-black' : 'text-gray-400 group-hover:text-black'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Reports
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8"> 