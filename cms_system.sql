-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generated Time: 2025-06-14 09:14:02
-- Server Version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+08:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms_system`
--

----------------------------------------------------------

--
-- Data table structure `admin_users`
--

CREATE TABLE `admin_users` (
`id` int(11) NOT NULL,
`username` varchar(50) NOT NULL,
`password` varchar(255) NOT NULL,
`email` varchar(100) DEFAULT NULL,
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump data from the data table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$qyFgcRo6IUi03xFYmrsqzezE0FdXskLVprGQ./lyVci5DSrks1d9e', 'admin@example.com', '2025-06-14 04:29:53', '2025-06-14 04:45:52');

-- --------------------------------------------------------

--
-- Data table structure `articles`
--

CREATE TABLE `articles` (
`id` int(11) NOT NULL,
`title` varchar(200) NOT NULL,
`content` text DEFAULT NULL,
`author_id` int(11) DEFAULT NULL,
`status` enum('published','draft') DEFAULT 'draft',
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump data from data sheet `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `author_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Test123', 'Test123', 1, 'draft', '2025-06-14 06:21:32', '2025-06-14 06:38:18');

----------------------------------------------------------

--
-- Data table structure `site_settings`
--

CREATE TABLE `site_settings` (
`id` int(11) NOT NULL,
`setting_key` varchar(100) NOT NULL,
`setting_value` text DEFAULT NULL,
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump data from the `site_settings` table
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'My CMS Website', '2025-06-14 04:29:54', '2025-06-14 04:29:54'),
(2, 'site_description', 'This is a CMS system built using PHP and MySQL', '2025-06-14 04:29:54', '2025-06-14 04:29:54'),
(3, 'site_keywords', 'CMS, PHP, MySQL', '2025-06-14 04:29:54', '2025-06-14 04:29:54');

----------------------------------------------------------

--
-- Data table structure `users`
--

CREATE TABLE `users` (
`id` int(11) NOT NULL,
`username` varchar(50) NOT NULL,
`password` varchar(255) NOT NULL,
`email` varchar(100) NOT NULL,
`role` text NOT NULL,
`status` enum('active','inactive') DEFAULT 'active',
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump data from table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'root', '$2y$10$0UYcFWOdmFKD2pzS3YQQoOqg..wHWTRC4L/H22sXTO2zF/vpliwCa', 'eapdb20211116@gmail.com', 'admin', 'active', '2025-06-14 04:36:28', '2025-06-14 07:02:26');

--
-- Dumped table indexes
--
--
-- Table index `admin_users`
--
ALTER TABLE `admin_users`

ADD PRIMARY KEY (`id`),

ADD UNIQUE KEY `username` (`username`);

--
-- Table index `articles`
--
ALTER TABLE `articles`

ADD PRIMARY KEY (`id`),
ADD KEY `author_id` (`author_id`);

--
-- Index `site_settings` Table
--
ALTER TABLE `site_settings`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Index `users` Table
--
ALTER TABLE `users`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`username`),
ADD UNIQUE KEY `email` (`email`);

--
-- Use AUTO_INCREMENT in the Dumped Table
--
--
-- Use AUTO_INCREMENT in the Table `admin_users`
--
ALTER TABLE `admin_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Use table auto-increment (AUTO_INCREMENT) `articles`
--
ALTER TABLE `articles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Use table auto-increment (AUTO_INCREMENT) `site_settings`
--
ALTER TABLE `site_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Use table auto-increment (AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dumped table constraints
--
--
-- Table constraints `articles`
--
ALTER TABLE `articles`
ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `admin`_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
