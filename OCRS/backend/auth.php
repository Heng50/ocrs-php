<?php
    session_start();
    date_default_timezone_set('Asia/Kuching');
    require_once '../db/conn.php';


    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $auth = new Auth($pdo);
        if(isset($_POST['action']) && $_POST['action'] === 'signup'){
            $result = $auth->signup();
            if($result === 0){
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
                $_SESSION['success'] = "Login successful";
                if($_SESSION['user']['role'] === 'admin'){
                    header('Location: ../admin/dashboard.php');
                }else{
                    header('Location: ../user/dashboard.php'); 
                }
                exit();
            }elseif($result === 1){
                $_SESSION['error'] = "Invalid username or password";
                header('Location: ../index.php');
                exit();
            }
        }
    }

    class Auth{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function signup(){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $role = 'user';
            $status = 'pending';
            $isActive = 0;
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            if($password == $confirm_password){
                $sql = "SELECT username, email FROM users WHERE (username = ? OR email = ?) AND is_active = 1 AND status = 'approved'"; // check if username or email already exists
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$username, $email]);
                $user = $stmt->fetch();
                
                if ($user) {
                    if ($user['username'] === $username) {
                        return 1;
                    } elseif ($user['email'] === $email) {
                        return 2;
                    }
                }

                $sql = "INSERT INTO users (username, email, password, role, status, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role, $status, $isActive, $created_at, $updated_at]);
                return 0;
            } else {
                return 3;
            }
        }

        public function login(){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT user_id, username, password, role FROM users WHERE username = ? AND is_active = 1 AND status = 'approved'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if($user && password_verify($password, $user['password'])){
                $_SESSION['user'] = [
                    'id' => $user['user_id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    // later on add
                ];

                return 0;
            } else {
                return 1;
            }
        }
    }