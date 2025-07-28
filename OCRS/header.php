<?php
    require_once 'db/conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Course Registration System</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <script>
        function loadContent(url) {
            const contentPanel = document.getElementById('content-panel');
            contentPanel.innerHTML = '';
            contentPanel.innerHTML = '<p class="text-gray-600">Loading content...</p>';
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    contentPanel.innerHTML = data;

                    if(url.includes('add_class_session.php') || url.includes('update_class_session.php')) {
                        initClassSessionScripts();
                    }else if(url.includes('course.php')) {
                        initSearchScripts();
                    }
                })
                .catch(error => {
                    contentPanel.innerHTML = '<p class="text-red-500">Error loading content</p>';
                });
        }

        function initClassSessionScripts() {
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

            const addBtn = document.getElementById('add_schedule');
            if (addBtn) {
                addBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const schedule_container = document.getElementById('schedule_container');
                    const schedule_item = document.querySelector('.schedule_item');
                    const clone = schedule_item.cloneNode(true);

                    clone.querySelectorAll('input, select').forEach(input => {
                        input.value = '';
                    });

                    const scheduleNumber = document.querySelectorAll('.schedule_item').length + 1;
                    clone.querySelector('h4').textContent = `Schedule ${scheduleNumber}`;

                    // Add event listener to new remove button
                    clone.querySelector('.remove_schedule').addEventListener('click', function() {
                        this.parentElement.parentElement.remove();
                        updateRemoveButton();
                        document.querySelectorAll('.schedule_item').forEach((item, index) => {
                            item.querySelector('h4').textContent = `Schedule ${index + 1}`;
                        });
                    });

                    schedule_container.appendChild(clone);
                    updateRemoveButton();
                });
            }

            document.querySelectorAll('.remove_schedule').forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.parentElement.remove();
                    updateRemoveButton();
                    document.querySelectorAll('.schedule_item').forEach((item, index) => {
                        item.querySelector('h4').textContent = `Schedule ${index + 1}`;
                    });
                });
            });

            updateRemoveButton();
        }

        function initSearchScripts() {
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let lastQuery = '';
        let debounceTimeout;

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            if (query === lastQuery) return;
            lastQuery = query;
            clearTimeout(debounceTimeout);
            if (query.length === 0) {
                searchResults.classList.add('hidden');
                searchResults.innerHTML = '';
                return;
            }
            debounceTimeout = setTimeout(() => {
                fetch('../backend/class_session_controller.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (Array.isArray(data) && data.length > 0) {
                            searchResults.innerHTML = '';
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'p-3 border-b border-gray-200 relative hover:bg-gray-50';

                                // Enroll button
                                const enrollBtn = document.createElement('button');
                                let isPending = item.status === 'pending';
                                let isEnrolled = item.status === 'enrolled';
                                
                                if (isPending) {
                                    enrollBtn.textContent = 'Pending';
                                    enrollBtn.disabled = true;
                                    enrollBtn.className = 'float-right bg-gray-500 text-white border-none px-3 py-1 rounded text-sm cursor-not-allowed';
                                } else if (isEnrolled) {
                                    enrollBtn.textContent = 'Enrolled';
                                    enrollBtn.disabled = true;
                                    enrollBtn.className = 'float-right bg-green-500 text-white border-none px-3 py-1 rounded text-sm cursor-not-allowed';
                                } else {
                                    enrollBtn.textContent = 'Enroll';
                                    enrollBtn.disabled = false;
                                    enrollBtn.className = 'float-right bg-[#FFD700] text-black border-none px-3 py-1 rounded text-sm hover:bg-[#e6c200] transition cursor-pointer';
                                    enrollBtn.addEventListener('click', function() {
                                        fetch('../backend/enrollment_controller.php', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                session_id: item.id,
                                                student_id: <?php echo $_SESSION['user']['id']; ?>,
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(result => {
                                            if(result.status === 'pending') {
                                                enrollBtn.textContent = 'Pending';
                                                enrollBtn.disabled = true;
                                                enrollBtn.className = 'float-right bg-gray-500 text-white border-none px-3 py-1 rounded text-sm cursor-not-allowed';
                                            } else if(result.status === 'enrolled') {
                                                enrollBtn.textContent = 'Enrolled';
                                                enrollBtn.disabled = true;
                                                enrollBtn.className = 'float-right bg-green-500 text-white border-none px-3 py-1 rounded text-sm cursor-not-allowed';
                                            } else {
                                                alert('Enrollment failed.');
                                            }

                                            console.log("hello");
                                        })
                                        .catch(() => {
                                            alert('Error enrolling.');
                                        });
                                    });
                                }
                                div.appendChild(enrollBtn);

                                // Course information
                                const strong = document.createElement('strong');
                                strong.textContent = item.course_code;
                                strong.className = 'text-black';
                                div.appendChild(strong);

                                div.appendChild(document.createTextNode(`: ${item.course_name}`));
                                div.appendChild(document.createElement('br'));

                                const emSemester = document.createElement('em');
                                emSemester.textContent = 'Semester:';
                                emSemester.className = 'text-black';
                                div.appendChild(emSemester);

                                div.appendChild(document.createTextNode(` ${item.semester}   `));

                                const emInstructor = document.createElement('em');
                                emInstructor.textContent = 'Instructor:';
                                emInstructor.className = 'text-black';
                                div.appendChild(emInstructor);

                                div.appendChild(document.createTextNode(` ${item.username}   `));

                                const emCapacity = document.createElement('em');
                                emCapacity.textContent = 'Capacity:';
                                emCapacity.className = 'text-black';
                                div.appendChild(emCapacity);

                                div.appendChild(document.createTextNode(` ${item.capacity}`));

                                searchResults.appendChild(div);
                            });
                            searchResults.classList.remove('hidden');
                        } else {
                            searchResults.innerHTML = '<div class="p-3 text-gray-500">No results found.</div>';
                            searchResults.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        searchResults.innerHTML = '<div class="p-3 text-red-500">Error searching.</div>';
                        searchResults.classList.remove('hidden');
                    });
                }, 300);
            });
        }
    </script>
    