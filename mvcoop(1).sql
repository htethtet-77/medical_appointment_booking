-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Aug 05, 2025 at 03:29 PM
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
-- Database: `mvcoop`
--

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
(3, '2025-08-02 09:05:53', '2025-08-04', '09:00:00', 'mmm', 4, 25, 90, 2),
(15, '2025-08-04 15:52:07', '2025-08-04', '17:30:00', 'ss', 65, 19, 64, 2),
(16, '2025-08-04 15:54:36', '2025-08-04', '18:30:00', 'ddddd', 65, 19, 64, 2),
(17, '2025-08-04 16:48:20', '2025-08-05', '09:00:00', 'xxxx', 4, 19, 90, 1),
(18, '2025-08-05 02:48:23', '2025-08-05', '09:20:00', 'wwww', 4, 19, 90, 2),
(19, '2025-08-05 05:45:23', '2025-08-06', '08:30:00', 'headache', 64, 19, 62, 2),
(21, '2025-08-05 05:48:40', '2025-08-06', '10:00:00', 'headache', 4, 19, 90, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `appointment_view`
-- (See below for the actual view)
--
CREATE TABLE `appointment_view` (
`address` text
,`appointment_date` date
,`appointment_id` int
,`appointment_time` time
,`created_at` datetime
,`degree` varchar(255)
,`doctor_email` varchar(255)
,`doctor_name` varchar(255)
,`doctor_phone` varchar(15)
,`doctor_profile_image` varchar(255)
,`doctor_user_id` int
,`doctorprofile_id` int
,`end_time` time
,`fee` decimal(10,0)
,`patient_email` varchar(255)
,`patient_id` int
,`patient_name` varchar(255)
,`patient_phone` varchar(15)
,`patient_profile_image` varchar(255)
,`reason` text
,`specialty` varchar(255)
,`start_time` time
,`status_id` tinyint
,`status_name` varchar(255)
,`timeslot_id` int
);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `type_id`) VALUES
(2, 'Aung Zin ', 'Hello12345', 2),
(3, 'Car', 'my lambo', 1),
(4, 'Blinders', 'Peaky Blinders', 1),
(5, 'cc', 'Hello', 2),
(6, 'test', 'I love coding', 2),
(7, 'zzz', 'Hello zzz', 2),
(8, 'vvvv', 'vvvv added', 2),
(9, 'dddd', 'dddd', 1);

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
(24, 'MD Pediatrics', 2, 'Dr. Min Thu is a compassionate and knowledgeable pediatrician committed to the health and development of children from infancy through adolescence. With a gentle bedside manner and years of experience in child healthcare, Dr. Min Thu is dedicated to providing comprehensive, family-centered care that supports both the physical and emotional well-being of young patients.  After graduating from [Medical School Name], Dr. Min Thu completed specialized training in pediatrics and has since helped countless families navigate childhood illnesses, growth milestones, and preventive health. ', 20000, 'Pediatrician', 'Putaoh', 59),
(27, 'MDS', 3, 'Dr. Mi Mi Khaing Lin is a compassionate and skilled dentist dedicated to providing high-quality dental care with a gentle and personalized approach. With years of experience in both general and cosmetic dentistry, Dr. Lin is committed to helping patients achieve and maintain healthy, beautiful smiles.  Graduating from [Dental School Name], Dr. Lin continually updates her knowledge and skills through advanced training and education, ensuring that her patients benefit from the latest dental techniques and technologies. Whether you need routine cleanings, restorative treatments, or cosmetic enhancements, Dr. Lin creates customized treatment plans tailored to each patient’s unique needs.  Dr. Lin values building trusting relationships with her patients, focusing on comfort, education, and long-term oral health. Outside the clinic, she enjoys [your hobbies or interests, e.g., community work, reading, or spending time with family].  ', 20000, 'Dentist', 'Ygn', 62),
(29, 'BDS', 10, 'Dr. Paing Kyaw Moe is a compassionate and skilled dentist dedicated to providing high-quality dental care with a gentle and personalized approach. With years of experience in both general and cosmetic dentistry, Dr. Moe is committed to helping patients achieve and maintain healthy, beautiful smiles.  Graduating from [Dental School Name], Dr. Moe continually updates his knowledge and skills through advanced training and education, ensuring that his patients benefit from the latest dental techniques and technologies.', 15000, 'Dentist', 'Meiktila', 64),
(55, 'MD', 3, 'Dr. Wai Yan  is a dedicated general physician known for his compassionate care and commitment to improving the overall well-being of his patients. With a strong background in internal medicine and primary care, Dr. Hein provides comprehensive medical services to individuals and families across all age groups.  After earning his medical degree from [Medical School Name], Dr. Hein has continued to expand his knowledge through ongoing medical education and clinical experience. He is skilled in diagnosing and managing a wide range of health conditions — from common illnesses to chronic diseases such as hypertension, diabetes, and heart disease.  Dr. Hein believes in a patient-centered approach, taking the time to listen carefully, explain clearly, and involve patients in every step of their care. His calm demeanor and thorough examinations help patients feel confident and supported throughout their health journey.  Outside of his practice, Dr. Hein is passionate about [optional: health education, community ou', 150000, 'General Physician', 'Nay Pyi Taw', 90),
(62, 'MBBS', 3, 'Dr. Mi Mi Khaing Lin is a compassionate and skilled dentist dedicated to providing high-quality dental care with a gentle and personalized approach. With years of experience in both general and cosmetic dentistry, Dr. Lin is committed to helping patients achieve and maintain healthy, beautiful smiles.  Graduating from [Dental School Name], Dr. Lin continually updates her knowledge and skills through advanced training and education, ensuring that her patients benefit from the latest dental techniques and technologies. Whether you need routine cleanings, restorative treatments, or cosmetic enhancements, Dr. Lin creates customized treatment plans tailored to each patient’s unique needs.  ', 150000, 'General Physician', 'NY', 97),
(63, 'MD Pediatrics', 10, 'Dr. Htet Htet Win is a compassionate and experienced pediatrician dedicated to the health and well-being of children from birth through adolescence. With a gentle approach and a deep understanding of child development, Dr. Win provides high-quality, family-centered care that supports every stage of a child’s growth.  After earning her medical degree from [Medical School Name], Dr. Win completed specialized training in pediatrics, gaining expertise in diagnosing and managing a wide range of childhood conditions — from common illnesses to complex developmental concerns. She is passionate about preventive care, early diagnosis, and building lasting relationships with families.  Dr. Win is known for her warm demeanor, clear communication, and commitment to making each young patient feel comfortable and safe during every visit. She works closely with parents to ensure they feel informed and supported in all aspects of their child’s health — physical, emotional, and behavioral.  Outside of her practice, Dr. Htet Htet Win enjoys [insert personal interests, e.g., reading, spending time with family, or participating in child health education programs].  With Dr. Htet Htet Win, your child is in caring and capable hands.', 15000, 'Pediatrician', 'Mandalay', 98),
(68, 'MD Dermatology', 10, 'Senior Doctor', 150000, 'Dermatologist', 'Yangon', 103),
(72, 'MBBS', 10, ' Junior doctor', 30000, 'General Physician', 'NY', 107),
(77, 'MBBS', 3, 'shfkjsjkahjkhhkjfhkfdk', 20000, 'General Physician', '123 street, Yangon', 124),
(78, 'MD Dermatology', 10, 'Dr. Daniel Smith is a board-certified dermatologist specializing in the diagnosis and treatment of skin cancer, acne, psoriasis, eczema, rosacea, and other skin conditions. He practices at the Dermatology Group of Arkansas in Little Rock and teaches as adjunct faculty at UAMS.', 20000, 'Dermatologist', '123 Street,Yangon', 125);

