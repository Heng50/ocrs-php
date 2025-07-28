<?php
    session_start();
    date_default_timezone_set('Asia/Kuching');
    require_once '../db/conn.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET'){
        $course = new CourseController($pdo);
        if(isset($_POST['add_course'])){
            $result = $course->addCourse();
            if($result == 1){
                $_SESSION['error'] = "Course code already exists";
                header('Location: ../admin/add_course.php');
                exit();
            }elseif($result == 2){
                $_SESSION['error'] = "Failed to add course";
                header('Location: ../admin/add_course.php');
                exit();
            }else{
                $_SESSION['success'] = "Course added successfully";
                header('Location: ../admin/course.php');
                exit();
            }
        }
        if(isset($_POST['update_course'])){
            $result = $course->updateCourse();
            if($result == 1){
                $_SESSION['error'] = "Course code already exists";
                header('Location: ../admin/update_course.php?id=' . $_POST['id']);
                exit();
            }elseif($result == 2){
                $_SESSION['error'] = "Failed to update course";
                header('Location: ../admin/update_course.php?id=' . $_POST['id']);
                exit();
            }else{
                $_SESSION['success'] = "Course updated successfully";
                header('Location: ../admin/course.php');
                exit();
            }
        }

        if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete'){
            $result = $course->deleteCourse();
            if($result == 2){
                $_SESSION['error'] = "Failed to delete course";
                header('Location: ../admin/course.php');
                exit();
            }else{
                $_SESSION['success'] = "Course deleted successfully";
                header('Location: ../admin/course.php');
                exit();
            }
        }
    }

    class CourseController{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function addCourse(){
            $course_code = $_POST['course_code'];
            $course_name = $_POST['course_name'];
            $description = $_POST['description'];
            $credits = $_POST['credits'];
            $prog_id = $_POST['prog_id'];
            $created_by = $_SESSION['user']['id'];

            $sql = "SELECT * FROM courses WHERE course_code = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$course_code]);
            $course = $stmt->fetch();
            if($course){
                return 1;
            }

            $sql = "INSERT INTO courses (course_code, course_name, description, credits, prog_id, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$course_code, $course_name, $description, $credits, $prog_id, $created_by]);

            if(!$result){
                return 2;
            }

            return 0;
        }

        public function updateCourse(){
            $id = $_POST['id'];
            $course_code = $_POST['course_code'];
            $course_name = $_POST['course_name'];
            $description = $_POST['description'];
            $credits = $_POST['credits'];
            $prog_id = $_POST['prog_id'];

            $sql = "SELECT * FROM courses WHERE course_code = ? AND course_id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$course_code, $id]);
            $course = $stmt->fetch();
            if($course){
                return 1;
            }

            $sql = "UPDATE courses SET course_code = ?, course_name = ?, description = ?, credits = ?, prog_id = ?, updated_at = NOW() WHERE course_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$course_code, $course_name, $description, $credits, $prog_id, $id]);

            if(!$result){
                return 2;
            }

            return 0;
        }

        public function deleteCourse(){
            $id = $_GET['id'];
            $sql = "DELETE FROM courses WHERE course_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$id]);

            if(!$result){
                return 2;
            }

            return 0;
        }
    }

?>