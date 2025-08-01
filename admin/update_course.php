<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';

    $id = $_GET['id'];
    $sql = "SELECT * FROM courses JOIN programmes ON courses.prog_id = programmes.prog_id WHERE courses.course_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
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
                    <h1 class="text-3xl font-bold text-black mb-2">Update Course</h1>
                    <p class="text-gray-600">Modify course information and details.</p>
                </div>
            </div>

            <!-- Alert Messages -->
            <?php if(isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
                <form action="../backend/course_controller.php" method="post" id="updateForm" class="space-y-6">
                    <input type="hidden" name="action" value="editCourse">
                    <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($id); ?>">
                    
                    <div>
                        <label for="course_code" class="block text-sm font-medium text-gray-700 mb-2">Course Code</label>
                        <input type="text" 
                               id="course_code"
                               name="course_code" 
                               value="<?php echo htmlspecialchars($course['course_code']); ?>"
                               class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                               required>
                    </div>
                    
                    <div>
                        <label for="course_name" class="block text-sm font-medium text-gray-700 mb-2">Course Name</label>
                        <input type="text" 
                               id="course_name"
                               name="course_name" 
                               value="<?php echo htmlspecialchars($course['course_name']); ?>"
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
                                  required><?php echo htmlspecialchars($course['description']); ?></textarea>
                    </div>
                    
                    <div>
                        <label for="credits" class="block text-sm font-medium text-gray-700 mb-2">Credit Hours</label>
                        <input type="number" 
                               id="credits"
                               name="credits" 
                               value="<?php echo htmlspecialchars($course['credits']); ?>"
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
                            <?php
                                require_once '../backend/model/programmeModel.php';
                                $programmeModel = new Programme($pdo);
                                $programmes = $programmeModel->selectOption();
                                if($programmes !== 1):
                                    foreach($programmes as $programme):
                                        $selected = ($programme['prog_id'] == $course['prog_id']) ? 'selected' : '';
                            ?>
                            <option value="<?php echo htmlspecialchars($programme['prog_id']); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($programme['prog_name']); ?></option>
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
                                        $selected = ($category['category_id'] == ($course['category_id'] ?? '')) ? 'selected' : '';
                            ?>
                            <option value="<?php echo htmlspecialchars($category['category_id']); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($category['category_name']); ?></option>
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
                            <option value="1" <?php echo ($course['is_active'] ?? 1) == 1 ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo ($course['is_active'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex space-x-4 pt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Course
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
