<?php
    session_start();
    require_once '../db/conn.php';

    class ScheduleController {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function getUserSchedule($user_id) {
            $stmt = $this->pdo->prepare("
                SELECT 
                    e.enrollment_id,
                    e.status as enrollment_status,
                    e.enrolled_at,
                    cs.session_id,
                    cs.semester,
                    c.course_code,
                    c.course_name,
                    u.username as instructor_name,
                    cs.capacity
                FROM enrollments e
                JOIN class_sessions cs ON e.session_id = cs.session_id
                JOIN courses c ON cs.course_id = c.course_id
                JOIN users u ON cs.instructor_id = u.user_id
                WHERE e.student_id = ? AND e.status = 'enrolled'
                ORDER BY cs.semester, c.course_code
            ");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getClassSchedules($session_id) {
            $stmt = $this->pdo->prepare("
                SELECT 
                    schedule_id,
                    day,
                    start_time,
                    end_time,
                    location
                FROM class_schedules 
                WHERE session_id = ?
                ORDER BY 
                    CASE day
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                        WHEN 'Saturday' THEN 6
                        WHEN 'Sunday' THEN 7
                    END,
                    start_time
            ");
            $stmt->execute([$session_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // Handle AJAX requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
        $schedule = new ScheduleController($pdo);
        
        if ($_GET['action'] === 'get_user_schedule' && isset($_SESSION['user']['id'])) {
            $userSchedule = $schedule->getUserSchedule($_SESSION['user']['id']);
            echo json_encode($userSchedule);
            exit();
        }
        
        if ($_GET['action'] === 'get_class_schedules' && isset($_GET['session_id'])) {
            $classSchedules = $schedule->getClassSchedules($_GET['session_id']);
            echo json_encode($classSchedules);
            exit();
        }
    }
?> 