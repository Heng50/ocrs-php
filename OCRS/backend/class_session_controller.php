<?php
    session_start();
    date_default_timezone_set('Asia/Kuching');
    require_once '../db/conn.php';

    

    class ClassSessionController {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function search() {
            $query = $_GET['q'];
            $data = array(); // Initialize the array
            $stmt = $this->pdo->prepare("
                SELECT 
                    cs.session_id, 
                    cs.semester, 
                    c.course_code, 
                    c.course_name, 
                    u.username, 
                    cs.capacity, 
                    e.status
                FROM class_sessions cs
                JOIN courses c ON cs.course_id = c.course_id 
                JOIN users u ON cs.instructor_id = u.user_id
                LEFT JOIN enrollments e 
                    ON cs.session_id = e.session_id AND e.student_id = :student_id
                WHERE c.course_code LIKE :query OR c.course_name LIKE :query
                ORDER BY cs.created_at DESC
            ");
            $stmt->execute(['query' => '%' . $query . '%', 'student_id' => $_SESSION['user']['id']]);
            while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $result;
            }
            return $data;
        }


        public function addClassSession() {
            $course_id = $_POST['course_id'];
            $instructor_id = $_POST['instructor_id'];
            $semester = $_POST['semester'];
            $capacity = $_POST['capacity'];
    
            $days = $_POST['day'];
            $start_times = $_POST['start_time'];
            $end_times = $_POST['end_time'];
            $locations = $_POST['location'];

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM class_sessions WHERE course_id = ? AND instructor_id = ? AND semester = ?");
            $stmt->execute([$course_id, $instructor_id, $semester]);

            if($stmt->fetchColumn() > 0) {
                return 1;
            }

            $stmt = $this->pdo->prepare("INSERT INTO class_sessions (course_id, instructor_id, semester, capacity, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
            $result = $stmt->execute([$course_id, $instructor_id, $semester, $capacity, $_SESSION['user']['id']]);

            if(!$result) {
                return 2;
            }

            $session_id = $this->pdo->lastInsertId();

            for($i = 0; $i < count($days); $i++) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM class_schedules WHERE day = ? AND start_time = ? AND end_time = ? AND location = ?");
                $stmt->execute([$days[$i], $start_times[$i], $end_times[$i], $locations[$i]]);
                $count = $stmt->fetchColumn();

                if($count > 0) {
                    return 3;
                }

                $day = $days[$i];
                $start_time = $start_times[$i];
                $end_time = $end_times[$i];
                $location = $locations[$i];

                $stmt = $this->pdo->prepare("INSERT INTO class_schedules (session_id, day, start_time, end_time, location, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
                $result = $stmt->execute([$session_id, $day, $start_time, $end_time, $location, $_SESSION['user']['id']]);

                if(!$result) {
                    return 4;
                }
            }

            return 0;
        }
        
        public function updateClassSession() {
            $id = $_POST['id'];
            $course_id = $_POST['course_id'];
            $instructor_id = $_POST['instructor_id'];
            $semester = $_POST['semester'];
            $capacity = $_POST['capacity'];

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM class_sessions WHERE course_id = ? AND instructor_id = ? AND semester = ? AND session_id != ?");
            $stmt->execute([$course_id, $instructor_id, $semester, $id]);
            if($stmt->fetchColumn() > 0) {
                return 1;
            }

            $stmt = $this->pdo->prepare("UPDATE class_sessions SET course_id = ?, instructor_id = ?, semester = ?, capacity = ? WHERE session_id = ?");
            $result = $stmt->execute([$course_id, $instructor_id, $semester, $capacity, $id]);

            if(!$result) {
                return 2;
            }

            $submitted_ids = array_filter($_POST['schedule_id']);
            $stmt = $this->pdo->prepare("SELECT schedule_id FROM class_schedules WHERE session_id = ?");
            $stmt->execute([$id]);

            $existing_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $deleted_schedules = array_diff($existing_ids, $submitted_ids);

            if(!empty($deleted_schedules)) {
                $deleted_schedules = array_values($deleted_schedules);
                $placeholders = implode(',', array_fill(0, count($deleted_schedules), '?'));
                $stmt = $this->pdo->prepare("DELETE FROM class_schedules WHERE schedule_id IN ($placeholders)");
                $result = $stmt->execute($deleted_schedules);

                if(!$result) {
                    return 3;
                }
            }

            for($i = 0; $i < count($_POST['day']); $i++) {
                $schedule_id = $_POST['schedule_id'][$i] ?? null;
                $day = $_POST['day'][$i];
                $start_time = $_POST['start_time'][$i];
                $end_time = $_POST['end_time'][$i];
                $location = $_POST['location'][$i];

                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM class_schedules WHERE day = ? AND start_time = ? AND end_time = ? AND location = ? AND session_id != ?");
                $stmt->execute([$day, $start_time, $end_time, $location, $id]);
                $count = $stmt->fetchColumn();

                if($count > 0) {
                    return 4;
                }

                if(!empty($schedule_id)) {
                    $sql = "UPDATE class_schedules SET day = ?, start_time = ?, end_time = ?, location = ? , updated_at = NOW() WHERE schedule_id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $result = $stmt->execute([$day, $start_time, $end_time, $location, $schedule_id]);
                } else {
                    $stmt = $this->pdo->prepare("INSERT INTO class_schedules (session_id, day, start_time, end_time, location, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
                    $result = $stmt->execute([$id, $day, $start_time, $end_time, $location, $_SESSION['user']['id']]);
                }

                if(!$result) {
                    return 5;
                }
            }

            return 0;
        }

        public function deleteClassSession() {
            $id = $_GET['id'];
            $stmt = $this->pdo->prepare("DELETE FROM class_schedules WHERE session_id = ?");
            $result = $stmt->execute([$id]);
            if(!$result) {
                return 1;
            }
            $stmt = $this->pdo->prepare("DELETE FROM class_sessions WHERE session_id = ?");
            $result = $stmt->execute([$id]);
            if(!$result) {
                return 2;
            }
            return 0;
        }
    }
