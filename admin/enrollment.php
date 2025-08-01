<?php
    session_start();
    require_once '../header.php';
    require_once 'components/admin_nav.php';
    require_once '../backend/enrollment_controller.php';

    $enrollment = new EnrollmentController($pdo);
    $enrollments = $enrollment->CheckEnrollment();
?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black mb-2">Enrollment Management</h1>
                <p class="text-gray-600">Manage student course enrollments and their status.</p>
            </div>

            <!-- Statistics Cards -->
            <?php
                $pending_count = 0;
                $enrolled_count = 0;
                $rejected_count = 0;
                
                foreach($enrollments as $enrollment) {
                    switch($enrollment['status']) {
                        case 'pending':
                            $pending_count++;
                            break;
                        case 'enrolled':
                            $enrolled_count++;
                            break;
                        case 'rejected':
                            $rejected_count++;
                            break;
                    }
                }
            ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending Enrollments</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $pending_count; ?></p>
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
                            <p class="text-sm font-medium text-gray-500">Enrolled Students</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $enrolled_count; ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Rejected Enrollments</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo $rejected_count; ?></p>
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

            <!-- Enrollments Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if($enrollments): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($enrollments as $enrollment): ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($enrollment['enrollment_id']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($enrollment['user_id']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($enrollment['username']); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($enrollment['course_code'] . ' - ' . $enrollment['course_name']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($enrollment['semester_code']); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                                $status_color = '';
                                                $status_bg = '';
                                                $status_icon = '';
                                                
                                                switch($enrollment['status']) {
                                                    case 'pending':
                                                        $status_color = 'text-yellow-800';
                                                        $status_bg = 'bg-yellow-100';
                                                        $status_icon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                                        break;
                                                    case 'enrolled':
                                                        $status_color = 'text-green-800';
                                                        $status_bg = 'bg-green-100';
                                                        $status_icon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                                        break;
                                                    case 'rejected':
                                                        $status_color = 'text-red-800';
                                                        $status_bg = 'bg-red-100';
                                                        $status_icon = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                                                        break;
                                                }
                                            ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $status_bg . ' ' . $status_color; ?>">
                                                <?php echo $status_icon; ?>
                                                <?php echo ucfirst(htmlspecialchars($enrollment['status'])); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if($enrollment['status'] === 'pending'): ?>
                                                <!-- Pending: Show Approve/Reject buttons -->
                                                <div class="flex space-x-2">
                                                    <form method="POST" action="../backend/enrollment_controller.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to approve this enrollment?')">
                                                        <input type="hidden" name="enrollment_id" value="<?php echo $enrollment['enrollment_id']; ?>">
                                                        <input type="hidden" name="action" value="approve">
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600 transition-colors duration-200">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="../backend/enrollment_controller.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to reject this enrollment?')">
                                                        <input type="hidden" name="enrollment_id" value="<?php echo $enrollment['enrollment_id']; ?>">
                                                        <input type="hidden" name="action" value="reject">
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors duration-200">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php elseif($enrollment['status'] === 'enrolled'): ?>
                                                <!-- Enrolled: Show Delete button -->
                                                <form method="POST" action="../backend/enrollment_controller.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this enrollment? This action cannot be undone.')">
                                                    <input type="hidden" name="enrollment_id" value="<?php echo $enrollment['enrollment_id']; ?>">
                                                    <input type="hidden" name="action" value="delete">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            <?php elseif($enrollment['status'] === 'rejected'): ?>
                                                <!-- Rejected: No actions -->
                                                <span class="text-gray-400 text-sm">No actions available</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No enrollments found</h3>
                        <p class="text-gray-500">There are no enrollments to display.</p>
                    </div>
                <?php endif; ?>
            </div>
<?php require_once 'components/admin_nav_end.php'; ?>

