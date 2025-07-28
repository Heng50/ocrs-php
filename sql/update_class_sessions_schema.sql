-- Update class_sessions table to match model expectations

-- 1. Add missing columns to class_sessions table
ALTER TABLE `class_sessions` 
ADD COLUMN `semester_id` int(11) DEFAULT NULL AFTER `instructor_id`,
ADD COLUMN `is_active` tinyint(1) DEFAULT 1 AFTER `semester_id`;

-- 2. Create instructors table
CREATE TABLE `instructors` (
  `instructor_id` int(11) NOT NULL AUTO_INCREMENT,
  `instructor_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `description` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`instructor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3. Add some sample instructors (migrate from users table)
INSERT INTO `instructors` (`instructor_name`, `email`, `description`, `created_at`, `updated_at`)
SELECT username, email, 'Migrated from users table', NOW(), NOW()
FROM users WHERE role = 'user';

-- 4. Update class_sessions to use instructor_id from instructors table
-- First, create a mapping from users to instructors
UPDATE class_sessions cs
JOIN users u ON cs.instructor_id = u.user_id
JOIN instructors i ON u.username = i.instructor_name
SET cs.instructor_id = i.instructor_id;

-- 5. Update class_sessions to use semester_id from semesters table
-- First, create some sample semesters if they don't exist
INSERT INTO `semesters` (`semester_code`, `start_date`, `end_date`, `status`, `created_by`, `created_at`, `updated_at`) 
VALUES 
('S1/25', '2025-01-01', '2025-06-30', 'active', '1', NOW(), NOW()),
('S2/25', '2025-07-01', '2025-12-31', 'active', '1', NOW(), NOW())
ON DUPLICATE KEY UPDATE semester_code = semester_code;

-- 6. Map existing semester text to semester_id
UPDATE class_sessions cs
JOIN semesters s ON cs.semester = s.semester_code
SET cs.semester_id = s.semester_id;

-- 7. Add foreign key constraints
ALTER TABLE `class_sessions` 
ADD CONSTRAINT `fk_class_sessions_instructor` 
FOREIGN KEY (`instructor_id`) REFERENCES `instructors`(`instructor_id`) 
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `class_sessions` 
ADD CONSTRAINT `fk_class_sessions_semester` 
FOREIGN KEY (`semester_id`) REFERENCES `semesters`(`semester_id`) 
ON DELETE RESTRICT ON UPDATE CASCADE;

-- 8. Drop the old semester column (after ensuring data is migrated)
-- ALTER TABLE `class_sessions` DROP COLUMN `semester`; 