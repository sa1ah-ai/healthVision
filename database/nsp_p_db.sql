-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 12:53 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `odfs_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertEvent` (IN `p_title` VARCHAR(255), IN `p_description` TEXT, IN `p_event_date` DATETIME, IN `p_college_id` INT, IN `p_created_by` INT)   BEGIN
    INSERT INTO `events` (`title`, `description`, `event_date`, `college_id`, `created_by`) 
    VALUES (p_title, p_description, p_event_date, p_college_id, p_created_by);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `specialization_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`, `specialization_id`) VALUES
(1, 'PHP', 'PHP is an open-source server-side scripting language that many devs use for web development. It is also a general-purpose language that you can use to make lots of projects, including Graphical User Interfaces (GUIs).', 1, 0, '2025-02-16 10:02:41', '2025-03-03 01:33:07', 3),
(2, 'VB.NET', 'Visual Basic, originally called Visual Basic .NET, is a multi-paradigm, object-oriented programming language, implemented on .NET, Mono, and the .NET Framework. Microsoft launched VB.NET in 2002 as the successor to its original Visual Basic language.', 1, 0, '2025-02-16 10:03:27', '2025-03-03 01:33:20', 4),
(3, 'Python', 'Python is a high-level, interpreted, general-purpose programming language. Its design philosophy emphasizes code readability with the use of significant indentation. Python is dynamically-typed and garbage-collected.', 1, 0, '2025-02-16 10:03:48', '2025-03-03 01:33:14', 2),
(4, 'JavaScript', 'JavaScript, often abbreviated JS, is a programming language that is one of the core technologies of the World Wide Web, alongside HTML and CSS. Over 97% of websites use JavaScript on the client side for web page behavior, often incorporating third-party libraries.', 1, 0, '2025-02-16 10:04:11', '2025-03-03 01:33:10', 3),
(5, 'test', 'test', 1, 1, '2025-02-16 10:04:54', '2025-02-17 10:04:59', NULL),
(6, 'Web Development', 'Courses and resources for learning web development technologies.', 1, 0, '2025-03-03 01:56:01', '2025-03-03 01:56:01', 2),
(7, 'Data Science', 'Resources for learning data science concepts and tools.', 1, 0, '2025-03-03 01:56:01', '2025-03-03 01:56:01', 4),
(8, 'Artificial Intelligence', 'A collection of resources for understanding AI concepts.', 1, 0, '2025-03-03 01:56:01', '2025-03-03 01:56:01', 1),
(9, 'Machine Learning', 'Materials focused on machine learning techniques and frameworks.', 1, 0, '2025-03-03 01:56:01', '2025-03-03 01:56:01', 3),
(10, 'Software Engineering', 'Comprehensive resources for software engineering principles.', 1, 0, '2025-03-03 01:56:01', '2025-03-03 01:56:01', 5);

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `college_id` int(11) NOT NULL,
  `college_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`college_id`, `college_name`) VALUES
(1, 'Computer Science'),
(2, 'Engineering'),
(3, 'Business'),
(4, 'Medicine'),
(5, 'Education');

-- --------------------------------------------------------

--
-- Table structure for table `comment_list`
--

CREATE TABLE `comment_list` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `post_id` int(30) NOT NULL,
  `comment` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment_list`
--

INSERT INTO `comment_list` (`id`, `user_id`, `post_id`, `comment`, `date_created`) VALUES
(1, 4, 2, 'Test Comment 123', '2025-02-16 12:05:21'),
(2, 4, 2, '<p>This is a sample comment only</p>', '2025-02-16 13:00:42'),
(4, 4, 3, '<p>test 123</p>', '2025-02-16 13:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `college_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `college_id`) VALUES
(1, 'Computer Science', 1),
(2, 'Information Systems', 1),
(3, 'Software Engineering', 1),
(4, 'Electrical Engineering', 2),
(5, 'Mechanical Engineering', 2),
(6, 'Civil Engineering', 2),
(7, 'Business Administration', 3),
(8, 'Accounting', 3),
(9, 'Finance', 3),
(10, 'General Medicine', 4),
(11, 'Nursing', 4),
(12, 'Educational Technology', 5),
(13, 'Curriculum & Instruction', 5);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` datetime NOT NULL DEFAULT current_timestamp(),
  `college_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `description`, `event_date`, `college_id`, `created_by`, `date_added`, `date_updated`, `delete_flag`) VALUES
