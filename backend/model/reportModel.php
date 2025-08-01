<?php

    class Report{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function getOverallEnrollment(){
            $where = '';
            $where_params = [];

            if(isset($_POST['semester_id']) && !empty($_POST['semester_id'])) {
                $semester_id = $_POST['semester_id'];
                $where .= " AND s.semester_id = ?";
                $where_params[] = $semester_id;

                $sql = "SELECT semester_code, start_date, end_date FROM semesters WHERE semester_id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$semester_id]);
                $semester = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if(isset($_POST['prog_id']) && !empty($_POST['prog_id'])) {
                $prog_id = $_POST['prog_id'];
                $where .= " AND c.prog_id = ?";
                $where_params[] = $prog_id;

                $sql = "SELECT prog_name FROM programmes WHERE prog_id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$prog_id]);
                $programme = $stmt->fetch(PDO::FETCH_ASSOC);
            }


            $sql = "
                SELECT 
                    c.course_code,
                    c.course_name,
                    i.instructor_name,
                    s.capacity AS total_seats,
                    COUNT(e.enrollment_id) AS enrolled_students,
                    (s.capacity - COUNT(e.enrollment_id)) AS vacant_seats,
                    ROUND((COUNT(e.enrollment_id) / s.capacity) * 100, 2) AS enrollment_percentage
                FROM 
                    class_sessions s
                JOIN
                    instructors i ON s.instructor_id = i.instructor_id
                JOIN 
                    courses c ON s.course_id = c.course_id
                LEFT JOIN 
                    enrollments e ON s.session_id = e.session_id AND e.status = 'enrolled'
                WHERE 
                    s.is_active = 1 AND c.is_active = 1 
                $where
                GROUP BY 
                    s.session_id, c.course_code, c.course_name;
            ";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($where_params);
            if(!$result) {
                return 1;
            }
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [$data, $semester, $programme];    
        }

        public function getStudentPerformance(){
            $where = '';
            $where_params = [];

            $user_id = $_POST['user_id'];
            $where_params[] = $user_id;

            // Check if semester_id is set and not null
            if(isset($_POST['semester_id']) && !empty($_POST['semester_id'])) {
                $semester_id = $_POST['semester_id'];
                $where .= " AND s.semester_id = ?";
                $where_params[] = $semester_id;
            }


            $sql = "
                SELECT 
                    c.course_code,
                    c.course_name,
                    c.credits,
                    e.final_grade,
                    sem.semester_code,
                    u.username AS student_name,
                    u.user_id AS student_id
                FROM enrollments e
                JOIN class_sessions s ON e.session_id = s.session_id
                JOIN semesters sem ON s.semester_id = sem.semester_id
                JOIN courses c ON s.course_id = c.course_id
                JOIN users u ON e.student_id = u.user_id
                WHERE e.status = 'enrolled' AND e.final_grade IS NOT NULL AND u.user_id = ? $where
                ORDER BY sem.semester_code, c.course_name ASC;
            ";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($where_params);

            

            if(!$result) {
                return 1;
            }

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sql = "
                SELECT
                    u.username,
                    u.user_id,
                    ROUND(SUM(
                        CASE 
                            WHEN e.is_completed = 1 AND e.final_grade IS NOT NULL THEN 
                                CASE e.final_grade
                                    WHEN 'A' THEN 4.00
                                    WHEN 'A-' THEN 3.67
                                    WHEN 'B+' THEN 3.33
                                    WHEN 'B' THEN 3.00
                                    WHEN 'B-' THEN 2.67
                                    WHEN 'C+' THEN 2.33
                                    WHEN 'C' THEN 2.00
                                    WHEN 'D' THEN 1.00
                                    WHEN 'F' THEN 0.00
                                    ELSE NULL
                                END * c.credits
                            ELSE NULL
                        END
                    ) / SUM(
                        CASE 
                            WHEN e.is_completed = 1 AND e.final_grade IS NOT NULL THEN c.credits
                            ELSE NULL
                        END
                    ), 2) AS cgpa
                FROM enrollments e
                JOIN class_sessions cs ON e.session_id = cs.session_id
                JOIN courses c ON cs.course_id = c.course_id
                JOIN users u ON e.student_id = u.user_id
                WHERE e.student_id = ?
                GROUP BY u.user_id;
            ";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$user_id]);
            if(!$result) {
                return 1;
            }
            $cgpa = $stmt->fetch(PDO::FETCH_ASSOC);

            return [$data, $cgpa];
        }

        public function getOverallAcademicProgress(){
            $where = '';
            $where_params = [];

            if(isset($_POST['prog_id']) && !empty($_POST['prog_id'])) {
                $prog_id = $_POST['prog_id'];
                $where .= " AND u.programme = ?";
                $where_params[] = $prog_id;
            }

            $sql = "
                SELECT 
                    u.user_id AS student_id,
                    u.username AS student_name,
                    p.prog_name,
                    p.prog_code,
                    SUM(c.credits) AS total_credits_completed,
                    ROUND((SUM(c.credits) / p.total_credit) * 100, 2) AS degree_progress_percentage,

                    ROUND(SUM(
                        CASE 
                            WHEN e.is_completed = 1 AND e.final_grade IS NOT NULL THEN 
                                CASE e.final_grade
                                    WHEN 'A' THEN 4.00
                                    WHEN 'A-' THEN 3.67
                                    WHEN 'B+' THEN 3.33
                                    WHEN 'B' THEN 3.00
                                    WHEN 'B-' THEN 2.67
                                    WHEN 'C+' THEN 2.33
                                    WHEN 'C' THEN 2.00
                                    WHEN 'D' THEN 1.00
                                    WHEN 'F' THEN 0.00
                                    ELSE NULL
                                END * c.credits
                            ELSE NULL
                        END
                    ) / SUM(
                        CASE 
                            WHEN e.is_completed = 1 AND e.final_grade IS NOT NULL THEN c.credits
                            ELSE NULL
                        END
                    ), 2) AS cgpa,

                    CASE
                        WHEN ROUND(SUM(
                            CASE 
                                WHEN e.is_completed = 1 AND e.final_grade IS NOT NULL THEN 
                                    CASE e.final_grade
                                        WHEN 'A' THEN 4.00
                                        WHEN 'A-' THEN 3.70
                                        WHEN 'B+' THEN 3.30
                                        WHEN 'B' THEN 3.00
                                        WHEN 'B-' THEN 2.70
                                        WHEN 'C+' THEN 2.30
                                        WHEN 'C' THEN 2.00
                                        WHEN 'D' THEN 1.00
                                        WHEN 'F' THEN 0.00
                                        ELSE NULL
                                    END * c.credits
                                ELSE NULL
                            END
                        ) / SUM(
                            CASE 
                                WHEN e.is_completed = 1 AND e.final_grade IS NOT NULL THEN c.credits
                                ELSE NULL
                            END
                        ), 2) >= 2.00 THEN 'Good'
                        ELSE 'Probation'
                    END AS academic_standing

                FROM 
                    users u
                JOIN 
                    enrollments e ON u.user_id = e.student_id AND e.status = 'enrolled'
                JOIN 
                    class_sessions s ON s.session_id = e.session_id
                JOIN 
                    courses c ON c.course_id = s.course_id
                JOIN 
                    programmes p ON p.prog_id = u.programme
                WHERE 
                    u.role = 'user'
                    AND e.is_completed = 1
                    $where
                GROUP BY 
                    u.user_id, u.username, p.prog_name;
            ";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($where_params);

            if(!$result) {
                return 1;
            }

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
    }