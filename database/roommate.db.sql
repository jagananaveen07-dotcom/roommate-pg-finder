-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 10:28 AM
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
-- Database: `roommate.db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `pg_name` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `rent` int(11) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(30) DEFAULT 'Confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--


-- --------------------------------------------------------

--
-- Table structure for table `pgs`
--

CREATE TABLE `pgs` (
  `id` int(11) NOT NULL,
  `pg_name` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `rent` int(11) DEFAULT NULL,
  `sharing` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `availability` varchar(20) NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pgs`
--

INSERT INTO `pgs` (`id`, `pg_name`, `city`, `rent`, `sharing`, `description`, `availability`) VALUES
(1, 'Sunrise PG', 'Bangalore', 7000, '2 Sharing', 'WiFi, Food, Laundry', 'Available'),
(2, 'Green Nest PG', 'Hyderabad', 6500, '3 Sharing', 'AC Rooms, WiFi', 'Available'),
(3, 'Royal Stay PG', 'Chennai', 8000, 'Single', 'Near Metro Station', 'Available'),
(4, 'Happy Homes PG', 'Visakhapatnam', 5500, '2 Sharing', 'Food Included', 'Available'),
(5, 'Elite Residency', 'Bangalore', 9000, 'Single', 'Gym, WiFi', 'Available'),
(6, 'Comfort PG', 'Hyderabad', 6000, '3 Sharing', 'Laundry, Security', 'Available'),
(7, 'Blue Sky PG', 'Chennai', 7500, '2 Sharing', 'AC, Food', 'Available'),
(8, 'Lake View PG', 'Visakhapatnam', 5800, '2 Sharing', 'Near Beach', 'Available'),
(9, 'City Comfort PG', 'Bangalore', 6800, '3 Sharing', 'WiFi, Parking', 'Available'),
(10, 'Urban Nest PG', 'Hyderabad', 7200, 'Single', 'Attached Bathroom', 'Available'),
(11, 'Metro PG', 'Chennai', 8200, 'Single', 'Near Bus Stop', 'Available'),
(12, 'Palm Residency', 'Visakhapatnam', 6100, '2 Sharing', 'WiFi', 'Available'),
(13, 'Golden PG', 'Bangalore', 7600, '2 Sharing', 'Food, Laundry', 'Available'),
(14, 'Dream Stay', 'Hyderabad', 6900, '3 Sharing', '24x7 Security', 'Available'),
(15, 'Ocean View PG', 'Visakhapatnam', 6700, '2 Sharing', 'Sea View', 'Available'),
(16, 'Smart Living PG', 'Chennai', 8300, 'Single', 'Gym', 'Available'),
(17, 'Budget PG', 'Bangalore', 5000, '4 Sharing', 'Affordable Stay', 'Available'),
(18, 'Prime Residency', 'Hyderabad', 8700, 'Single', 'AC, WiFi', 'Available'),
(19, 'Cozy Corner PG', 'Chennai', 6400, '2 Sharing', 'Food Included', 'Available'),
(20, 'Fresh Living PG', 'Visakhapatnam', 5900, '3 Sharing', 'Laundry, WiFi', 'Available'),
(21, 'Sunrise PG', 'Bangalore', 7000, '2 Sharing', 'WiFi, Food, Laundry', 'Available'),
(22, 'Green Nest PG', 'Hyderabad', 6500, '3 Sharing', 'AC Rooms, WiFi', 'Available'),
(23, 'Royal Stay PG', 'Chennai', 8000, 'Single', 'Near Metro Station', 'Available'),
(24, 'Happy Homes PG', 'Visakhapatnam', 5500, '2 Sharing', 'Food Included', 'Available'),
(25, 'Elite Residency', 'Bangalore', 9000, 'Single', 'Gym, WiFi', 'Available'),
(26, 'Comfort PG', 'Hyderabad', 6000, '3 Sharing', 'Laundry, Security', 'Available'),
(27, 'Blue Sky PG', 'Chennai', 7500, '2 Sharing', 'AC, Food', 'Available'),
(28, 'Lake View PG', 'Visakhapatnam', 5800, '2 Sharing', 'Near Beach', 'Available'),
(29, 'City Comfort PG', 'Bangalore', 6800, '3 Sharing', 'WiFi, Parking', 'Available'),
(30, 'Urban Nest PG', 'Hyderabad', 7200, 'Single', 'Attached Bathroom', 'Available'),
(31, 'Metro PG', 'Chennai', 8200, 'Single', 'Near Bus Stop', 'Available'),
(32, 'Palm Residency', 'Visakhapatnam', 6100, '2 Sharing', 'WiFi', 'Available'),
(33, 'Golden PG', 'Bangalore', 7600, '2 Sharing', 'Food, Laundry', 'Available'),
(34, 'Dream Stay', 'Hyderabad', 6900, '3 Sharing', '24x7 Security', 'Available'),
(35, 'Ocean View PG', 'Visakhapatnam', 6700, '2 Sharing', 'Sea View', 'Available'),
(36, 'Smart Living PG', 'Chennai', 8300, 'Single', 'Gym', 'Available'),
(37, 'Budget PG', 'Bangalore', 5000, '4 Sharing', 'Affordable Stay', 'Available'),
(38, 'Prime Residency', 'Hyderabad', 8700, 'Single', 'AC, WiFi', 'Available'),
(39, 'Cozy Corner PG', 'Chennai', 6400, '2 Sharing', 'Food Included', 'Available'),
(40, 'Fresh Living PG', 'Visakhapatnam', 5900, '3 Sharing', 'Laundry, WiFi', 'Available'),
(41, 'radisson blue pg', 'vizag', 7500, 'single', 'very rich and positive resort', 'Not Available');

-- --------------------------------------------------------

--
-- Table structure for table `roommates`
--

CREATE TABLE `roommates` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `budget` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `preferences` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roommates`
--

-- --------------------------------------------------------

--
-- Table structure for table `roommate_requests`
--

CREATE TABLE `roommate_requests` (
  `id` int(11) NOT NULL,
  `sender_email` varchar(100) DEFAULT NULL,
  `receiver_email` varchar(100) DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roommate_requests`
--

-- --------------------------------------------------------

--
-- Table structure for table `saved_pgs`
--

CREATE TABLE `saved_pgs` (
  `id` int(11) NOT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `pg_name` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `rent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_pgs`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `budget` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `preferences` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pgs`
--
ALTER TABLE `pgs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roommates`
--
ALTER TABLE `roommates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roommate_requests`
--
ALTER TABLE `roommate_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_pgs`
--
ALTER TABLE `saved_pgs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pgs`
--
ALTER TABLE `pgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `roommates`
--
ALTER TABLE `roommates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roommate_requests`
--
ALTER TABLE `roommate_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_pgs`
--
ALTER TABLE `saved_pgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
