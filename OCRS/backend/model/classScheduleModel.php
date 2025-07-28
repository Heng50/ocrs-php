<?php
    class ClassSchedule {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function getSchedule($session_id) {
            $session_id = implode(',', $session_id);
            $stmt = $this->pdo->prepare("SELECT * FROM class_schedules WHERE session_id IN ($session_id)");
            $result = $stmt->execute();
            if(!$result) {
                return 1;
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getUserSchedule($user_id) {
            $stmt = $this->pdo->prepare("
                SELECT 
                    s.semester_code,
                    e.enrollment_id,
                    e.status as enrollment_status,
                    e.enrolled_at,
                    cs.session_id,
                    c.course_code,
                    c.course_name,
                    i.instructor_name,
                    cs.capacity
                FROM enrollments e
                JOIN class_sessions cs ON e.session_id = cs.session_id
                JOIN courses c ON cs.course_id = c.course_id
                JOIN instructors i ON cs.instructor_id = i.instructor_id
                JOIN semesters s ON cs.semester_id = s.semester_id
                WHERE e.student_id = ? AND e.status = 'enrolled'
                ORDER BY s.semester_code, c.course_code
            ");
            $result = $stmt->execute([$user_id]);

            if(!$result) {
                return 1;
            }
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($result) == 0) {
                return 2;
            }
            $session_id = array_column($result, 'session_id');


            $classSchedules = $this->getClassSchedules($session_id);

            if($classSchedules === 1) {
                return 3;
            }

            return [$result, $classSchedules];
        }

        public function getClassSchedules($session_id) {
            // Ensure $session_id is always an array
            if (!is_array($session_id)) {
                $session_id = [$session_id];
            }

            // For PDO IN clause:
            $placeholders = implode(',', array_fill(0, count($session_id), '?'));

            $stmt = $this->pdo->prepare("
                SELECT 
                    schedule_id,
                    session_id,
                    day,
                    start_time,
                    end_time,
                    location
                FROM class_schedules 
                WHERE session_id IN ($placeholders)
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
            $result = $stmt->execute($session_id);

            if(!$result) {
                return 1;
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>