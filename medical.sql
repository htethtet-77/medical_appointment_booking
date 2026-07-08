-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Sep 21, 2025 at 01:17 PM
-- Server version: 8.0.42
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medical`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`%` PROCEDURE `add_doctor` (IN `p_name` VARCHAR(255), IN `p_email` VARCHAR(255), IN `p_phone` VARCHAR(20), IN `p_gender` VARCHAR(10), IN `p_password` VARCHAR(255), IN `p_profile_image` VARCHAR(255), IN `p_degree` VARCHAR(255), IN `p_experience` INT, IN `p_bio` TEXT, IN `p_fee` DECIMAL(10,2), IN `p_specialty` VARCHAR(255), IN `p_address` TEXT, IN `p_start_time` TIME, IN `p_end_time` TIME)   BEGIN
    DECLARE new_user_id INT;

    -- Insert into users
    INSERT INTO users (
        name, email, phone, gender, password, profile_image,
        is_login, is_active, is_confirmed, type_id, status_id
    ) VALUES (
        p_name, p_email, p_phone, p_gender, p_password, p_profile_image,
        0, 0, 0, 2, 6
    );

    SET new_user_id = LAST_INSERT_ID();

    -- Insert into doctorprofile
    INSERT INTO doctorprofile (
        user_id, degree, experience, bio, fee, specialty, address
    ) VALUES (
        new_user_id, p_degree, p_experience, p_bio, p_fee, p_specialty, p_address
    );

    -- Insert into timeslots
    INSERT INTO timeslots (
        user_id, start_time, end_time
    ) VALUES (
        new_user_id, p_start_time, p_end_time
    );

    -- Return created user ID
    SELECT new_user_id AS user_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `book_appointment` (IN `in_doctor_id` INT, IN `in_user_id` INT, IN `in_timeslot_id` INT, IN `in_appointment_date` DATE, IN `in_appointment_time` TIME, IN `in_reason` TEXT, IN `in_status_id` INT)   BEGIN
    INSERT INTO appointment (
        doctor_id,
        user_id,
        timeslot_id,
        appointment_date,
        appointment_time,
        reason,
        status_id,
        created_at
    ) VALUES (
        in_doctor_id,
        in_user_id,
        in_timeslot_id,
        in_appointment_date,
        in_appointment_time,
        in_reason,
        in_status_id,
        NOW()
    );
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `delete_appointment` (IN `in_appointment_id` INT)   BEGIN
    DELETE FROM appointment WHERE id = in_appointment_id;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `update_doctor` (IN `p_user_id` INT, IN `p_name` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_phone` VARCHAR(20), IN `p_gender` VARCHAR(10), IN `p_password` VARCHAR(255), IN `p_profile_image` VARCHAR(255), IN `p_degree` VARCHAR(100), IN `p_experience` INT, IN `p_bio` TEXT, IN `p_fee` DECIMAL(10,2), IN `p_specialty` VARCHAR(100), IN `p_address` TEXT, IN `p_start_time` TIME, IN `p_end_time` TIME)   BEGIN
    START TRANSACTION;

    -- 1. Update users table
    UPDATE users
    SET name = p_name,
        email = p_email,
        phone = p_phone,
        gender = p_gender,
        password = p_password,
        profile_image = p_profile_image
    WHERE id = p_user_id;

    -- 2. Update or Insert doctorprofile
    IF EXISTS (SELECT 1 FROM doctorprofile WHERE user_id = p_user_id) THEN
        UPDATE doctorprofile
        SET degree = p_degree,
            experience = p_experience,
            bio = p_bio,
            fee = p_fee,
            specialty = p_specialty,
            address = p_address
        WHERE user_id = p_user_id;
    ELSE
        INSERT INTO doctorprofile(user_id, degree, experience, bio, fee, specialty, address)
        VALUES (p_user_id, p_degree, p_experience, p_bio, p_fee, p_specialty, p_address);
    END IF;

    -- 3. Update or Insert timeslots
    IF EXISTS (SELECT 1 FROM timeslots WHERE user_id = p_user_id) THEN
        UPDATE timeslots
        SET start_time = p_start_time,
            end_time = p_end_time
        WHERE user_id = p_user_id;
    ELSE
        INSERT INTO timeslots(user_id, start_time, end_time)
        VALUES (p_user_id, p_start_time, p_end_time);
    END IF;

    COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `reason` text NOT NULL,
  `timeslot_id` int NOT NULL,
  `user_id` int NOT NULL,
  `doctor_id` int NOT NULL,
  `status_id` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `created_at`, `appointment_date`, `appointment_time`, `reason`, `timeslot_id`, `user_id`, `doctor_id`, `status_id`) VALUES
(17, '2025-08-04 16:48:20', '2025-08-05', '09:00:00', 'xxxx', 4, 19, 90, 1),
(19, '2025-08-05 05:45:23', '2025-08-06', '08:30:00', 'headache', 64, 19, 62, 2),
(21, '2025-08-05 05:48:40', '2025-08-06', '10:00:00', 'headache', 4, 19, 90, 1),
(23, '2025-08-06 05:07:02', '2025-08-06', '12:10:00', 'testing', 74, 19, 130, 2),
(24, '2025-08-06 07:18:07', '2025-08-06', '14:00:00', 'mine mine', 72, 19, 128, 2),
(26, '2025-08-07 03:39:59', '2025-08-07', '10:10:00', 'htetkyaw', 64, 132, 62, 2),
(28, '2025-08-08 10:57:02', '2025-08-09', '12:40:00', 'gggggg', 12, 19, 107, 2),
(29, '2025-08-08 10:58:58', '2025-08-09', '09:20:00', 'ggggggg', 9, 19, 90, 1),
(30, '2025-08-09 06:53:57', '2025-08-09', '16:30:00', 'hhhh', 4, 19, 59, 2),
(31, '2025-08-09 08:12:33', '2025-08-09', '17:30:00', 'ddddd', 5, 19, 64, 2),
(32, '2025-08-10 09:55:46', '2025-08-12', '09:00:00', 'headache', 9, 19, 90, 1),
(33, '2025-08-10 14:28:51', '2025-08-11', '09:00:00', 'checkup', 9, 108, 90, 1),
(34, '2025-08-15 09:33:26', '2025-08-16', '09:00:00', 'sssssssssssssss', 9, 19, 90, 1),
(35, '2025-08-15 09:38:08', '2025-08-16', '09:20:00', 'hhhhhhhhhhhhh', 9, 19, 90, 1),
(36, '2025-08-15 09:40:23', '2025-08-16', '09:40:00', 'jjjjjjjjjjjjjjj', 9, 19, 90, 3),
(42, '2025-08-17 09:28:10', '2025-08-18', '09:00:00', 'ssss', 9, 19, 90, 1),
(43, '2025-08-17 10:30:05', '2025-08-18', '09:20:00', 'sss', 9, 19, 90, 1),
(50, '2025-08-17 13:30:55', '2025-08-18', '09:40:00', 'zzzzzzzzzzzz', 9, 19, 90, 1),
(51, '2025-08-18 08:40:28', '2025-08-19', '09:00:00', 'check up', 9, 19, 90, 3),
(72, '2025-08-19 08:55:53', '2025-08-19', '17:30:00', 'aaaa', 5, 19, 64, 2),
(73, '2025-08-19 10:00:43', '2025-08-19', '18:10:00', 'check up', 5, 108, 64, 2),
(74, '2025-08-19 10:01:09', '2025-08-19', '17:50:00', 'haha', 5, 108, 64, 2),
(75, '2025-08-19 10:03:17', '2025-08-20', '09:00:00', 'headache', 9, 108, 90, 1),
(77, '2025-08-21 09:34:26', '2025-08-21', '18:10:00', 'a', 5, 19, 64, 2),
(78, '2025-08-21 09:49:03', '2025-08-21', '17:50:00', '', 5, 108, 64, 2),
(79, '2025-08-21 10:17:08', '2025-08-21', '17:30:00', 'a', 5, 108, 64, 2),
(81, '2025-08-22 10:51:32', '2025-08-23', '09:00:00', 'axda', 9, 19, 90, 1),
(82, '2025-08-23 03:18:42', '2025-08-23', '11:10:00', 'sdljosj', 7, 172, 62, 2),
(83, '2025-08-23 03:19:19', '2025-08-23', '17:50:00', 's', 5, 172, 64, 2),
(84, '2025-08-23 03:20:18', '2025-08-23', '10:00:00', '', 9, 172, 90, 1),
(85, '2025-08-23 07:06:18', '2025-08-23', '17:30:00', 'vh', 5, 172, 64, 2),
(91, '2025-08-24 14:11:52', '2025-08-25', '17:30:00', 'aaa', 5, 19, 64, 2),
(92, '2025-08-25 15:26:07', '2025-08-26', '09:00:00', 'yaya', 9, 19, 90, 3),
(93, '2025-08-25 15:26:25', '2025-08-26', '09:20:00', 'yaya', 9, 19, 90, 1),
(95, '2025-08-26 04:31:21', '2025-08-27', '09:00:00', 'check up', 9, 19, 90, 1),
(97, '2025-09-01 03:34:24', '2025-09-01', '10:20:00', 'check up', 9, 19, 90, 1),
(98, '2025-09-01 03:49:23', '2025-09-01', '10:40:00', 'hj', 9, 161, 90, 2),
(99, '2025-09-02 02:51:23', '2025-09-02', '09:30:00', 'check teeth', 7, 19, 62, 2),
(101, '2025-09-06 09:28:43', '2025-09-07', '09:50:00', 'm', 7, 19, 62, 2),
(102, '2025-09-06 09:29:02', '2025-09-08', '10:50:00', ',', 7, 19, 62, 2),
(103, '2025-09-09 06:57:21', '2025-09-10', '09:00:00', 'check up', 9, 19, 90, 1),
(104, '2025-09-16 03:27:50', '2025-09-16', '13:40:00', 'headache', 12, 19, 107, 2),
(105, '2025-09-16 03:29:17', '2025-09-16', '10:00:00', 'headache', 9, 19, 90, 1);

--
-- Triggers `appointment`
--
DELIMITER $$
CREATE TRIGGER `prevent_double_booking` BEFORE INSERT ON `appointment` FOR EACH ROW BEGIN
  DECLARE duplicate_count INT;

  SELECT COUNT(*) INTO duplicate_count
  FROM appointment
  WHERE doctor_id = NEW.doctor_id
    AND appointment_date = NEW.appointment_date
    AND appointment_time = NEW.appointment_time
    AND status_id != 3; -- ignore rejected

  IF duplicate_count > 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'This timeslot is already booked';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `appointment_view`
-- (See below for the actual view)
--
CREATE TABLE `appointment_view` (
`appointment_id` int
,`created_at` datetime
,`appointment_date` date
,`appointment_time` time
,`reason` text
,`status_id` tinyint
,`status_name` varchar(255)
,`patient_id` int
,`patient_name` varchar(255)
,`patient_email` varchar(255)
,`patient_phone` varchar(15)
,`patient_profile_image` varchar(255)
,`doctor_user_id` int
,`doctorprofile_id` int
,`doctor_name` varchar(255)
,`doctor_email` varchar(255)
,`doctor_phone` varchar(15)
,`doctor_profile_image` varchar(255)
,`degree` varchar(255)
,`specialty` varchar(255)
,`fee` decimal(10,0)
,`address` text
,`timeslot_id` int
,`start_time` time
,`end_time` time
);

-- --------------------------------------------------------

--
-- Table structure for table `doctorprofile`
--

