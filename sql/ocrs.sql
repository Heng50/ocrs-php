-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 12, 2025 at 04:32 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ocrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `class_schedules`
--

CREATE TABLE `class_schedules` (
  `schedule_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class_schedules`
--

INSERT INTO `class_schedules` (`schedule_id`, `session_id`, `day`, `start_time`, `end_time`, `location`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 1, 'Monday', '11:00:00', '13:00:00', 'A201', 1, '2025-07-12 20:07:16', '2025-07-12 20:14:58'),
(4, 2, 'Tuesday', '10:00:00', '12:00:00', 'A201', 1, '2025-07-12 20:58:01', '2025-07-12 20:58:01');

-- --------------------------------------------------------

--
-- Table structure for table `class_sessions`
--

CREATE TABLE `class_sessions` (
  `session_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `semester` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class_sessions`
--

INSERT INTO `class_sessions` (`session_id`, `course_id`, `instructor_id`, `semester`, `capacity`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'S1/25', 20, 1, '2025-07-12 17:20:52', '2025-07-12 17:20:52'),
(2, 2, 2, 'S1/25', 20, 1, '2025-07-12 20:58:01', '2025-07-12 20:58:01');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_code` varchar(100) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `credits` decimal(5,2) NOT NULL,
  `prog_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_name`, `description`, `credits`, `prog_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Web Design & Application', 'CFE4044', 'Learn to develop a web-based app', 3.00, 1, 1, '2025-07-12 16:28:06', '2025-07-12 16:28:26'),
(2, 'BSE1053', 'Introduction to Software Engineering', 'This is about the real like what software engineering look like', 3.00, 1, 1, '2025-07-12 20:55:35', '2025-07-12 20:55:35');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` enum('enrolled','pending','rejected','') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `session_id`, `student_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'pending', '2025-07-12 21:51:15', '2025-07-12 21:51:15');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int(11) NOT NULL,
  `semester_code` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `prog_id` int(11) NOT NULL,
  `prog_name` varchar(100) NOT NULL,
  `prog_code` varchar(100) NOT NULL,
  `prog_desc` text NOT NULL,
  `duration` decimal(5,2) NOT NULL,
  `total_credit` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`prog_id`, `prog_name`, `prog_code`, `prog_desc`, `duration`, `created_at`, `updated_at`) VALUES
(1, ' Bachelor of Software Engineering (Honours)', 'BSE', 'The Bachelor of Software Engineering (Honours) is a dynamic and forward-thinking program designed to equip students with the latest skills and knowledge essential for success in the ever-evolving field of software development. Offering a comprehensive curriculum, this program goes beyond the basics, fostering a mindset of innovation and problem-solving. Through hands-on, project-based learning and collaboration with industry experts, students gain practical experience, ensuring they are well-prepared for the demands of the professional software development landscape.', 3.00, '2025-07-12 16:07:46', '2025-07-12 22:38:41'),
(2, 'Bachelor of Business Administration', 'BBA', 'The Bachelor of Business Administration (Honours) programme provides the graduates with current insights on the world of business, and a glimpse into the future through a structured 3-year undergraduate curriculum. This programme is a broad-based programme that places equal emphasis on all of the various disciplines in the field of business management. This aims to meet the industrial demand for capable and knowledgeable business management graduates.', 3.00, '2025-07-12 16:09:24', '2025-07-12 16:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `status` enum('approved','pending','rejected') NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `status`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123@gmail.com', '$2y$10$kMiok/xVZtnBsb8OXOFJsOE/99tpJHV7IS/CbJlkdqbPgflDgHTem', 'admin', 'approved', 1, '2025-07-12 15:37:19', '2025-07-12 15:37:19'),
(2, 'lee', 'lee123@gmail.com', '$2y$10$YzJmHsUcK4FYJnx9shq4feXbMdr5bPDYunYn/.BxgM5bU9P1AaNY.', 'user', 'approved', 1, '2025-07-12 15:38:12', '2025-07-12 16:01:08'),
(3, 'hong', 'hong123@gmail.com', '$2y$10$RpbILVT4f98I1YPJ35vtdOlHr0JGWFMyiIVmHvnblyl7HlLCFBgka', 'user', 'approved', 1, '2025-07-12 15:41:31', '2025-07-12 16:01:46'),
(4, 'jan', 'jan123@gmail.com', '$2y$10$zDWO1L8/4xC/6Hj1NWUYPO5wVMmUXLwKOPQfjH9rkwL1524cA3cYO', 'user', 'approved', 1, '2025-07-12 15:46:00', '2025-07-12 20:56:43'),
(5, 'bryan', 'bryan123@gmail.com', '$2y$10$Qyx5S5SqQ0ViRs5.gyxvWuV6mwR6pwFVvFHLTV4P4DlPRSqQ1lBpO', 'user', 'pending', 0, '2025-07-12 15:46:42', '2025-07-12 15:46:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `class_sessions`
--
ALTER TABLE `class_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`prog_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_schedules`
--
ALTER TABLE `class_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `class_sessions`
--
ALTER TABLE `class_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `prog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
