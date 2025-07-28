<?php
class ClassSession {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function search($student_id) {
        $where = [];
        $params = [];

        $params[] = $student_id;
        // Parse POST values (accept comma-separated string or array)
        $categories = isset($_POST['category_id']) ? $_POST['category_id'] : '';
        $semesters = isset($_POST['semester_id']) ? $_POST['semester_id'] : '';
        $instructors = isset($_POST['instructor_id']) ? $_POST['instructor_id'] : '';
        $keyword = isset($_POST['query']) ? trim($_POST['query']) : '';


        // Convert comma-separated strings to arrays if needed
        if (is_string($categories)) $categories = array_filter(explode(',', $categories));
        if (is_string($semesters)) $semesters = array_filter(explode(',', $semesters));
        if (is_string($instructors)) $instructors = array_filter(explode(',', $instructors));

        if(!empty($categories)) {
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $where[] = "cat.category_id IN ($placeholders)";
            $params = array_merge($params, $categories);
        }

        if(!empty($semesters)) {
            $placeholders = implode(',', array_fill(0, count($semesters), '?'));
            $where[] = "s.semester_id IN ($placeholders)";
            $params = array_merge($params, $semesters);
        }

        if(!empty($instructors)) {
            $placeholders = implode(',', array_fill(0, count($instructors), '?'));
            $where[] = "i.instructor_id IN ($placeholders)";
            $params = array_merge($params, $instructors);
        }

        if(!empty($keyword)){
            $where[] = "(c.course_code LIKE ? OR c.course_name LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $where[] = "cs.is_active = 1";

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->pdo->prepare("
            SELECT 
                cs.session_id,
                c.course_id,
                c.course_code,
                c.course_name,
                cat.category_name,
                s.semester_code,
                i.instructor_name,
                cs.capacity,
                e.status
            FROM class_sessions cs
            JOIN courses c ON cs.course_id = c.course_id 
            JOIN instructors i ON cs.instructor_id = i.instructor_id
            JOIN categories cat ON c.category_id = cat.category_id
            JOIN semesters s ON cs.semester_id = s.semester_id
            LEFT JOIN enrollments e ON cs.session_id = e.session_id AND e.student_id = ?
            $whereClause
            ORDER BY cs.created_at DESC
        ");

        $stmt->execute($params);

        $data = [];
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $result;
        }

        return $data;
    }

    public function getAllClassSessions() {
        $stmt = $this->pdo->prepare("
            SELECT 
                cs.session_id,
                c.course_id, 
                c.course_name, 
                c.course_code,
                i.instructor_id,
                i.instructor_name, 
                s.semester_id,
                s.semester_code,
                cs.capacity, 
                cs.is_active
            FROM class_sessions cs 
            JOIN courses c ON cs.course_id = c.course_id 
            JOIN instructors i ON cs.instructor_id = i.instructor_id 
            JOIN semesters s ON cs.semester_id = s.semester_id
            ORDER BY cs.created_at DESC
        ");
        $result = $stmt->execute();
        if(!$result) {
            return 1;
        }
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }

    public function addClassSession($id) {
        $course_id = $_POST['course_id'];
        $instructor_id = $_POST['instructor_id'];
        $semester_id = $_POST['semester_id'];
        $capacity = $_POST['capacity'];
        $is_active = 1;
        $created_by = $id;

        $schedules = json_decode($_POST['schedules'], true);

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM class_sessions WHERE course_id = ? AND instructor_id = ? AND semester_id = ?");
        $stmt->execute([$course_id, $instructor_id, $semester_id]);

        if($stmt->fetchColumn() > 0) {
            return 1;
        }

        $stmt = $this->pdo->prepare("INSERT INTO class_sessions (course_id, instructor_id, semester_id, capacity, is_active, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $result = $stmt->execute([$course_id, $instructor_id, $semester_id, $capacity, $is_active, $created_by]);

        if(!$result) {
            return 2;
        }

        $session_id = $this->pdo->lastInsertId();


        foreach($schedules as $schedule) {
            $day = $schedule['day'];
            $start_time = $schedule['start_time'];
            $end_time = $schedule['end_time'];
            $location = $schedule['location'];

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM class_schedules WHERE day = ? AND location = ? AND NOT (end_time <= ? OR start_time >= ?)");
            $stmt->execute([$day, $location, $start_time, $end_time]);
            $count = $stmt->fetchColumn();

            if($count > 0) {
                return 3;
            }

            $stmt = $this->pdo->prepare("INSERT INTO class_schedules (session_id, day, start_time, end_time, location, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $result = $stmt->execute([$session_id, $day, $start_time, $end_time, $location, $created_by]);

            if(!$result) {
                return 4;
            }
        }

        return 0;

    }
    
    public function updateClassSession($id) {
        $session_id = $_POST['session_id'];
        $course_id = $_POST['course_id'];
        $instructor_id = $_POST['instructor_id'];
        $semester_id = $_POST['semester_id'];
        $capacity = $_POST['capacity'];
        $schedules = json_decode($_POST['schedules'], true);
        $is_active = $_POST['is_active'];
        $created_by = $id;

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM class_sessions WHERE course_id = ? AND instructor_id = ? AND semester_id = ? AND session_id != ?");
        $stmt->execute([$course_id, $instructor_id, $semester_id, $session_id]);
        if($stmt->fetchColumn() > 0) {
            return 1;
        }

        $stmt = $this->pdo->prepare("UPDATE class_sessions SET course_id = ?, instructor_id = ?, semester_id = ?, capacity = ? WHERE session_id = ?");
        $result = $stmt->execute([$course_id, $instructor_id, $semester_id, $capacity, $session_id]);

        if(!$result) {
            return 2;
        }

        $sql = "DELETE FROM class_schedules WHERE session_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([$session_id]);
        if(!$result) {
            return 3;
        }

        foreach($schedules as $schedule) {
            $day = $schedule['day'];
            $start_time = $schedule['start_time'];
            $end_time = $schedule['end_time'];
            $location = $schedule['location'];

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM class_schedules WHERE day = ? AND location = ? AND NOT (end_time <= ? OR start_time >= ?)");
            $stmt->execute([$day, $location, $start_time, $end_time]);
            $count = $stmt->fetchColumn();

            if($count > 0) {
                return 4;
            }

            $stmt = $this->pdo->prepare("INSERT INTO class_schedules (session_id, day, start_time, end_time, location, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $result = $stmt->execute([$session_id, $day, $start_time, $end_time, $location, $created_by]);

            if(!$result) {
                return 5;
            }
        }

        return 0;
    }

    public function deleteClassSession() {
        $id = $_POST['session_id'];
        $stmt = $this->pdo->prepare("DELETE FROM class_schedules WHERE session_id = ?");
        $result = $stmt->execute([$id]);
        if(!$result) {
            return 1;
        }

        $stmt = $this->pdo->prepare("DELETE FROM enrollments WHERE session_id = ?");
        $result = $stmt->execute([$id]);
        if(!$result) {
            return 2;
        }

        $stmt = $this->pdo->prepare("DELETE FROM class_sessions WHERE session_id = ?");
        $result = $stmt->execute([$id]);
        if(!$result) {
            return 3;
        }

        return 0;
    }
}
?>