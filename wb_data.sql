-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 08:50 PM
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
-- Database: `wb_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `title`, `content`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'จิ้งจกร้องยังไง', 'กุ๊ก กุ๊ก กุ๊ก กุ๊ก\r\naksdjlkasjdlasd', '2024-10-01 21:10:53', '2024-10-03 14:00:19', 2),
(2, 'Hello', 'world world world world', '2024-10-03 14:02:16', '2024-10-03 14:02:16', 6),
(4, 'asdfghjkl', 'qasdfghjkl.;/', '2024-10-03 14:12:41', '2024-10-03 14:12:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`reply_id`, `content`, `created_at`, `updated_at`, `post_id`, `user_id`) VALUES
(1, 'เจ๋งเกิ๊น', '2024-09-29 01:44:06', '2024-09-29 01:44:06', 1, 4),
(2, '....', '2024-10-01 21:11:13', '2024-10-01 21:11:13', 1, 2),
(4, 'โคตรแจ๋วเลยเพร่', '2024-10-03 14:01:57', '2024-10-03 14:01:57', 1, 6),
(5, 'apiwdioawdjioawd', '2024-10-05 16:52:38', '2024-10-05 16:52:38', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'member',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Thun', 'Saen', 'ThunSaen47@gmail.com', '$2y$10$dhNR70z8OCtJG0aMCKs.z.rEI7xW9kG5E8uebZY.PhSBalxl5YCma', 'admin', '2024-10-03 07:03:25'),
(2, 'ทดสอบอีกรอบ', 'ทดสอบการแก้ไข 2024', '1@gmail.com', '$2y$10$woxWF5LYAeFbQky1qJ8B6.mvArgysOehvLvxu/b/yM7cv12lerxSW', 'member', '2024-09-29 00:47:17'),
(3, 'Seemen', 'v1', 'Seemen@hotmail.com', '$2y$10$2iYFGDcbIwQ/TOjC1Sf6..sihM9AST.HnPLb5H6puY1o.3x23wGXu', 'member', '2024-09-29 00:48:54'),
(4, 'JY', 'Yoi', 'JY@hotmail.com', '$2y$10$8OjSaBuuU0InFskY6qODW.0.UqvQ6MMpvXQNqT17fFje0bPtUjEpm', 'member', '2024-09-29 00:50:13'),
(5, 'sunset', 'foryou', 'admin@2dkung.xyz', '$2y$10$FajUsmK4T9b1X.wb446R/.MkBwlqOT0H7G7NJmSdJf3XT1.jgGn9y', 'member', '2024-09-29 00:56:30'),
(6, '2', '2', '2@gmail.com', '$2y$10$EzSQvE8.xdqjHCdld2cSCOomtateLAEuQHniADQv0vNMMxVAKaWrS', 'member', '2024-10-03 14:01:38'),
(8, 'asd', 'asd', 'tanpisis0614504635@gmail.com', '$2y$10$bjvX.mZ2Rwm1h8U9RoVn.e9V4Fb2KATDZErOlwpmNT/5bgjX94aSO', 'member', '2024-10-03 14:15:05'),
(9, 'asd', 'asd', 'dpjapod@paowjdpoad.com', '$2y$10$X8BLClbyk2ganoFP4JuVteHb2xBCUKoc/qdPnNi4ylK/sRWlPEfxu', 'member', '2024-10-03 14:15:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
