<?php
    session_start();
    date_default_timezone_set('Asia/Kuching');
    require_once '../db/conn.php';
    require_once 'model/authModel.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $auth = new Auth($pdo);
        
        if(isset($_POST['action']) && $_POST['action'] === 'signup'){
            $result = $auth->signup();
            if($result === 0){
                unset($_SESSION['error']);
                $_SESSION['success'] = "Account created successfully";
                header('Location: ../index.php');
                exit();
            }elseif($result === 1){
                $_SESSION['error'] = "Username already exists";
                header('Location: ../signup.php');
                exit();
            }elseif($result === 2){
                $_SESSION['error'] = "Email already exists";
                header('Location: ../signup.php');
                exit();
            }elseif($result === 3){
                $_SESSION['error'] = "Password and Confirm Password do not match";
                header('Location: ../signup.php');
                exit();
            }
        }

        if(isset($_POST['action']) && $_POST['action'] === 'login'){
            $result = $auth->login();
            if($result === 0){
                unset($_SESSION['error']);
                $_SESSION['success'] = "Login successful";
                if($_SESSION['user']['role'] === 'admin'){
                    header('Location: ../admin/dashboard.php');
                }else{
                    header('Location: ../user/dashboard.php'); 
                }
                exit();
            }elseif($result === 1 || $result === 2){
                unset($_SESSION['success']);
                $_SESSION['error'] = "Invalid username or password";
                header('Location: ../index.php');
                exit();
            }
        }
    }
?>