<?php
session_start();
require_once '../db/conn.php';
require_once 'model/semesterModel.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Initialize Semester class
$semester = new Semester($pdo);

// Add Semester
if (isset($_POST['action']) && $_POST['action'] == 'addSemester') {
    $result = $semester->addSemester($_SESSION['user']['id']);
    
    switch($result) {
        case 0:
            header('Location: ../admin/semester.php?success=1&message=Semester added successfully');
            break;
        case 1:
            header('Location: ../admin/add_semester.php?success=0&message=Semester code already exists');
            break;
        case 2:
            header('Location: ../admin/add_semester.php?success=0&message=Date overlap with existing semester');
            break;
        case 3:
            header('Location: ../admin/add_semester.php?success=0&message=Database error occurred');
            break;
        default:
            header('Location: ../admin/add_semester.php?success=0&message=Unknown error occurred');
    }
    exit();
}

// Edit Semester
if (isset($_POST['action']) && $_POST['action'] == 'editSemester') {
    $result = $semester->editSemester();
    
    switch($result) {
        case 0:
            header('Location: ../admin/semester.php?success=1&message=Semester updated successfully');
            break;
        case 1:
            header('Location: ../admin/edit_semester.php?id=' . $_POST['semester_id'] . '&success=0&message=Semester code already exists');
            break;
        case 2:
            header('Location: ../admin/edit_semester.php?id=' . $_POST['semester_id'] . '&success=0&message=Date overlap with existing semester');
            break;
        default:
            header('Location: ../admin/edit_semester.php?id=' . $_POST['semester_id'] . '&success=0&message=Unknown error occurred');
    }
    exit();
}

// Delete Semester
if (isset($_GET['delete_semester'])) {
    $_POST['semester_id'] = $_GET['delete_semester'];
    $result = $semester->deleteSemester();
    
    if($result === 0) {
        header('Location: ../admin/semester.php?success=1&message=Semester deleted successfully');
    } else {
        header('Location: ../admin/semester.php?success=0&message=Failed to delete semester');
    }
    exit();
}

// If no action matches, redirect to semester page
header('Location: ../admin/semester.php');
exit();
?> 