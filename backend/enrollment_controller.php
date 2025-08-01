<?php
    session_start();
    require_once '../db/conn.php';
    require_once 'model/enrollmentModel.php';

    $data = json_decode(file_get_contents('php://input'), true);

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $enrollmentModel = new Enrollment($pdo);

        // Handle JSON enrollment requests (from frontend)
        if(isset($data['session_id'], $data['student_id'])) {
            // Set POST data for the model
            $_POST['session_id'] = $data['session_id'];
            $_POST['student_id'] = $data['student_id'];
            
            $result = $enrollmentModel->enroll($data['student_id']);

            if($result === 0) {
                echo json_encode(['success' => true, 'status' => 'pending']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Enrollment failed.']);
                exit();
            }
        }

        // Handle admin approval/rejection requests
        if(isset($_POST['enrollment_id'], $_POST['action'])) {
            $enrollment_id = $_POST['enrollment_id'];
            $action = $_POST['action'];
            
            if($action === 'approve') {
                $_POST['status'] = 'approved';
                $result = $enrollmentModel->updateEnrollment();
                
                if($result === 0) {
                    $_SESSION['success'] = 'Enrollment approved successfully.';
                } else {
                    $_SESSION['error'] = 'Enrollment approval failed.';
                }
                
                header('Location: ../admin/enrollment.php');
                exit();
            } elseif($action === 'reject') {
                $_POST['status'] = 'rejected';
                $result = $enrollmentModel->updateEnrollment();
                
                if($result === 0) {
                    $_SESSION['success'] = 'Enrollment rejected successfully.';
                } else {
                    $_SESSION['error'] = 'Enrollment rejection failed.';
                }
                
                header('Location: ../admin/enrollment.php');
                exit();
            } elseif($action === 'delete') {
                $result = $enrollmentModel->deleteEnrollment($enrollment_id);
                
                if($result === 0) {
                    $_SESSION['success'] = 'Enrollment deleted successfully.';
                } else {
                    $_SESSION['error'] = 'Enrollment deletion failed.';
                }
                
                header('Location: ../admin/enrollment.php');
                exit();
            } elseif($action === 'update_grade') {
                $grade = $_POST['grade'];
                $_POST['enrollment_id'] = $enrollment_id;
                $_POST['grade'] = $grade;
                
                $result = $enrollmentModel->updateGrade();
                
                if($result === 0) {
                    $_SESSION['success'] = 'Grade updated successfully.';
                } else {
                    $_SESSION['error'] = 'Grade update failed.';
                }
                
                header('Location: ../admin/grades.php');
                exit();
            } else {
                $_SESSION['error'] = 'Invalid action.';
                header('Location: ../admin/enrollment.php');
                exit();
            }
        }
    }

    class EnrollmentController {
        private $enrollmentModel;

        public function __construct($pdo) {
            $this->enrollmentModel = new Enrollment($pdo);
        }

        public function CheckEnrollment() { 
            return $this->enrollmentModel->getAllEnrollment();
        }

        public function getEnrolledStudent() {
            return $this->enrollmentModel->getEnrolledStudent();
        }
    }