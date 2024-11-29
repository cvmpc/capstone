-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 04:01 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cvmpc`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `sitio` varchar(45) NOT NULL,
  `barangay` varchar(45) NOT NULL,
  `city` varchar(45) NOT NULL,
  `province` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `sitio`, `barangay`, `city`, `province`) VALUES
(1, 'Mag-asawang Parang', 'Parang', 'Calapan City', 'Oriental Mindoro'),
(2, 'Ewan', 'Salong', 'Calapan City', 'Oriental Mindoro'),
(3, 'ewan', 'sampaguita', 'Naujan', 'Oriental Mindoro'),
(4, 'Kalawang', 'Tanod', 'Calapan City', 'Oriental Mindoro'),
(5, '', '', '', ''),
(6, 'dunow', 'Silonay', 'Calapan City', 'Oriental Mindoro'),
(7, '', '', '', ''),
(10, '', '', '', 'Oriental Mindoro'),
(11, '', '', '', ''),
(12, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `uc_id` int(11) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) NOT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `last_login` date DEFAULT NULL,
  `about_me` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `uc_id`, `first_name`, `middle_name`, `last_name`, `phone_number`, `is_active`, `last_login`, `about_me`) VALUES
(1, 5, 'Kenjie', 'Flores', 'Lucy', '091234567819', NULL, NULL, 'Admin of the Calapan Vendors Multi-Purpose Cooperative (CVMPC)\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `sent_at` date DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `document_id` int(11) NOT NULL,
  `file_name` varchar(45) DEFAULT NULL,
  `produce_date` date DEFAULT NULL,
  `loan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loan_id` int(11) NOT NULL,
  `principal_amount` decimal(15,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `remaining_balance` decimal(15,2) DEFAULT NULL,
  `status` enum('Active','Inactive','Closed','On Progress') DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `release_date` varchar(45) DEFAULT NULL,
  `loan_plan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`loan_id`, `principal_amount`, `start_date`, `end_date`, `remaining_balance`, `status`, `application_id`, `release_date`, `loan_plan_id`) VALUES
