<?php
    class Semester{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function getAllSemester(){
            $sql = "
            SELECT s.*, u.username
            FROM semesters s
            JOIN users u ON s.created_by = u.user_id
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if(!$result){
                return 1;
            }
            return $result;
        }

        public function addSemester($id){
            $semester_code = $_POST['semester_code'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = 'active';
            $created_by = $id;

            $sql = "SELECT COUNT(*) FROM semesters WHERE semester_code = ?  and status = 'active'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$semester_code]);
            $result = $stmt->fetchColumn();

            if($result > 0){
                return 1;
            }


            $sql = "SELECT COUNT(*) FROM semesters WHERE status = 'active' AND NOT (end_date < ? OR start_date > ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$start_date, $end_date]);
            $result = $stmt->fetchColumn();
            if($result > 0){
                return 2;
            }

            $sql = "INSERT INTO semesters (semester_code, start_date, end_date, status, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);
            

            $result = $stmt->execute([$semester_code, $start_date, $end_date, $status, $created_by]);

            if(!$result){
                return 3;
            }

            return 0;
        }

        public function editSemester(){
            $semester_id = $_POST['semester_id'];
            $semester_code = $_POST['semester_code'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            // 1. Check for unique semester_code (excluding self)
            $sql = "SELECT COUNT(*) FROM semesters WHERE semester_code = ? AND semester_id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$semester_code, $semester_id]);
            if ($stmt->fetchColumn() > 0) {
                return 1; // Semester code already exists
            }

            // 2. Check for date overlap (excluding self)
            $sql = "SELECT COUNT(*) FROM semesters 
                    WHERE semester_id != ? 
                    AND NOT (end_date < ? OR start_date > ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$semester_id, $start_date, $end_date]);
            if ($stmt->fetchColumn() > 0) {
                return 2; // Date overlap with another semester
            }

            $sql = "UPDATE semesters SET semester_code = ?, start_date = ?, end_date = ?, updated_at = NOW() WHERE semester_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$semester_code, $start_date, $end_date, $semester_id]);
            return 0;
        }

        public function deleteSemester(){
            $semester_id = $_POST['semester_id'];
            $sql = "DELETE FROM semesters WHERE semester_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$semester_id]);
            return 0;
        }

        public function selectOption(){
            $stmt = $this->pdo->prepare("SELECT semester_id, semester_code FROM semesters");
            $result = $stmt->execute();
            if(!$result){
                return 1;
            }
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }
?>