-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 10:26 AM
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
-- Database: `advocate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `advocate_data`
--

CREATE TABLE `advocate_data` (
  `id` int(11) NOT NULL,
  `reg_id` int(11) NOT NULL,
  `dob` date DEFAULT NULL,
  `date_of_enrollment` date DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advocate_data`
--

INSERT INTO `advocate_data` (`id`, `reg_id`, `dob`, `date_of_enrollment`, `photo_path`, `updated_at`) VALUES
(1, 1, '2001-07-17', '2025-11-17', 'C:\\xampp\\htdocs\\AdvocateDataCollection/uploads/photo_1_1763362704.jpg', '2025-11-17 06:58:24'),
(2, 3, '2005-05-19', '2025-11-17', 'C:\\xampp\\htdocs\\AdvocateDataCollection/uploads/photo_3_1763368417.jpg', '2025-11-17 08:33:37');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `enrollment_no` varchar(20) NOT NULL,
  `mobile_enc` text NOT NULL,
  `email_enc` text NOT NULL,
  `state` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `pin_code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `name`, `password_hash`, `enrollment_no`, `mobile_enc`, `email_enc`, `state`, `district`, `pin_code`, `created_at`) VALUES
(1, 'Jatin', '$2y$10$pzifZ033WFDraXrsEA/xa.smkdH6I757/PEU5sfGCG0mEn5Zhp4JC', 'A1234AA1234', 'P86yKRlAREGVYH495OJ33Q==:ZoLCC1ta/etEeGOXLDU+Lw==', 'hEy7ofmCKPR3jV0fOgxPkA==:Hih43jp2v6SzI2/wi1UEdv8SWkQIVxUpLRdzFfoJ4nE=', 'rajasthan', 'jaipur', '302020', '2025-11-17 06:47:28'),
(2, 'harish', '$2y$10$24mPm72qadbe//s2h/JnEu2LWw/U53yhbAO646fehqjyUWO/61y7S', 'A2025AA2025', 'n+R7MnqgkwG507/kY8iw3w==:fOUdxcY7WnNWnyF4Kj23Yg==', '9dcZ4OTZIN4hxGvrB8U1UA==:WRfOHrBjUQcLXRtfyPleXt0MqsReB5U5G/PTGceL/Hk=', 'rajasthan', 'jaipur', '302020', '2025-11-17 07:13:04'),
(3, 'abhijeet', '$2y$10$0n0RHPGSKMwiB0us1kTHDuCd6dHMVkZacR62Jdvu61BoGyCWO/E1u', 'A2026AA2026', 'HKmkpqUBnS9G39JH3v37ag==:AdgR8k4IZBkwjdvHeiK5dA==', 'Q57jYWHEt7sb/rppIT9+Ww==:bOv7UA6lHLZH49ix7vSNFQ==', 'rajasthan', 'jaipur', '302201', '2025-11-17 08:32:07'),
(4, 'demo_usr', '$2y$10$ovLasVxjDcbqFAwCMYR1yeIfzRCKXWjw7g0LXoav41NvLq8rWysTK', 'R2026RR2026', 'lBVUrdctcpxoZKKDdGxrUg==:NVRFjbRqRx11rm7t29ikIw==', 'zS5vISdYxH1DI59iwO3r+Q==:MmJxUtBxt2PxyNNs/rBznQ==', 'Rajasthan', 'Jaipur', '302522', '2025-11-17 09:10:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advocate_data`
--
ALTER TABLE `advocate_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reg_id` (`reg_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enrollment_no` (`enrollment_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advocate_data`
--
ALTER TABLE `advocate_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advocate_data`
--
ALTER TABLE `advocate_data`
  ADD CONSTRAINT `advocate_data_ibfk_1` FOREIGN KEY (`reg_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
