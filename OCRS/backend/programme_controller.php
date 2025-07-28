<?php
    session_start();
    date_default_timezone_set('Asia/Kuching');
    require_once '../db/conn.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET'){
        $programme = new ProgrammeController($pdo);
        if(isset($_POST['add_programme'])){
            $result = $programme->addProgramme();
            if($result == 1){
                $_SESSION['error'] = "Programme already exists";
                header('Location: ../admin/programme.php');
                exit();
            }else if($result == 2){
                $_SESSION['error'] = "Programme added failed";
                header('Location: ../admin/programme.php');
                exit();
            }else{
                $_SESSION['success'] = "Programme added successfully";
                header('Location: ../admin/programme.php');
                exit();
            }
        }
        if(isset($_GET['id']) && isset($_POST['edit_programme'])){
            $result = $programme->editProgramme($_GET['id']);
            if($result == 1){
                $_SESSION['error'] = "Programme already exists";
                header('Location: ../admin/programme.php');
                exit();
            }else{
                $_SESSION['success'] = "Programme edited successfully";
                header('Location: ../admin/programme.php');
                exit();
            }
        }
        if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){
            $result = $programme->deleteProgramme($_GET['id']);
            if($result == 1){
                $_SESSION['error'] = "Programme deleted failed";
                header('Location: ../admin/programme.php');
                exit();
            }else{
                $_SESSION['success'] = "Programme deleted successfully";
                header('Location: ../admin/programme.php');
                exit();
            }
        }
    }

    class ProgrammeController{
        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function addProgramme(){
            $prog_name = $_POST['prog_name'];
            $prog_code = $_POST['prog_code'];
            $prog_desc = $_POST['prog_desc'];
            $prog_duration = $_POST['prog_duration'];
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            $sql = "SELECT * FROM programmes WHERE prog_code = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$prog_code]);
            if($stmt->rowCount() > 0){
                return 1;
            }

            $sql = "INSERT INTO programmes (prog_name, prog_code, prog_desc, duration, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$prog_name, $prog_code, $prog_desc, $prog_duration, $created_at, $updated_at]);

            if(!$result){
                return 2;
            }

            return 0;
        }

        public function editProgramme($id){
            $prog_name = $_POST['prog_name'];
            $prog_code = $_POST['prog_code'];
            $prog_desc = $_POST['prog_desc'];
            $prog_duration = $_POST['prog_duration'];
            $updated_at = date('Y-m-d H:i:s');

            $sql = "UPDATE programmes SET prog_name = ?, prog_code = ?, prog_desc = ?, duration = ?, updated_at = ? WHERE prog_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$prog_name, $prog_code, $prog_desc, $prog_duration, $updated_at, $id]);
            return $stmt->rowCount();
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