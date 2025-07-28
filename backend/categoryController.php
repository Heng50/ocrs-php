<?php
session_start();
require_once '../config/db.php';
require_once '../model/categoryModel.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

$category = new Category($pdo);

// Add Category
if (isset($_POST['action']) && $_POST['action'] == 'addCategory') {
    $result = $category->addCategory();
    
    if($result === 0) {
        header('Location: ../../admin/category.php?success=1&message=Category added successfully');
    } else {
        header('Location: ../../admin/add_category.php?success=0&message=Failed to add category');
    }
    exit();
}

// Edit Category
if (isset($_POST['action']) && $_POST['action'] == 'editCategory') {
    $result = $category->editCategory();
    
    if($result === 0) {
        header('Location: ../../admin/category.php?success=1&message=Category updated successfully');
    } else {
        header('Location: ../../admin/edit_category.php?id=' . $_POST['category_id'] . '&success=0&message=Failed to update category');
    }
    exit();
}

// Delete Category
if (isset($_GET['delete_category'])) {
    $_POST['category_id'] = $_GET['delete_category'];
    $result = $category->deleteCategory();
    
    if($result === 0) {
        header('Location: ../../admin/category.php?success=1&message=Category deleted successfully');
    } else {
        header('Location: ../../admin/category.php?success=0&message=Failed to delete category');
    }
    exit();
}

// If no action matches, redirect to category page
header('Location: ../../admin/category.php');
exit();
?>