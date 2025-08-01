<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';
    require_once '../backend/model/semesterModel.php';
    require_once '../backend/model/programmeModel.php';
    require_once '../backend/model/courseModel.php';

    $semesterModel = new Semester($pdo);
    $programmeModel = new Programme($pdo);
    $courseModel = new Course($pdo);

    $semesterOptions = $semesterModel->selectOption();
    $programmeOptions = $programmeModel->selectOption();
    $courseOptions = $courseModel->selectOption();
?>
        <div class="flex-1 p-8">
            <!-- Report Type Selector -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black mb-2">Reports & Analytics</h1>
                <div class="flex space-x-4 mt-4">
                    <button type="button" class="report-tab px-6 py-2 rounded-lg font-medium transition-colors duration-200" data-report="courseEnrollment">Course Enrollment</button>
                    <button type="button" class="report-tab px-6 py-2 rounded-lg font-medium transition-colors duration-200" data-report="studentPerformance">Student Performance</button>
                    <button type="button" class="report-tab px-6 py-2 rounded-lg font-medium transition-colors duration-200" data-report="overallAcademicProgress">Overall Academic Progress</button>
                </div>
            </div>

            <!-- Dynamic Filters -->
            <div id="reportFilters" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8"></div>

            <!-- Generate Button -->
            <div class="mb-8">
                <button id="generateReport" class="bg-[#FFD700] text-black px-6 py-2 rounded-lg font-medium hover:bg-[#e6c200] transition-colors duration-200">
                    Generate Report
                </button>
                            </div>
                            
            <!-- PDF Preview -->
            <div id="pdfPreview" class="w-full" style="min-height: 600px;">
                <!-- iframe will be injected here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
<!-- jsPDF & autoTable CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>
<script>
let activeReport = 'courseEnrollment';
let pdfBlobUrl = null;
// Pass PHP options to JS
const semesterOptions = <?php echo json_encode($semesterOptions); ?>;
const programmeOptions = <?php echo json_encode($programmeOptions); ?>;
const courseOptions = <?php echo json_encode($courseOptions); ?>;

function renderFilters() {
    let html = '';
    if (activeReport === 'courseEnrollment') {
        html = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg" id="filterSemester">
                        <option value="">All Semesters</option>
                        ${semesterOptions.map(opt => `<option value="${opt.semester_id}">${opt.semester_code}</option>`).join('')}
                    </select>
                </div>
                            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Programme</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg" id="filterProgramme">
                        <option value="">All Programmes</option>
                        ${programmeOptions.map(opt => `<option value="${opt.prog_id}">${opt.prog_name} (${opt.prog_code})</option>`).join('')}
                    </select>
                                </div>
                            </div>
        `;
    } else if (activeReport === 'studentPerformance') {
        html = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Student Search</label>
                    <input type="text" class="block w-full px-3 py-2 border border-gray-300 rounded-lg" id="filterStudent" placeholder="Enter student name or ID" autocomplete="off">
                    <div id="studentSearchResults" class="bg-white border border-gray-200 rounded shadow absolute z-10 w-80 hidden"></div>
                </div>
                            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg" id="filterSemester" disabled>
                        <option value="">All Semesters</option>
                        ${semesterOptions.map(opt => `<option value="${opt.semester_id}">${opt.semester_code}</option>`).join('')}
                    </select>
                                </div>
                            </div>
        `;
    } else if (activeReport === 'overallAcademicProgress') {
        html = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Programme</label>
                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg" id="filterProgramme">
                        <option value="">All Programmes</option>
                        ${programmeOptions.map(opt => `<option value="${opt.prog_id}">${opt.prog_name} (${opt.prog_code})</option>`).join('')}
                    </select>
                </div>
            </div>
        `;
    }
    document.getElementById('reportFilters').innerHTML = html;
    if (activeReport === 'studentPerformance') setupStudentSearch();
}

function setupStudentSearch() {
    const input = document.getElementById('filterStudent');
    const resultsBox = document.getElementById('studentSearchResults');
    const semesterDropdown = document.getElementById('filterSemester');
    let timeout = null;
    input.dataset.userId = '';
    semesterDropdown.disabled = true;
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        input.dataset.userId = '';
        semesterDropdown.disabled = true;
        const query = this.value.trim();
        if (!query) {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
            return;
        }
        timeout = setTimeout(() => {
            fetch('../backend/student_controller.php?action=search', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'query=' + encodeURIComponent(query)
            })
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    resultsBox.innerHTML = data.map(s => `<div class='px-4 py-2 hover:bg-[#FFD700] cursor-pointer' data-id='${s.user_id}' data-name='${s.username}'>${s.username} (${s.user_id})</div>`).join('');
                    resultsBox.classList.remove('hidden');
                } else {
                    resultsBox.innerHTML = '<div class="px-4 py-2 text-gray-500">No results</div>';
                    resultsBox.classList.remove('hidden');
                }
            });
        }, 250);
    });
    resultsBox.addEventListener('mousedown', function(e) {
        if (e.target && e.target.dataset && e.target.dataset.id) {
            input.value = e.target.dataset.name + ' (' + e.target.dataset.id + ')';
            input.dataset.userId = e.target.dataset.id;
            resultsBox.classList.add('hidden');
            semesterDropdown.disabled = false;
        }
    });
    document.addEventListener('click', function(e) {
        if (!resultsBox.contains(e.target) && e.target !== input) {
            resultsBox.classList.add('hidden');
        }
    });
}

// --- Tab Switching ---
document.querySelectorAll('.report-tab').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.report-tab').forEach(b => b.classList.remove('bg-[#FFD700]', 'text-black'));
        this.classList.add('bg-[#FFD700]', 'text-black');
        activeReport = this.getAttribute('data-report');
        renderFilters();
        document.getElementById('pdfPreview').innerHTML = '';
        if (pdfBlobUrl) {
            URL.revokeObjectURL(pdfBlobUrl);
            pdfBlobUrl = null;
        }
    });
});

// --- Generate Report Button ---
document.getElementById('generateReport').addEventListener('click', async function() {
    // 1. Gather filter values
    let filters = {};
    if (activeReport === 'courseEnrollment') {
        filters.semester_id = document.getElementById('filterSemester').value;
        filters.prog_id = document.getElementById('filterProgramme').value;
    } else if (activeReport === 'studentPerformance') {
        const input = document.getElementById('filterStudent');
        const semesterDropdown = document.getElementById('filterSemester');
        if (!input.dataset.userId) {
            alert('Please select a student from the search results.');
            input.focus();
            return;
        }
        if (semesterDropdown.disabled) {
            alert('Please select a student first.');
            return;
        }
        filters.user_id = input.dataset.userId;
        filters.semester_id = semesterDropdown.value;
    } else if (activeReport === 'overallAcademicProgress') {
        filters.prog_id = document.getElementById('filterProgramme').value;
    }

    // 2. Fetch actual data from report controller
    let data;
    try {
        const url = `../backend/report_controller.php?action=${activeReport}`;
        console.log('=== REPORT DEBUG INFO ===');
        console.log('Report Type:', activeReport);
        console.log('URL:', url);
        console.log('Filters being sent:', filters);
        const formBody = Object.entries(filters)
            .map(([key, val]) => encodeURIComponent(key) + '=' + encodeURIComponent(val))
            .join('&');
        console.log('Form body:', formBody);
        console.log('========================');
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: formBody
        });

        
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            const errorText = await response.json();
            console.error('Response error text:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        
        console.log('Response data:', formBody);
        data = await response.json();



        // Only show loading if there is data to process
        document.getElementById('pdfPreview').innerHTML = `<div class="text-center text-gray-500 py-12">Generating PDF...</div>`;
        
        if (data.error) {
            throw new Error(data.error);
        }
    } catch (error) {
        console.error('Error fetching report data:', error);
        document.getElementById('pdfPreview').innerHTML = `<div class="text-center text-red-500 py-12">Failed to fetch report data: ${error.message}</div>`;
        return;
    }

    // 3. Generate PDF with jsPDF + autoTable
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    console.log('Data:', data);
    console.log("cgpa:",data.cgpa);

    // Header
    doc.setFontSize(16);
    doc.text(getReportTitle(), 14, 18);
    doc.setFontSize(10);
    doc.text(`Generated: ${new Date().toLocaleString()}`, 14, 25);
    doc.text(`Prepared by: <?php echo htmlspecialchars($_SESSION['user']['username']); ?>`, 14, 30);

    // Table and layout per report type
    if (activeReport === 'courseEnrollment') {
        // Subheaders
        doc.setFontSize(12);
        let semester = data.semester ? data.semester.semester_code : 'All Semesters';
        let programme = data.programme ? data.programme.prog_name : 'All Programmes';
        doc.text(`Semester: ${semester}    Programme: ${programme}`, 14, 38);
        
        // Table
        const rows = Array.isArray(data) ? data : (Array.isArray(data.data) ? data.data : []);
        if (!rows.length) {
            console.log(data);
            document.getElementById('pdfPreview').innerHTML = `<div class='text-center text-gray-500 py-12'>No data found for the selected filters.</div>`;
            return;
        }

        const tableData = rows
            .filter(row => row && typeof row === 'object')
            .map(row => [
                row.course_code,
                row.course_name,
                row.instructor_name,
                row.total_seats,
                row.enrolled_students,
                row.vacant_seats,
                Number(row.enrollment_percentage).toFixed(1) + '%'
            ]);
        console.log('Table data:', tableData);
        doc.autoTable({
            startY: 42,
            head: [['Course Code', 'Course Name', 'Instructor', 'Total Seats', 'Enrolled', 'Vacant', 'Enrollment %']],
            body: tableData,
            theme: 'grid'
        });

    } else if (activeReport === 'studentPerformance') {
        if (!data.data || !Array.isArray(data.data) || data.data.length === 0) {
            document.getElementById('pdfPreview').innerHTML = `<div class='text-center text-gray-500 py-12'>No data found for the selected filters.</div>`;
            return;
        }
        
        document.getElementById('pdfPreview').innerHTML = `<div class="text-center text-gray-500 py-12">Generating PDF...</div>`;
        
        // Subheaders
        doc.setFontSize(12);
        doc.text(`Student: ${data.cgpa.username}   ID: ${data.cgpa.user_id}`, 14, 38);
        
        // Group courses by semester
        const semesterGroups = {};
        data.data.forEach(course => {
            const semester = course.semester_code;
            console.log(semester);
            if (!semesterGroups[semester]) {
                semesterGroups[semester] = [];
            }
            semesterGroups[semester].push(course);
        });
        
        let y = 44;
        for (const [semester, courses] of Object.entries(semesterGroups)) {
            doc.setFontSize(11);
            doc.text(semester, 14, y);
            doc.autoTable({
                startY: y + 2,
                head: [['Course Code', 'Course Name', 'Credit Hours', 'Final Grade']],
                body: courses.map(c => [c.course_code, c.course_name, c.credits, c.final_grade]),
                theme: 'grid'
            });
            y = doc.lastAutoTable.finalY + 8;
        }

        doc.setFontSize(12);
        doc.text(`CGPA: ${data.cgpa.cgpa}`, 14, y+10);

    
    } else if (activeReport === 'overallAcademicProgress') {
        if (!data.data || !Array.isArray(data.data) || data.data.length === 0) {
            document.getElementById('pdfPreview').innerHTML = `<div class='text-center text-gray-500 py-12'>No data found for the selected filters.</div>`;
            return;
        }
        document.getElementById('pdfPreview').innerHTML = `<div class="text-center text-gray-500 py-12">Generating PDF...</div>`;
        let startY = 54;
        // KPI Summary
        const students = data.data;
        const graduationReady = students.filter(s => s.degree_progress_percentage >= 70).length;
        const avgCGPA = students.length ? (students.reduce((sum, s) => sum + (parseFloat(s.cgpa) || 0), 0) / students.length).toFixed(2) : '0.00';
        const avgCredits = students.length ? (students.reduce((sum, s) => sum + (parseFloat(s.total_credits_completed) || 0), 0) / students.length).toFixed(1) : '0.0';

        // Group students by program
        const grouped = {};
        students.forEach(d => {
            if (!grouped[d.prog_code]) grouped[d.prog_code] = [];
            grouped[d.prog_code].push(d);
        });

        // Debug to check prog_code value
        students.forEach(d => {
            console.log('prog_code:', d.prog_code); 
        });
        let rowNo = 1;
        Object.entries(grouped).forEach(([prog, students]) => {
            console.log(prog);
            console.log(students);

            doc.setFont('helvetica', 'bold');
            doc.text(students[0].prog_code, 14, startY-4);
            // Table for this program
            doc.autoTable({
                startY,
                head: [[
                    'No', 'Student ID', 'Name', 'Program', 'Credits Completed', 'CGPA', 'Degree Progress %', 'Academic Standing', 'Graduation Eligible'
                ]],
                body: students.map((s, idx) => [
                    rowNo++,
                    s.student_id,
                    s.student_name,
                    s.prog_code,
                    s.total_credits_completed,
                    s.cgpa,
                    Number(s.degree_progress_percentage).toFixed(1) + '%',
                    s.academic_standing,
                    s.degree_progress_percentage >= 70 ? 'Yes' : 'No',
                ]),
                theme: 'plain',
                headStyles: { fontStyle: 'bold', fillColor: [191, 161, 74], textColor: 20 },
                styles: { font: 'helvetica', fontSize: 10 },
                didDrawPage: (dataTable) => {
                    startY = dataTable.cursor.y + 16;
                },
                margin: { left: 14, right: 14 },
            });

        });
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.text('KPI Summary', 14, startY);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(9);
        doc.text(`Graduation Ready: `, 14, startY + 8);
        doc.text(`${graduationReady}`, 70, startY + 8);
        doc.text(`Average CGPA: `, 14, startY + 16);
        doc.text(`${avgCGPA}`, 70, startY + 16);
        doc.text(`Average Credits Completed: `, 14, startY + 24);
        doc.text(`${avgCredits}`, 70, startY + 24);
        startY += 32;
    }

    // Footer
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(8);
        doc.text(`Page ${i} of ${pageCount}`, doc.internal.pageSize.getWidth() - 30, doc.internal.pageSize.getHeight() - 10);
    }

    // 4. Show PDF in iframe
    const pdfBlob = doc.output('blob');
    if (pdfBlobUrl) URL.revokeObjectURL(pdfBlobUrl);
    pdfBlobUrl = URL.createObjectURL(pdfBlob);
    document.getElementById('pdfPreview').innerHTML = `<iframe src="${pdfBlobUrl}" width="100%" height="700px" style="border:1px solid #ccc"></iframe>`;
});

function getReportTitle() {
    if (activeReport === 'courseEnrollment') return 'Course Enrollment Report';
    if (activeReport === 'studentPerformance') return 'Student Performance Report';
    if (activeReport === 'overallAcademicProgress') return 'Overall Academic Progress Report';
    return '';
}

// --- Initial Render ---
renderFilters();
document.querySelector('.report-tab[data-report="courseEnrollment"]').classList.add('bg-[#FFD700]', 'text-black');
</script>
<?php require_once 'components/admin_nav_end.php'; ?> 