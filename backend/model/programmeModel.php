<?php
    class Programme{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function getAllProgramme(){
            $sql = "SELECT * FROM programmes";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if(!$result){
                return 1;
            }
            return $result;
        }

        public function selectOption(){
            $sql = "SELECT prog_id, prog_name, prog_code FROM programmes";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if(!$result){
                return 1;
            }
            return $result;
        }


        public function addProgramme(){
            $prog_name = $_POST['prog_name'];
            $prog_code = $_POST['prog_code'];
            $prog_desc = $_POST['prog_desc'];
            $duration = $_POST['duration'];
            $total_credit = !empty($_POST['total_credit']) ? $_POST['total_credit'] : null;

            $sql = "SELECT * FROM programmes WHERE prog_code = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$prog_code]);
            if($stmt->rowCount() > 0){
                return 1;
            }

            $sql = "INSERT INTO programmes (prog_name, prog_code, prog_desc, duration, total_credit, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);

            $result = $stmt->execute([$prog_name, $prog_code, $prog_desc, $duration, $total_credit]);

            if(!$result){
                return 2;
            }

            return 0;
        }

        public function editProgramme($id){
            $prog_name = $_POST['prog_name'];
            $prog_code = $_POST['prog_code'];
            $prog_desc = $_POST['prog_desc'];
            $duration = $_POST['duration'];
            $total_credit = !empty($_POST['total_credit']) ? $_POST['total_credit'] : null;

            $sql = "SELECT COUNT(*) as count FROM programmes WHERE prog_name = ? AND prog_id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$prog_name, $id]);
            $result = $stmt->fetch();
            if($result['count'] > 0){
                return 1;
            }

            $sql = "SELECT COUNT(*) as count FROM programmes WHERE prog_code = ? AND prog_id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$prog_code, $id]);
            $result = $stmt->fetch();
            if($result['count'] > 0){
                return 2;
            }

            $sql = "UPDATE programmes SET prog_name = ?, prog_code = ?, prog_desc = ?, duration = ?, total_credit = ?, updated_at = NOW() WHERE prog_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$prog_name, $prog_code, $prog_desc, $duration, $total_credit, $id]);
            if(!$result){
                return 3;
            }

            return 0;
        }

        public function deleteProgramme($id){
            $sql = "DELETE FROM programmes WHERE prog_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$id]);
            if(!$result){
                return 1;
            }
            return 0;
        }
    }
?>