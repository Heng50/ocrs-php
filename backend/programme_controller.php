<?php
session_start();
require_once '../db/conn.php';
require_once 'model/programmeModel.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

$programme = new Programme($pdo);

// Add Programme
if (isset($_POST['action']) && $_POST['action'] == 'addProgramme') {
    $result = $programme->addProgramme();
    
    if($result === 0) {
        $_SESSION['success'] = "Programme added successfully";
    } else if($result === 1) {
        $_SESSION['error'] = "Programme already exists";
    } else {
        $_SESSION['error'] = "Programme added failed";
    }
    header('Location: ../admin/programme.php');
    exit();
}

// Edit Programme
if (isset($_POST['action']) && $_POST['action'] == 'editProgramme') {
    $result = $programme->editProgramme($_POST['prog_id']);
    
    if($result === 0) {
        $_SESSION['success'] = "Programme edited successfully";
    } else if($result === 1) {
        $_SESSION['error'] = "Programme name already exists";
    } else if($result === 2) {
        $_SESSION['error'] = "Programme code already exists";
    } else {
        $_SESSION['error'] = "Programme edited failed";
    }
    header('Location: ../admin/programme.php');
    exit();
}

// Delete Programme
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $result = $programme->deleteProgramme($_GET['id']);
    
    if($result === 0) {
        $_SESSION['success'] = "Programme deleted successfully";
    } else {
        $_SESSION['error'] = "Programme deleted failed";
    }
    header('Location: ../admin/programme.php');
    exit();
}

// If no action matches, redirect to programme page
header('Location: ../admin/programme.php');
exit();
?>