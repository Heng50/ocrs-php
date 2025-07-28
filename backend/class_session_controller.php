<?php
session_start();
require_once '../db/conn.php';
require_once 'model/classSessionModel.php';

$classSession = new ClassSession($pdo);

// Search endpoint for course search
if (isset($_GET['q']) || isset($_GET['category_id']) || isset($_GET['semester_id']) || isset($_GET['instructor_id'])) {
    // Allow search for both admin and user roles
    if (!isset($_SESSION['user'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not authenticated']);
        exit();
    }
    
    // Set POST parameters for the model
    if (isset($_GET['q'])) $_POST['query'] = $_GET['q'];
    if (isset($_GET['category_id'])) $_POST['category_id'] = $_GET['category_id'];
    if (isset($_GET['semester_id'])) $_POST['semester_id'] = $_GET['semester_id'];
    if (isset($_GET['instructor_id'])) $_POST['instructor_id'] = $_GET['instructor_id'];
    
    $student_id = $_SESSION['user']['id'];
    $results = $classSession->search($student_id);
    
    header('Content-Type: application/json');
    echo json_encode($results);
    exit();
}

// Get filter options endpoint
if (isset($_GET['action']) && $_GET['action'] == 'getFilterOptions') {
    // Allow filter options for both admin and user roles
    if (!isset($_SESSION['user'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not authenticated']);
        exit();
    }
    
    $options = [];
    
    // Get categories using Category model
    require_once 'model/categoryModel.php';
    $categoryModel = new Category($pdo);
    $options['categories'] = $categoryModel->selectOption();
    
    // Get semesters using Semester model
    require_once 'model/semesterModel.php';
    $semesterModel = new Semester($pdo);
    $options['semesters'] = $semesterModel->selectOption();
    
    // Get instructors using Instructor model
    require_once 'model/instructorModel.php';
    $instructorModel = new Instructor($pdo);
    $options['instructors'] = $instructorModel->selectOption();
    
    header('Content-Type: application/json');
    echo json_encode($options);
    exit();
}

// Check if user is logged in and is admin for other operations
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Add Class Session
if (isset($_POST['action']) && $_POST['action'] == 'addClassSession') {
    $result = $classSession->addClassSession($_SESSION['user']['id']);
    
    if($result === 0) {
        $_SESSION['success'] = "Class session added successfully";
    } else if($result === 1) {
        $_SESSION['error'] = "Class session already exists for this course, instructor, and semester";
    } else if($result === 2) {
        $_SESSION['error'] = "Failed to add class session";
    } else if($result === 3) {
        $_SESSION['error'] = "Schedule conflict detected";
    } else {
        $_SESSION['error'] = "Failed to add schedule";
    }
    header('Location: ../admin/class_session.php');
    exit();
}

// Edit Class Session
if (isset($_POST['action']) && $_POST['action'] == 'editClassSession') {
    $result = $classSession->updateClassSession($_SESSION['user']['id']);
    
    if($result === 0) {
        $_SESSION['success'] = "Class session updated successfully";
    } else if($result === 1) {
        $_SESSION['error'] = "Class session already exists for this course, instructor, and semester";
    } else if($result === 2) {
        $_SESSION['error'] = "Failed to update class session";
    } else if($result === 3) {
        $_SESSION['error'] = "Failed to delete old schedules";
    } else if($result === 4) {
        $_SESSION['error'] = "Schedule conflict detected";
    } else {
        $_SESSION['error'] = "Failed to add new schedules";
    }
    header('Location: ../admin/class_session.php');
    exit();
}

// Delete Class Session
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $_POST['session_id'] = $_GET['id'];
    $result = $classSession->deleteClassSession();
    
    if($result === 0) {
        $_SESSION['success'] = "Class session deleted successfully";
    } else if($result === 1) {
        $_SESSION['error'] = "Failed to delete schedules";
    } else if($result === 2) {
        $_SESSION['error'] = "Failed to delete enrollments";
    } else {
        $_SESSION['error'] = "Failed to delete class session";
    }
    header('Location: ../admin/class_session.php');
    exit();
}

// If no action matches, redirect to class session page
header('Location: ../admin/class_session.php');
exit();
?>