-- --------------------------------------------------------

--
-- Stand-in structure for view `doctor_view`
-- (See below for the actual view)
--
CREATE TABLE `doctor_view` (
`address` text
,`bio` text
,`degree` varchar(255)
,`doctorprofile_id` int
,`email` varchar(255)
,`end_time` time
,`experience` smallint
,`fee` decimal(10,0)
,`gender` varchar(10)
,`name` varchar(255)
,`phone` varchar(15)
,`profile_image` varchar(255)
,`specialty` varchar(255)
,`start_time` time
,`status_id` int
,`status_name` varchar(255)
,`timeslot_id` int
,`user_id` int
);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int NOT NULL,
  `amount` double NOT NULL,
  `category_id` int NOT NULL,
  `qty` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `amount`, `category_id`, `qty`, `user_id`, `date`) VALUES
(1, 10000, 4, 70, 16, '2020-12-02'),
(3, 1290, 3, 1, 16, '2020-12-02'),
(4, 2020, 4, 3, 16, '2020-12-08'),
(5, 1500, 4, 12, 16, '2020-12-09'),
(6, 9000000, 4, 3, 16, '2020-12-10'),
(7, 9090990, 4, 5, 16, '2020-12-10'),
(8, 8000, 4, 16, 16, '2020-12-10'),
(9, 3000, 2, 12, 16, '2020-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `amount` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`id`, `category_id`, `amount`, `user_id`, `date`) VALUES
(40, 2, 1500, 16, '2020-12-10'),
(41, 3, 20000000, 16, '2020-12-10');

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
(4, 'Success'),
(5, 'Fail'),
(6, 'is_active');

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
(7, '16:30:00', '18:30:00', 59),
(64, '05:30:00', '10:30:00', 62),
(65, '17:30:00', '19:22:00', 64),
(66, '05:30:00', '08:30:00', 97),
(67, '16:30:00', '19:30:00', 103),
(68, '07:00:00', '10:00:00', 124),
(69, '12:00:00', '16:00:00', 125);

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
  `appointment_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(18, 'Htet Htet Win', 'female', 'htet05052@gmail.com', '09778771232', 'SHRldDEyMzIh', 'default_profile.jpg', 0, 0, 0, 3, 2),
(19, 'Mi Mi ', 'female', 'mimikhainglin70@gmail.com', '09441386934', 'TWltaWtoYWluZ2xpbjcwQA==', 'default_profile.jpg', 0, 0, 1, 3, 2),
(20, 'KaungPyae', 'male', 'kaung@gmail.com', '09872132456', 'S2F1bmcxMTJA', 'default_profile.jpg', 0, 0, 1, 3, 2),
(23, 'Htet Htet Win', 'female', 'htethtetwin614@gmail.com', '0933224455', 'SHRldDMzMjJA', 'default_profile.jpg', 0, 0, 0, 3, 2),
(24, 'Htet Htet Win', 'female', 'Htet112@gamail.com', '0922335566', 'SHRldDQ1NkA=', 'default_profile.jpg', 0, 0, 0, 3, 2),
(25, 'Htet Htet Win', 'female', 'htethtetwin664@gmail.com', '0944556789', 'SHRldGh0ZXR3aW42NjRA', 'default_profile.jpg', 0, 0, 1, 1, 2),
(59, 'Min Thu', 'male', 'min@gmail.com', '09955078900', 'VkZkc2RXUkhhREZSUkVWNA==', 'public/image/book_6885eb0869882_book_68834e7f82beb_premium_photo-1658506671316-0b293df7c72b.avif', 1, 1, 0, 2, 6),
(62, 'Mi Mi Khaing Lin', 'female', 'mimikhainglin770@gmail.com', '09441386935', 'VkZkc2RHRlhkRzlaVjJ4MVdqSjRjR0pxWXpOTlJVRTk=', 'public/image/book_68835428ecffa_photo-1638202993928-7267aad84c31.avif', 1, 1, 0, 2, 6),
(64, 'Paing Kyaw Moe', 'male', 'paingkyawmoe33@gmail.com', '09665554897', 'UGFpbmdreWF3bW9lMzNA', 'public/image/book_68835f89c3204_book_68835428ecffa_photo-1638202993928-7267aad84c31.avif', 1, 1, 0, 2, 6),
(90, 'Wai  Yan', 'male', 'waiyan772@gmail.com', '09955078924', 'V2FpeWFuNzcyQA==', 'public/image/book_6884b2a7a82cf_book_6884941ae387a_book_6884919cf3c14_book_68846740c8465_doctor.jpg', 0, 0, 1, 2, 2),
(97, 'Mi Mi ', 'female', 'mimikhainglin990@gmail.com', '09441386275', 'TWltaWtoYWluZ2xpbjkwQA==', 'public/image/book_6884b7d2cce52_book_688488792bccb_book_68846740c8465_doctor.jpg', 1, 1, 0, 2, 6),
(98, 'Htet Htet Win', 'female', 'htet050522@gmail.com', '0995507845', 'VTBoU2JHUkVRVEZOUkZWNVVVRTlQUT09', 'public/image/book_6885e10f28d33_doctor_6885d812f2fc8_doctor_6885d77b6f05e_doctor_6885d768dc8cb_book_68835428ecffa_photo-1638202993928-7267aad84c31.avif', 1, 1, 0, 2, 6),
(103, 'Phue Phue', 'female', 'phuephue11@gmail.com', '09955077826', 'UGh1ZXBodWUxMUA=', 'public/image/doctor_68870b1fb9091_doctor_688708e9ba8e9_doctor_6887067b660f8_doctor_6885d768dc8cb_book_68835428ecffa_photo-1638202993928-7267aad84c31.avif', 1, 1, 0, 2, 6),
(107, 'Wai  Yan', 'male', 'waiyan45@gmail.com', '09955078928', 'VmpKR2NHVlhSblZOVlVFOQ==', 'public/image/doctor_68872314362d1_book_68834e7f82beb_premium_photo-1658506671316-0b293df7c72b.avif', 0, 0, 0, 2, 2),
(108, 'Htet Htet Win', 'female', 'htethtetwin654@gmail.com', '09771223456', 'SHRldGh0ZXR3aW42NTRA', 'default_profile.jpg', 0, 0, 1, 3, 6),
(109, 'Mi Mi ', 'female', 'mimikhainglin80@gmail.com', '0955664412', 'TWltaWtoYWluZ2xpbjgwQA==', 'default_profile.jpg', 0, 0, 1, 3, 6),
(110, 'Mi Mi ', 'female', 'mimikhainglin90@gmail.com', '0955664417', 'TWltaWtoYWluZ2xpbjkwQA==', 'default_profile.jpg', 0, 0, 0, 3, 6),
(111, 'Mi Mi ', 'female', 'mimikhainglin50@gmail.com', '09441386938', 'TWltaWtoYWluZ2xpbjUwQA==', 'default_profile.jpg', 0, 0, 0, 3, 6),
(112, 'Paingkyawmoe', 'male', 'paing@gmail.com', '09750231601', 'UGFpbmdAMTIz', 'default_profile.jpg', 0, 0, 1, 3, 6),
(113, 'Paing', 'male', 'paing1@gmail.com', '09672636439', 'UGFpbmdAMTIz', 'default_profile.jpg', 0, 0, 1, 3, 6),
(114, 'Paingkyaw', 'male', 'Paingkyaw@gmail.com', '09750231602', 'UGFpbmdAMTIz', 'default_profile.jpg', 0, 0, 1, 3, 6),
(115, 'Htet Kyaw Lin', 'male', 'htetkyawlin11@gmail.com', '09755566523', 'SGV0a3lhd2xpbjExQA==', 'default_profile.jpg', 0, 0, 0, 3, 6),
(116, 'Htet Kyaw', 'male', 'htetkyaw11@gmail.com', '09441386939', 'SHRldGt5YXcxMUA=', 'default_profile.jpg', 0, 0, 1, 3, 6),
(117, 'Htet Kyaw Lin', 'male', 'htetkyawlin1997@gmail.com', '09556642358', 'SHRldGt5YXdsaW4xOTk3QA==', 'default_profile.jpg', 0, 0, 1, 3, 6),
(118, 'Htet Kyaw Win', 'male', 'htetkyawwin25@gmail.com', '09441386925', 'SHRldGt5YXd3aW4yNUA=', 'default_profile.jpg', 0, 0, 1, 3, 6),
(119, 'Jo Jo', 'male', 'jojo500@gmail.com', '0945632477', 'Sm9qbzUwMEA=', 'default_profile.jpg', 0, 0, 1, 3, 6),
(124, 'Jo O', 'female', 'jojo112@gmail.com', '09760585356', 'Sm9qbzExMkA=', 'public/image/doctor_6891524c028b6_book_68835f89c3204_book_68835428ecffa_photo-1638202993928-7267aad84c31.avif', 0, 0, 0, 2, 2),
(125, 'Daniel Smith', 'male', 'danielsmith55@gmail.com', '0978456321', 'RGFuaWVsc21pdGg1NUA=', 'public/image/doctor_68918fdab76b4_istockphoto-2158610739-612x612.webp', 1, 1, 0, 2, 6);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_categories_income`
-- (See below for the actual view)
--
CREATE TABLE `vw_categories_income` (
`amount` int
,`category_name` varchar(255)
,`date` date
,`id` int
,`user_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_categories_type`
-- (See below for the actual view)
--
CREATE TABLE `vw_categories_type` (
`description` varchar(255)
,`id` int
,`name` varchar(255)
,`type_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_expenses_categories_users`
-- (See below for the actual view)
--
CREATE TABLE `vw_expenses_categories_users` (
`amount` double
,`category_name` varchar(255)
,`date` date
,`id` int
,`qty` int
,`user_name` varchar(255)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctorprofile`
--
ALTER TABLE `doctorprofile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `doctorprofile`
--
ALTER TABLE `doctorprofile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `timeslots`
--
ALTER TABLE `timeslots`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `doctor_view`  AS SELECT `u`.`id` AS `user_id`, `d`.`id` AS `doctorprofile_id`, `u`.`name` AS `name`, `u`.`gender` AS `gender`, `u`.`email` AS `email`, `u`.`phone` AS `phone`, `u`.`profile_image` AS `profile_image`, `u`.`status_id` AS `status_id`, `s`.`name` AS `status_name`, `d`.`degree` AS `degree`, `d`.`experience` AS `experience`, `d`.`bio` AS `bio`, `d`.`fee` AS `fee`, `d`.`specialty` AS `specialty`, `d`.`address` AS `address`, `t`.`id` AS `timeslot_id`, `t`.`start_time` AS `start_time`, `t`.`end_time` AS `end_time` FROM (((`users` `u` join `doctorprofile` `d` on((`u`.`id` = `d`.`user_id`))) left join `timeslots` `t` on((`u`.`id` = `t`.`user_id`))) join `status` `s` on((`u`.`status_id` = `s`.`id`))) WHERE (`u`.`type_id` = 2) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_categories_income`
--
DROP TABLE IF EXISTS `vw_categories_income`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_categories_income`  AS SELECT `incomes`.`id` AS `id`, `categories`.`name` AS `category_name`, `incomes`.`amount` AS `amount`, `users`.`name` AS `user_name`, `incomes`.`date` AS `date` FROM ((`incomes` left join `categories` on((`categories`.`id` = `incomes`.`category_id`))) left join `users` on((`users`.`id` = `incomes`.`user_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_categories_type`
--
DROP TABLE IF EXISTS `vw_categories_type`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_categories_type`  AS SELECT `categories`.`id` AS `id`, `categories`.`name` AS `name`, `categories`.`description` AS `description`, `types`.`name` AS `type_name` FROM (`categories` left join `types` on((`categories`.`type_id` = `types`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_expenses_categories_users`
--
DROP TABLE IF EXISTS `vw_expenses_categories_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_expenses_categories_users`  AS SELECT `expenses`.`id` AS `id`, `expenses`.`amount` AS `amount`, `expenses`.`qty` AS `qty`, `expenses`.`date` AS `date`, `categories`.`name` AS `category_name`, `users`.`name` AS `user_name` FROM ((`expenses` left join `categories` on((`categories`.`id` = `expenses`.`category_id`))) left join `users` on((`users`.`id` = `expenses`.`user_id`))) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
