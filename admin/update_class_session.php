<?php
    session_start();
    require_once '../header.php';

    $stmt = $pdo->prepare("SELECT *, c.course_code, c.course_name, u.username FROM class_sessions cs
    JOIN courses c ON cs.course_id = c.course_id 
    JOIN users u ON cs.instructor_id = u.user_id 
    WHERE cs.session_id = ?");
    $stmt->execute([$_GET['id']]);
    $class_session = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM class_schedules WHERE session_id = ?");
    $stmt->execute([$_GET['id']]);
    $schedules = $stmt->fetchAll();
    $all_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    $used_days = [];
    foreach ($schedules as $row):
        $used_days[] = $row['day'];
    endforeach;
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
                    <a href="dashboard.php" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="programme.php" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Programme Management
                    </a>
                    <a href="course.php" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Course Management
                    </a>
                    <a href="class_session.php" class="flex items-center px-4 py-3 text-black bg-[#FFD700] rounded-lg group">
                        <svg class="w-5 h-5 mr-3 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Class Sessions
                    </a>
                    <a href="enrollment.php" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Enrollment Management
                    </a>
                    <a href="student.php" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Student Management
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <a href="class_session.php" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Class Sessions
                    </a>
                </div>
                <div class="mt-4">
                    <h1 class="text-3xl font-bold text-black mb-2">Update Class Session</h1>
                    <p class="text-gray-600">Edit class session details and schedules.</p>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <form action="../backend/class_session_controller.php" method="post" class="space-y-6" onsubmit="return prepareForm()">
                    <input type="hidden" name="action" value="editClassSession">
                    <input type="hidden" name="session_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                    <input type="hidden" name="schedules" id="schedules_json">
                    
                    <!-- Course Selection -->
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                        <select name="course_id" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                            <?php
                                require_once '../backend/model/courseModel.php';
                                $courseModel = new Course($pdo);
                                $courses = $courseModel->selectOption();
                                if($courses !== 1):
                                    foreach($courses as $course):
                                        $selected = ($course['course_id'] == $class_session['course_id']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo htmlspecialchars($course['course_id']); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($course['course_code']); ?> - <?php echo htmlspecialchars($course['course_name']); ?></option>
                            <?php 
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </div>

                    <!-- Instructor Selection -->
                    <div>
                        <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">Instructor</label>
                        <select name="instructor_id" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                            <?php
                                require_once '../backend/model/instructorModel.php';
                                $instructorModel = new Instructor($pdo);
                                $instructors = $instructorModel->selectOption();
                                if($instructors !== 1):
                                    foreach($instructors as $instructor):
                                        $selected = ($instructor['instructor_id'] == $class_session['instructor_id']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo htmlspecialchars($instructor['instructor_id']); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($instructor['instructor_name']); ?></option>
                            <?php 
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </div>

                    <!-- Semester -->
                    <div>
                        <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select name="semester_id" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                            <?php
                                require_once '../backend/model/semesterModel.php';
                                $semesterModel = new Semester($pdo);
                                $semesters = $semesterModel->selectOption();
                                if($semesters !== 1):
                                    foreach($semesters as $semester):
                                        $selected = ($semester['semester_id'] == ($class_session['semester_id'] ?? '')) ? 'selected' : '';
                            ?>
                                <option value="<?php echo htmlspecialchars($semester['semester_id']); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($semester['semester_code']); ?></option>
                            <?php 
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                        <input type="number" name="capacity" value="<?php echo htmlspecialchars($class_session['capacity']); ?>" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                    </div>

                    <!-- Schedule Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Schedule</label>
                        <button type="button" id="add_schedule" class="inline-flex items-center px-4 py-2 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200 mb-4">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Schedule
                        </button>
                        
                        <div id="schedule_container" class="space-y-4">
                            <?php if (empty($schedules)): ?>
                                <div class="schedule_item bg-gray-50 rounded-lg p-6 border border-gray-200">
                                    <input type="hidden" name="schedule_id[]" value="">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-sm font-semibold text-gray-700">Schedule 1</h4>
                                        <button type="button" class="remove_schedule inline-flex items-center px-3 py-1 bg-red-500 text-white rounded text-xs font-medium hover:bg-red-600 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remove
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Day Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Day</label>
                                            <select name="day[]" required class="block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                                <option value="">Select Day</option>
                                                <?php foreach ($all_days as $day): ?>
                                                    <option value="<?php echo htmlspecialchars($day); ?>"><?php echo htmlspecialchars($day); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Start Time -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                            <input type="time" name="start_time[]" required class="block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                        </div>

                                        <!-- End Time -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                            <input type="time" name="end_time[]" required class="block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                        </div>

                                        <!-- Location -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                            <input type="text" name="location[]" required class="block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($schedules as $index => $row): ?>
                                    <div class="schedule_item bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <input type="hidden" name="schedule_id[]" value="<?php echo htmlspecialchars($row['schedule_id'] ?? ''); ?>">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="text-sm font-semibold text-gray-700">Schedule <?php echo $index + 1; ?></h4>
                                            <button type="button" class="remove_schedule inline-flex items-center px-3 py-1 bg-red-500 text-white rounded text-xs font-medium hover:bg-red-600 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Remove
                                            </button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Day Selection -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Day</label>
                                                <select name="day[]" required class="block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                                    <option value="">Select Day</option>
                                                <?php foreach ($all_days as $day): ?>
                                                        <option value="<?php echo htmlspecialchars($day); ?>" <?php if ($row['day'] === $day) echo 'selected'; ?>>
                                                            <?php echo htmlspecialchars($day); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Start Time -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                                <input type="time" name="start_time[]" value="<?php echo htmlspecialchars($row['start_time']); ?>" required class="block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                            </div>

                                            <!-- End Time -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                                <input type="time" name="end_time[]" value="<?php echo htmlspecialchars($row['end_time']); ?>" required class="block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                            </div>

                                            <!-- Location -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                                <input type="text" name="location[]" value="<?php echo htmlspecialchars($row['location']); ?>" required class="block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <div class="flex space-x-4">
                            <button type="submit" name="update_class_session" value="Update Class Session" class="inline-flex items-center px-6 py-3 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Class Session
                            </button>
                            <a href="class_session.php" class="inline-flex items-center px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateRemoveButton() {
        const totalClone = document.querySelectorAll('.schedule_item').length;
        document.querySelectorAll('.remove_schedule').forEach(button => {
            button.disabled = totalClone <= 1;
            if (button.disabled) {
                button.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                button.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }

    function convertSchedulesToJSON() {
        const schedules = [];
        const scheduleItems = document.querySelectorAll('.schedule_item');
        
        scheduleItems.forEach(item => {
            const day = item.querySelector('select[name="day[]"]').value;
            const startTime = item.querySelector('input[name="start_time[]"]').value;
            const endTime = item.querySelector('input[name="end_time[]"]').value;
            const location = item.querySelector('input[name="location[]"]').value;
            
            if (day && startTime && endTime && location) {
                schedules.push({
                    day: day,
                    start_time: startTime,
                    end_time: endTime,
                    location: location
                });
            }
        });
        
        return JSON.stringify(schedules);
    }

    function prepareForm() {
        const schedulesJSON = convertSchedulesToJSON();
        document.getElementById('schedules_json').value = schedulesJSON;
        
        // Validate that at least one schedule is provided
        const schedules = JSON.parse(schedulesJSON);
        if (schedules.length === 0) {
            alert('Please add at least one schedule.');
            return false;
        }
        
        return true;
    }

    document.getElementById('add_schedule').addEventListener('click', function(e) {
        e.preventDefault();
        const schedule_container = document.getElementById('schedule_container');
        const schedule_item = document.querySelector('.schedule_item');
        const clone = schedule_item.cloneNode(true);

        // Clear all inputs in the clone
        clone.querySelectorAll('input, select').forEach(input => {
            input.value = '';
        });

        // Update the schedule number
        const scheduleNumber = document.querySelectorAll('.schedule_item').length + 1;
        clone.querySelector('h4').textContent = `Schedule ${scheduleNumber}`;

        // Add event listener to the new remove button
        clone.querySelector('.remove_schedule').addEventListener('click', function() {
            this.parentElement.parentElement.remove();
            updateRemoveButton();
            // Update schedule numbers
            document.querySelectorAll('.schedule_item').forEach((item, index) => {
                item.querySelector('h4').textContent = `Schedule ${index + 1}`;
            });
        });

        schedule_container.appendChild(clone);
        updateRemoveButton();
    });

    document.querySelectorAll('.remove_schedule').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.parentElement.remove();
            updateRemoveButton();
            // Update schedule numbers
            document.querySelectorAll('.schedule_item').forEach((item, index) => {
                item.querySelector('h4').textContent = `Schedule ${index + 1}`;
            });
        });
    });

    updateRemoveButton();
</script>

<?php
    require_once '../footer.php';
?>
