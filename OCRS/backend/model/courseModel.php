<?php
    class Course{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function getAllCourse(){
            $sql = "SELECT c.*, p.prog_name, ca.category_name
            FROM courses c
            JOIN programmes p ON c.prog_id = p.prog_id
            JOIN categories ca ON c.category_id = ca.category_id
            ";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!$result){
                return false;
            }

            return $result;
        }

        public function addCourse($id){
            $course_code = $_POST['course_code'];
            $course_name = $_POST['course_name'];
            $description = $_POST['description'];
            $credits = $_POST['credits'];
            $prog_id = $_POST['prog_id'];
            $category_id = $_POST['category_id'];
            $is_active = $_POST['is_active'];
            $created_by = $id;

            $sql = "SELECT * FROM courses WHERE course_code = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$course_code]);
            $course = $stmt->fetch();
            if($course){
                return 1;
            }

            $sql = "INSERT INTO courses (course_code, course_name, description, credits, prog_id, category_id, is_active, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);

            $result = $stmt->execute([$course_code, $course_name, $description, $credits, $prog_id, $category_id, $is_active, $created_by]);

            if(!$result){
                return 2;
            }

            return 0;
        }

        public function updateCourse(){
            $id = $_POST['course_id'];
            $course_code = $_POST['course_code'];
            $course_name = $_POST['course_name'];
            $description = $_POST['description'];
            $credits = $_POST['credits'];
            $prog_id = $_POST['prog_id'];
            $category_id = $_POST['category_id'];
            $is_active = $_POST['is_active'];

            $sql = "SELECT * FROM courses WHERE course_code = ? AND course_id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$course_code, $id]);
            $course = $stmt->fetch();
            if($course){
                return 1;
            }

            $sql = "UPDATE courses SET course_code = ?, course_name = ?, description = ?, credits = ?, prog_id = ?, category_id = ?, is_active = ?, updated_at = NOW() WHERE course_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$course_code, $course_name, $description, $credits, $prog_id, $category_id, $is_active, $id]);

            if(!$result){
                return 2;
            }

            return 0;
        }

        public function deleteCourse(){
            $id = $_POST['course_id'];
            $sql = "DELETE FROM courses WHERE course_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$id]);

            if(!$result){
                return 2;
            }

            return 0;
        }

        public function selectOption(){
            $stmt = $this->pdo->prepare("SELECT course_id, course_code, course_name FROM courses");
            $result = $stmt->execute();
            if(!$result){
                return 1;
            }
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }
