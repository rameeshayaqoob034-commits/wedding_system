-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2026 at 11:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wedding_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `event_id` int(10) UNSIGNED NOT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_type` varchar(50) DEFAULT NULL,
  `hall` varchar(50) DEFAULT NULL,
  `guest_count` int(11) DEFAULT NULL,
  `hall_charges` int(11) DEFAULT NULL,
  `advance_payment` int(11) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `Decor` int(11) NOT NULL,
  `Heater` int(11) NOT NULL,
  `Ac` int(11) NOT NULL,
  `Tax_Amount` int(11) NOT NULL,
  `Breakage` int(11) NOT NULL,
  `Payment_Type` varchar(20) NOT NULL,
  `Reciept_image` varchar(255) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `event_status` varchar(20) NOT NULL,
  `event_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`event_id`, `client_name`, `phone`, `event_date`, `event_type`, `hall`, `guest_count`, `hall_charges`, `advance_payment`, `payment_status`, `Decor`, `Heater`, `Ac`, `Tax_Amount`, `Breakage`, `Payment_Type`, `Reciept_image`, `total_amount`, `event_status`, `event_time`) VALUES
(1, 'Rameesha Yaqoob', '03049998524', '2026-03-28', 'Barat', '1', 350, 400000, 15000, 'Pending', 50000, 0, 15000, 15000, 0, 'Bank', '1772899172_unnamed.jpg', 495000, '', ''),
(2, 'Alisha', '03049998524', '2026-03-25', 'Barat', '1', 400, 500000, 2000, 'Pending', 60000, 0, 15000, 25000, 0, 'Bank', '1772997212_1000382272.jpg', 602000, '', ''),
(3, 'Ahsan', '03049998524', '2026-03-22', 'walima', '1', 500, 5500000, 20000, 'Pending', 60000, 0, 15000, 25000, 0, 'Bank', '1772998520_1000382272.jpg', 5620000, '', ''),
(4, 'Nayab Yaqoob', '03049998524', '2026-03-24', 'walima', '1', 400, 50000, 20000, 'Pending', 50000, 0, 15000, 25000, 0, 'Cash', '', 160000, 'Pending', 'Night');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Photo` varchar(255) NOT NULL,
  `Salary` decimal(10,2) NOT NULL,
  `Advance` decimal(10,2) DEFAULT 0.00,
  `Attendance` varchar(255) DEFAULT NULL,
  `id_card` varchar(50) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Agreement_letter` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `Name`, `Photo`, `Salary`, `Advance`, `Attendance`, `id_card`, `Phone`, `Agreement_letter`) VALUES
(8, 'Rameesha Yaqoob', 'uploads/employees/1000382272.jpg', 60000.00, 12000.00, 'full', '37405-9221210-4', '03049998524', 'uploads/employees/Management System.pdf'),
(9, 'Alisha Sadiq', 'uploads/employees/1000382272.jpg', 50000.00, 10000.00, '23', '37405-9256412-8', '03049998524', 'uploads/employees/Management System.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_attendance`
--

INSERT INTO `employee_attendance` (`id`, `employee_id`, `attendance_date`, `status`, `remarks`) VALUES
(3, 9, '2026-03-01', 'Present', ''),
(4, 9, '2026-03-02', 'Present', ''),
(5, 9, '2026-03-03', 'Present', ''),
(6, 9, '2026-03-04', 'Absent', ''),
(7, 9, '2026-03-05', 'Present', '');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `event_id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` enum('Day','Night') NOT NULL,
  `meat_cost` decimal(10,2) DEFAULT 0.00,
  `vegetable_cost` decimal(10,2) DEFAULT 0.00,
  `dairy_cost` decimal(10,2) DEFAULT 0.00,
  `other_grocery` decimal(10,2) DEFAULT 0.00,
  `generator_cost` decimal(10,2) DEFAULT 0.00,
  `waiter_wages` decimal(10,2) DEFAULT 0.00,
  `cook_wages` decimal(10,2) DEFAULT 0.00,
  `miscellaneous_cost` decimal(10,2) DEFAULT 0.00,
  `total_expense` decimal(10,2) DEFAULT NULL,
  `total_amount` int(11) NOT NULL,
  `profit` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`event_id`, `client_name`, `event_date`, `event_time`, `meat_cost`, `vegetable_cost`, `dairy_cost`, `other_grocery`, `generator_cost`, `waiter_wages`, `cook_wages`, `miscellaneous_cost`, `total_expense`, `total_amount`, `profit`) VALUES
(1, 'Rameesha Yaqoob', '2026-03-28', '', 25000.00, 10000.00, 25000.00, 10000.00, 10000.00, 25000.00, 15000.00, 5000.00, 125000.00, 495000, 370000.00),
(2, 'Alisha', '2026-03-25', '', 1.00, 2.00, 3.00, 4.00, 5.00, 6.00, 7.00, 8.00, 36.00, 602000, 601964.00);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_expenses`
--

CREATE TABLE `monthly_expenses` (
  `id` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `electricity` decimal(10,2) DEFAULT 0.00,
  `rent` decimal(10,2) DEFAULT 0.00,
  `staff_salaries` decimal(10,2) DEFAULT 0.00,
  `miscellaneous` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_expenses`
--

INSERT INTO `monthly_expenses` (`id`, `month`, `year`, `electricity`, `rent`, `staff_salaries`, `miscellaneous`) VALUES
(1, 3, 2026, 100000.00, 100000.00, 100000.00, 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_profit_report`
--

CREATE TABLE `monthly_profit_report` (
  `id` int(11) NOT NULL,
  `month` varchar(20) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `electricity_cost` decimal(10,2) DEFAULT NULL,
  `rent_of_land` decimal(10,2) DEFAULT NULL,
  `staff_salaries` decimal(10,2) DEFAULT NULL,
  `miscellaneous_cost` decimal(10,2) DEFAULT NULL,
  `monthly_events_profit` decimal(10,2) DEFAULT NULL,
  `monthly_expenses` decimal(10,2) DEFAULT NULL,
  `total_profit` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_profit_report`
--

INSERT INTO `monthly_profit_report` (`id`, `month`, `year`, `electricity_cost`, `rent_of_land`, `staff_salaries`, `miscellaneous_cost`, `monthly_events_profit`, `monthly_expenses`, `total_profit`) VALUES
(1, '3', 2026, 50000.00, 3000.00, 5000.00, 2000.00, 601964.00, 60000.00, 541964.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Username`, `Password`) VALUES
(1, 'admin', '1234'),
(2, 'admin', 'admin123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `monthly_expenses`
--
ALTER TABLE `monthly_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_profit_report`
--
ALTER TABLE `monthly_profit_report`
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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `event_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `monthly_expenses`
--
ALTER TABLE `monthly_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `monthly_profit_report`
--
ALTER TABLE `monthly_profit_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD CONSTRAINT `employee_attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
