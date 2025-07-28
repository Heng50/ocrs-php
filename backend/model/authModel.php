<?php
    class Auth{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function signup(){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = 'user';
            $status = 'pending';
            $isActive = 0;
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

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
        }

        public function login(){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT user_id, username, password, role, email FROM users WHERE username = ? AND is_active = 1 AND status = 'approved'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if($user && password_verify($password, $user['password'])){
                $_SESSION['user'] = [
                    'id' => $user['user_id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'email' => $user['email'],
                    // later on add
                ];

                return 0;
            } else {
                return 1;
            }
        }

        public function check(){
            if(isset($_SESSION['user'])){
                return 0;
            } else {
                return 1;
            }
        }

        public function selectOption($id){
            $stmt = $this->pdo->prepare("SELECT user_id as instructor_id, username FROM users WHERE user_id != ?");
            $result = $stmt->execute([$id]);
            if(!$result){
                return 1;
            }
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function forgotPassword(){
            $email = $_POST['email'];
            $sql = "SELECT user_id, email FROM users WHERE email = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$email]);
            if(!$result || $stmt->rowCount() === 0){
                return 1;
            }

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function logout(){
            session_start();
            session_unset();
            session_destroy();
            return 0;
        }
    }
?>