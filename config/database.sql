-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2026 at 02:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fitlife_wellness`
--

CREATE DATABASE IF NOT EXISTS `fitlife_wellness` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `fitlife_wellness`;

-- --------------------------------------------------------

--
-- Drop existing tables if they exist (in reverse order of dependencies)
--

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `trainer_program_history`;
DROP TABLE IF EXISTS `trainer_certification`;
DROP TABLE IF EXISTS `program_member`;
DROP TABLE IF EXISTS `class_member`;
DROP TABLE IF EXISTS `payment`;
DROP TABLE IF EXISTS `class`;
DROP TABLE IF EXISTS `trainer`;
DROP TABLE IF EXISTS `cashier`;
DROP TABLE IF EXISTS `program`;
DROP TABLE IF EXISTS `program_category`;
DROP TABLE IF EXISTS `member`;
DROP TABLE IF EXISTS `membership_type`;
DROP TABLE IF EXISTS `employee`;
DROP TABLE IF EXISTS `certification`;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------

--
-- Table structure for table `cashier`
--

CREATE TABLE `cashier` (
  `employee_id` int(11) NOT NULL,
  `work_shift` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cashier`
--

INSERT INTO `cashier` (`employee_id`, `work_shift`) VALUES
(102, 'Morning (8am-4pm)'),
(104, 'Evening (3pm-11pm)'),
(106, 'Morning (8am-4pm)'),
(108, 'Evening (3pm-11pm)'),
(110, 'Morning (8am-4pm)');

-- --------------------------------------------------------

--
-- Table structure for table `certification`
--