CREATE TABLE `doctorprofile` (
  `id` int NOT NULL,
  `degree` varchar(255) NOT NULL,
  `experience` smallint NOT NULL,
  `bio` text NOT NULL,
  `fee` decimal(10,0) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctorprofile`
--

INSERT INTO `doctorprofile` (`id`, `degree`, `experience`, `bio`, `fee`, `specialty`, `address`, `user_id`) VALUES
(27, 'MDS', 8, 'Dr. Mi Mi Khaing Lin is a compassionate and skilled dentist dedicated to providing high-quality dental care with a gentle and personalized approach. With years of experience in both general and cosmetic dentistry, Dr. Lin is committed to helping patients achieve and maintain healthy, beautiful smiles.  Graduating from [Dental School Name], Dr. Lin continually updates her knowledge and skills through advanced training and education, ensuring that her patients benefit from the latest dental techniques and technologies. Whether you need routine cleanings, restorative treatments, or cosmetic enhancements, Dr. Lin creates customized treatment plans tailored to each patient’s unique needs.  Dr. Lin values building trusting relationships with her patients, focusing on comfort, education, and long-term oral health. Outside the clinic, she enjoys [your hobbies or interests, e.g., community work, reading, or spending time with family].  ', 20000, 'Dentist', '123 Main Street,Yangon', 62),
(29, 'BDS', 7, 'Dr. Paing Kyaw Moe is a compassionate and skilled dentist dedicated to providing high-quality dental care with a gentle and personalized approach. With years of experience in both general and cosmetic dentistry, Dr. Moe is committed to helping patients achieve and maintain healthy, beautiful smiles.  ', 15000, 'Dentist', '123 Main Street,Yangon', 64),
(55, 'MD', 3, 'Dr. Wai Yan  is a dedicated general physician known for his compassionate care and commitment to improving the overall well-being of his patients. With a strong background in internal medicine and primary care, Dr. Hein provides comprehensive medical services to individuals and families across all age groups.  After earning his medical degree from [Medical School Name], Dr. Hein has continued to expand his knowledge through ongoing medical education and clinical experience. He is skilled in diagnosing and managing a wide range of health conditions — from common illnesses to chronic diseases such as hypertension, diabetes, and heart disease.  Dr. Hein believes in a patient-centered approach, taking the time to listen carefully, explain clearly, and involve patients in every step of their care. His calm demeanor and thorough examinations help patients feel confident and supported throughout their health journey. ', 15000, 'General Physician', '123 Main Street,Yangon', 90),
(62, 'MBBS', 3, 'Dr. Mi Mi Khaing Lin is a compassionate and skilled dentist dedicated to providing high-quality dental care with a gentle and personalized approach. With years of experience in both general and cosmetic dentistry, Dr. Lin is committed to helping patients achieve and maintain healthy, beautiful smiles.   ', 150000, 'General Physician', '123 Main Street,Yangon', 97),
(63, 'MD Pediatrics', 10, 'Dr. Htet Htet Win is a compassionate and experienced pediatrician dedicated to the health and well-being of children from birth through adolescence. With a gentle approach and a deep understanding of child development, Dr. Win provides high-quality, family-centered care that supports every stage of a child’s growth.  After earning her medical degree from [Medical School Name], Dr. Win completed specialized training in pediatrics, gaining expertise in diagnosing and managing a wide range of childhood conditions — from common illnesses to complex developmental concerns. She is passionate about preventive care, early diagnosis, and building lasting relationships with families.  Dr. Win is known for her warm demeanor, clear communication, and commitment to making each young patient feel comfortable and safe during every visit. ', 15000, 'Pediatrician', '123 Main Street,Yangon', 98),
(68, 'MD Dermatology', 6, 'Senior Doctor', 150000, 'Dermatologist', '123 Main Street,Yangon', 103),
(72, 'MBBS', 10, 'Dr. Wai Yan is a general physician with over 8 years of experience in treating common illnesses, performing routine checkups, and providing preventive care for patients of all ages.', 15000, 'General Physician', '123 Main Street,Yangon', 107),
(78, 'MD Dermatology', 10, 'Dr. Daniel Smith is a board-certified dermatologist specializing in the diagnosis and treatment of skin cancer, acne, psoriasis, eczema, rosacea, and other skin conditions. He practices at the Dermatology Group of Arkansas in Little Rock and teaches as adjunct faculty at UAMS.', 20000, 'Dermatologist', '123 Street,Yangon', 125),
(79, 'MD Pediatrics', 8, 'Dr. Michael Chen specializes in pediatric care and has 5 years of experience managing the health of infants, children, and adolescents. She is passionate about child wellness and early diagnosis.', 18000, 'Pediatrician', '123 Main Street, Yangon', 127),
(80, 'MBBS', 7, 'Dr.Emily Davis is a certified dermatologist with 7 years of experience treating skin, hair, and nail conditions. He also provides cosmetic skin consultations and treatments.', 25000, 'Dermatologist', '123 Main Street,Yangon', 128),
(81, 'DDVL', 7, 'Dr. James Wilson is a certified dermatologist with 7 years of experience treating skin, hair, and nail conditions. He also provides cosmetic skin consultations and treatments.\r\n\r\n', 25000, 'Dermatologist', '123 Main Street,Yangon', 129),
(82, 'MDS', 6, 'Dr. Lisa Brown is a skilled dentist with 6 years of clinical practice in dental care, including cleanings, fillings, extractions, and cosmetic procedures. She aims to make every visit comfortable for her patients.\r\n\r\n', 20000, 'Dentist', '123 Main Street,Yangon', 130),
(83, 'MBBS', 8, 'Dr. Smith is a general physician with over 8 years of experience in treating common illnesses, performing routine checkups, and providing preventive care for patients of all ages.', 15000, 'General Physician', '123 Main Street,Yangon', 131),
(85, 'MBBS', 6, 'Dr. Htet Kyaw Lin is a celebrated general physician with a reputation for diagnosing ailments faster than most people can pronounce them. With degrees in Medicine, Galactic Health Studies, and Interdimensional First Aid, Dr. Sky has been serving patients both on Earth and in low-orbit space stations for over 20 years.', 15000, 'General Physician', '123 Main Street,Yangon', 134),
(86, 'MBBS', 6, 'Dr. Crystal is a dedicated pediatrician with over 6 years of experience caring for infants, children, and adolescents. Known for their gentle approach and keen diagnostic skills, Dr. Crystal believes that building trust with young patients and their families is the key to effective healthcare.', 20000, 'Pediatrician', '123 Main Street,Yangon', 135),
(87, 'MBBS', 6, 'Dr. Crystal is a dedicated pediatrician with over 6 years of experience caring for infants, children, and adolescents. Known for their gentle approach and keen diagnostic skills, Dr. Crystal believes that building trust with young patients and their families is the key to effective healthcare.', 20000, 'Pediatrician', '123 Main Street,Yangon', 136),
(88, 'MBBS', 6, 'Dr. Crystal is a dedicated pediatrician with over 6 years of experience caring for infants, children, and adolescents. Known for their gentle approach and keen diagnostic skills, Dr. Crystal believes that building trust with young patients and their families is the key to effective healthcare.', 20000, 'Pediatrician', '123 Main Street,Yangon', 137),
(89, 'MBBS', 6, 'Dr. Crystal is a dedicated pediatrician with over 6 years of experience caring for infants, children, and adolescents. Known for their gentle approach and keen diagnostic skills, Dr. Crystal believes that building trust with young patients and their families is the key to effective healthcare.', 20000, 'Pediatrician', '123 Main Street,Yangon', 138),
(90, 'MBBS', 6, 'Dr. Crystal is a dedicated pediatrician with over 6 years of experience caring for infants, children, and adolescents. Known for their gentle approach and keen diagnostic skills, Dr. Crystal believes that building trust with young patients and their families is the key to effective healthcare.', 20000, 'Pediatrician', '123 Main Street,Yangon', 139),
(97, 'MBBS', 2, 'ssssssssssss', 150000, 'General Physician', 'sssssssss', 158),
(98, 'MBBS', 6, 'ffff', 20000, 'General Physician', '123 Main Street,Yangon', 173),
(99, 'MD Pediatrics', 3, 'Dr. Linda Davis is a compassionate pediatrician with 3 years of experience caring for infants, children, and adolescents. She specializes in routine check-ups, vaccinations, and child health education.', 20000, 'Pediatrician', '123 Main Street,Yangon', 174),
(100, 'MD Pediatrics', 3, 'Dr. Aurelia Petrician is a dedicated physician with three years of clinical experience specializing in community health and preventive care. Known for her warm bedside manner and tireless curiosity, she has worked in both urban clinics and rural outreach programs, always focusing on practical, patient-centered solutions. When not in the exam room, Dr. Petrician contributes to public health initiatives, aiming to make healthcare more accessible for underserved populations.', 20000, 'Pediatrician', '123 Main Street,Yangon', 175);

--
-- Triggers `doctorprofile`
--
DELIMITER $$
CREATE TRIGGER `trg_log_doctorprofile_update` AFTER UPDATE ON `doctorprofile` FOR EACH ROW BEGIN
    	
        IF OLD.bio != NEW.bio THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'doctorprofile','bio',OLD.bio,NEW.bio);
        END IF;
        
        IF OLD.degree != NEW.degree THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'doctorprofile','degree',OLD.degree,NEW.degree);
        END IF;
        
        IF OLD.experience != NEW.experience THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'doctorprofile','experience',OLD.experience,NEW.experience);
        END IF;
        
        IF OLD.specialty != NEW.specialty THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'doctorprofile','specialty',OLD.specialty,NEW.specialty);
        END IF;
        
        IF OLD.fee != NEW.fee THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'doctorprofile','fee',OLD.fee,NEW.fee);
        END IF;
        
        IF OLD.address != NEW.address THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'doctorprofile','address',OLD.address,NEW.address);
        END IF;
        
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_update_log`
--

CREATE TABLE `doctor_update_log` (
  `id` int NOT NULL,
  `doctor_id` int DEFAULT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `field_changed` varchar(100) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  `changed_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_update_log`
--

INSERT INTO `doctor_update_log` (`id`, `doctor_id`, `table_name`, `field_changed`, `old_value`, `new_value`, `changed_at`) VALUES
(1, 62, 'users', 'name', 'Mi Mi Khaing Lin', 'Mi Mi Khaing Lin LIN', '2025-08-07 07:33:13'),
(2, 59, 'users', 'email', 'min@gmail.com', 'minthu@gmail.com', '2025-08-07 08:01:39'),
(3, 24, 'doctorprofile', 'degree', 'MD Pediatrics', 'DCH', '2025-08-07 08:01:39'),
(4, 24, 'doctorprofile', 'experience', '3', '4', '2025-08-07 08:01:39'),
(5, 24, 'doctorprofile', 'fee', '20000', '25000', '2025-08-07 08:01:39'),
(6, 27, 'doctorprofile', 'experience', '4', '8', '2025-08-08 05:42:34'),
(7, 62, 'users', 'name', 'Mi Mi Khaing Lin LIN', 'Mi Mi Khaing Lin', '2025-08-09 08:20:28'),
(8, 134, 'users', 'profile_image', 'public/image/doctor_68970f573eb0b_doctor_68870527adb72_doctor_6886ff2ee39f4_istockphoto-2158610739-612x612.webp', 'public/image/doctor_689711f2629c5_book_6884b211cc555_book_6884941ae387a_book_6884919cf3c14_book_68846740c8465_doctor.jpg', '2025-08-09 09:16:34'),
(9, 64, 'users', 'profile_image', 'public/image/book_68835f89c3204_book_68835428ecffa_photo-1638202993928-7267aad84c31.avif', 'public/image/doctor_68971be643b47_humberto-chavez-FVh_yqLR9eA-unsplash.jpg', '2025-08-09 09:59:02'),
(10, 64, 'users', 'profile_image', 'public/image/doctor_68971be643b47_humberto-chavez-FVh_yqLR9eA-unsplash.jpg', 'public/image/doctor_68971c01a87c8_book_68849930b63ad_book_688495cede3ae_book_688495216dc1f_book_6884919cf3c14_book_68846740c8465_doctor.jpg', '2025-08-09 09:59:29'),
(11, 29, 'doctorprofile', 'experience', '10', '11', '2025-08-09 09:59:29'),
(12, 140, 'users', 'profile_image', 'doctor__689853a335e9b.avif', 'doctor__689853bae53c3.jpg', '2025-08-10 08:09:31'),
(13, 140, 'users', 'profile_image', 'doctor__689853bae53c3.jpg', 'doctor__689853cf5632c.jpg', '2025-08-10 08:09:51'),
(14, 140, 'users', 'profile_image', 'doctor__689853cf5632c.jpg', 'image/doctor__689855706aabb.jpg', '2025-08-10 08:16:48'),
(15, 90, 'users', 'password', 'V2FpeWFuNzcyQA==', 'V2FpeWFuaGVpbjc3QA==', '2025-08-11 16:17:24'),
(16, 90, 'users', 'password', 'V2FpeWFuaGVpbjc3QA==', 'V2FpeWFuNzcyQA==', '2025-08-11 16:32:53'),
(18, 90, 'users', 'password', 'V2FpeWFuNzcyQA==', 'V2FpeWFuaGVpbjc3QA==', '2025-08-11 17:23:16'),
(19, 90, 'users', 'password', 'V2FpeWFuaGVpbjc3QA==', 'V2FpeWFuNzcyQA==', '2025-08-12 01:21:37'),
(20, 90, 'users', 'password', 'V2FpeWFuNzcyQA==', 'V2FpeWFuaGVpbjc3QA==', '2025-08-12 01:31:07'),
(21, 90, 'users', 'password', 'V2FpeWFuaGVpbjc3QA==', 'V2FpeWFuaGVpbjc3MkA=', '2025-08-12 01:32:56'),
(22, 90, 'users', 'password', 'V2FpeWFuaGVpbjc3MkA=', 'V2FpeWFuNzcyQA==', '2025-08-12 01:42:59'),
(23, 154, 'users', 'password', 'WmF3QDEyMzQ=', 'WmF3QDEyMzQ1', '2025-08-12 04:36:21'),
(24, 154, 'users', 'password', 'WmF3QDEyMzQ1', 'WmF3QDEyMzQ=', '2025-08-12 04:38:16'),
(25, 90, 'users', 'password', 'V2FpeWFuNzcyQA==', 'V2FpeWFuaGVpbjc3MkA=', '2025-08-12 04:40:40'),
(26, 90, 'users', 'password', 'V2FpeWFuaGVpbjc3MkA=', 'V2FpeWFuNzcyQA==', '2025-08-12 14:28:23'),
(27, 55, 'doctorprofile', 'fee', '150000', '15000', '2025-08-13 03:24:15'),
(28, 29, 'doctorprofile', 'experience', '11', '5', '2025-08-14 15:55:47'),
(29, 64, 'users', 'profile_image', 'public/image/doctor_68971c01a87c8_book_68849930b63ad_book_688495cede3ae_book_688495216dc1f_book_6884919cf3c14_book_68846740c8465_doctor.jpg', 'image/doctor__689e095277c9d.webp', '2025-08-14 16:05:38'),
(30, 64, 'users', 'profile_image', 'image/doctor__689e095277c9d.webp', 'image/doctor__689e096426a20.jpg', '2025-08-14 16:05:56'),
(31, 29, 'doctorprofile', 'experience', '5', '7', '2025-08-15 16:11:05'),
(32, 29, 'doctorprofile', 'experience', '7', '8', '2025-08-16 02:56:12'),
(33, 29, 'doctorprofile', 'experience', '8', '10', '2025-08-16 08:48:36'),
(34, 29, 'doctorprofile', 'experience', '10', '8', '2025-08-16 09:19:51'),
(35, 29, 'doctorprofile', 'experience', '8', '9', '2025-08-16 09:20:41'),
(36, 29, 'doctorprofile', 'experience', '9', '8', '2025-08-16 09:47:35'),
(37, 29, 'doctorprofile', 'experience', '8', '9', '2025-08-18 08:55:22'),
(38, 90, 'users', 'password', 'V2FpeWFuNzcyQA==', 'V2FpeWFuaGVpbjc3MkA=', '2025-08-24 14:12:41'),
(39, 90, 'users', 'password', 'V2FpeWFuaGVpbjc3MkA=', 'V2FpeWFuNzcyQA==', '2025-08-24 14:13:19'),
(40, 62, 'users', 'profile_image', 'public/image/book_68835428ecffa_photo-1638202993928-7267aad84c31.avif', 'image/doctor_4d9af6457f3dd803.jpg', '2025-08-25 16:24:37'),
(41, 103, 'users', 'profile_image', 'public/image/doctor_68870b1fb9091_doctor_688708e9ba8e9_doctor_6887067b660f8_doctor_6885d768dc8cb_book_68835428ecffa_photo-1638202993928-7267aad84c31.avif', 'image/doctor_16ab8d6b1fd0ed5b.webp', '2025-08-25 16:37:38'),
(42, 62, 'users', 'profile_image', 'image/doctor_4d9af6457f3dd803.jpg', 'image/doctor_da4a021774cf1f3a.webp', '2025-08-25 16:38:04'),
(43, 158, 'users', 'phone', '094561237', '0945612378', '2025-08-25 16:39:39'),
(44, 158, 'users', 'profile_image', 'image/doctor__689f596073f9d.avif', 'image/doctor_bec06efbed93e0d4.webp', '2025-08-25 16:39:39'),
(45, 173, 'users', 'profile_image', 'image/doctor_bc6a3704dc2fb5f1.avif', 'image/doctor_85bbede5786a82ac.webp', '2025-08-25 16:40:34'),
(46, 130, 'users', 'profile_image', 'public/image/doctor_6892e0f7a27cf_premium_photo-1673953510197-0950d951c6d9.avif', 'image/doctor_5bcbdcd38596d3d3.webp', '2025-08-25 16:40:49'),
(47, 29, 'doctorprofile', 'experience', '9', '6', '2025-08-29 15:05:08'),
(48, 29, 'doctorprofile', 'experience', '6', '7', '2025-09-02 02:43:25'),
(49, 90, 'users', 'password', 'V2FpeWFuNzcyQA==', 'V2FpeWFuaGVpbjc3MkA=', '2025-09-13 08:43:51'),
(50, 90, 'users', 'password', 'V2FpeWFuaGVpbjc3MkA=', 'V2FpeWFuNzcyQA==', '2025-09-13 08:45:39'),
(51, 68, 'doctorprofile', 'experience', '10', '6', '2025-09-15 13:44:22');

-- --------------------------------------------------------

--
-- Stand-in structure for view `doctor_view`
-- (See below for the actual view)
--
CREATE TABLE `doctor_view` (
`user_id` int
,`name` varchar(255)
,`gender` varchar(10)
,`email` varchar(255)
,`phone` varchar(15)
,`profile_image` varchar(255)
,`is_confirmed` int
,`is_active` int
,`is_login` int
,`type_id` int
,`status_id` int
,`degree` varchar(255)
,`experience` smallint
,`bio` text
,`fee` decimal(10,0)
,`specialty` varchar(255)
,`address` text
,`start_time` time
,`end_time` time
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `doctor_view1`
-- (See below for the actual view)
--
CREATE TABLE `doctor_view1` (
`user_id` int
,`doctorprofile_id` int
,`name` varchar(255)
,`gender` varchar(10)
,`email` varchar(255)
,`phone` varchar(15)
,`profile_image` varchar(255)
,`status_id` int
,`status_name` varchar(255)
,`degree` varchar(255)
,`experience` smallint
,`bio` text
,`fee` decimal(10,0)
,`specialty` varchar(255)
,`address` text
,`timeslot_id` int
,`start_time` time
,`end_time` time
);

-- --------------------------------------------------------

--
-- Table structure for table `email_verification_tokens`
--

CREATE TABLE `email_verification_tokens` (
  `id` int NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` tinyint NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Confirmed'),
(2, 'Pending'),
(3, 'Cancelled'),
(4, 'Completed'),
(5, 'Fail');

-- --------------------------------------------------------

--
-- Table structure for table `timeslots`
--

CREATE TABLE `timeslots` (
  `id` int NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `timeslots`
--

INSERT INTO `timeslots` (`id`, `start_time`, `end_time`, `user_id`) VALUES
(4, '09:00:00', '11:00:00', 90),
(5, '09:00:00', '12:00:00', 98),
(64, '07:30:00', '11:30:00', 62),
(65, '17:30:00', '19:22:00', 64),
(66, '05:30:00', '08:30:00', 97),
(67, '16:30:00', '19:30:00', 103),
(69, '12:00:00', '16:00:00', 125),
(70, '09:00:00', '17:00:00', 127),
(71, '12:00:00', '15:00:00', 107),
(72, '11:00:00', '18:00:00', 128),
(73, '06:00:00', '11:00:00', 129),
(74, '08:30:00', '13:00:00', 130),
(75, '09:00:00', '17:00:00', 131),
(77, '15:30:00', '19:29:00', 134),
(78, '06:30:00', '22:52:00', 135),
(79, '06:30:00', '22:52:00', 136),
(80, '06:30:00', '22:52:00', 137),
(81, '06:30:00', '22:52:00', 138),
(82, '06:30:00', '22:52:00', 139),
(89, '11:28:00', '16:29:00', 158),
(90, '14:00:00', '18:00:00', 173),
(91, '11:00:00', '17:00:00', 174),
(92, '09:00:00', '12:00:00', 175);

--
-- Triggers `timeslots`
--
DELIMITER $$
CREATE TRIGGER `trg_log_timeslot_update` AFTER UPDATE ON `timeslots` FOR EACH ROW BEGIN
    	IF OLD.start_time != NEW.start_time OR OLD.end_time !=NEW.end_time THEN
        	INSERT INTO timeslot_update_log(doctor_id,old_start_time,new_start_time,old_end_time,new_end_time)
            VALUES(OLD.user_id,OLD.start_time,NEW.start_time,OLD.end_time,NEW.end_time);
        END IF;
  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_update_log`
--

CREATE TABLE `timeslot_update_log` (
  `id` int NOT NULL,
  `doctor_id` int NOT NULL,
  `old_start_time` time DEFAULT NULL,
  `new_start_time` time DEFAULT NULL,
  `old_end_time` time DEFAULT NULL,
  `new_end_time` time DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `timeslot_update_log`
--

INSERT INTO `timeslot_update_log` (`id`, `doctor_id`, `old_start_time`, `new_start_time`, `old_end_time`, `new_end_time`, `updated_at`) VALUES
(1, 62, '05:30:00', '06:30:00', '10:30:00', '11:30:00', '2025-08-07 08:17:22'),
(2, 62, '06:30:00', '07:30:00', '11:30:00', '11:30:00', '2025-08-09 06:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `amount` decimal(65,0) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `status_id` tinyint NOT NULL,
  `appointment_id` int NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `payment_method`, `amount`, `remark`, `status_id`, `appointment_id`, `created_at`) VALUES
(1, 'KBZ Pay', 15000, 'success', 4, 84, '2025-08-23 10:20:00'),
(2, 'KBZ Pay', 15000, 'success', 4, 87, '2025-08-23 12:30:01'),
(3, 'KBZ Pay', 15000, 's', 4, 86, '2025-08-23 12:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Doctor'),
(3, 'Patient');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `is_confirmed` int NOT NULL,
  `is_active` int NOT NULL,
  `is_login` int NOT NULL,
  `type_id` int NOT NULL,
  `status_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `gender`, `email`, `phone`, `password`, `profile_image`, `is_confirmed`, `is_active`, `is_login`, `type_id`, `status_id`) VALUES
(19, 'Mi Mi ', 'female', 'mimikhainglin70@gmail.com', '09441386934', 'TWltaWtoYWluZ2xpbjcwQA==', 'profile_19_7da018d3142a11ff.avif', 0, 0, 1, 3, 2),
(20, 'KaungPyae', 'male', 'kaung@gmail.com', '09872132456', 'S2F1bmcxMTJA', 'default_profile.jpg', 0, 0, 1, 3, 2),
(23, 'Htet Htet Win', 'female', 'htethtetwin614@gmail.com', '0933224455', 'SHRldDMzMjJA', 'default_profile.jpg', 0, 0, 0, 3, 2),
(24, 'Htet Htet Win', 'female', 'Htet112@gamail.com', '0922335566', 'SHRldDQ1NkA=', 'default_profile.jpg', 0, 0, 0, 3, 2),
(25, 'Htet Htet Win', 'female', 'htethtetwin664@gmail.com', '0944556789', 'SHRldGh0ZXR3aW42NjRA', 'profile_25_1754563273.jpg', 1, 0, 0, 1, 2),
(62, 'Mi Mi Khaing Lin', 'female', 'mimikhainglin770@gmail.com', '09441386935', 'VkZkc2RHRlhkRzlaVjJ4MVdqSjRjR0pxWXpOTlJVRTk=', 'image/doctor_da4a021774cf1f3a.webp', 1, 1, 0, 2, 6),
(64, 'Paing Kyaw Moe', 'male', 'paingkyawmoe33@gmail.com', '09665554897', 'UGFpbmdreWF3bW9lMzNA', 'image/doctor__689e096426a20.jpg', 1, 1, 0, 2, 6),
(90, 'Wai  Yan', 'male', 'waiyan772@gmail.com', '09955078924', 'V2FpeWFuNzcyQA==', 'public/image/book_6884b2a7a82cf_book_6884941ae387a_book_6884919cf3c14_book_68846740c8465_doctor.jpg', 1, 1, 0, 2, 6),
(97, 'Mi Mi ', 'female', 'mimikhainglin990@gmail.com', '09441386275', 'TWltaWtoYWluZ2xpbjkwQA==', 'public/image/book_6884b7d2cce52_book_688488792bccb_book_68846740c8465_doctor.jpg', 1, 1, 0, 2, 6),
(103, 'Phue Phue', 'female', 'phuephue11@gmail.com', '09955077826', 'UGh1ZXBodWUxMUA=', 'image/doctor_16ab8d6b1fd0ed5b.webp', 1, 1, 0, 2, 6),
(107, 'Wai  Yan', 'male', 'waiyan45@gmail.com', '09955078928', 'VmpKR2NHVlhSblZOVlVFOQ==', 'public/image/doctor_68872314362d1_book_68834e7f82beb_premium_photo-1658506671316-0b293df7c72b.avif', 1, 1, 0, 2, 6),
(108, 'Htet Htet Win', 'female', 'htethtetwin654@gmail.com', '09771223456', 'SHRldGh0ZXR3aW42NTRA', 'profile_108_c30e785f1b98a183.avif', 0, 0, 1, 3, 6),
(109, 'Mi Mi ', 'female', 'mimikhainglin80@gmail.com', '0955664412', 'TWltaWtoYWluZ2xpbjgwQA==', 'default_profile.jpg', 0, 0, 1, 3, 6),
(110, 'Mi Mi ', 'female', 'mimikhainglin90@gmail.com', '0955664417', 'TWltaWtoYWluZ2xpbjkwQA==', 'default_profile.jpg', 0, 0, 0, 3, 6),
(111, 'Mi Mi ', 'female', 'mimikhainglin50@gmail.com', '09441386938', 'TWltaWtoYWluZ2xpbjUwQA==', 'default_profile.jpg', 0, 0, 0, 3, 6),
(112, 'Paingkyawmoe', 'male', 'paing@gmail.com', '09750231601', 'UGFpbmdAMTIz', 'default_profile.jpg', 0, 0, 1, 3, 6),
(113, 'Paing', 'male', 'paing1@gmail.com', '09672636439', 'UGFpbmdAMTIz', 'default_profile.jpg', 0, 0, 1, 3, 6),
(114, 'Paingkyaw', 'male', 'Paingkyaw@gmail.com', '09750231602', 'UGFpbmdAMTIz', 'default_profile.jpg', 0, 0, 1, 3, 6),
(115, 'Htet Kyaw Lin', 'male', 'htetkyawlin11@gmail.com', '09755566523', 'SGV0a3lhd2xpbjExQA==', 'default_profile.jpg', 0, 0, 0, 3, 6),
(117, 'Htet Kyaw Lin', 'male', 'htetkyawlin1997@gmail.com', '09556642358', 'SHRldGt5YXdsaW4xOTk3QA==', 'default_profile.jpg', 0, 0, 1, 3, 6),
(119, 'Jo Jo', 'male', 'jojo500@gmail.com', '0945632477', 'Sm9qbzUwMEA=', 'default_profile.jpg', 0, 0, 1, 3, 6),
(125, 'Daniel Smith', 'male', 'danielsmith55@gmail.com', '0978456321', 'RGFuaWVsc21pdGg1NUA=', 'public/image/doctor_68918fdab76b4_istockphoto-2158610739-612x612.webp', 1, 1, 0, 2, 6),
(126, 'Htet Kyaw Lin', 'male', 'htetkyawlin12@gmail.com', '09756123365', 'SHRldGt5YXdsaW4xMkA=', 'default_profile.jpg', 0, 0, 0, 3, 6),
(127, 'Michael Chen', 'male', 'michaelchen3@gmail.com', '09988812345', 'TWljaGFlbGNoZW4zQA==', 'public/image/doctor_6892dcbd7e7f7_doctor_68870527adb72_doctor_6886ff2ee39f4_istockphoto-2158610739-612x612.webp', 1, 1, 0, 2, 6),
(128, 'Emily Davis', 'female', 'emilydavis4@gmail.com', '09455671234', 'RW1pbHlkYXZpczRA', 'public/image/doctor_6892df64cd23d_humberto-chavez-FVh_yqLR9eA-unsplash.jpg', 0, 0, 0, 2, 6),
(129, 'James Wilson', 'male', 'jameswilson5@gmail.com', '09898765432', 'SmFtZXN3aWxzb241QA==', 'public/image/doctor_6892e052c9ecb_doctor_68872314362d1_book_68834e7f82beb_premium_photo-1658506671316-0b293df7c72b.avif', 0, 0, 0, 2, 6),
(130, 'Lisa Brown', 'female', 'lisabrown6@gmail.com', '09771234567', 'TGlzYWJyb3duNkA=', 'image/doctor_5bcbdcd38596d3d3.webp', 0, 0, 0, 2, 6),
(131, 'Smith', 'male', 'smith99@gmail.com', '09661112233', 'U21pdGg5OUA=', 'public/image/doctor_68931394b8f0e_premium_photo-1661764878654-3d0fc2eefcca.avif', 0, 0, 0, 2, 6),
(132, 'Htet Kyaw', 'male', 'htetkyaw30@gmail.com', '0988984572', 'SHRldGt5YXczMEA=', 'default_profile.jpg', 0, 0, 1, 3, 6),
(134, 'Htet Kyaw Lin', 'male', 'htetkyawlin29@gmail.com', '0978531452', 'SHRldGt5YXdsaW4yOUA=', 'public/image/doctor_689711f2629c5_book_6884b211cc555_book_6884941ae387a_book_6884919cf3c14_book_68846740c8465_doctor.jpg', 0, 0, 1, 2, 6),
(158, 'Mi Mi ', 'female', 'mimikhainglin78@gmail.com', '0945612378', 'TWltaTc3MEA=', 'image/doctor_bec06efbed93e0d4.webp', 0, 0, 0, 2, 6),
(163, 'Htet Htet Win Sein', 'female', 'htethtetwinsein23@gmail.com', '0978451289', 'SHRldGh0ZXR3aW5zZWluMjNA', 'default_profile.jpg', 0, 0, 1, 3, 6),
(167, 'Crystal', 'female', 'crystal74@gmail.com', '09441386937', 'Q0NjcnlzdGFsNzRA', 'default_profile.jpg', 0, 0, 0, 3, 6),
(169, 'Mi Mi ', 'female', 'mimikhainglin780@gmail.com', '09441386978', 'TWltaWtoYWluZ2xpbjc4MEA=', 'default_profile.jpg', 0, 0, 0, 3, 6),
(170, 'Htet Htet Win', 'female', 'mimikhainglin700@gmail.com', '097845123', 'SHRldDg4MzRA', 'default_profile.jpg', 0, 0, 0, 3, 6),
(172, 'Aung Nyein Chann', 'male', 'aungnyeinchann416@gmail.com', '09970989566', 'QXVuZzEwQDI5', 'profile_172_2808ae768e5f3972.avif', 0, 0, 1, 3, 6),
(173, 'Mi MI', 'female', 'mimikhainglin707@gmail.com', '0978456325', 'TWltaWtoYWluZ2xpbjcwN0A=', 'image/doctor_85bbede5786a82ac.webp', 0, 0, 0, 2, 6),
(174, 'Linda Davis', 'female', 'lindadavis11@gmail.com', '0978541266', 'TGluZGFkYXZpczExQA==', 'image/doctor_9093772c0bd9949b.avif', 0, 0, 0, 2, 6),
(175, 'Phue Phue', 'female', 'phuephue6@gmail.com', '09784561322', 'UGh1ZXBodWU2QA==', 'image/doctor_6b1fddf6b2a9e571.jpg', 0, 0, 0, 2, 6),
(176, 'Ma Thu', 'female', 'mathu112@gmail.com', '0978461254', 'TWF0aHUxMTJA', 'default_profile.jpg', 0, 0, 0, 3, 6);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `trg_log_users_update` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
	IF OLD.type_id = 2 THEN
    	
        IF OLD.name != NEW.name THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'users','name',OLD.name,NEW.name);
        END IF;
        
        IF OLD.email != NEW.email THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'users','email',OLD.email,NEW.email);
        END IF;
        
        IF OLD.phone != NEW.phone THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'users','phone',OLD.phone,NEW.phone);
        END IF;
        
        IF OLD.password != NEW.password THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'users','password',OLD.password,NEW.password);
        END IF;
        
        IF OLD.profile_image != NEW.profile_image THEN
        	INSERT INTO doctor_update_log(doctor_id,table_name,field_changed,old_value,new_value)
            VALUES(OLD.id,'users','profile_image',OLD.profile_image,NEW.profile_image);
        END IF;
     END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_identities`
--

CREATE TABLE `user_identities` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `provider` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified` tinyint(1) DEFAULT '0',
  `picture` varchar(512) DEFAULT NULL,
  `raw_claims` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_identities`
--

INSERT INTO `user_identities` (`id`, `user_id`, `provider`, `subject`, `email`, `email_verified`, `picture`, `raw_claims`, `created_at`) VALUES
(1, 25, 'google', '105888515418223230137', 'htethtetwin664@gmail.com', 1, 'https://lh3.googleusercontent.com/a/ACg8ocLofKt9qaJQsZnH0YpRxCFtYyoG7p66F3mYRr44FtY9J8X4lw=s96-c', '{\"aud\": \"903115405044-4mebros9hnrtjsmndll8je4651r313o5.apps.googleusercontent.com\", \"azp\": \"903115405044-4mebros9hnrtjsmndll8je4651r313o5.apps.googleusercontent.com\", \"exp\": 1756498216, \"iat\": 1756494616, \"iss\": \"https://accounts.google.com\", \"sub\": \"105888515418223230137\", \"name\": \"Htet Htet Win\", \"email\": \"htethtetwin664@gmail.com\", \"nonce\": \"a65ed994e548264152203f23754353a6\", \"at_hash\": \"eQ_M4ZR0EfLBWFU2ljWeig\", \"picture\": \"https://lh3.googleusercontent.com/a/ACg8ocLofKt9qaJQsZnH0YpRxCFtYyoG7p66F3mYRr44FtY9J8X4lw=s96-c\", \"given_name\": \"Htet Htet\", \"family_name\": \"Win\", \"email_verified\": true}', '2025-08-29 19:10:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorprofile`
--
ALTER TABLE `doctorprofile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_update_log`
--
ALTER TABLE `doctor_update_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_verification_tokens`
--
ALTER TABLE `email_verification_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeslots`
--
ALTER TABLE `timeslots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeslot_update_log`
--
ALTER TABLE `timeslot_update_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_identities`
--
ALTER TABLE `user_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_provider_sub` (`provider`,`subject`),
  ADD KEY `idx_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `doctorprofile`
--
ALTER TABLE `doctorprofile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `doctor_update_log`
--
ALTER TABLE `doctor_update_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `email_verification_tokens`
--
ALTER TABLE `email_verification_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `timeslots`
--
ALTER TABLE `timeslots`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `timeslot_update_log`
--
ALTER TABLE `timeslot_update_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `user_identities`
--
ALTER TABLE `user_identities`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- --------------------------------------------------------

--
-- Structure for view `appointment_view`
--
DROP TABLE IF EXISTS `appointment_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `appointment_view`  AS SELECT `a`.`id` AS `appointment_id`, `a`.`created_at` AS `created_at`, `a`.`appointment_date` AS `appointment_date`, `a`.`appointment_time` AS `appointment_time`, `a`.`reason` AS `reason`, `a`.`status_id` AS `status_id`, `s`.`name` AS `status_name`, `p`.`id` AS `patient_id`, `p`.`name` AS `patient_name`, `p`.`email` AS `patient_email`, `p`.`phone` AS `patient_phone`, `p`.`profile_image` AS `patient_profile_image`, `d`.`id` AS `doctor_user_id`, `dp`.`id` AS `doctorprofile_id`, `d`.`name` AS `doctor_name`, `d`.`email` AS `doctor_email`, `d`.`phone` AS `doctor_phone`, `d`.`profile_image` AS `doctor_profile_image`, `dp`.`degree` AS `degree`, `dp`.`specialty` AS `specialty`, `dp`.`fee` AS `fee`, `dp`.`address` AS `address`, `t`.`id` AS `timeslot_id`, `t`.`start_time` AS `start_time`, `t`.`end_time` AS `end_time` FROM (((((`appointment` `a` join `users` `p` on((`a`.`user_id` = `p`.`id`))) join `users` `d` on((`a`.`doctor_id` = `d`.`id`))) left join `doctorprofile` `dp` on((`d`.`id` = `dp`.`user_id`))) left join `timeslots` `t` on((`a`.`timeslot_id` = `t`.`id`))) join `status` `s` on((`a`.`status_id` = `s`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `doctor_view`
--
DROP TABLE IF EXISTS `doctor_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `doctor_view`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `name`, `u`.`gender` AS `gender`, `u`.`email` AS `email`, `u`.`phone` AS `phone`, `u`.`profile_image` AS `profile_image`, `u`.`is_confirmed` AS `is_confirmed`, `u`.`is_active` AS `is_active`, `u`.`is_login` AS `is_login`, `u`.`type_id` AS `type_id`, `u`.`status_id` AS `status_id`, `d`.`degree` AS `degree`, `d`.`experience` AS `experience`, `d`.`bio` AS `bio`, `d`.`fee` AS `fee`, `d`.`specialty` AS `specialty`, `d`.`address` AS `address`, `t`.`start_time` AS `start_time`, `t`.`end_time` AS `end_time` FROM ((`users` `u` join `doctorprofile` `d` on((`u`.`id` = `d`.`user_id`))) left join `timeslots` `t` on((`u`.`id` = `t`.`user_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `doctor_view1`
--
DROP TABLE IF EXISTS `doctor_view1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `doctor_view1`  AS SELECT `u`.`id` AS `user_id`, `d`.`id` AS `doctorprofile_id`, `u`.`name` AS `name`, `u`.`gender` AS `gender`, `u`.`email` AS `email`, `u`.`phone` AS `phone`, `u`.`profile_image` AS `profile_image`, `u`.`status_id` AS `status_id`, `s`.`name` AS `status_name`, `d`.`degree` AS `degree`, `d`.`experience` AS `experience`, `d`.`bio` AS `bio`, `d`.`fee` AS `fee`, `d`.`specialty` AS `specialty`, `d`.`address` AS `address`, `t`.`id` AS `timeslot_id`, `t`.`start_time` AS `start_time`, `t`.`end_time` AS `end_time` FROM (((`users` `u` join `doctorprofile` `d` on((`u`.`id` = `d`.`user_id`))) left join `timeslots` `t` on((`u`.`id` = `t`.`user_id`))) join `status` `s` on((`u`.`status_id` = `s`.`id`))) WHERE (`u`.`type_id` = 2) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
