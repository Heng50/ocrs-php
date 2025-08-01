<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';
?>
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <a href="course.php" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Courses
                    </a>
                </div>
                <div class="mt-4">
                    <h1 class="text-3xl font-bold text-black mb-2">Add New Course</h1>
                    <p class="text-gray-600">Create a new course offering for the system.</p>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
                <form action="../backend/course_controller.php" method="post" class="space-y-6">
                    <input type="hidden" name="action" value="addCourse">
                    <div>
                        <label for="course_code" class="block text-sm font-medium text-gray-700 mb-2">Course Code</label>
                        <input type="text" 
                               id="course_code"
                               name="course_code" 
                               placeholder="Enter course code" 
                               class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                               required>
                    </div>
                    
                    <div>
                        <label for="course_name" class="block text-sm font-medium text-gray-700 mb-2">Course Name</label>
                        <input type="text" 
                               id="course_name"
                               name="course_name" 
                               placeholder="Enter course name" 
                               class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                               required>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description"
                                  name="description" 
                                  placeholder="Enter course description" 
                                  rows="4"
                                  class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                                  required></textarea>
                    </div>
                    
                    <div>
                        <label for="credits" class="block text-sm font-medium text-gray-700 mb-2">Credit Hours</label>
                        <input type="number" 
                               id="credits"
                               name="credits" 
                               placeholder="Enter credit hours" 
                               min="2" 
                               max="10" 
                               class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                               required>
                    </div>
                    
                    <div>
                        <label for="prog_id" class="block text-sm font-medium text-gray-700 mb-2">Programme</label>
                        <select id="prog_id"
                                name="prog_id" 
                                class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                                required>
                            <option value="">Select Programme</option>
                            <?php
                                require_once '../backend/model/programmeModel.php';
                                $programmeModel = new Programme($pdo);
                                $programmes = $programmeModel->selectOption();
                                if($programmes !== 1):
                                    foreach($programmes as $programme):
                            ?>
                            <option value="<?php echo htmlspecialchars($programme['prog_id']); ?>"><?php echo htmlspecialchars($programme['prog_name']); ?></option>
                            <?php 
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category_id"
                                name="category_id" 
                                class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                                required>
                            <option value="">Select Category</option>
                            <?php
                                require_once '../backend/model/categoryModel.php';
                                $categoryModel = new Category($pdo);
                                $categories = $categoryModel->selectOption();
                                if($categories !== 1):
                                    foreach($categories as $category):
                            ?>
                            <option value="<?php echo htmlspecialchars($category['category_id']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                            <?php 
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="is_active"
                                name="is_active" 
                                class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                                required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex space-x-4 pt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Course
                        </button>
                        <a href="course.php" class="inline-flex items-center px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
<?php require_once 'components/admin_nav_end.php'; ?>