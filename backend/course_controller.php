<?php
session_start();
require_once '../db/conn.php';
require_once 'model/courseModel.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

$course = new Course($pdo);

// Add Course
if (isset($_POST['action']) && $_POST['action'] == 'addCourse') {
    $result = $course->addCourse($_SESSION['user']['id']);
    
    if($result === 0) {
        $_SESSION['success'] = "Course added successfully";
    } else if($result === 1) {
        $_SESSION['error'] = "Course code already exists";
    } else {
        $_SESSION['error'] = "Course added failed";
    }
    header('Location: ../admin/course.php');
    exit();
}

// Edit Course
if (isset($_POST['action']) && $_POST['action'] == 'editCourse') {
    $result = $course->updateCourse();
    
    if($result === 0) {
        $_SESSION['success'] = "Course updated successfully";
    } else if($result === 1) {
        $_SESSION['error'] = "Course code already exists";
    } else {
        $_SESSION['error'] = "Course updated failed";
    }
    header('Location: ../admin/course.php');
    exit();
}

// Delete Course
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $_POST['course_id'] = $_GET['id'];
    $result = $course->deleteCourse();
    
    if($result === 0) {
        $_SESSION['success'] = "Course deleted successfully";
    } else {
        $_SESSION['error'] = "Course deleted failed";
    }
    header('Location: ../admin/course.php');
    exit();
}

// If no action matches, redirect to course page
header('Location: ../admin/course.php');
exit();
?>