<?php
    session_start();
    require_once '../db/conn.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
        $enrollment = new EnrollmentController($pdo);

        if(isset($data['session_id'], $data['student_id'])) {
            $result = $enrollment->enroll($data);

            if($result === 'pending') {
                echo json_encode(['success' => true, 'status' => 'pending']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Enrollment failed.']);
                exit();
            }
        }

        if(isset($_GET['id'], $_GET['status']) && ($_GET['status'] == 'approved' || $_GET['status'] == 'rejected')) {
            $result = $enrollment->updateStatus($_GET['id'], $_GET['status']);

            if($result) {
                $_SESSION['success'] = 'Enrollment updated successfully.';
            } else {
                $_SESSION['error'] = 'Enrollment update failed.';
            }

            header('Location: ../admin/enrollment.php');
            exit();
        }
    }

    class EnrollmentController {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function enroll($data) {
            $session_id = $data['session_id'];
            $student_id = $data['student_id'];
            $status = 'pending';
            $stmt = $this->pdo->prepare("INSERT INTO enrollments (session_id, student_id, status, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([$session_id, $student_id, $status]);
            return $status;
        }

        public function CheckEnrollment() { 
            $stmt = $this->pdo->prepare("
                SELECT e.*, u.user_id as student_id, u.username, cs.semester, c.course_code, c.course_name
                FROM enrollments e
                JOIN class_sessions cs ON e.session_id = cs.session_id
                JOIN courses c ON cs.course_id = c.course_id
                JOIN users u ON e.student_id = u.user_id
                WHERE e.status='pending'
                ORDER BY e.created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function updateStatus($id, $status) {
            if($status == 'approved') {
                $status = 'enrolled';
            }

            $stmt = $this->pdo->prepare("UPDATE enrollments SET enrolled_at = NOW(), status = ?, updated_at = NOW() WHERE enrollment_id = ?");
            $result = $stmt->execute([$status, $id]);
            if(!$result) {
                return false;
            }

            return true;
        }
    }