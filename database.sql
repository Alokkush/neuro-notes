-- Database: online_notes_db

CREATE DATABASE IF NOT EXISTS online_notes_db;
USE online_notes_db;

-- Table structure for table `users`
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL DEFAULT 'student',
  `course` varchar(100) NOT NULL,
  `semester` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `subjects`
CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `semester` varchar(50) NOT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `notes`
CREATE TABLE `notes` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`note_id`),
  FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`subject_id`) ON DELETE CASCADE,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `downloads`
CREATE TABLE `downloads` (
  `download_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `download_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`download_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`note_id`) REFERENCES `notes`(`note_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for subjects
INSERT INTO `subjects` (`subject_name`, `course`, `semester`) VALUES
('Mathematics', 'B.Tech', 'Semester 1'),
('Physics', 'B.Tech', 'Semester 1'),
('Chemistry', 'B.Tech', 'Semester 1'),
('Computer Programming', 'B.Tech', 'Semester 1'),
('English', 'B.Tech', 'Semester 1');

-- Insert sample admin user (password is 'admin123')
INSERT INTO `users` (`name`, `email`, `password`, `role`, `course`, `semester`) VALUES
('Admin User', 'admin@example.com', '$2y$10$4ew9HnMN3jQ/M3Ly2FXEseRKL5NxFNnCxRyO2/RPuuc/DpVzHuBbG', 'admin', 'Administration', 'N/A');