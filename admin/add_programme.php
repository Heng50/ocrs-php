<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';
?>
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <a href="programme.php" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Programmes
                    </a>
                </div>
                <div class="mt-4">
                    <h1 class="text-3xl font-bold text-black mb-2">Add New Programme</h1>
                    <p class="text-gray-600">Create a new academic programme for the system.</p>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 max-w-2xl">
                <form action="../backend/programme_controller.php" method="post" class="space-y-6">
                    <input type="hidden" name="action" value="addProgramme">
                    <div>
                        <label for="prog_name" class="block text-sm font-medium text-gray-700 mb-2">Programme Name</label>
                        <input type="text" 
                               id="prog_name"
                               name="prog_name" 
                               placeholder="Enter programme name" 
                               class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                               required>
                    </div>
                    
                    <div>
                        <label for="prog_code" class="block text-sm font-medium text-gray-700 mb-2">Programme Code</label>
                        <input type="text" 
                               id="prog_code"
                               name="prog_code" 
                               placeholder="Enter programme code" 
                               class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                               required>
                    </div>
                    
                    <div>
                        <label for="prog_desc" class="block text-sm font-medium text-gray-700 mb-2">Programme Description</label>
                        <textarea id="prog_desc"
                                  name="prog_desc" 
                                  placeholder="Enter programme description" 
                                  rows="4"
                                  class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                                  required></textarea>
                    </div>
                    
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Programme Duration (years)</label>
                        <input type="number" 
                               id="duration"
                               name="duration" 
                               placeholder="Enter duration in years" 
                               min="1" 
                               max="6" 
                               step="0.05" 
                               class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                               required>
                    </div>
                    
                    <div>
                        <label for="total_credit" class="block text-sm font-medium text-gray-700 mb-2">Total Credits</label>
                        <input type="number" 
                               id="total_credit"
                               name="total_credit" 
                               placeholder="Enter total credits required" 
                               min="1" 
                               max="200" 
                               step="1" 
                               class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                    </div>
                    
                    <div class="flex space-x-4 pt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Programme
                        </button>
                        <a href="programme.php" class="inline-flex items-center px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
<?php require_once 'components/admin_nav_end.php'; ?>