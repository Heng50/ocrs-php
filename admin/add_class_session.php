<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';
?>
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
                    <h1 class="text-3xl font-bold text-black mb-2">Add Class Session</h1>
                    <p class="text-gray-600">Create a new class session with schedules.</p>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-4xl">
                <form action="../backend/class_session_controller.php" method="post" class="space-y-6" onsubmit="return prepareForm()">
                    <input type="hidden" name="action" value="addClassSession">
                    <input type="hidden" name="schedules" id="schedules_json">
                    <!-- Course Selection -->
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                        <select name="course_id" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                            <option value="">Select Course</option>
                            <?php
                                require_once '../backend/model/courseModel.php';
                                $courseModel = new Course($pdo);
                                $courses = $courseModel->selectOption();
                                if($courses !== 1):
                                    foreach($courses as $course):
                            ?>
                                <option value="<?php echo htmlspecialchars($course['course_id']); ?>"><?php echo htmlspecialchars($course['course_code']); ?> - <?php echo htmlspecialchars($course['course_name']); ?></option>
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
                            <option value="">Select Instructor</option>
                            <?php
                                require_once '../backend/model/instructorModel.php';
                                $instructorModel = new Instructor($pdo);
                                $instructors = $instructorModel->selectOption();
                                if($instructors !== 1):
                                    foreach($instructors as $instructor):
                            ?>
                                <option value="<?php echo htmlspecialchars($instructor['instructor_id']); ?>"><?php echo htmlspecialchars($instructor['instructor_name']); ?></option>
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
                            <option value="">Select Semester</option>
                            <?php
                                require_once '../backend/model/semesterModel.php';
                                $semesterModel = new Semester($pdo);
                                $semesters = $semesterModel->selectOption();
                                if($semesters !== 1):
                                    foreach($semesters as $semester):
                            ?>
                                <option value="<?php echo htmlspecialchars($semester['semester_id']); ?>"><?php echo htmlspecialchars($semester['semester_code']); ?></option>
                            <?php 
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                        <input type="number" name="capacity" placeholder="Enter capacity" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                    </div>

                    <!-- Schedule Section -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-sm font-medium text-gray-700">Schedule</label>
                            <button type="button" id="add_schedule" class="inline-flex items-center px-4 py-2 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Schedule
                            </button>
                        </div>
                        
                        <div id="schedule_container" class="space-y-4">
                            <div class="schedule_item bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-sm font-medium text-gray-900">Schedule 1</h4>
                                    <button type="button" class="remove_schedule inline-flex items-center px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors duration-200">
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
                                        <select name="day[]" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                            <option value="">Select Day</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>

                                    <!-- Start Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                        <input type="time" name="start_time[]" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                    </div>

                                    <!-- End Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                        <input type="time" name="end_time[]" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                    </div>

                                    <!-- Location -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                        <input type="text" name="location[]" placeholder="Enter location" required class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button type="submit" name="add_class_session" value="Add Class Session" class="w-full inline-flex items-center justify-center px-6 py-3 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Class Session
                        </button>
                    </div>
                </form>
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

<?php require_once 'components/admin_nav_end.php'; ?>
