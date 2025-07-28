-- Add missing fields to courses table
ALTER TABLE `courses` 
ADD COLUMN `category_id` int(11) DEFAULT NULL AFTER `prog_id`,
ADD COLUMN `is_active` tinyint(1) DEFAULT 1 AFTER `category_id`;

-- Add foreign key constraint for category_id
ALTER TABLE `courses` 
ADD CONSTRAINT `fk_courses_category` 
FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- Add foreign key constraint for prog_id (if not already exists)
ALTER TABLE `courses` 
ADD CONSTRAINT `fk_courses_programme` 
FOREIGN KEY (`prog_id`) REFERENCES `programmes`(`prog_id`) 
ON DELETE RESTRICT ON UPDATE CASCADE; 