CREATE TABLE `certification` (
  `cert_id` int(11) NOT NULL,
  `cert_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certification`
--

INSERT INTO `certification` (`cert_id`, `cert_name`) VALUES
(1, 'Certified Personal Trainer (NASM)'),
(2, 'Yoga Alliance RYT 200'),
(3, 'Group Fitness Instructor (ACE)'),
(4, 'Spinning Certified'),
(5, 'Pilates Mat Level 1'),
(6, 'First Aid & CPR');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `class_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `room_number` varchar(10) DEFAULT NULL,
  `program_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_date`, `start_time`, `end_time`, `room_number`, `program_id`, `employee_id`) VALUES
(3001, '2025-11-24', '18:00:00', '19:00:00', 'Room 1', 203, 103),
(3002, '2025-11-25', '09:00:00', '10:00:00', 'Room 2', 202, 105),
(3003, '2025-11-26', '12:00:00', '13:00:00', 'Room 1', 205, 107),
(3004, '2025-11-27', '17:30:00', '18:30:00', 'Room 1', 203, 103),
(3005, '2025-11-28', '10:30:00', '11:30:00', 'Room 2', 206, 109),
(3006, '2025-11-29', '08:00:00', '09:00:00', 'Room 2', 202, 105);

-- --------------------------------------------------------

--
-- Table structure for table `class_member`
--

CREATE TABLE `class_member` (
  `class_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_member`
--

INSERT INTO `class_member` (`class_id`, `member_id`) VALUES
(3001, 4001),
(3001, 4003),
(3002, 4005),
(3003, 4002),
(3004, 4001),
(3005, 4003);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `employee_ic` varchar(20) NOT NULL,
  `employee_contact` varchar(30) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `date_working` date DEFAULT NULL,
  `employee_salary` decimal(10,2) DEFAULT NULL,
  `employee_type` enum('C','T') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `employee_name`, `employee_ic`, `employee_contact`, `gender`, `date_of_birth`, `date_working`, `employee_salary`, `employee_type`) VALUES
(101, 'Alex Tan', '950101-07-5011', '012-3456789', 'Male', '1995-01-01', '2023-01-15', 4500.00, 'T'),
(102, 'Siti Binti Ahmad', '980520-10-6022', '017-8765432', 'Female', '1998-05-20', '2024-03-10', 3200.00, 'C'),
(103, 'David Lee', '921125-14-7033', '019-2345678', 'Male', '1992-11-25', '2022-08-01', 5000.00, 'T'),
(104, 'Muthu Samy', '960810-01-8044', '013-1122334', 'Male', '1996-08-10', '2023-05-20', 3000.00, 'C'),
(105, 'Chloe Wong', '990315-04-9055', '016-5432109', 'Female', '1999-03-15', '2024-01-01', 4800.00, 'T'),
(106, 'Aina Razali', '970707-07-1066', '011-9876543', 'Female', '1997-07-07', '2024-06-12', 3100.00, 'C'),
(107, 'Ben Lim', '910404-03-2177', '016-1234567', 'Male', '1991-04-04', '2021-11-20', 4900.00, 'T'),
(108, 'Zahra Hassan', '930218-05-3288', '017-6655443', 'Female', '1993-02-18', '2023-09-01', 3050.00, 'C'),
(109, 'Chris Ng', '900909-08-4399', '018-7788990', 'Male', '1990-09-09', '2024-05-05', 4700.00, 'T'),
(110, 'Diana Teo', '940424-06-5400', '019-1020304', 'Female', '1994-04-24', '2024-08-15', 3150.00, 'C');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `member_name` varchar(100) NOT NULL,
  `member_ic` varchar(20) NOT NULL,
  `member_contact` varchar(30) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `membership_status` varchar(20) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `member_name`, `member_ic`, `member_contact`, `gender`, `date_of_birth`, `membership_status`, `type_id`) VALUES
(4001, 'Emily Chen', '001010-02-5111', '014-9988776', 'Female', '2000-10-10', 'Active', 1),
(4002, 'Zainal Abidin', '880303-10-6222', '010-1110009', 'Male', '1988-03-03', 'Active', 3),
(4003, 'Jenny Lim', '940717-14-7333', '018-7654321', 'Female', '1994-07-17', 'Active', 2),
(4004, 'Ben Ho', '901205-07-8444', '015-2233445', 'Male', '1990-12-05', 'Active', 3),
(4005, 'Farah Nabila', '010620-04-9555', '011-3344556', 'Female', '2001-06-20', 'Active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `membership_type`
--

CREATE TABLE `membership_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(20) NOT NULL,
  `monthly_fee` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_type`
--

INSERT INTO `membership_type` (`type_id`, `type_name`, `monthly_fee`) VALUES
(1, 'Basic', 50.00),
(2, 'Standard', 100.00),
(3, 'Premium', 150.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(30) DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `payment_date`, `payment_amount`, `payment_method`, `member_id`, `employee_id`) VALUES
(5001, '2025-11-01', 150.00, 'Card', 4001, 102),
(5002, '2025-11-03', 1200.00, 'Bank Transfer', 4004, 104),
(5003, '2025-11-05', 180.00, 'Cash', 4003, 102),
(5004, '2025-11-10', 1500.00, 'Card', 4002, 106),
(5005, '2025-11-15', 150.00, 'Bank Transfer', 4005, 108),
(5006, '2025-11-20', 25.00, 'Cash', 4001, 110);

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(100) NOT NULL,
  `program_duration` varchar(50) DEFAULT NULL,
  `program_fee` decimal(10,2) DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `program_name`, `program_duration`, `program_fee`, `employee_id`, `category_id`) VALUES
(201, 'Beginner Weight Loss', '12 Weeks', 1200.00, 101, 1),
(202, 'Morning Yoga Flow', '60 Minutes', 30.00, 105, 2),
(203, 'Evening HIIT', '45 Minutes', 25.00, 103, 2),
(204, 'Advanced Strength', '16 Weeks', 1500.00, 101, 1),
(205, 'Zumba Fitness', '60 Minutes', 20.00, 107, 2),
(206, 'Core and Stability', '45 Minutes', 35.00, 109, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program_category`
--

CREATE TABLE `program_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_category`
--

INSERT INTO `program_category` (`category_id`, `category_name`) VALUES
(2, 'Group Class'),
(1, 'Personal Training');

-- --------------------------------------------------------

--
-- Table structure for table `program_member`
--

CREATE TABLE `program_member` (
  `program_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_member`
--

INSERT INTO `program_member` (`program_id`, `member_id`) VALUES
(201, 4003),
(201, 4004),
(201, 4005),
(204, 4001),
(204, 4002),
(206, 4003);

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE `trainer` (
  `employee_id` int(11) NOT NULL,
  `expertise` varchar(100) DEFAULT NULL,
  `certification` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`employee_id`, `expertise`, `certification`) VALUES
(101, 'Weightlifting', 'Certified Personal Trainer'),
(103, 'Cardio & HIIT', 'Group Fitness Instructor'),
(105, 'Yoga & Flexibility', 'Yoga Alliance RYT 200'),
(107, 'Spinning & Endurance', 'Spinning Certified'),
(109, 'Pilates', 'Pilates Mat Certified');

-- --------------------------------------------------------

--
-- Table structure for table `trainer_certification`
--

CREATE TABLE `trainer_certification` (
  `employee_id` int(11) NOT NULL,
  `cert_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer_certification`
--

INSERT INTO `trainer_certification` (`employee_id`, `cert_id`) VALUES
(101, 1),
(101, 6),
(103, 3),
(105, 2),
(107, 4),
(109, 5),
(109, 6);

-- --------------------------------------------------------

--
-- Table structure for table `trainer_program_history`
--

CREATE TABLE `trainer_program_history` (
  `history_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer_program_history`
--

INSERT INTO `trainer_program_history` (`history_id`, `employee_id`, `category_id`, `start_date`, `end_date`) VALUES
(1, 101, 1, '2023-01-15', '2024-01-15'),
(2, 103, 2, '2022-08-01', NULL),
(3, 105, 2, '2024-01-01', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashier`
--
ALTER TABLE `cashier`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`cert_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `fk_class_program` (`program_id`),
  ADD KEY `fk_class_trainer` (`employee_id`);

--
-- Indexes for table `class_member`
--
ALTER TABLE `class_member`
  ADD PRIMARY KEY (`class_id`,`member_id`),
  ADD KEY `fk_cm_member` (`member_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `employee_ic` (`employee_ic`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_ic` (`member_ic`),
  ADD KEY `fk_member_type` (`type_id`);

--
-- Indexes for table `membership_type`
--
ALTER TABLE `membership_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payment_cashier` (`employee_id`),
  ADD KEY `fk_payment_member` (`member_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`program_id`),
  ADD KEY `fk_program_trainer` (`employee_id`),
  ADD KEY `fk_program_cat` (`category_id`);

--
-- Indexes for table `program_category`
--
ALTER TABLE `program_category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `program_member`
--
ALTER TABLE `program_member`
  ADD PRIMARY KEY (`program_id`,`member_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `trainer`
--
ALTER TABLE `trainer`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `trainer_certification`
--
ALTER TABLE `trainer_certification`
  ADD PRIMARY KEY (`employee_id`,`cert_id`),
  ADD KEY `cert_id` (`cert_id`);

--
-- Indexes for table `trainer_program_history`
--
ALTER TABLE `trainer_program_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_history_category` (`category_id`),
  ADD KEY `fk_history_trainer` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certification`
--
ALTER TABLE `certification`
  MODIFY `cert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3007;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4006;

--
-- AUTO_INCREMENT for table `membership_type`
--
ALTER TABLE `membership_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5007;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `program_category`
--
ALTER TABLE `program_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trainer_program_history`
--
ALTER TABLE `trainer_program_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cashier`
--
ALTER TABLE `cashier`
  ADD CONSTRAINT `fk_cashier_employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `fk_class_program` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`),
  ADD CONSTRAINT `fk_class_trainer` FOREIGN KEY (`employee_id`) REFERENCES `trainer` (`employee_id`);

--
-- Constraints for table `class_member`
--
ALTER TABLE `class_member`
  ADD CONSTRAINT `fk_cm_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`),
  ADD CONSTRAINT `fk_cm_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`);

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `fk_member_type` FOREIGN KEY (`type_id`) REFERENCES `membership_type` (`type_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_cashier` FOREIGN KEY (`employee_id`) REFERENCES `cashier` (`employee_id`),
  ADD CONSTRAINT `fk_payment_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`);

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `fk_program_cat` FOREIGN KEY (`category_id`) REFERENCES `program_category` (`category_id`),
  ADD CONSTRAINT `fk_program_trainer` FOREIGN KEY (`employee_id`) REFERENCES `trainer` (`employee_id`);

--
-- Constraints for table `program_member`
--
ALTER TABLE `program_member`
  ADD CONSTRAINT `fk_pm_member` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
  ADD CONSTRAINT `fk_pm_program` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`);

--
-- Constraints for table `trainer`
--
ALTER TABLE `trainer`
  ADD CONSTRAINT `fk_trainer_employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `trainer_certification`
--
ALTER TABLE `trainer_certification`
  ADD CONSTRAINT `trainer_certification_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `trainer` (`employee_id`),
  ADD CONSTRAINT `trainer_certification_ibfk_2` FOREIGN KEY (`cert_id`) REFERENCES `certification` (`cert_id`);

--
-- Constraints for table `trainer_program_history`
--
ALTER TABLE `trainer_program_history`
  ADD CONSTRAINT `fk_history_trainer` FOREIGN KEY (`employee_id`) REFERENCES `trainer` (`employee_id`),
  ADD CONSTRAINT `fk_history_category` FOREIGN KEY (`category_id`) REFERENCES `program_category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
