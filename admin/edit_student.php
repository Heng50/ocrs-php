<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';
    require_once '../backend/model/studentModel.php';

    $studentModel = new Student($pdo);
    $student = $studentModel->getStudentById($_GET['id']);
?>
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <a href="student.php" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Students
                    </a>
                </div>
                <div class="mt-4">
                    <h1 class="text-3xl font-bold text-black mb-2">Edit Student</h1>
                    <p class="text-gray-600">Update student information and details.</p>
                </div>
            </div>

            <?php if($student): ?>
                <!-- Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
                    <form action="../backend/student_controller.php" method="post" class="space-y-6">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['user_id']); ?>">
                        
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" 
                                   id="username"
                                   name="username" 
                                   value="<?php echo htmlspecialchars($student['username']); ?>"
                                   class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                                   required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" 
                                   id="email"
                                   name="email" 
                                   value="<?php echo htmlspecialchars($student['email']); ?>"
                                   class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                                   required>
                        </div>
                        
                        <div class="flex space-x-4 pt-6">
                            <button type="submit" 
                                    name="edit_student" 
                                    value="Edit Student"
                                    class="inline-flex items-center px-6 py-3 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Student
                            </button>
                            <a href="student.php" class="inline-flex items-center px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Error Message -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Student Not Found</h3>
                        <p class="text-gray-600 mb-6">The student you're looking for doesn't exist or has been removed.</p>
                        <a href="student.php" class="inline-flex items-center px-6 py-3 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Students
                        </a>
                    </div>
                </div>
            <?php endif; ?>
<?php require_once 'components/admin_nav_end.php'; ?>