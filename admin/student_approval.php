<?php
    require_once '../header.php';
    require_once '../backend/student_controller.php';

    $student = new StudentController($pdo);
    $students = $student->getPendingStudents();
?>

<div class="bg-white p-6 rounded-xl shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-black">Student Approval</h1>
        <button onclick="loadContent('student.php')" class="bg-[#FFD700] text-black font-semibold py-2 px-4 rounded hover:bg-[#e6c200] transition">
            Back
        </button>
    </div>

    <?php if($students): ?>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-black">
                <thead>
                    <tr class="bg-black text-white">
                        <th class="border border-black px-4 py-3 text-left">ID</th>
                        <th class="border border-black px-4 py-3 text-left">Username</th>
                        <th class="border border-black px-4 py-3 text-left">Email</th>
                        <th class="border border-black px-4 py-3 text-left">Status</th>
                        <th class="border border-black px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($students as $student): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border border-black px-4 py-3"><?php echo $student['user_id']; ?></td>
                            <td class="border border-black px-4 py-3"><?php echo $student['username']; ?></td>
                            <td class="border border-black px-4 py-3"><?php echo $student['email']; ?></td>
                            <td class="border border-black px-4 py-3">
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">
                                    <?php echo $student['status']; ?>
                                </span>
                            </td>
                            <td class="border border-black px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="../backend/student_controller.php?id=<?php echo $student['user_id']; ?>&action=approved" 
                                       onclick="return confirm('Are you sure you want to approve this student?');"
                                       class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">
                                        Approve
                                    </a>
                                    <a href="../backend/student_controller.php?id=<?php echo $student['user_id']; ?>&action=rejected" 
                                       onclick="return confirm('Are you sure you want to reject this student?');"
                                       class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">
                                        Reject
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-8">
            <p class="text-black text-lg">No pending students.</p>
        </div>
    <?php endif; ?>
</div>

<?php
    require_once '../footer.php';
?>