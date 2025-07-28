<?php
    session_start();
    require_once '../header.php';
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
                    <a href="course.php" class="flex items-center px-4 py-3 text-black bg-[#FFD700] rounded-lg group">
                        <svg class="w-5 h-5 mr-3 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Browse Courses
                    </a>
                    <a href="schedule.php" class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-[#FFD700] hover:text-black transition-colors duration-200 group">
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
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black mb-2">Browse Available Courses</h1>
                <p class="text-gray-600">Search and enroll in courses for the upcoming semester.</p>
            </div>

            <!-- Search Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="max-w-4xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <!-- Search Input -->
                        <div class="lg:col-span-2">
                            <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2">Search Courses</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="searchInput" 
                                    placeholder="Search by course code or name..." 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200"
                                >
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="categoryFilter" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select id="categoryFilter" class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                <option value="">All Categories</option>
                            </select>
                        </div>

                        <!-- Semester Filter -->
                        <div>
                            <label for="semesterFilter" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                            <select id="semesterFilter" class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                <option value="">All Semesters</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Instructor Filter -->
                        <div>
                            <label for="instructorFilter" class="block text-sm font-medium text-gray-700 mb-2">Instructor</label>
                            <select id="instructorFilter" class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700] transition-colors duration-200">
                                <option value="">All Instructors</option>
                            </select>
                        </div>

                        <!-- Clear Filters Button -->
                        <div class="flex items-end">
                            <button id="clearFilters" class="w-full px-4 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors duration-200">
                                Clear Filters
                            </button>
                        </div>
                    </div>

                    <p class="mt-2 text-sm text-gray-500">Use filters to narrow down your search results</p>
                </div>
            </div>

            <!-- Search Results -->
            <div id="searchResults" class="bg-white rounded-xl shadow-sm border border-gray-200 hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-black">Search Results</h2>
                    <p class="text-gray-600 mt-1">Found courses matching your search</p>
                </div>
                
                <div id="resultsContent" class="divide-y divide-gray-200">
                    <!-- Results will be populated here -->
                </div>
            </div>

            <!-- Initial State -->
            <div id="initialState" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Search for Courses</h3>
                    <p class="text-gray-500 mb-6">Enter a course code or name to find available courses for enrollment.</p>
                    <div class="text-sm text-gray-400">
                        <p>Try searching for:</p>
                        <ul class="mt-2 space-y-1">
                            <li>• Course codes (e.g., CS101, MATH201)</li>
                            <li>• Course names (e.g., Programming, Calculus)</li>
                            <li>• Subject areas (e.g., Computer Science, Mathematics)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const resultsContent = document.getElementById('resultsContent');
    const initialState = document.getElementById('initialState');
    const categoryFilter = document.getElementById('categoryFilter');
    const semesterFilter = document.getElementById('semesterFilter');
    const instructorFilter = document.getElementById('instructorFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    let lastQuery = '';
    let debounceTimeout;
    let filterOptions = {};

    // Load filter options on page load
    loadFilterOptions();

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        if (query === lastQuery) return;
        lastQuery = query;
        clearTimeout(debounceTimeout);
        
        if (query.length === 0) {
            searchResults.classList.add('hidden');
            initialState.classList.remove('hidden');
            return;
        }
        
        debounceTimeout = setTimeout(() => {
            performSearch();
        }, 300);
    });

    // Filter event listeners
    categoryFilter.addEventListener('change', performSearch);
    semesterFilter.addEventListener('change', performSearch);
    instructorFilter.addEventListener('change', performSearch);

    // Clear filters button
    clearFiltersBtn.addEventListener('click', () => {
        searchInput.value = '';
        categoryFilter.value = '';
        semesterFilter.value = '';
        instructorFilter.value = '';
        lastQuery = '';
        searchResults.classList.add('hidden');
        initialState.classList.remove('hidden');
    });

    function loadFilterOptions() {
        fetch('../backend/class_session_controller.php?action=getFilterOptions')
            .then(response => response.json())
            .then(data => {
                filterOptions = data;
                
                // Populate category filter
                data.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.category_id;
                    option.textContent = category.category_name;
                    categoryFilter.appendChild(option);
                });
                
                // Populate semester filter
                data.semesters.forEach(semester => {
                    const option = document.createElement('option');
                    option.value = semester.semester_id;
                    option.textContent = semester.semester_code;
                    semesterFilter.appendChild(option);
                });
                
                // Populate instructor filter
                data.instructors.forEach(instructor => {
                    const option = document.createElement('option');
                    option.value = instructor.instructor_id;
                    option.textContent = instructor.instructor_name;
                    instructorFilter.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading filter options:', error);
            });
    }

    function performSearch() {
        const query = searchInput.value.trim();
        const categoryId = categoryFilter.value;
        const semesterId = semesterFilter.value;
        const instructorId = instructorFilter.value;
        
        if (query.length === 0 && !categoryId && !semesterId && !instructorId) {
            searchResults.classList.add('hidden');
            initialState.classList.remove('hidden');
            return;
        }
        
        // Build search parameters
        const params = new URLSearchParams();
        if (query) params.append('q', query);
        if (categoryId) params.append('category_id', categoryId);
        if (semesterId) params.append('semester_id', semesterId);
        if (instructorId) params.append('instructor_id', instructorId);
        
        fetch('../backend/class_session_controller.php?' + params.toString())
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Search results:', data);
                initialState.classList.add('hidden');
                searchResults.classList.remove('hidden');
                
                if (Array.isArray(data) && data.length > 0) {
                    resultsContent.innerHTML = '';
                    data.forEach(item => {
                        const courseCard = createCourseCard(item);
                        resultsContent.appendChild(courseCard);
                    });
                } else {
                    resultsContent.innerHTML = `
                        <div class="p-8 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No courses found</h3>
                            <p class="text-gray-500">Try adjusting your search terms or browse all available courses.</p>
                        </div>
                    `;
                }
            })
            .catch((error) => {
                console.error('Search error:', error);
                initialState.classList.add('hidden');
                searchResults.classList.remove('hidden');
                resultsContent.innerHTML = `
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Error searching</h3>
                        <p class="text-gray-500">Please try again later.</p>
                    </div>
                `;
            });
    }

    function createCourseCard(item) {
        const div = document.createElement('div');
        div.className = 'p-6 hover:bg-gray-50 transition-colors duration-200';
        
        const isPending = item.status === 'pending';
        const isEnrolled = item.status === 'enrolled';
        
        let statusBadge = '';
        let actionButton = '';
        
        if (isPending) {
            statusBadge = `
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending
                </span>
            `;
        } else if (isEnrolled) {
            statusBadge = `
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Enrolled
                </span>
            `;
        } else {
            actionButton = `
                <button 
                    onclick="enrollInCourse(${item.session_id}, this)" 
                    class="inline-flex items-center px-4 py-2 bg-[#FFD700] text-black rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Enroll
                </button>
            `;
        }
        
        div.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold text-black">${item.course_code}</h3>
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">${item.semester_code}</span>
                        ${statusBadge}
                    </div>
                    <h4 class="text-lg font-medium text-black mb-3">${item.course_name}</h4>
                    <div class="flex items-center gap-6 text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>${item.instructor_name}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Capacity: ${item.capacity}</span>
                        </div>
                    </div>
                </div>
                <div class="ml-6">
                    ${actionButton}
                </div>
            </div>
        `;
        
        return div;
    }

    function enrollInCourse(session_id, button) {
        button.disabled = true;
        button.innerHTML = `
            <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Enrolling...
        `;
        
        console.log(session_id);
        console.log(<?php echo $_SESSION['user']['id']; ?>);
        fetch('../backend/enrollment_controller.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                session_id: session_id,
                student_id: <?php echo $_SESSION['user']['id']; ?>,
            })
        })
        .then(response => response.json())
        .then(result => {
            if(result.status === 'pending') {
                button.innerHTML = `
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pending
                    </span>
                `;
                button.disabled = true;
            } else if(result.status === 'enrolled') {
                button.innerHTML = `
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Enrolled
                    </span>
                `;
                button.disabled = true;
            } else {
                alert('Enrollment failed. Please try again.');
                button.disabled = false;
                button.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Enroll
                `;
            }
        })
        .catch(() => {
            alert('Error enrolling. Please try again.');
            button.disabled = false;
            button.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Enroll
            `;
        });
    }
</script>

<?php
    require_once '../footer.php';
?>