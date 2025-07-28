-- Create semesters table if it doesn't exist
CREATE TABLE IF NOT EXISTS `semesters` (
  `semester_id` int(11) NOT NULL AUTO_INCREMENT,
  `semester_code` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`semester_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create categories table if it doesn't exist
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Add total_credit column to programmes table if it doesn't exist
ALTER TABLE `programmes` ADD COLUMN IF NOT EXISTS `total_credit` int(11) DEFAULT NULL AFTER `duration`; 