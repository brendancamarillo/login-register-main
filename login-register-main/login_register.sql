-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2025 at 12:18 PM
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
-- Database: `login_register`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `date`, `time_in`, `latitude`, `longitude`) VALUES
(20, 19, '2025-06-21', '09:28:31', NULL, NULL),
(21, 19, '2025-06-22', '06:15:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `church_id` varchar(100) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `instagram_account` varchar(100) DEFAULT NULL,
  `member_type` varchar(50) DEFAULT NULL,
  `baptism_date` date DEFAULT NULL,
  `baptism_place` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `zone` varchar(100) DEFAULT NULL,
  `locale` varchar(100) DEFAULT NULL,
  `locale_group` varchar(100) DEFAULT NULL,
  `locale_group_joined_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `old_church_id` varchar(100) DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL,
  `birthplace` varchar(150) DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `citizenship` varchar(100) DEFAULT NULL,
  `ethnicity` varchar(100) DEFAULT NULL,
  `street_address` text DEFAULT NULL,
  `state_province_region` varchar(100) DEFAULT NULL,
  `city_country_valley` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `zipcode_postcode` varchar(20) DEFAULT NULL,
  `former_religions` text DEFAULT NULL,
  `assisted_by` varchar(100) DEFAULT NULL,
  `indoctrinated_by` varchar(100) DEFAULT NULL,
  `baptized_by` varchar(100) DEFAULT NULL,
  `facebook_account` varchar(100) DEFAULT NULL,
  `twitter_account` varchar(100) DEFAULT NULL,
  `tiktok_account` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `church_id`, `gender`, `birthdate`, `phone_number`, `instagram_account`, `member_type`, `baptism_date`, `baptism_place`, `division`, `district`, `zone`, `locale`, `locale_group`, `locale_group_joined_date`, `created_at`, `old_church_id`, `civil_status`, `birthplace`, `blood_type`, `citizenship`, `ethnicity`, `street_address`, `state_province_region`, `city_country_valley`, `country`, `zipcode_postcode`, `former_religions`, `assisted_by`, `indoctrinated_by`, `baptized_by`, `facebook_account`, `twitter_account`, `tiktok_account`, `profile_picture`) VALUES
(19, 'Brendan Jalbuena Camarillo', 'brendancamarillo@gmail.co', '$2y$10$/mB0Agt.VQz9iNGzAQkeh.xgEYTmSn3vD/MNQgn2wq81/gdrCGbjW', 'V13499438', 'Male', '2002-01-14', '93029032932', 'na_rbred', 'Member', '2017-11-23', 'Santo Tomas Batangas', 'LBMR ', 'Laguna North ', 'Zone 1 ', 'San Pedro ', 'SPCC_17D', '2023-10-23', '2025-06-21 06:34:00', '11754', 'Single ', 'Ilasan Tayabas Quezon', '+O', 'Filipino ', 'Filipino', 'Canlalay', 'Canlalay Binan Laguna', 'Binan City', 'Philippines', '4024', 'Roman Catholic', 'N/A', 'N/A', 'Rolan Ocampo', 'Brendan Camarillo', 'Brendan Camarillo', '@Kaeya', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
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
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
