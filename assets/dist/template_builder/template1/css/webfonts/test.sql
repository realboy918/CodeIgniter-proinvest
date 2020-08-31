-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3307:3307
-- Generation Time: Oct 18, 2019 at 09:41 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_last_login`
--

CREATE TABLE `tbl_last_login` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `sessionData` varchar(2048) NOT NULL,
  `machineIp` varchar(1024) NOT NULL,
  `userAgent` varchar(128) NOT NULL,
  `agentString` varchar(1024) NOT NULL,
  `platform` varchar(128) NOT NULL,
  `createdDtm` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_last_login`
--

INSERT INTO `tbl_last_login` (`id`, `userId`, `sessionData`, `machineIp`, `userAgent`, `agentString`, `platform`, `createdDtm`) VALUES
(1, 1, '{"role":"1","roleText":"System Administrator","name":"System Administrator"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 11:20:47'),
(2, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 11:21:47'),
(3, 3, '{"role":"3","roleText":"Employee","name":"Employee"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 11:22:41'),
(4, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 13:35:00'),
(5, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 14:16:38'),
(6, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 14:17:16'),
(7, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 17:32:04'),
(8, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:00:14'),
(9, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:01:57'),
(10, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:03:44'),
(11, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:03:45'),
(12, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:05:23'),
(13, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:05:23'),
(14, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:05:23'),
(15, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:06:17'),
(16, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:06:17'),
(17, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:06:17'),
(18, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:06:17'),
(19, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:16:19'),
(20, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:16:26'),
(21, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:16:26'),
(22, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:16:49'),
(23, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:16:49'),
(24, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:16:50'),
(25, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:18:43'),
(26, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:18:49'),
(27, 0, '{"role":"3","roleText":"Client","name":"0"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 18:18:50'),
(28, 51, '{"role":"3","roleText":"Client","name":"Kenneth Ndung''u"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 20:54:43'),
(29, 52, '{"role":"3","roleText":"Client","name":"Kenneth Mwaura"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 21:29:19'),
(30, 53, '{"role":"3","roleText":"Client","name":"Ken Mwaura"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-17 21:32:12'),
(31, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 08:15:04'),
(32, 53, '{"role":"3","roleText":"Client","name":"Ken Mwaura"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 08:18:07'),
(33, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 08:20:34'),
(34, 53, '{"role":"3","roleText":"Client","name":"Ken Mwaura"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 08:21:11'),
(35, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 08:26:35'),
(36, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 09:37:42'),
(37, 1, '{"role":"1","roleText":"System Administrator","name":"System Admin"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 09:51:01'),
(38, 1, '{"role":"1","roleText":"System Administrator","name":"Ken Doe"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 09:53:52'),
(39, 1, '{"role":"1","roleText":"System Administrator","name":"Ken Doe"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 10:31:10'),
(40, 1, '{"role":"1","roleText":"System Administrator","name":"Ken Doe"}', '::1', 'Chrome 77.0.3865.120', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36', 'Windows 7', '2019-10-18 10:32:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reset_password`
--

CREATE TABLE `tbl_reset_password` (
  `id` bigint(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activation_id` varchar(32) NOT NULL,
  `agent` varchar(512) NOT NULL,
  `client_ip` varchar(32) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT '0',
  `createdBy` bigint(20) NOT NULL DEFAULT '1',
  `createdDtm` datetime NOT NULL,
  `updatedBy` bigint(20) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_reset_password`
--

INSERT INTO `tbl_reset_password` (`id`, `email`, `activation_id`, `agent`, `client_ip`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'admin@example.com', 'KtDSGOIWMwY7HFp', 'Chrome 77.0.3865.120', '::1', 0, 1, '2019-10-17 13:16:00', NULL, NULL),
(6, 'kendoe93@gmail.com', 'Y0ERLIqQc9wBbx8', 'Chrome 77.0.3865.120', '::1', 0, 1, '2019-10-17 13:50:54', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `roleId` tinyint(4) NOT NULL COMMENT 'role id',
  `role` varchar(50) NOT NULL COMMENT 'role text'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`roleId`, `role`) VALUES
(1, 'System Administrator'),
(2, 'Manager'),
(3, 'Client');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userId` int(11) NOT NULL,
  `email` varchar(128) NOT NULL COMMENT 'login email',
  `password` varchar(128) NOT NULL COMMENT 'hashed login password',
  `name` varchar(128) DEFAULT NULL COMMENT 'full name of user',
  `mobile` varchar(20) DEFAULT NULL,
  `roleId` tinyint(4) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT '0',
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userId`, `email`, `password`, `name`, `mobile`, `roleId`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'kendoe93@gmail.com', '$2y$10$44MBSiEMQUNURCvLqAeYSOWslzSTmA8ybmF8sLCEMTJ2/wd7/0mIm', 'Ken Doe', '9890098900', 1, 0, 0, '2015-07-01 18:56:49', 1, '2019-10-17 13:17:02'),
(2, 'manager@example.com', '$2y$10$quODe6vkNma30rcxbAHbYuKYAZQqUaflBgc4YpV9/90ywd.5Koklm', 'Manager', '9890098900', 2, 0, 1, '2016-12-09 17:49:56', 1, '2018-01-12 07:22:11'),
(3, 'employee@example.com', '$2y$10$UYsH1G7MkDg1cutOdgl2Q.ZbXjyX.CSjsdgQKvGzAgl60RXZxpB5u', 'Employee', '9890098900', 3, 0, 1, '2016-12-09 17:50:22', 3, '2018-01-04 07:58:28'),
(53, 'kennethnmwaura@gmail.com', '$2y$10$jaq6PkUWqdAKU8II8dNs5eGpjBCbzZdNQdCo3M4uBSsphAI7WpZsa', 'Ken Mwaura', NULL, 3, 0, 0, '2019-10-17 20:32:12', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `tbl_last_login`
--
ALTER TABLE `tbl_last_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reset_password`
--
ALTER TABLE `tbl_reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`roleId`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_last_login`
--
ALTER TABLE `tbl_last_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `tbl_reset_password`
--
ALTER TABLE `tbl_reset_password`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `roleId` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT 'role id', AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
