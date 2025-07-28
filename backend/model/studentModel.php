<?php

    class Student {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function getAllStudents() {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = 'user' AND is_active = 1 AND status = 'approved'");
            $result = $stmt->execute();

            if(!$result) {
                return 1;
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getPendingStudents() {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = 'user' AND status = 'pending' AND is_active = 0");
            $result = $stmt->execute();

            if(!$result) {
                return 1;
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getStudentById($id) {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }


        public function editStudent() {
            $user_id = $_POST['user_id'];
            $username = $_POST['username'];
            $email = $_POST['email'];

            $stmt = $this->pdo->prepare("SELECT count(*) FROM users WHERE (username = ? OR email = ?) AND user_id != ?");
            $stmt->execute([$username, $email, $user_id]);

            $count = $stmt->fetchColumn();

            if($count > 0) {
                return 1;
            } 

            $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, updated_at = NOW() WHERE user_id = ?");
            $result = $stmt->execute([$username, $email, $user_id]);
            if(!$result) {
                return 2;
            }

            return 0;
        }

        public function deleteStudent() {
            $user_id = $_POST['user_id'];
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = ?");
            $result = $stmt->execute([$user_id]);
            if(!$result) {
                return 1;
            }

            return 0;
        }

        public function updateStatus() {
            $user_id = $_POST['user_id'];
            $status = $_POST['status'];

            if($status === 'approved') {
                $sql = "UPDATE users SET status = ?, is_active = 1, updated_at = NOW() WHERE user_id = ?";
            } else {
                $sql = "UPDATE users SET status = ?, updated_at = NOW() WHERE user_id = ?";
            }

            $params = [$status, $user_id];

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            if(!$result) {
                return 1;
            }

            return 0;
        }

        public function search() {
            $query = $_POST['query'];
            $stmt = $this->pdo->prepare("SELECT user_id, username, email FROM users WHERE username LIKE ? OR user_id LIKE ? AND status = 'approved' AND is_active = 1");
            $result = $stmt->execute(["%$query%", "%$query%"]);

            if(!$result) {
                return 1;
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

?>