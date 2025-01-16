-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2025 at 06:33 PM
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
-- Database: `finproj`
--

-- --------------------------------------------------------

--
-- Table structure for table `appusers`
--

CREATE TABLE `appusers` (
  `user_id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) NOT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appusers`
--

INSERT INTO `appusers` (`user_id`, `username`, `password`, `email`, `CreatedAt`, `reset_token`, `reset_token_expiry`) VALUES
(52, 'TomHardy2048', '958602c55d2d14adcfe4f309fad6d917', 'thardy@gmail.home', '2024-12-31 21:53:03', '', NULL),
(87, 'tinaturner22', '445a97d2bae4a22d27e2f7a46f023c44', 'tinasheturner22@gmail.com', '2024-12-31 21:53:03', '', NULL),
(92, 'seanmurphy', 'be5b3da64da31cda3c0225335489d47d', 'smirkey505@gmail.com', '2024-12-31 21:53:03', 'f81b8e20dc2b47784adb169d4cf4a503', '2025-01-15 11:04:01'),
(114, 'tom', '90135b2c9c8022febbaf848c3716cacc', 'timmy@gmail.com', '2025-01-14 19:39:38', '732f48e1d44824b8c3834016b25966b7', '2025-01-15 11:03:01'),
(138, 'Musonda Malangisha', '4d6876f1cdac14157de912c33030a6f8', 'musomala123@gmail.com', '2025-01-15 17:30:06', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `houses_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `owners_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`houses_id`, `address`, `image_path`, `owners_id`) VALUES
(3, '202 Cedar Boulevard', 'uploads/house57.jpg', 99345),
(5, 'Ul. Warynskiego 12, 00-631, Warszaw', 'uploads/house14.jpeg', 82640),
(15, 'Ul. Warynskiego 12, 00-631, Warsaw', 'uploads/house30.jpeg', 99354),
(17, 'CHILANGA ROAD, CHILANGA ESATES', 'uploads/house12.jpg', 99348),
(23, 'Ul. Warynskiego 12, 00-631, Warsaw', 'uploads/6787cdaeb837b-house1.jpg', 99350),
(27, '202 Cedar Boulevard', 'uploads/house2.jpeg', 99352),
(28, 'CHILANGA ROAD, CHILANGA ESATES', 'uploads/house4.jpeg', 99353),
(29, 'AL KEN 1, 03-990, WARSAW', 'uploads/house5.jpeg', 99356);

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `owners_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`owners_id`, `name`, `email`) VALUES
(82640, 'Mary Jones', 'msjones505@yahoo.co.uk'),
(99345, 'Anne Marie', 'amarie407@gmail.com'),
(99348, 'Alex Smith', 'alexsmith@example.com'),
(99350, 'Michael Brown', 'michaelb@gmail.com'),
(99352, 'David Green', 'david.green@example.com'),
(99353, 'Olivia Black', 'olivia.black@example.com'),
(99354, 'Liam Turner', 'liam.turner@example.com'),
(99356, 'James Wilson', 'james.wilson@gmail.com'),
(99358, 'Ethan Clark', 'ethan.clark@example.com'),
(99360, 'Benjamin Walker', 'benjamin.walker@example.com'),
(99368, 'Barbara Han', 'barbarahan@gmail.com'),
(99369, 'Mary Jones', 'msjones505@yahoo.co.uk'),
(99370, 'Anne Marie', 'amarie407@gmail.com'),
(99373, 'Tony Stark', 'ironmant1@gmail.com'),
(99375, 'Mary Jones', 'msjoneesss45@yahoo.co.uk'),
(99376, 'Emily Blunt', 'emiliablante@gmail.com'),
(99380, 'Mary Jones', 'msjones505@yahoo.co.uk'),
(99382, 'John  Doe', 'johndoe@example.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appusers`
--
ALTER TABLE `appusers`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`houses_id`),
  ADD UNIQUE KEY `owner_id` (`owners_id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`owners_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appusers`
--
ALTER TABLE `appusers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `houses_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `owners_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99385;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `houses`
--
ALTER TABLE `houses`
  ADD CONSTRAINT `fk_houses` FOREIGN KEY (`owners_id`) REFERENCES `owners` (`owners_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
