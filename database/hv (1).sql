-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 03:35 AM
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
-- Database: `hv`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertDoctor` (IN `p_name` VARCHAR(255), IN `p_username` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_passwd` VARCHAR(255), IN `p_specialization` VARCHAR(100), IN `p_license_number` VARCHAR(50), IN `p_contact` VARCHAR(15))   BEGIN
    DECLARE hashed_passwd VARCHAR(255);
    DECLARE new_user_id INT;
    
    SET hashed_passwd = SHA(p_passwd);
    
    INSERT INTO Users (name, username, email, password, role) 
    VALUES (p_name, p_username, p_email, hashed_passwd, 'doctor');
    
    SET new_user_id = LAST_INSERT_ID();
    
    INSERT INTO Doctors (doctor_id, specialization, license_number, contact_number) 
    VALUES (new_user_id, p_specialization, p_license_number, p_contact);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertPatient` (IN `p_name` VARCHAR(255), IN `p_username` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_passwd` VARCHAR(255), IN `p_dob` DATE, IN `p_Gender` ENUM('Male','Female'), IN `p_contact` VARCHAR(15))   BEGIN
    DECLARE hashed_passwd VARCHAR(255);
    DECLARE new_user_id INT;
    
    SET hashed_passwd = SHA(p_passwd);
    
    INSERT INTO Users (name, username, email, password, role) 
    VALUES (p_name, p_username, p_email, hashed_passwd, 'patient');
    
    SET new_user_id = LAST_INSERT_ID();
    
    INSERT INTO Patients (patient_id, date_of_birth, gender, contact_number) 
    VALUES (new_user_id, p_dob, p_Gender, p_contact);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UserLogin` (IN `p_username` VARCHAR(50), IN `p_passwd` VARCHAR(255))   BEGIN
    DECLARE user_role ENUM('patient', 'doctor');

    -- Check if the user exists and fetch their role
    SELECT role INTO user_role 
    FROM Users 
    WHERE username = p_username AND password = SHA(p_passwd);
    
    -- If the user is a doctor, retrieve their full details
    IF user_role = 'doctor' THEN
        SELECT u.*, d.*
        FROM Users u
        JOIN Doctors d ON u.user_id = d.doctor_id
        WHERE u.username = p_username;
        
    -- If the user is a patient, retrieve their full details
    ELSEIF user_role = 'patient' THEN
        SELECT u.*, p.*
        FROM Users u
        JOIN Patients p ON u.user_id = p.patient_id
        WHERE u.username = p_username;
        
    ELSE
        -- Return an empty result if login fails
        SELECT '0' AS message;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `diagnosticresults`
--

CREATE TABLE `diagnosticresults` (
  `result_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `diagnosis` varchar(255) NOT NULL,
  `confidence` decimal(5,2) DEFAULT NULL,
  `status` enum('Pending','Reviewed') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diagnosticresults`
--

INSERT INTO `diagnosticresults` (`result_id`, `image_id`, `diagnosis`, `confidence`, `status`, `created_at`, `delete_flag`) VALUES
(1, 1, 'Breast Cancer Detected', 95.50, 'Reviewed', '2025-03-07 01:09:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 'Breast Cancer', 'Breast cancer is a type of cancer that forms in the cells of the breasts. It is one of the most common cancers among women worldwide. Early detection through mammograms can significantly improve treatment outcomes.', 1, 0, '2025-03-08 01:29:05', '2025-03-08 01:29:05'),
(2, 'Pneumonia', 'Pneumonia is an infection that affects one or both lungs. It causes the air sacs, or alveoli, of the lungs to fill up with fluid or pus. Bacteria, viruses, or fungi may cause pneumonia. Symptoms can range from mild to serious and may include a cough with or without mucus (a slimy substance), fever, chills, and trouble breathing. How serious your pneumonia is depends on your age, your overall health, and what caused your infection.', 1, 0, '2025-03-08 01:29:05', '2025-03-08 01:45:29');

-- --------------------------------------------------------

--
-- Table structure for table `doctorreviews`
--

CREATE TABLE `doctorreviews` (
  `review_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `is_approved` tinyint(1) NOT NULL,
  `review_comments` text DEFAULT NULL,
  `reviewed_at` datetime DEFAULT current_timestamp(),
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctorreviews`
--

INSERT INTO `doctorreviews` (`review_id`, `doctor_id`, `result_id`, `is_approved`, `review_comments`, `reviewed_at`, `delete_flag`) VALUES
(1, 3, 1, 1, 'Confirmed diagnosis, further tests recommended', '2025-03-07 01:09:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `license_number` varchar(50) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `specialization`, `license_number`, `contact_number`) VALUES
(3, 'Radiology', 'DOC12345', '+966500000003'),
(4, 'Pulmonology', 'DOC67890', '+966500000004'),
(6, 'Radiology', 'DOC1211', '0569876543');

-- --------------------------------------------------------

--
-- Table structure for table `medicalimages`
--

CREATE TABLE `medicalimages` (
  `image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_type` enum('Mammogram','Chest X-ray') NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `title` text NOT NULL,
  `image_path` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicalimages`