(1, 'AI Symposium', 'Discussion on advancements in AI', '2025-03-15 10:00:00', 1, 1, '2025-02-26 22:06:18', '2025-02-28 02:42:34', 0),
(2, 'Cybersecurity Workshop', 'Hands-on training in cybersecurity practices', '2025-04-10 14:00:00', 1, 1, '2025-02-26 22:06:18', '2025-02-26 22:06:18', 0),
(3, 'sdgfsdg', 'sdfgsdgf', '2025-02-19 03:21:26', 4, 4, '2025-02-28 03:25:21', '2025-02-28 20:50:33', 1),
(4, 'sdgfsdg', 'sddgf', '2025-02-19 03:21:26', 4, 1, '2025-02-28 03:27:24', '2025-02-28 20:50:37', 1),
(5, 'fhgkj', 'cvber', '2025-03-01 12:55:00', 2, 1, '2025-02-28 20:50:51', '2025-02-28 20:50:51', 0),
(6, 'sdf', 'asdfasdfasdf', '2025-03-07 14:52:00', 1, 1, '2025-02-28 20:52:16', '2025-02-28 20:54:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `post_list`
--

CREATE TABLE `post_list` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_list`
--

INSERT INTO `post_list` (`id`, `user_id`, `category_id`, `title`, `content`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 4, 1, 'Sample Topic Title', '<p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur fringilla bibendum urna ac eleifend. Quisque in est eu ipsum blandit accumsan ultrices nec tortor. Aliquam lacinia, ex sit amet iaculis sollicitudin, urna odio tempor nulla, eu lacinia augue mi a felis. Quisque finibus in arcu sed ultricies. Duis eleifend metus consectetur vulputate bibendum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut interdum libero vitae nisi finibus, non varius quam volutpat. Cras non iaculis neque. Integer bibendum sagittis dignissim. Ut aliquet suscipit velit sit amet hendrerit. Sed mattis pellentesque augue id bibendum. Phasellus quis justo ornare, faucibus arcu at, ullamcorper lectus.</p><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px;\">Nulla a nisl quis tellus volutpat lacinia. Nullam et eros ac mi dapibus ornare. Suspendisse sit amet purus mattis, ullamcorper nulla sit amet, euismod urna. Fusce ac pulvinar velit. Vivamus tellus nibh, pretium eu consectetur et, hendrerit eu elit. Proin et augue ultricies, euismod augue a, varius nibh. Donec condimentum justo erat, non cursus mi pharetra vel. Cras pretium nulla quis justo venenatis, vitae aliquet justo dapibus. Maecenas efficitur viverra tellus, vestibulum pharetra est dictum at. Aenean mauris tellus, luctus vitae odio sit amet, sagittis faucibus nisl. Aliquam in dignissim sapien, nec sagittis lorem. Donec facilisis vulputate purus vitae congue. Nunc eros risus, congue id nisi nec, hendrerit tristique sem. Phasellus sodales nunc arcu, nec ultricies tellus tincidunt et.</p>', 1, 0, '2025-02-16 11:13:02', '2025-02-17 13:57:01'),
(2, 4, 1, 'Topic 2 - Updated', '<p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px;\">Quisque et commodo sem, sed aliquam justo. In varius erat purus, sit amet fermentum sapien semper sed. Quisque consequat blandit est eget gravida. Aliquam venenatis, libero eget hendrerit auctor, arcu dui interdum diam, ac hendrerit lacus eros ut sapien. Aenean commodo luctus metus eget vestibulum. Vestibulum nec convallis nulla, porttitor aliquet justo. In quis augue non ligula commodo tempus. Fusce ex ex, blandit sit amet lorem quis, pharetra aliquam leo. Mauris consequat vel mauris vitae consequat. Donec a enim ac erat malesuada varius non eget erat. Aliquam erat volutpat.</p><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px;\">Fusce quis nisi at libero sodales pretium. Proin interdum, nisl quis ornare malesuada, nisi erat vestibulum nisi, nec egestas leo orci vel mauris. Ut quis varius orci. Vivamus nec vulputate purus. Sed ut magna euismod, accumsan arcu non, sagittis purus. Ut tempor elit at scelerisque eleifend. Morbi pharetra est in nibh eleifend, nec sagittis orci posuere. In malesuada, libero sit amet rutrum accumsan, quam leo ultricies augue, a maximus leo massa sit amet diam. Nunc dictum orci dui, vitae condimentum ex porta in. Ut sodales posuere mollis. Sed at sem pellentesque ligula commodo blandit. Ut sem velit, vulputate at porttitor vel, rutrum sit amet velit. Nunc ultrices, felis lacinia lobortis pharetra, purus quam porta massa, sed hendrerit arcu mi in magna. In dignissim urna sit amet mi pharetra, eu molestie libero rhoncus. Sed sit amet ipsum accumsan libero ullamcorper egestas.</p><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px;\">Aliquam et tincidunt eros. Pellentesque ut luctus leo, nec fermentum velit. Vestibulum a justo et ligula hendrerit laoreet vitae et nunc. Pellentesque commodo dignissim justo, rutrum dictum est euismod vel. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nunc convallis arcu nec ullamcorper gravida. Phasellus ullamcorper nisi euismod tellus convallis, a aliquet ex commodo. Vivamus cursus elit purus, eu tristique lorem congue nec. Suspendisse tincidunt commodo purus, eget pharetra ipsum.</p><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: \"Open Sans\", Arial, sans-serif; font-size: 14px;\">Morbi cursus tincidunt ex vel elementum. Suspendisse et suscipit quam, vel interdum elit. Nullam mollis massa nisi, id consectetur nibh sodales vel. Pellentesque lobortis dignissim odio, vitae hendrerit dolor sollicitudin sit amet. Suspendisse ut leo id ex interdum ornare eget eu ex. Fusce laoreet erat in leo venenatis scelerisque. Aliquam laoreet sem ipsum, ac euismod justo egestas a. Ut facilisis posuere ante, sit amet tincidunt augue pretium vitae. Curabitur ullamcorper venenatis felis, ac pharetra velit varius vitae. Quisque et dignissim orci. Mauris non felis nec ligula posuere dignissim. Vivamus semper lacinia magna sed mollis. Maecenas a euismod lectus.</p>', 1, 0, '2025-02-16 11:25:21', '2025-02-17 13:56:52'),
(3, 4, 2, 'test', '<p>Data to delete</p>', 1, 1, '2025-02-16 13:52:36', '2025-02-17 13:54:05'),
(4, 1, 1, 'test', '<p>test</p>', 1, 1, '2025-02-16 14:11:24', '2025-02-17 14:12:10'),
(5, 4, 4, 'about something', '<p>I just wanted to ask you about the JavaScript Language</p>', 1, 0, '2025-02-24 20:08:49', '2025-02-24 20:09:15');

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

CREATE TABLE `sources` (
  `source_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `source_type` enum('file','link','other') DEFAULT 'link',
  `url` text DEFAULT NULL,
  `file_data` longblob DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `specialization_id` int(11) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sources`
--

INSERT INTO `sources` (`source_id`, `title`, `source_type`, `url`, `file_data`, `added_by`, `approved_by`, `specialization_id`, `is_approved`, `date_added`, `delete_flag`, `subject_id`) VALUES
(5, 'Machine Learning Book', 'link', 'https://mlbook.com', NULL, 1, 1, 1, 1, '2025-02-26 21:05:59', 0, 1),
(6, 'Cybersecurity Guide', 'link', 'https://cybersec-guide.com', NULL, 1, 1, 2, 1, '2025-02-26 21:05:59', 0, 2),
(7, 'Introduction to Web Development', 'link', 'https://www.w3schools.com', NULL, 1, 1, 1, 1, '2025-03-03 01:52:50', 0, 12),
(8, 'Data Science Handbook', 'link', 'https://www.datasciencehandbook.com', NULL, 1, 1, 2, 1, '2025-03-03 01:52:50', 0, 13),
(9, 'AI Basics', 'link', 'https://www.aibasics.com', NULL, 1, 1, 3, 1, '2025-03-03 01:52:50', 0, 14),
(10, 'Machine Learning Crash Course', 'link', 'https://www.mlcourse.com', NULL, 1, 1, 4, 1, '2025-03-03 01:52:50', 0, 15),
(11, 'Software Engineering Principles', 'link', 'https://www.softwareengineering.com', NULL, 1, 1, 5, 1, '2025-03-03 01:52:50', 0, 16),
(12, 'testtitle', 'link', 'https://www.blindtextgenerator.com/lorem-ipsum', NULL, 4, NULL, 4, 0, '2025-03-05 00:56:35', 1, 1),
(13, 'sgf', 'link', 'http://localhost/AdminLTE-4.0.0-beta3/dist/pages/generate/theme.html', NULL, 4, NULL, 1, 0, '2025-03-05 01:15:54', 1, 1),
(14, 'ghgh', 'link', 'http://localhost/AdminLTE-4.0.0-beta3/dist/pages/generate/theme.html', NULL, 4, NULL, 1, 0, '2025-03-05 01:26:05', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `specialization_id` int(11) NOT NULL,
  `specialization_name` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`specialization_id`, `specialization_name`, `department_id`) VALUES
(1, 'Artificial Intelligence', 1),
(2, 'Cyber Security', 1),
(3, 'Data Science', 1),
(4, 'Information Management', 2),
(5, 'Software Development', 3),
(6, 'Power Systems', 4),
(7, 'Structural Engineering', 6),
(8, 'Financial Analysis', 9),
(9, 'Surgical Studies', 10),
(10, 'Teaching Methodologies', 12);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(50) NOT NULL,
  `subject_explanation` varchar(255) DEFAULT NULL,
  `subject_file` longblob DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `specialization_id` int(11) DEFAULT NULL,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_code`, `subject_name`, `subject_explanation`, `subject_file`, `added_by`, `specialization_id`, `delete_flag`, `date_added`) VALUES
(1, 'CS101', 'Introduction to Programming', 'Basic programming concepts using Python.', NULL, 1, 1, 0, '2025-03-03 02:13:55'),
(2, 'CS201', 'Data Structures', 'Study of different data structures and their applications.', NULL, 1, 2, 0, '2025-03-03 02:13:55'),
(3, 'SE301', 'Software Testing', 'Techniques and tools for software testing.', NULL, 1, 3, 0, '2025-03-03 02:13:55'),
(7, 'ITEC-433', 'Neural Network', 'Deep Learning Techniques, Including CNN, RNN,', NULL, 1, 1, 0, '2025-03-03 02:13:55'),
(8, 'ITEC-433', 'Neural Network', 'Deep Learning Techniques, Including CNN, RNN,', NULL, 1, 5, 0, '2025-03-03 02:13:55'),
(12, 'WD101', 'Introduction to Web Development', 'Learn the basics of web development including HTML, CSS, and JavaScript.', NULL, 1, 1, 0, '2025-03-03 02:13:55'),
(13, 'DS201', 'Data Science Foundations', 'An overview of the fundamental concepts in data science.', NULL, 1, 2, 0, '2025-03-03 02:13:55'),
(14, 'AI301', 'Principles of Artificial Intelligence', 'Explore the fundamental principles that guide AI technologies.', NULL, 1, 3, 0, '2025-03-03 02:13:55'),
(15, 'ML401', 'Advanced Machine Learning', 'In-depth exploration of machine learning algorithms and applications.', NULL, 1, 4, 0, '2025-03-03 02:13:55'),
(16, 'SE501', 'Software Engineering Methodologies', 'Study various software engineering methodologies and their applications.', NULL, 1, 5, 0, '2025-03-03 02:13:55');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'New Student Portal'),
(6, 'short_name', 'NSP'),
(11, 'logo', 'uploads/logo.svg?v=1652665183'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover.png?v=1652665183');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mark`
--

CREATE TABLE `tbl_mark` (
  `tbl_mark_id` int(11) NOT NULL,
  `mark_name` varchar(255) NOT NULL,
  `mark_long` double NOT NULL,
  `mark_lat` double NOT NULL,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `specialization_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='2';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`, `specialization_id`) VALUES
(1, 'ali', '', 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatars/1.png?v=1649834664', NULL, 1, '2021-01-20 14:02:37', '2025-03-03 06:07:49', 1),
(4, 'fahd', '', 'Al-Adhadi', 'fhd', '202cb962ac59075b964b07152d234b70', 'uploads/avatars/4.png?v=1652667135', NULL, 2, '2022-05-16 10:12:15', '2025-03-03 00:19:47', 2),
(5, 'Saud ', 'D', 'Al-Asmari', 'sud', '1254737c076cf867dc53d60a0364f38e', 'uploads/avatars/5.png?v=1740590222', NULL, 2, '2022-05-16 14:19:03', '2025-03-03 00:19:49', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`college_id`);

--
-- Indexes for table `comment_list`
--
ALTER TABLE `comment_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `college_id` (`college_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `post_list`
--
ALTER TABLE `post_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`source_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `specialization_id` (`specialization_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`specialization_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_mark`
--
ALTER TABLE `tbl_mark`
  ADD PRIMARY KEY (`tbl_mark_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `college_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comment_list`
--
ALTER TABLE `comment_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_list`
--
ALTER TABLE `post_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sources`
--
ALTER TABLE `sources`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `specialization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_mark`
--
ALTER TABLE `tbl_mark`
  MODIFY `tbl_mark_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment_list`
--
ALTER TABLE `comment_list`
  ADD CONSTRAINT `post_id_fk_cl` FOREIGN KEY (`post_id`) REFERENCES `post_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_id_fk_cl` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`college_id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`college_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_list`
--
ALTER TABLE `post_list`
  ADD CONSTRAINT `category_id_fk_tl` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_id_fk_tl` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `sources`
--
ALTER TABLE `sources`
  ADD CONSTRAINT `sources_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sources_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sources_ibfk_3` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`specialization_id`) ON DELETE CASCADE;

--
-- Constraints for table `specializations`
--
ALTER TABLE `specializations`
  ADD CONSTRAINT `specializations_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`specialization_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
