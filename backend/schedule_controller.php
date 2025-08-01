<?php
    session_start();
    require_once '../db/conn.php';
    require_once 'model/classScheduleModel.php';

    // Handle AJAX requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
        $schedule = new ClassSchedule($pdo);
        
        if ($_GET['action'] === 'get_user_schedule' && isset($_SESSION['user']['id'])) {
            $userSchedule = $schedule->getUserSchedule($_SESSION['user']['id']);
            
            // Handle model return values
            if (is_array($userSchedule) && count($userSchedule) === 2) {
                // Success case - model returns [enrollments, schedules]
                echo json_encode([
                    'success' => true,
                    'enrollments' => $userSchedule[0],
                    'schedules' => $userSchedule[1]
                ]);
            } else {
                // Error cases from model
                $errorMessages = [
                    1 => 'Database error occurred',
                    2 => 'No enrolled courses found',
                    3 => 'Error retrieving class schedules'
                ];
                
                echo json_encode([
                    'success' => false,
                    'error' => $errorMessages[$userSchedule] ?? 'Unknown error'
                ]);
            }
            exit();
        }
        
        if ($_GET['action'] === 'get_class_schedules' && isset($_GET['session_id'])) {
            $classSchedules = $schedule->getClassSchedules($_GET['session_id']);
            
            if (is_array($classSchedules)) {
                echo json_encode([
                    'success' => true,
                    'schedules' => $classSchedules
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Error retrieving class schedules'
                ]);
            }
            exit();
        }
    }
?> 