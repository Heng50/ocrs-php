<?php

    class Instructor{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function getAllInstructors(){
            $sql = "SELECT instructor_id, instructor_name, email, description FROM instructors"; // fetch from instructors table
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute();

            if(!$result){
                return 1;
            }

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }

        public function addInstructor(){
            $instructor_name = $_POST['instructor_name'];
            $email = $_POST['email'];
            $description = $_POST['description'];

            $sql = "INSERT INTO instructors (instructor_name, email, description, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$instructor_name, $email, $description]);

            if(!$result){
                return 1;
            }

            return 0;
        }

        public function editInstructor(){
            $instructor_id = $_POST['instructor_id'];
            $instructor_name = $_POST['instructor_name'];
            $email = $_POST['email'];
            $description = $_POST['description'];

            $sql = "UPDATE instructors SET instructor_name = ?, email = ?, description = ?, updated_at = NOW() WHERE instructor_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$instructor_name, $email, $description, $instructor_id]);

            if(!$result){
                return 1;
            }

            return 0;
        }

        public function deleteInstructor(){
            $instructor_id = $_POST['instructor_id'];

            $sql = "DELETE FROM instructors WHERE instructor_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$instructor_id]);

            if(!$result){
                return 1;
            }

            return 0;
        }

        public function selectOption(){
            $stmt = $this->pdo->prepare("SELECT instructor_id, instructor_name FROM instructors");
            $result = $stmt->execute();
            if(!$result){
                return 1;
            }
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }

