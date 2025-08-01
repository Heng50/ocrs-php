<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';
    require_once '../backend/enrollment_controller.php';

    $enrollment = new EnrollmentController($pdo);
    $enrolledStudents = $enrollment->getEnrolledStudent();
?>

<script>
function validateGrade(button) {
    var form = button.closest('form');
    var gradeSelect = form.querySelector('select[name="grade"]');
    
    if (!gradeSelect.value) {
        alert('Please select a grade before updating.');
        return false;
    }
    return true;
}
</script>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black mb-2">Grade Management</h1>
                <p class="text-gray-600">Update grades for enrolled students.</p>
            </div>

            <!-- Statistics Cards -->
            <?php
                $total_enrolled = count($enrolledStudents);
                $graded_count = 0;
                $ungraded_count = 0;
                
                foreach($enrolledStudents as $student) {
                    if($student['final_grade'] !== null) {
                        $graded_count++;
                    } else {
                        $ungraded_count++;
                    }
                }
            ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Enrolled</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $total_enrolled; ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Graded</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $graded_count; ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending Grades</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $ungraded_count; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            <?php if(isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Grades Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if($enrolledStudents): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update Grade</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($enrolledStudents as $student): ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['user_id']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($student['username']); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($student['course_code'] . ' - ' . $student['course_name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($student['semester_code']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($student['final_grade'] !== null): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <?php echo htmlspecialchars($student['final_grade']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Not Graded
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <form method="POST" action="../backend/enrollment_controller.php" class="flex items-center space-x-2">
                                                <input type="hidden" name="enrollment_id" value="<?php echo $student['enrollment_id']; ?>">
                                                <input type="hidden" name="action" value="update_grade">
                                                <select name="grade" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Select Grade</option>
                                                    <option value="A" <?php echo ($student['final_grade'] === 'A') ? 'selected' : ''; ?>>A</option>
                                                    <option value="A-" <?php echo ($student['final_grade'] === 'A-') ? 'selected' : ''; ?>>A-</option>
                                                    <option value="B+" <?php echo ($student['final_grade'] === 'B+') ? 'selected' : ''; ?>>B+</option>
                                                    <option value="B" <?php echo ($student['final_grade'] === 'B') ? 'selected' : ''; ?>>B</option>
                                                    <option value="B-" <?php echo ($student['final_grade'] === 'B-') ? 'selected' : ''; ?>>B-</option>
                                                    <option value="C+" <?php echo ($student['final_grade'] === 'C+') ? 'selected' : ''; ?>>C+</option>
                                                    <option value="C" <?php echo ($student['final_grade'] === 'C') ? 'selected' : ''; ?>>C</option>
                                                    <option value="C-" <?php echo ($student['final_grade'] === 'C-') ? 'selected' : ''; ?>>C-</option>
                                                    <option value="D+" <?php echo ($student['final_grade'] === 'D+') ? 'selected' : ''; ?>>D+</option>
                                                    <option value="D" <?php echo ($student['final_grade'] === 'D') ? 'selected' : ''; ?>>D</option>
                                                    <option value="E" <?php echo ($student['final_grade'] === 'E') ? 'selected' : ''; ?>>E</option>
                                                </select>
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600 transition-colors duration-200" onclick="return validateGrade(this)">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                    </svg>
                                                    Update
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No enrolled students found</h3>
                        <p class="text-gray-500">There are no enrolled students to display grades for.</p>
                    </div>
                <?php endif; ?>
            </div>
<?php require_once 'components/admin_nav_end.php'; ?> 