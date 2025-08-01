<?php
    session_start();
    require_once '../header.php';
    require_once '../backend/schedule_controller.php';

    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        header('Location: ../index.php');
        exit();
    }
    
    // If profile is not completed, redirect to profile completion page
    if ($_SESSION['user']['profile_completed'] == 0) {
        header('Location: profile_completed.php');
        exit();
    }

    $schedule = new ClassSchedule($pdo);
    $userSchedule = $schedule->getUserSchedule($_SESSION['user']['id']);
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
                    <a href="dashboard.php" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="course.php" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Browse Courses
                    </a>
                    <a href="schedule.php" class="flex items-center px-4 py-3 text-black bg-[#FFD700] rounded-lg group">
                        <svg class="w-5 h-5 mr-3 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        My Schedule
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black mb-2">My Schedule</h1>
                <p class="text-gray-600">View your enrolled courses and their schedules.</p>
            </div>
            
            <?php 
            // Handle model return values
            $enrollments = [];
            $schedules = [];
            
            if (is_array($userSchedule) && count($userSchedule) === 2) {
                // Success case - model returns [enrollments, schedules]
                $enrollments = $userSchedule[0];
                $schedules = $userSchedule[1];
            } elseif (is_numeric($userSchedule)) {
                // Error case - model returns error code
                $enrollments = [];
            }
            
            // Group enrollments by semester
            $semesterGroups = [];
            foreach ($enrollments as $enrollment) {
                $semester = $enrollment['semester_code'];
                if (!isset($semesterGroups[$semester])) {
                    $semesterGroups[$semester] = [];
                }
                $semesterGroups[$semester][] = $enrollment;
            }
            
            if (empty($enrollments)): ?>
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No enrolled courses</h3>
                    <p class="text-gray-500 mb-6">You need to enroll in courses first to view your schedule.</p>
                    <a href="course.php" class="inline-block bg-[#FFD700] text-black px-6 py-3 rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                        Browse Courses
                    </a>
                </div>
            <?php else: ?>
                <!-- Semester Groups -->
                <div class="space-y-8">
                    <?php foreach ($semesterGroups as $semester => $semesterEnrollments): ?>
                        <!-- Semester Header -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="p-6 border-b border-gray-200 bg-[#FFD700]">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <h2 class="text-2xl font-bold text-black"><?php echo htmlspecialchars($semester); ?></h2>
                                        <span class="px-3 py-1 text-sm bg-black text-white rounded-full font-medium">
                                            <?php echo count($semesterEnrollments); ?> Course<?php echo count($semesterEnrollments) > 1 ? 's' : ''; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Courses in this semester -->
                            <div class="divide-y divide-gray-200">
                                <?php foreach ($semesterEnrollments as $enrollment): ?>
                                    <div class="p-6">
                                        <!-- Course Header -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-xl font-semibold text-black">
                                                        <?php echo htmlspecialchars($enrollment['course_code']); ?>
                                                    </h3>
                                                    <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full font-medium">
                                                        Enrolled
                                                    </span>
                                                </div>
                                                <h4 class="text-lg font-medium text-black mb-2">
                                                    <?php echo htmlspecialchars($enrollment['course_name']); ?>
                                                </h4>
                                                <div class="flex items-center gap-1 text-sm text-gray-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span><?php echo htmlspecialchars($enrollment['instructor_name']); ?></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-500">Enrolled on</p>
                                                <p class="text-sm font-medium text-black">
                                                    <?php echo date('M j, Y', strtotime($enrollment['enrolled_at'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Schedule Section -->
                                        <div class="mt-4">
                                            <h5 class="text-sm font-semibold text-black mb-4 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-[#FFD700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Class Schedule
                                            </h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="schedules-<?php echo $enrollment['session_id']; ?>">
                                                <div class="flex items-center justify-center py-8">
                                                    <div class="text-center">
                                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#FFD700] mx-auto mb-2"></div>
                                                        <p class="text-gray-500 text-sm">Loading schedules...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Load class schedules for each enrollment
document.addEventListener('DOMContentLoaded', function() {
    const enrollments = <?php echo json_encode($enrollments); ?>;
    const allSchedules = <?php echo json_encode($schedules); ?>;
    
    enrollments.forEach(enrollment => {
        displayClassSchedules(enrollment.session_id, allSchedules);
    });
});

function displayClassSchedules(sessionId, allSchedules) {
    const container = document.getElementById(`schedules-${sessionId}`);
    
    // Filter schedules for this specific session
    const sessionSchedules = allSchedules.filter(schedule => schedule.session_id == sessionId);
    
    if (sessionSchedules.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500">No schedules assigned</p>
                <p class="text-sm text-gray-400 mt-1">Check back later for class schedules</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = sessionSchedules.map(schedule => `
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-sm transition-shadow duration-200">
            <div class="text-center">
                <h6 class="font-semibold text-black text-sm mb-2">${schedule.day}</h6>
                <div class="text-sm text-gray-600 mb-2">
                    ${formatTime(schedule.start_time)} - ${formatTime(schedule.end_time)}
                </div>
                <div class="text-xs text-gray-500 flex items-center justify-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    ${schedule.location}
                </div>
            </div>
        </div>
    `).join('');
}

function formatTime(timeString) {
    const time = new Date(`2000-01-01T${timeString}`);
    return time.toLocaleTimeString('en-US', { 
        hour: 'numeric', 
        minute: '2-digit',
        hour12: true 
    });
}
</script>

<?php
    require_once '../footer.php';
?> 