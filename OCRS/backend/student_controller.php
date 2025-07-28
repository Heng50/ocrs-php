<?php
    session_start();
    require_once '../db/conn.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
        $student = new StudentController($pdo);

        if(isset($_POST['edit_student'])) {
            $result = $student->editStudent($_POST['id'], $_POST['username'], $_POST['email']);

            if($result === 1) {
                $_SESSION['error'] = 'Username or email already exists.';
            } else if($result === 2) {
                $_SESSION['error'] = 'Failed to update student.';
            } else if($result === 3) {
                $_SESSION['success'] = 'Student updated successfully.';
            }

            header('Location: ../admin/student.php');
            exit();
        }

        if(isset($_GET['id'], $_GET['action']) && $_GET['action'] === 'delete') {
            $result = $student->deleteStudent($_GET['id']);

            if($result) {
                $_SESSION['success'] = 'Student deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete student.';
            }

            header('Location: ../admin/student.php');
            exit();
        }

        if(isset($_GET['id'], $_GET['action']) && ($_GET['action'] === 'approved' || $_GET['action'] === 'rejected')) {
            $result = $student->updateStatus($_GET['id'], $_GET['action']);

            if($result) {
                $_SESSION['success'] = 'Student ' . $_GET['action'] . ' successfully.';
            } else {
                $_SESSION['error'] = 'Failed to ' . $_GET['action'] . ' student.';
            }

            header('Location: ../admin/student_approval.php');
            exit();
        }
    }

    class StudentController {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function getAllStudents() {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = 'user' AND is_active = 1 AND status = 'approved'");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getStudentById($id) {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getPendingStudents() {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = 'user' AND status = 'pending'");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function editStudent($id, $username, $email) {
            $stmt = $this->pdo->prepare("SELECT count(*) FROM users WHERE (username = ? OR email = ?) AND user_id != ?");
            $stmt->execute([$username, $email, $id]);
            $count = $stmt->fetchColumn();

            if($count > 0) {
                return 1;
            } 

            $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, updated_at = NOW() WHERE user_id = ?");
            $result = $stmt->execute([$username, $email, $id]);
            if(!$result) {
                return 2;
            }

            return 3;
        }

        public function deleteStudent($id) {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        }

        public function updateStatus($id, $status) {
            if($status === 'approved') {
                $sql = "UPDATE users SET status = ?, is_active = 1, updated_at = NOW() WHERE user_id = ?";
            } else {
                $sql = "UPDATE users SET status = ?, updated_at = NOW() WHERE user_id = ?";
            }

            $params = [$status, $id];

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            if(!$result) {
                return false;
            }

            return true;
        }
    }