(1, 60000.00, '2024-11-25', '2025-11-25', NULL, 'Active', 1, NULL, 1),
(2, 100000.00, NULL, NULL, NULL, 'On Progress', 2, NULL, 1),
(3, 80000.00, NULL, NULL, NULL, 'On Progress', 4, NULL, 3),
(4, 120000.00, NULL, NULL, NULL, 'On Progress', 5, NULL, 5),
(5, 50000.00, NULL, NULL, NULL, 'On Progress', 6, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `loan_application`
--

CREATE TABLE `loan_application` (
  `application_id` int(11) NOT NULL,
  `amount_requested` decimal(15,2) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Active') DEFAULT NULL,
  `review_notes` varchar(255) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_application`
--

INSERT INTO `loan_application` (`application_id`, `amount_requested`, `purpose`, `application_date`, `status`, `review_notes`, `member_id`) VALUES
(1, 60000.00, NULL, '2024-11-06', 'Active', NULL, 8),
(2, 100000.00, 'wala lang', '2024-11-29', 'Pending', NULL, 8),
(4, 80000.00, 'trip lang\r\n', '2024-11-29', 'Pending', NULL, 8),
(5, 120000.00, 'maganda ata to', '2024-11-29', 'Pending', NULL, 8),
(6, 50000.00, 'pang kain lang', '2024-11-29', 'Pending', NULL, 9);

-- --------------------------------------------------------

--
-- Table structure for table `loan_plan`
--

CREATE TABLE `loan_plan` (
  `loan_plan_id` int(11) NOT NULL,
  `interest_rate` decimal(5,2) DEFAULT NULL,
  `penalty_rate` decimal(5,2) DEFAULT NULL,
  `monthly_term` int(11) DEFAULT NULL,
  `loan_type_id` int(11) DEFAULT NULL,
  `status` enum('Active','Deactivated') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_plan`
--

INSERT INTO `loan_plan` (`loan_plan_id`, `interest_rate`, `penalty_rate`, `monthly_term`, `loan_type_id`, `status`) VALUES
(1, 10.00, 5.00, 12, 1, 'Active'),
(2, 5.00, 5.00, 24, 2, 'Active'),
(3, 15.00, 10.00, 36, 3, 'Active'),
(4, 15.00, 20.00, 10, 5, 'Active'),
(5, 15.00, 20.00, 10, 4, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `loan_report`
--

CREATE TABLE `loan_report` (
  `loan_report_id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `loan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `loan_type_id` int(11) NOT NULL,
  `type_name` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`loan_type_id`, `type_name`, `description`) VALUES
(1, 'Regular Loan', 'A loan for small business owners to help them grow their business, buy equipment, or cover daily expenses.'),
(2, 'Tulong Pangkabuhayan Para sa Nayon (TUPASAN)', 'A loan for rural families to start or grow small projects like farming, selling goods, or crafts to earn a living.'),
(3, 'MIGS Loan (Members in Good Standing)', 'A loan for members who follow the rules, have no violations, and actively participate in the cooperative. It offers better terms.'),
(4, 'Provident Loan', 'A loan to help with urgent needs like medical bills, house repairs, or other unexpected expenses.'),
(5, 'Rice Production Loan', 'A loan for farmers to help them plant and grow rice by providing funds for seeds, tools, and other farming needs.'),
(6, 'Necessity Loan', 'A loan to help members buy important items like phones, laptops, or bicycles to make life easier.'),
(7, 'Commodity Loan', 'A loan that provides grocery gift certificates for Puregold, making it easier for members to buy their everyday needs.'),
(8, 'Feeds Loan', 'A loan for livestock farmers to buy feeds and other supplies, offered with support from SoroSoro Ibaba Development Cooperative (SIDC).');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `uc_id` int(11) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `status` enum('Active','Inactive','Deactivated','Archived') NOT NULL,
  `line_of_business` varchar(45) DEFAULT NULL,
  `about_me` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `uc_id`, `first_name`, `middle_name`, `last_name`, `phone_number`, `date_of_birth`, `registration_date`, `is_active`, `status`, `line_of_business`, `about_me`) VALUES
(8, 8, 'Rolan Joseph', 'Ewan', 'Abutar', '0986743634', '2004-02-27', '2023-07-05', 1, 'Active', 'Investment', 'Im the one and only Abutar ng mundo'),
(9, 9, 'Boy', 'Sobrang', 'Tapang', '09111111111', '0000-00-00', NULL, NULL, 'Active', '', 'juan dela cruz the pagasa ng mundo'),
(12, 12, 'Victoria', NULL, 'Magtangola', NULL, NULL, NULL, NULL, 'Active', NULL, NULL),
(13, 13, 'juan', NULL, 'tamad', NULL, NULL, NULL, NULL, 'Active', NULL, NULL),
(14, 14, 'sdfasf', NULL, 'fasfs', NULL, NULL, NULL, NULL, 'Active', NULL, NULL),
(15, 15, 'sad', NULL, 'dasd', NULL, NULL, NULL, NULL, 'Active', NULL, NULL),
(16, 16, 'dasd', NULL, 'dsadsad', NULL, NULL, NULL, NULL, 'Active', NULL, NULL),
(17, 17, 'sasda', NULL, 'dsads', NULL, NULL, NULL, NULL, 'Active', NULL, NULL),
(18, 18, 'magpa', 'deact', 'kana', '', NULL, NULL, NULL, 'Archived', '', 'ahiuasghbfk'),
(20, 21, 'aldous', 'one', 'punch', NULL, NULL, NULL, NULL, 'Active', NULL, NULL),
(21, 22, 'Benedeitta', 'spada', 'watatatat', NULL, NULL, '2024-11-24', NULL, 'Archived', NULL, NULL),
(22, 26, 'fsdf', 'fdsf', 'dfd', NULL, NULL, NULL, NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` date NOT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `notification_type` enum('Payment','Loan Status','Reminder') DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('PayMaya','Gcash') DEFAULT NULL,
  `status` enum('Successful','Failed','Pending') DEFAULT NULL,
  `loan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repayment_schedule`
--

CREATE TABLE `repayment_schedule` (
  `schedule_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `is_paid` tinyint(1) DEFAULT NULL,
  `loan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `uc_id` int(11) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `status` enum('Active','Inactive','Deactivated','Archived') DEFAULT NULL,
  `about_me` varchar(255) DEFAULT NULL,
  `last_login` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `uc_id`, `first_name`, `middle_name`, `last_name`, `phone_number`, `hire_date`, `is_active`, `status`, `about_me`, `last_login`, `date_of_birth`) VALUES
(3, 6, 'Jamelyn', 'Miller', 'Manongsong', '0912213212', '2023-11-09', NULL, 'Active', 'Dean Lister', NULL, '2024-11-01'),
(4, 19, 'alaska', NULL, 'evaporads', NULL, '2022-11-02', NULL, 'Active', NULL, NULL, '2024-11-08'),
(7, 25, 'Fanny', 'Strit', 'Keybolll', '', '1970-01-01', NULL, 'Active', '', NULL, NULL),
(8, 27, 'fsf', 'fasf', 'fsafsafsaf', NULL, '1970-01-01', NULL, 'Archived', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_document`
--

CREATE TABLE `uploaded_document` (
  `uploaded_document_id` int(11) NOT NULL,
  `document_type` varchar(45) DEFAULT NULL,
  `uploaded_date` varchar(45) DEFAULT NULL,
  `member_member_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_credentials`
--

CREATE TABLE `user_credentials` (
  `uc_id` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(45) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `us_id` int(11) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `pp` varchar(255) NOT NULL DEFAULT 'default-pp.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_credentials`
--

INSERT INTO `user_credentials` (`uc_id`, `password`, `role`, `address_id`, `us_id`, `email`, `pp`) VALUES
(5, '$2y$10$Jcw/rLupXVEpsnF7soNLU.LLud7wRupQb8CMqcnI5C0NcokVZDoSu', 'Admin', 1, 1, 'admin@gmail.com', 'default-pp.png'),
(6, '$2y$10$YFffS6T9wLjRZyTqrq/RxujoezucJG7g1gge5nHtRc4orrg8TrKxu', 'Staff', 3, 4, 'staff@gmail.com', 'default-pp.png'),
(8, '$2y$10$XshqwnMnbGa4yPc.mi5MIe3YsGeARXNYydiGNU3.3s53s2amK8Bvy', 'Member', 2, 3, 'member@gmail.com', 'default-pp.png'),
(9, '$2y$10$IbYWeqHjD2zUZjgodDwm/eVRj2LHBB15YSAjIIookXhuH/wWKA2aK', 'Member', 4, 5, 'boy@gmail.com', 'default-pp.png'),
(12, '$2y$10$aVNZtgUbZHHmpVNEZE/CYeu1TQh2jIlTVB.blG', 'Member', NULL, NULL, 'vic@gmail.com', 'default-pp.png'),
(13, '$2y$10$4vpLley/N0cYzKocFTIrT.UJzLD8GpYwvM3MdX', 'Member', NULL, NULL, 'juan@gmail.com', 'default-pp.png'),
(14, '$2y$10$FGU3uN.DfcN1TsacpwyDjuURCQRAuRM6SA/Bcr', 'Member', NULL, NULL, 'juans@gmail.com', 'default-pp.png'),
(15, '$2y$10$syR7OtLCsMQtrYkgXQO9seA7G6q3SwC5q.Lyd3', 'Member', NULL, NULL, 'dasdsa@gmail.com', 'default-pp.png'),
(16, '$2y$10$76R3oiE5ybjczKQnWoBkmecCjRBdeiEomF12R3', 'Member', NULL, NULL, 'juan_fasf@gmail.com', 'default-pp.png'),
(17, '$2y$10$vmLRtmdIBPT5/sDracGdZeym08VhA8I4Q/VMgv', 'Member', NULL, NULL, 'saddsa@gmail.com', 'default-pp.png'),
(18, '$2y$10$xD001ynpAdb0UhTSA.T/k.x8kzwhahNP7/ZrOUdN.32mFp84NV4a2', 'Member', 5, 6, 'mem@gmail.com', 'default-pp.png'),
(19, 'aaaaa', 'Staff', NULL, NULL, 'aaaa@gmail.com', 'default-pp.png'),
(21, '$2y$10$uOvN8lAtfnOn9blF7ueGMOlJQgvsZSUn17yZ6osA2xwXHT7tt7V8a', 'Member', 6, NULL, 'aldous@gmail.com', 'default-pp.png'),
(22, '$2y$10$.AqVOlIV/kHAKCo8n5tFieCXJrIo6xj2dvAMlbJocwyZ.9pzh1uim', 'Member', 7, NULL, 'bene@gmail.com', 'default-pp.png'),
(25, '$2y$10$c7B2tkzRbKxQGKKoPkAsf.ZiU6YfIYBsL.OwFGZe.DrvTqaRErxBa', 'Staff', 10, 7, 'fanny@gmail.com', 'default-pp.png'),
(26, '$2y$10$1r08mIO67aaVkUpwC2XhEO9m51oBXgsyWmMuS6l/s3fB24JhiKk72', 'Member', 11, NULL, 'tikboy_member@gmail.com', 'default-pp.png'),
(27, '$2y$10$H35gbm6Ncrg9EkC6O9vDreK9J4BmgwgV37KXiET53Eh1NIAUlhCLq', 'Staff', 12, NULL, 'neneng_staff@gmail.com', 'default-pp.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_socials`
--

CREATE TABLE `user_socials` (
  `us_id` int(11) NOT NULL,
  `facebook_link` varchar(45) DEFAULT NULL,
  `twitter_link` varchar(45) DEFAULT NULL,
  `instagram_link` varchar(45) DEFAULT NULL,
  `linkedin_link` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_socials`
--

INSERT INTO `user_socials` (`us_id`, `facebook_link`, `twitter_link`, `instagram_link`, `linkedin_link`) VALUES
(1, 'https://www.facebook.com/kenjie.lucy/', '', 'https://www.instagram.com/knjskie/', ''),
(2, '', '', '', ''),
(3, 'https://www.facebook.com/abutarrolan27', '', '', ''),
(4, 'https://www.facebook.com/jamelyn.manongsong.3', '', '', ''),
(5, '', '', '', ''),
(6, '', '', '', ''),
(7, '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `admin_ibfk_2` (`uc_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `loan_plan_id` (`loan_plan_id`);

--
-- Indexes for table `loan_application`
--
ALTER TABLE `loan_application`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `loan_plan`
--
ALTER TABLE `loan_plan`
  ADD PRIMARY KEY (`loan_plan_id`),
  ADD KEY `loan_type_id` (`loan_type_id`);

--
-- Indexes for table `loan_report`
--
ALTER TABLE `loan_report`
  ADD PRIMARY KEY (`loan_report_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`loan_type_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `member_ibfk_2` (`uc_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `repayment_schedule`
--
ALTER TABLE `repayment_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `staff_ibfk_2` (`uc_id`);

--
-- Indexes for table `uploaded_document`
--
ALTER TABLE `uploaded_document`
  ADD PRIMARY KEY (`uploaded_document_id`),
  ADD KEY `member_member_id` (`member_member_id`);

--
-- Indexes for table `user_credentials`
--
ALTER TABLE `user_credentials`
  ADD PRIMARY KEY (`uc_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `us_ibfk_2` (`address_id`),
  ADD KEY `us_ibfk_3` (`us_id`);

--
-- Indexes for table `user_socials`
--
ALTER TABLE `user_socials`
  ADD PRIMARY KEY (`us_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loan_application`
--
ALTER TABLE `loan_application`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loan_plan`
--
ALTER TABLE `loan_plan`
  MODIFY `loan_plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loan_report`
--
ALTER TABLE `loan_report`
  MODIFY `loan_report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `loan_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repayment_schedule`
--
ALTER TABLE `repayment_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `uploaded_document`
--
ALTER TABLE `uploaded_document`
  MODIFY `uploaded_document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_credentials`
--
ALTER TABLE `user_credentials`
  MODIFY `uc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_socials`
--
ALTER TABLE `user_socials`
  MODIFY `us_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_2` FOREIGN KEY (`uc_id`) REFERENCES `user_credentials` (`uc_id`);

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`loan_id`);

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `loan_application` (`application_id`),
  ADD CONSTRAINT `loan_ibfk_2` FOREIGN KEY (`loan_plan_id`) REFERENCES `loan_plan` (`loan_plan_id`);

--
-- Constraints for table `loan_application`
--
ALTER TABLE `loan_application`
  ADD CONSTRAINT `loan_application_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`);

--
-- Constraints for table `loan_plan`
--
ALTER TABLE `loan_plan`
  ADD CONSTRAINT `loan_plan_ibfk_1` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`loan_type_id`);

--
-- Constraints for table `loan_report`
--
ALTER TABLE `loan_report`
  ADD CONSTRAINT `loan_report_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`loan_id`);

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_2` FOREIGN KEY (`uc_id`) REFERENCES `user_credentials` (`uc_id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`loan_id`);

--
-- Constraints for table `repayment_schedule`
--
ALTER TABLE `repayment_schedule`
  ADD CONSTRAINT `repayment_schedule_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`loan_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`uc_id`) REFERENCES `user_credentials` (`uc_id`);

--
-- Constraints for table `uploaded_document`
--
ALTER TABLE `uploaded_document`
  ADD CONSTRAINT `uploaded_document_ibfk_1` FOREIGN KEY (`member_member_id`) REFERENCES `member` (`member_id`);

--
-- Constraints for table `user_credentials`
--
ALTER TABLE `user_credentials`
  ADD CONSTRAINT `us_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`),
  ADD CONSTRAINT `us_ibfk_3` FOREIGN KEY (`us_id`) REFERENCES `user_socials` (`us_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
