<?php
    session_start();
    require_once '../header.php';
    require_once '../backend/model/programmeModel.php';

    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        header('Location: ../index.php');
        exit();
    }

    if ($_SESSION['user']['profile_completed'] == 1) {
        header('Location: ../user/dashboard.php');
        exit();
    }

    $programmeModel = new Programme($pdo);
    $programmes = $programmeModel->selectOption();

    $error = '';
    $success = '';

    // Check for session messages
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        $success = $_SESSION['success'];
        unset($_SESSION['success']);
    }
?>


    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-[#FFD700]">
                    <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Complete Your Profile
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Please provide your programme information to complete your profile setup.
                </p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800"><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800"><?php echo htmlspecialchars($success); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6" method="POST" action="../backend/student_controller.php">
                <input type="hidden" name="action" value="update-profile">
                <div class="space-y-4">
                    <div>
                        <label for="prog_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Your Programme
                        </label>
                        <select id="prog_id" name="prog_id" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-[#FFD700] focus:border-[#FFD700] focus:z-10 sm:text-sm">
                            <option value="">Choose a programme...</option>
                            <?php if ($programmes !== false): ?>
                                <?php foreach ($programmes as $programme): ?>
                                    <option value="<?php echo htmlspecialchars($programme['prog_id']); ?>">
                                        <?php echo htmlspecialchars($programme['prog_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-black bg-[#FFD700] hover:bg-[#e6c200] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FFD700] transition-colors duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-black group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        Complete Profile
                    </button>
                </div>

                <div class="text-center">
                    <a href="../backend/logout.php" class="text-sm text-gray-600 hover:text-gray-900">
                        Logout
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
