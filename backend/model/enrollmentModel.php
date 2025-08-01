<?php

    class Enrollment {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function enroll($id) {
            $session_id = $_POST['session_id'];
            $status = 'pending';
            $stmt = $this->pdo->prepare("INSERT INTO enrollments (session_id, student_id, status, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $result = $stmt->execute([$session_id, $id, $status]);


            if(!$result) {
                return 1;
            }

            return 0;
        }

        public function getAllEnrollment() {
            $stmt = $this->pdo->prepare("
                SELECT e.enrollment_id, e.status, e.created_at, e.updated_at,
                       u.username, u.user_id,
                       c.course_code, c.course_name,
                       s.semester_code
                FROM enrollments e
                JOIN users u ON e.student_id = u.user_id
                JOIN class_sessions cs ON e.session_id = cs.session_id
                JOIN courses c ON cs.course_id = c.course_id
                JOIN semesters s ON cs.semester_id = s.semester_id
                ORDER BY e.status, e.enrollment_id
            ");
            $result = $stmt->execute();
            if(!$result) {
                return 1;
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function deleteEnrollment($enrollment_id) {
            $stmt = $this->pdo->prepare("DELETE FROM enrollments WHERE enrollment_id = ?");
            $result = $stmt->execute([$enrollment_id]);
            if(!$result) {
                return 1;
            }

            return 0;
        }

        public function getEnrolledStudent() {
            $where = '';
            $where_params = [];
            // if get request
            if(isset($_POST['semester_id'])) {
                $semester_id = $_POST['semester_id'];
                $where .= " AND s.semester_id = ?";
                $where_params[] = $semester_id;
            }
            if(isset($_POST['prog_id'])) {
                $prog_id = $_POST['prog_id'];
                $where .= " AND c.prog_id = ?";
                $where_params[] = $prog_id;
            }

            if(isset($_POST['course_id'])) {
                $course_id = $_POST['course_id'];
                $where .= " AND c.course_id = ?";
                $where_params[] = $course_id;
            }

            if(isset($_POST['grade_status'])) {
                $grade_status = $_POST['grade_status'];
                if($grade_status === '1') {
                    $where .= " AND e.final_grade IS NOT NULL";
                } elseif($grade_status === '0') {
                    $where .= " AND e.final_grade IS NULL";
                } else {
                    $where .= " AND e.final_grade = ?";
                    $where_params[] = $grade_status;
                }
            }

            // Student Name	Student ID	Course	Programme	Semester
            $stmt = $this->pdo->prepare("
                SELECT e.enrollment_id, e.final_grade, e.is_completed,
                       u.username, u.user_id,
                       c.course_code, c.course_name, p.prog_name,
                       s.semester_code
                FROM enrollments e
                JOIN users u ON e.student_id = u.user_id
                JOIN class_sessions cs ON e.session_id = cs.session_id
                JOIN courses c ON cs.course_id = c.course_id
                JOIN programmes p ON c.prog_id = p.prog_id
                JOIN semesters s ON cs.semester_id = s.semester_id
                WHERE e.status='enrolled' $where");
            $result = $stmt->execute($where_params);
            if(!$result) {
                return 1;
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function checkPendingEnrollment() { 
            $stmt = $this->pdo->prepare("
                SELECT e.enrollment_id, e.status, u.username, s.semester_code, c.course_code, c.course_name
                FROM enrollments e
                JOIN class_sessions cs ON e.session_id = cs.session_id
                JOIN courses c ON cs.course_id = c.course_id
                JOIN users u ON e.student_id = u.user_id
                JOIN semesters s ON cs.semester_id = s.semester_id
                WHERE e.status='pending'
                ORDER BY e.created_at DESC
            ");
            $result = $stmt->execute();

            if(!$result) {
                return 1;
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function updateEnrollment() {
            $enrollment_id = $_POST['enrollment_id'];
            $status = $_POST['status'];

            if($status == 'approved') {
                $status = 'enrolled';
            }

            $stmt = $this->pdo->prepare("UPDATE enrollments SET enrolled_at = NOW(), status = ?, updated_at = NOW() WHERE enrollment_id = ?");
            $result = $stmt->execute([$status, $enrollment_id]);
            if(!$result) {
                return 1;
            }

            return 0;
        }

        public function updateGrade() {
            $enrollment_id = $_POST['enrollment_id'];
            $grade = $_POST['grade'];

            $stmt = $this->pdo->prepare("UPDATE enrollments SET final_grade = ?, is_completed = 1, updated_at = NOW() WHERE enrollment_id = ?");
            $result = $stmt->execute([$grade, $enrollment_id]);
            if(!$result) {
                return 1;
            }

            return 0;
        }
    }

?>