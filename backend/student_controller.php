<?php
    session_start();
    require_once '../db/conn.php';
    require_once 'model/studentModel.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
        $studentModel = new Student($pdo);

        if(isset($_POST['edit_student'])) {
            $result = $studentModel->editStudent($_POST['id'], $_POST['username'], $_POST['email']);

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
            $result = $studentModel->deleteStudent($_GET['id']);

            if($result) {
                $_SESSION['success'] = 'Student deleted successfully.';
            } else {
                $_SESSION['error'] = 'Failed to delete student.';
            }

            header('Location: ../admin/student.php');
            exit();
        }

        if(isset($_GET['id'], $_GET['action']) && ($_GET['action'] === 'approved' || $_GET['action'] === 'rejected')) {
            $result = $studentModel->updateStatus($_GET['id'], $_GET['action']);

            if($result) {
                $_SESSION['success'] = 'Student ' . $_GET['action'] . ' successfully.';
            } else {
                $_SESSION['error'] = 'Failed to ' . $_GET['action'] . ' student.';
            }

            header('Location: ../admin/student_approval.php');
            exit();
        }

        if(isset($_POST['action']) && $_POST['action'] === 'update-profile') {
            $result = $studentModel->updateProfile($_POST['prog_id'], $_SESSION['user']['id']);
            if($result) {
                // Update the session to reflect that profile is completed
                $_SESSION['user']['profile_completed'] = 1;
                $_SESSION['success'] = 'Profile updated successfully.';
                header('Location: ../user/dashboard.php');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to update profile.';
                header('Location: ../user/profile_completed.php');
                exit();
            }
        }
    }

    if (isset($_GET['action']) && $_GET['action'] === 'search' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        $studentModel = new Student($pdo);
        $results = [];
        if (isset($_POST['query'])) {
            $_POST['query'] = $_POST['query'];
            $results = $studentModel->search();
        }
        echo json_encode(is_array($results) ? $results : []);
        exit;
    }