--

INSERT INTO `medicalimages` (`image_id`, `user_id`, `image_type`, `uploaded_at`, `delete_flag`, `status`, `title`, `image_path`) VALUES
(1, 1, 'Chest X-ray', '2025-03-07 01:09:17', 0, 1, 'image_2', 'uploads/images/Screenshot 2025-03-03 234329.jpg'),
(12, 1, 'Chest X-ray', '2025-04-18 19:15:32', 1, 0, 'samia image', 'uploads/images/img_1744999030_1.jpg'),
(13, 1, 'Chest X-ray', '2025-04-18 20:11:50', 1, 0, 'amani3', 'uploads/images/img_1744998965_1.jpg'),
(14, 1, 'Mammogram', '2025-04-18 20:57:53', 1, 0, 'test2', 'uploads/images/img_1744999073_1.jpg'),
(15, 1, 'Mammogram', '2025-04-18 21:20:46', 1, 0, 'test3', 'uploads/images/c.png'),
(16, 1, 'Mammogram', '2025-04-18 21:44:30', 0, 0, 'test', 'uploads/images/settings.jpg'),
(27, 2, 'Mammogram', '2025-04-20 04:15:21', 0, 0, 'test2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `date_of_birth`, `gender`, `contact_number`) VALUES
(1, '1985-06-15', 'Male', '+966500000001'),
(2, '1990-09-20', 'Female', '+966500000002'),
(5, '1990-05-15', 'Male', '0551234567');

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `recommendation_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `recommendation` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recommendations`
--

INSERT INTO `recommendations` (`recommendation_id`, `doctor_id`, `patient_id`, `result_id`, `recommendation`, `created_at`, `updated_at`, `delete_flag`) VALUES
(1, 3, 1, 1, 'Schedule biopsy and further evaluation', '2025-03-07 01:09:23', '2025-03-07 01:09:23', 0);

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
(1, 'name', 'HealthVision'),
(6, 'short_name', 'HV'),
(11, 'logo', 'uploads/logo.png?v=1741385753'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover.png?v=1741385832');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('patient','doctor') NOT NULL DEFAULT 'patient',
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `name` varchar(255) DEFAULT NULL,
  `avatar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `delete_flag`, `created_at`, `name`, `avatar`) VALUES
(1, 'p1', 'p1@example.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'patient', 0, '2025-03-07 01:08:55', 'name1', NULL),
(2, 'p2', 'p2@example.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'patient', 0, '2025-03-07 01:08:55', 'name2', NULL),
(3, 'd1', 'd1@example.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'doctor', 0, '2025-03-07 01:08:55', 'name3', NULL),
(4, 'd2', 'd2@example.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'doctor', 0, '2025-03-07 01:08:55', 'name4', NULL),
(5, 'patient1', 'patient1@example.com', 'd43a398e06cbf1c5b84e3afacb07de6e0f759e6f', 'patient', 0, '2025-03-07 02:00:17', 'name5', NULL),
(6, 'doctor1', 'doctor1@example.com', '643761b6fc16b7e21cbd2e4887a979da53ceb68a', 'doctor', 0, '2025-03-07 02:16:43', 'name6', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diagnosticresults`
--
ALTER TABLE `diagnosticresults`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `image_id` (`image_id`);

--
-- Indexes for table `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorreviews`
--
ALTER TABLE `doctorreviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `result_id` (`result_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `license_number` (`license_number`);

--
-- Indexes for table `medicalimages`
--
ALTER TABLE `medicalimages`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`recommendation_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `result_id` (`result_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diagnosticresults`
--
ALTER TABLE `diagnosticresults`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctorreviews`
--
ALTER TABLE `doctorreviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `medicalimages`
--
ALTER TABLE `medicalimages`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `recommendation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diagnosticresults`
--
ALTER TABLE `diagnosticresults`
  ADD CONSTRAINT `diagnosticresults_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `medicalimages` (`image_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctorreviews`
--
ALTER TABLE `doctorreviews`
  ADD CONSTRAINT `doctorreviews_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctorreviews_ibfk_2` FOREIGN KEY (`result_id`) REFERENCES `diagnosticresults` (`result_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `medicalimages`
--
ALTER TABLE `medicalimages`
  ADD CONSTRAINT `medicalimages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD CONSTRAINT `recommendations_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recommendations_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recommendations_ibfk_3` FOREIGN KEY (`result_id`) REFERENCES `diagnosticresults` (`result_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
