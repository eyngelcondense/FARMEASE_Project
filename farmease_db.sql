-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 29, 2025 at 05:33 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u768452900_farmease`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `type` enum('equipment','service','food') NOT NULL DEFAULT 'equipment',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `name`, `description`, `price`, `type`, `status`, `created_at`, `updated_at`) VALUES
(2, '5 chair', 'aditional chaiers for the clientss', 150.00, 'equipment', 'active', '2025-12-02 01:39:34', '2025-12-02 01:39:34');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `position` varchar(255) NOT NULL,
  `permission` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `position`, `permission`) VALUES
(1, 1, 'Admin', 'All Access');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `record_id` int(11) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
(1, 1, 'admin', '2025-11-10 17:52:53'),
(2, 2, 'client', '2025-11-10 17:55:42'),
(4, 4, 'client', '2025-11-14 05:33:21'),
(6, 3, 'client', '2025-11-23 16:03:50'),
(7, 5, 'admin', '2025-11-25 03:55:13'),
(8, 6, 'client', '2025-11-25 03:56:27'),
(9, 7, 'client', '2025-12-02 01:29:59'),
(10, 8, 'client', '2025-12-05 10:41:04'),
(11, 9, 'client', '2025-12-05 10:42:22'),
(12, 10, 'client', '2025-12-05 15:54:49');

-- --------------------------------------------------------

--
-- Table structure for table `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_identities`
--

INSERT INTO `auth_identities` (`id`, `user_id`, `type`, `name`, `secret`, `secret2`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'email_password', NULL, 'administrator@farmease.app', '$2y$12$/rxw7eadQjAk8MTco7fuOeZlxCYGqYrviWBdnZssDk9wY5qSWoE9e', NULL, NULL, 0, '2025-12-23 13:27:10', '2025-11-10 17:52:53', '2025-12-23 13:27:10'),
(3, 2, 'email_password', NULL, 'magnaye.rp@gmail.com', '$2y$12$t5JJwWpfrt4gg3hEZJgkbOo5xXOkKoTI0cE2uu0lESc05noTpSC06', NULL, NULL, 0, '2025-12-23 13:27:52', '2025-11-10 17:55:42', '2025-12-23 13:27:52'),
(5, 3, 'email_password', NULL, '23-74604@g.batstate-u.edu.ph', '$2y$12$uj4tGsbmTruZm3XY.sfG4eTSkS4VzjzVWs4b6hSYffe/UYeRNBhbG', NULL, NULL, 0, '2025-11-14 05:18:24', '2025-11-11 09:52:02', '2025-11-14 05:18:24'),
(8, 4, 'email_password', NULL, 'sample@gmail.com', '$2y$12$ROMg8dpzsbBBB6NXqXx.fOLKRwO5Y6/sjc1HbkCJRAaHTJKBC.9P6', NULL, NULL, 0, '2025-11-14 05:37:29', '2025-11-14 05:33:21', '2025-11-14 05:37:29'),
(12, 5, 'email_password', NULL, 'earlsincombenido0@gmail.com', '$2y$12$9mVgiNSfeRDp0btlYl/tYeh8DajOkrFcMb1I7AqsAX493fx3TcgmO', NULL, NULL, 0, '2025-12-02 01:46:52', '2025-11-25 03:55:13', '2025-12-02 01:46:52'),
(14, 6, 'email_password', NULL, 'abbbyygarcia@gmail.com', '$2y$12$eSrS.1p9mD3AJw6Nx.5QJuT9wwrPROUbBHjMv36rR2c30nQ9hYGQa', NULL, NULL, 0, '2025-12-12 14:18:03', '2025-11-25 03:56:27', '2025-12-12 14:18:03'),
(16, 7, 'email_password', NULL, 'albertpaytaren@gmail.com', '$2y$10$Yt86HYLNfkBsOKllgRSrnevAzRapeHkgmtOS2/UN8v30tCLO0y.JG', NULL, NULL, 0, NULL, '2025-12-02 01:29:59', '2025-12-02 01:29:59'),
(17, 7, 'email_activate', NULL, 'e63b56492346608ac25e6bf0b26e5e704b248e8ded84ba5ecbc73f9ccb89c947', '2025-12-03 01:29:59', NULL, NULL, 0, NULL, '2025-12-02 01:29:59', '2025-12-02 01:29:59'),
(18, 8, 'email_password', NULL, 'angelmaecortino@gmail.com', '$2y$10$/m49./ZLvvmV.DItSRuKSOue72mfjlhS2pm1WNhqjoqK5Dj08KN0q', NULL, NULL, 0, NULL, '2025-12-05 10:41:04', '2025-12-05 10:41:04'),
(19, 8, 'email_activate', NULL, '26c9ce125826030a46d0cf2db1da5ba3f6db33394f9d214d3f5efce2a14ffbfa', '2025-12-06 10:41:04', NULL, NULL, 0, NULL, '2025-12-05 10:41:04', '2025-12-05 10:41:04'),
(20, 9, 'email_password', NULL, '23-77063@g.batstate-u.edu.ph', '$2y$12$EQokEhWnL3gSYFEh3oWvteuU.m/P.aVY1r6RhVIOzJ1yyT.0IzugW', NULL, NULL, 0, '2025-12-05 14:50:06', '2025-12-05 10:42:22', '2025-12-05 14:50:06'),
(22, 10, 'email_password', NULL, 'priscilocortino@gmail.com', '$2y$10$8KyrDC9P0zm.W0Ld/we/dupXlCXKmUpjA4rGaz1TePAE1ylmPax56', NULL, NULL, 0, NULL, '2025-12-05 15:54:49', '2025-12-05 15:54:49'),
(23, 10, 'email_activate', NULL, '731ec1d9321ed47cb5cbea2a75c29dafe986ad12291584001e90570295e28460', '2025-12-06 15:54:49', NULL, NULL, 0, NULL, '2025-12-05 15:54:49', '2025-12-05 15:54:49');

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-10 17:53:38', 1),
(2, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-10 17:57:14', 1),
(3, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-11 07:17:07', 1),
(4, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-11 09:48:04', 1),
(5, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-11 09:51:22', 1),
(6, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', '23-74604@g.batstate-u.edu.ph', 3, '2025-11-11 09:52:25', 1),
(7, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-11 11:06:16', 1),
(8, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-11 12:58:40', 1),
(9, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-11 13:01:15', 1),
(10, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-12 00:21:05', 1),
(11, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-12 00:49:02', 1),
(12, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-12 01:49:59', 1),
(13, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-12 04:39:27', 1),
(14, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-12 04:41:05', 1),
(15, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-12 04:41:42', 1),
(16, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-12 07:05:54', 1),
(17, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-12 07:08:08', 1),
(18, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-12 07:09:24', 1),
(19, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-12 10:52:35', 1),
(20, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-12 11:01:05', 1),
(21, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-12 11:01:15', 1),
(22, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-13 02:27:40', 1),
(23, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', NULL, '2025-11-13 02:29:53', 0),
(24, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-13 02:29:58', 1),
(25, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-13 03:01:41', 1),
(26, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-13 06:05:39', 1),
(27, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-14 02:36:15', 1),
(28, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-14 02:52:36', 1),
(29, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-14 02:56:18', 1),
(30, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-14 02:56:36', 1),
(31, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-14 02:59:52', 1),
(32, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-14 03:02:10', 1),
(33, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-14 03:23:32', 1),
(34, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-14 05:15:58', 1),
(35, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', '23-74604@g.batstate-u.edu.ph', 3, '2025-11-14 05:18:24', 1),
(37, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmil.com', NULL, '2025-11-14 05:49:01', 0),
(38, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', NULL, '2025-11-14 05:49:13', 0),
(39, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-14 05:51:10', 1),
(40, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-14 21:19:40', 1),
(41, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-14 21:20:41', 1),
(42, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-15 03:01:53', 1),
(43, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-15 06:15:22', 1),
(44, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-15 06:25:46', 1),
(45, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-15 06:35:18', 1),
(46, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-15 06:44:05', 1),
(47, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-15 21:07:22', 1),
(48, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-16 05:40:49', 1),
(49, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-16 06:01:26', 1),
(50, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-16 17:33:52', 1),
(51, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-16 17:34:15', 1),
(52, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-16 17:38:41', 1),
(53, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-16 18:31:39', 1),
(54, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-16 18:31:52', 1),
(55, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', NULL, '2025-11-16 18:43:27', 0),
(56, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-16 18:43:34', 1),
(57, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-16 23:19:19', 1),
(58, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-16 23:23:59', 1),
(59, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', NULL, '2025-11-18 15:47:16', 0),
(60, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-18 15:47:32', 1),
(61, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', NULL, '2025-11-19 01:10:28', 0),
(62, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-19 01:10:43', 1),
(63, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-19 09:05:48', 1),
(64, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-19 12:09:53', 1),
(65, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-19 18:12:40', 1),
(66, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-20 04:13:23', 1),
(67, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 04:17:31', 1),
(68, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-20 06:54:40', 1),
(69, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-20 12:07:53', 1),
(70, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:08:22', 1),
(71, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:17:14', 1),
(72, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:17:53', 1),
(73, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:18:17', 1),
(74, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:19:17', 1),
(75, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:22:44', 1),
(76, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:25:43', 1),
(77, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:29:57', 1),
(78, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:32:53', 1),
(79, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-20 12:38:11', 1),
(80, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-20 12:53:13', 1),
(81, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-20 14:41:38', 1),
(82, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-21 01:37:29', 1),
(83, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-21 02:56:22', 1),
(84, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-21 03:04:12', 1),
(85, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-21 05:57:49', 1),
(86, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-21 06:01:31', 1),
(87, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-21 12:56:36', 1),
(88, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-21 12:57:28', 1),
(89, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-21 15:05:18', 1),
(90, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-21 17:28:50', 1),
(91, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-21 17:29:50', 1),
(92, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-22 00:18:38', 1),
(93, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'adminiastrator@farmease.app', NULL, '2025-11-22 00:41:20', 0),
(94, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-22 00:41:32', 1),
(95, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-22 01:39:15', 1),
(96, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-22 01:41:14', 1),
(97, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-22 05:13:04', 1),
(98, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-22 06:47:31', 1),
(99, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-22 23:40:03', 1),
(100, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-23 05:38:15', 1),
(101, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-23 07:13:58', 1),
(102, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-23 11:30:11', 1),
(103, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-23 11:37:25', 1),
(104, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-23 12:48:27', 1),
(105, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-23 12:51:31', 1),
(106, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-23 16:38:08', 1),
(107, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-24 01:08:02', 1),
(108, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-24 01:09:49', 1),
(109, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-24 01:25:43', 1),
(110, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-24 13:50:43', 1),
(111, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-24 16:07:00', 1),
(112, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-24 20:52:46', 1),
(113, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-11-24 21:30:30', 1),
(114, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-11-25 01:04:57', 1),
(115, '172.18.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-11-25 01:06:14', 1),
(116, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', NULL, '2025-11-25 03:54:45', 0),
(117, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-11-25 03:55:45', 1),
(118, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-25 03:56:55', 1),
(119, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-11-25 03:57:40', 1),
(120, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-25 04:00:03', 1),
(121, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-11-25 04:03:45', 1),
(122, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-25 04:05:47', 1),
(123, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-25 04:25:21', 1),
(124, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-25 04:59:20', 1),
(125, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-27 13:22:48', 1),
(126, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-11-27 13:36:20', 1),
(127, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-27 13:42:22', 1),
(128, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-11-27 13:44:46', 1),
(129, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-27 13:45:33', 1),
(130, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-11-27 14:39:41', 1),
(131, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-28 03:02:32', 1),
(132, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-28 06:23:53', 1),
(133, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-28 13:01:58', 1),
(134, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-28 13:59:19', 1),
(135, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-11-28 14:21:53', 1),
(136, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-11-28 15:57:20', 1),
(137, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-11-28 17:11:02', 1),
(138, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 03:04:02', 1),
(139, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 03:30:12', 1),
(140, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 03:55:05', 1),
(141, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 03:56:26', 1),
(142, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 03:57:07', 1),
(143, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 04:19:38', 1),
(144, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 04:21:04', 1),
(145, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 05:47:47', 1),
(146, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 05:51:32', 1),
(147, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 06:19:48', 1),
(148, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 06:25:55', 1),
(149, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 06:26:39', 1),
(150, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 06:46:22', 1),
(151, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 06:47:43', 1),
(152, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 06:48:08', 1),
(153, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 06:58:20', 1),
(154, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 06:59:23', 1),
(155, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 07:31:58', 1),
(156, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 07:33:49', 1),
(157, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 07:42:56', 1),
(158, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 21:02:04', 1),
(159, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 21:04:50', 1),
(160, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 21:25:33', 1),
(161, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 21:26:42', 1),
(162, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 21:29:04', 1),
(163, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 21:29:58', 1),
(164, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-01 21:47:44', 1),
(165, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-01 22:13:41', 1),
(166, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:02:06', 1),
(167, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:04:40', 1),
(168, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:09:43', 1),
(169, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:10:40', 1),
(170, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:25:02', 1),
(171, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:31:34', 1),
(172, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:32:31', 1),
(173, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:35:50', 1),
(174, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:36:43', 1),
(175, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:39:38', 1),
(176, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:40:15', 1),
(177, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:41:21', 1),
(178, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:41:37', 1),
(179, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:44:31', 1),
(180, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:48:25', 1),
(181, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:53:16', 1),
(182, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:54:39', 1),
(183, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:56:28', 1),
(184, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 00:57:56', 1),
(185, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 00:58:58', 1),
(186, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 01:00:48', 1),
(187, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 01:30:48', 1),
(188, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 01:34:24', 1),
(189, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-02 01:45:50', 1),
(190, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'email_password', 'earlsincombenido0@gmail.com', 5, '2025-12-02 01:46:52', 1),
(191, '136.158.46.26', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-05 09:57:16', 1),
(192, '136.158.46.26', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-05 10:05:00', 1),
(193, '136.239.183.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-05 10:39:34', 1),
(194, '136.239.183.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'angelmaecortino@gmail.com', NULL, '2025-12-05 10:40:31', 0),
(195, '136.239.183.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'angelmaeramirezcortino@gmail.com', NULL, '2025-12-05 10:41:55', 0),
(196, '136.239.183.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', '23-77063@g.batstate-u.edu.ph', 9, '2025-12-05 10:42:49', 1),
(197, '158.62.4.139', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-12-05 11:21:34', 1),
(198, '136.239.183.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', '23-77063@g.batstate-u.edu.ph', 9, '2025-12-05 12:20:49', 1),
(199, '136.239.183.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', '23-77063@g.batstate-u.edu.ph', 9, '2025-12-05 13:50:52', 1),
(200, '136.239.183.216', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', '23-77063@g.batstate-u.edu.ph', 9, '2025-12-05 14:50:06', 1),
(201, '158.62.4.139', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-12-06 00:22:24', 1),
(202, '158.62.4.139', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-12-06 02:37:39', 1),
(203, '158.62.4.139', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-12-06 02:39:05', 1),
(204, '122.52.137.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-12-06 05:37:15', 1),
(205, '2405:8d40:4097:b2fe:f9f7:24ed:8a7c:479c', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-12-06 06:09:05', 1),
(206, '136.158.46.26', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-06 22:19:59', 1),
(207, '136.158.46.26', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'administrator@farmease.app', 1, '2025-12-06 22:24:59', 1),
(208, '136.158.46.26', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-06 22:27:44', 1),
(209, '136.158.46.26', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-10 09:28:23', 1),
(210, '103.252.32.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'email_password', 'abbbyygarcia@gmail.com', 6, '2025-12-12 14:18:03', 1),
(211, '136.158.46.64', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-14 23:09:11', 1),
(212, '136.158.46.64', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-20 03:37:55', 1),
(213, '136.158.46.64', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'email_password', 'administrator@farmease.app', 1, '2025-12-23 13:27:10', 1),
(214, '136.158.46.64', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'email_password', 'ryanwillalwaysremember@gmail.com', NULL, '2025-12-23 13:27:40', 0),
(215, '136.158.46.64', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'email_password', 'magnaye.rp@gmail.com', 2, '2025-12-23 13:27:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_remember_tokens`
--

CREATE TABLE `auth_remember_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `booking_reference` varchar(20) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` int(11) NOT NULL,
  `total_guests` int(11) NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `venue_id` int(10) UNSIGNED NOT NULL,
  `base_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `addons_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `overtime_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `special_requests` text DEFAULT NULL,
  `status` enum('pending','confirmed','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','partial','paid','refunded') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_id`, `booking_reference`, `event_type`, `event_date`, `start_time`, `end_time`, `total_hours`, `total_guests`, `package_id`, `venue_id`, `base_amount`, `addons_amount`, `overtime_amount`, `total_amount`, `special_requests`, `status`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 2, 'BK20251120D062F8', 'sfad', '2025-11-27', '08:30:00', '14:30:00', 6, 34, 6, 7, 4500.00, 150.00, 100.00, 4750.00, '', 'pending', 'pending', '2025-11-20 04:47:25', '2025-11-20 04:47:25'),
(2, 2, 'BK20251120F0130E', 'wfesfdfas', '2025-11-26', '17:00:00', '23:00:00', 6, 35, 6, 7, 4500.00, 150.00, 100.00, 4750.00, '', 'rejected', 'pending', '2025-11-20 04:53:35', '2025-11-20 13:10:07'),
(3, 2, 'BK2025112018F250', 'Wedding', '2025-11-27', '08:00:00', '12:00:00', 4, 35, 6, 7, 4500.00, 150.00, 0.00, 4650.00, '', 'pending', 'pending', '2025-11-20 05:00:01', '2025-11-20 05:00:01'),
(4, 2, 'BK2025112024B405', 'wedding', '2025-11-28', '13:00:00', '23:00:00', 10, 35, 6, 7, 4500.00, 150.00, 500.00, 5150.00, '', 'pending', 'pending', '2025-11-20 05:01:06', '2025-11-20 05:01:06'),
(5, 2, 'BK20251120B03618', 'ef', '2025-11-29', '09:30:00', '15:30:00', 6, 40, 6, 7, 4500.00, 0.00, 100.00, 4600.00, '', 'pending', 'pending', '2025-11-20 05:19:55', '2025-11-20 05:19:55'),
(6, 2, 'BK20251120438511', 'xcfgvhbjn', '2025-11-28', '08:00:00', '12:00:00', 4, 5, 5, 6, 45.00, 150.00, 0.00, 195.00, '', 'pending', 'pending', '2025-11-20 05:22:28', '2025-11-20 05:22:28'),
(7, 2, 'BK202511204A8276', 'dfhzb', '2025-11-28', '08:00:00', '12:00:00', 4, 34, 6, 7, 4500.00, 0.00, 0.00, 4500.00, '', 'pending', 'pending', '2025-11-20 05:33:24', '2025-11-20 05:33:24'),
(8, 2, 'BK20251120942B12', 'DVVSvD', '2025-11-29', '08:00:00', '12:00:00', 4, 45, 6, 7, 4500.00, 300.00, 0.00, 4800.00, '', 'pending', 'pending', '2025-11-20 05:33:45', '2025-11-20 05:33:45'),
(9, 2, 'BK20251120B2A921', 'niggas', '2025-11-29', '09:30:00', '13:30:00', 4, 45, 6, 7, 4500.00, 1800.00, 0.00, 6300.00, '', 'pending', 'pending', '2025-11-20 06:58:19', '2025-11-20 06:58:19'),
(10, 2, 'BK20251120F58942', 'aray ko', '2025-11-26', '08:00:00', '16:00:00', 8, 35, 6, 7, 4500.00, 2700.00, 300.00, 7500.00, '', 'approved', 'pending', '2025-11-20 07:03:27', '2025-11-20 13:10:07'),
(11, 2, 'BK20251120A8A5A1', 'asdvsaf', '2025-11-27', '10:00:00', '14:00:00', 4, 34, 6, 7, 4500.00, 0.00, 0.00, 4500.00, '', 'pending', 'pending', '2025-11-20 13:55:22', '2025-11-20 13:55:22'),
(12, 2, 'BK202511206D39AA', 'fafasdfas', '2025-11-27', '08:00:00', '12:00:00', 4, 23, 6, 7, 4500.00, 600.00, 0.00, 5100.00, '', 'rejected', 'pending', '2025-11-20 14:12:06', '2025-11-24 01:12:28'),
(13, 2, 'BK20251121518608', 'asdfghjklkjhgf', '2025-11-27', '08:00:00', '12:00:00', 4, 8, 8, 8, 20000.00, 2100.00, 0.00, 22100.00, '', 'pending', 'pending', '2025-11-21 03:09:41', '2025-11-21 03:09:41'),
(14, 2, 'BK20251121B6B99A', 'Wedding', '2025-11-30', '08:00:00', '16:00:00', 8, 9, 8, 8, 20000.00, 150.00, 0.00, 20150.00, '', 'pending', 'pending', '2025-11-21 07:05:47', '2025-11-21 07:05:47'),
(15, 2, 'BK2025112172088F', 'Social Event', '2025-11-28', '08:30:00', '12:30:00', 4, 45, 5, 6, 45.00, 150.00, 0.00, 195.00, 'try lang po', 'pending', 'pending', '2025-11-21 15:24:55', '2025-11-21 15:24:55'),
(16, 6, 'BK20251125C70E18', 'Other - rambutan', '2025-11-27', '08:00:00', '12:00:00', 4, 45, 6, 7, 4500.00, 150.00, 0.00, 4650.00, 'gusto ko sya', 'pending', 'pending', '2025-11-25 04:02:52', '2025-11-25 04:02:52'),
(17, 6, 'BK202512010E782A', 'Commercial / Video Shoot', '2025-12-01', '08:00:00', '12:00:00', 4, 45, 6, 7, 4500.00, 150.00, 0.00, 4650.00, '', 'approved', 'pending', '2025-12-01 05:48:32', '2025-12-02 00:35:42'),
(18, 6, 'BK20251202E4B918', 'Social Event', '2025-12-10', '08:30:00', '12:30:00', 4, 50, 13, 6, 20000.00, 300.00, 1000.00, 21300.00, '', 'approved', 'pending', '2025-12-02 01:33:18', '2025-12-02 01:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `booking_addons`
--

CREATE TABLE `booking_addons` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `addon_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `user_id`, `profile_pic`, `fullname`, `email`, `phone`, `address`, `is_deleted`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 2, '1765059689_b5d91a924fcadc239dfb.png', 'Ryan Paulo Magnaye', 'magnaye.rp@gmail.com', '09913084036', 'Lucban Balayan Batangas 4213 Philippines Lipa City', 0, '2025-11-10 17:55:42', '2025-12-06 22:21:29', NULL),
(4, 4, NULL, 'cdfghjk', 'sample@gmail.com', '2345678921', 'dfghjk', 0, '2025-11-14 05:33:21', '2025-11-14 05:33:21', NULL),
(5, 5, NULL, 'Earlsin Combenido', 'earlsincombenido0@gmail.com', '09634312395', 'wawa,nas,bats', 0, '2025-11-25 03:55:13', '2025-11-25 03:55:13', NULL),
(6, 6, '1764571497_5a3b1053042c35368aaf.png', 'Abby Garcia', 'abbbyygarcia@gmail.com', '09385532814', 'brgy. wawa nas, bats', 0, '2025-11-25 03:56:27', '2025-12-01 06:44:57', NULL),
(7, 7, NULL, 'Ryan Paulo ', 'albertpaytaren@gmail.com', '09634312397', 'wawa,nas,bats', 0, '2025-12-02 01:29:59', '2025-12-02 01:29:59', NULL),
(8, 8, NULL, 'injelme', 'angelmaecortino@gmail.com', '09123456789', 'Encantadia', 0, '2025-12-05 10:41:04', '2025-12-05 10:41:04', NULL),
(9, 9, NULL, 'injelme', '23-77063@g.batstate-u.edu.ph', '3453284791', 'Encantadia', 0, '2025-12-05 10:42:22', '2025-12-05 10:42:22', NULL),
(10, 10, NULL, 'kjefhroqeiwof', 'priscilocortino@gmail.com', '734513749', 'basta', 0, '2025-12-05 15:54:49', '2025-12-05 15:54:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `contract_number` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL,
  `signature_data` text DEFAULT NULL,
  `signature_date` datetime DEFAULT NULL,
  `signed_contract_path` varchar(500) DEFAULT NULL,
  `status` enum('draft','sent','signed','expired','cancelled') NOT NULL DEFAULT 'draft',
  `sent_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_locked` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `booking_id`, `client_id`, `contract_number`, `title`, `content`, `terms_conditions`, `signature_data`, `signature_date`, `signed_contract_path`, `status`, `sent_at`, `expires_at`, `created_by`, `created_at`, `updated_at`, `is_locked`) VALUES
(1, 10, 2, 'CON2025112536C410', 'Event Service Title Agreement', '<p>Hello welvcom fnsjdhfsjdhfshksn isa ka bang kupal, kung hinde, edi nice</p>\r\n', '<p>try daw yow yey what a nice</p>\r\n', NULL, NULL, NULL, 'sent', '2025-11-25 02:19:51', '2025-12-02 02:19:51', 1, '2025-11-25 02:19:47', '2025-11-25 02:19:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `data_requests`
--

CREATE TABLE `data_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `registered_email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `request_type` enum('booking_history','personal_data','data_correction','data_deletion','other') NOT NULL,
  `details` text NOT NULL,
  `booking_reference` varchar(50) DEFAULT NULL,
  `valid_id_file` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `status` enum('pending','processing','completed','rejected') NOT NULL DEFAULT 'pending',
  `submitted_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `status` enum('pending','rejected','approved') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `client_id`, `rating`, `comments`, `status`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 'What a nice, this will go to the database if it works', 'rejected', '2025-11-10 19:59:23', '2025-12-01 06:26:05'),
(4, 2, 3, 'yey what a nice', 'rejected', '2025-11-10 20:02:09', '2025-11-15 06:43:16'),
(5, 2, 3, 'Sira laang ang rating', 'approved', '2025-11-10 20:12:00', '2025-11-14 05:14:53'),
(7, 2, 2, 'Andaming', 'rejected', '2025-11-12 04:41:55', '2025-12-01 06:26:20'),
(8, 2, 2, 'try m,gyfghbjnklmjhgvcfrse', 'rejected', '2025-11-12 07:08:30', '2025-12-01 06:26:24'),
(9, 2, 3, 'Try daw,  kung gagana', 'rejected', '2025-11-14 02:56:57', '2025-11-14 02:57:05'),
(11, 2, 3, 'sample po with sir mags', 'approved', '2025-11-15 06:41:09', '2025-11-15 06:42:58'),
(12, 2, 1, 'n jbjbjtttttt', 'rejected', '2025-11-15 06:41:48', '2025-12-01 06:26:12'),
(13, 6, 5, 'okay naman e', 'rejected', '2025-11-25 04:01:15', '2025-11-28 16:57:47'),
(14, 6, 5, 'maganda sya', 'approved', '2025-12-01 06:22:18', '2025-12-01 06:26:28'),
(15, 6, 4, 'feedback december 2', 'approved', '2025-12-02 01:34:09', '2025-12-02 01:43:50');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1762295098, 1),
(2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1762295098, 1),
(3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1762295098, 1),
(4, '2025-10-07-132125', 'App\\Database\\Migrations\\CreateClientTable', 'default', 'App', 1762295098, 1),
(5, '2025-10-07-143446', 'App\\Database\\Migrations\\CreateAdminTable', 'default', 'App', 1762295098, 1),
(6, '2025-10-08-004748', 'App\\Database\\Migrations\\CreateBookingTable', 'default', 'App', 1762295098, 1),
(7, '2025-10-08-004749', 'App\\Database\\Migrations\\CreatePaymentTable', 'default', 'App', 1762295098, 1),
(8, '2025-10-08-004830', 'App\\Database\\Migrations\\CreateAddonTable', 'default', 'App', 1762295098, 1),
(9, '2025-10-08-004840', 'App\\Database\\Migrations\\CreatePackageTable', 'default', 'App', 1762295098, 1),
(10, '2025-10-08-004854', 'App\\Database\\Migrations\\CreateStaffTable', 'default', 'App', 1762295098, 1),
(11, '2025-10-08-004856', 'App\\Database\\Migrations\\CreateBookingPackageTable', 'default', 'App', 1762295098, 1),
(12, '2025-10-08-004906', 'App\\Database\\Migrations\\CreateBookingStaffTable', 'default', 'App', 1762295098, 1),
(13, '2025-10-08-004922', 'App\\Database\\Migrations\\CreateBookingAddonTable', 'default', 'App', 1762295098, 1),
(14, '2025-10-08-005005', 'App\\Database\\Migrations\\CreateAuditLogsTable', 'default', 'App', 1762295098, 1),
(15, '2025-11-10-180837', 'App\\Database\\Migrations\\CreateFeedbackTable', 'default', 'App', 1762799367, 2),
(16, '2025-11-10-183826', 'App\\Database\\Migrations\\UpdateClientTable', 'default', 'App', 1762799998, 3),
(17, '2025-11-16-180623', 'App\\Database\\Migrations\\CreateDataRequestsTable', 'default', 'App', 1763317224, 4),
(18, '2025-11-16-183854', 'App\\Database\\Migrations\\CreateNotificationsTable', 'default', 'App', 1763318596, 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `related_type` varchar(100) DEFAULT NULL,
  `related_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `type`, `is_read`, `user_id`, `related_type`, `related_id`, `created_at`, `updated_at`) VALUES
(1, 'Welcome to the Dashboard', 'Your notification system is working correctly!', 'info', 0, NULL, NULL, NULL, '2025-11-23 16:36:56', '2025-11-23 16:36:56'),
(2, 'New Booking Request', 'A client has requested a booking for the Enclosed Venue', 'booking', 0, NULL, NULL, NULL, '2025-11-23 16:36:56', '2025-11-23 16:36:56'),
(3, 'Payment Received', 'Payment of ₱15,000 received from John Doe', 'payment', 0, NULL, NULL, NULL, '2025-11-23 16:36:56', '2025-11-23 16:36:56');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `base_hours` int(11) NOT NULL DEFAULT 4,
  `overtime_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_capacity` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `description`, `base_price`, `base_hours`, `overtime_rate`, `max_capacity`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Venue', 'open', 12000.00, 15000, 1000.00, 50, 'active', '2025-11-19 18:02:20', '2025-12-02 00:26:39'),
(6, 'Playground', 'Open', 8000.00, 10000, 1000.00, 45, 'active', '2025-11-19 18:03:22', '2025-12-01 07:35:16'),
(8, 'Prep & Photoshoot', 'Basic', 3000.00, 5000, 1000.00, 9, 'active', '2025-11-21 03:07:29', '2025-12-01 07:31:35'),
(10, 'Cafe 2nd Floor', 'kapehan', 20000.00, 6, 1000.00, 50, 'active', '2025-12-01 07:39:55', '2025-12-01 07:39:55'),
(11, 'Cafe Meetings', 'kapehan din', 20000.00, 6, 1000.00, 50, 'active', '2025-12-01 07:39:55', '2025-12-01 07:39:55'),
(12, 'Cafe 2nd Floor', 'Basic', 20000.00, 6, 10000.00, 50, 'active', '2025-12-02 00:30:27', '2025-12-02 00:30:27'),
(13, 'Cafe Meetings', 'exclusive', 20000.00, 3, 1000.00, 50, 'active', '2025-12-02 00:31:22', '2025-12-02 00:31:22'),
(14, 'new Package', 'package for all', 12000.00, 5, 450.00, 34, 'active', '2025-12-02 01:38:48', '2025-12-02 01:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `package_venues`
--

CREATE TABLE `package_venues` (
  `id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `venue_id` int(10) UNSIGNED NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `package_venues`
--

INSERT INTO `package_venues` (`id`, `package_id`, `venue_id`, `is_primary`, `created_at`, `updated_at`) VALUES
(8, 6, 6, 0, '2025-11-19 18:03:22', '2025-12-02 00:26:25'),
(9, 6, 7, 1, '2025-11-19 18:03:22', '2025-12-02 00:26:25'),
(10, 6, 8, 0, '2025-11-19 18:03:22', '2025-12-02 00:26:25'),
(13, 8, 6, 0, '2025-11-21 03:07:29', '2025-12-02 00:26:25'),
(14, 8, 7, 0, '2025-11-21 03:07:29', '2025-12-02 00:26:25'),
(15, 8, 8, 1, '2025-11-21 03:07:29', '2025-12-02 00:26:25'),
(19, 5, 6, 1, '2025-12-02 00:26:39', '2025-12-02 00:26:39'),
(20, 12, 7, 1, '2025-12-02 00:30:27', '2025-12-02 00:30:27'),
(21, 12, 10, 0, '2025-12-02 00:30:27', '2025-12-02 00:30:27'),
(22, 13, 6, 1, '2025-12-02 00:31:22', '2025-12-02 00:31:22'),
(26, 14, 14, 0, '2025-12-02 01:39:07', '2025-12-02 01:39:07'),
(27, 14, 15, 1, '2025-12-02 01:39:07', '2025-12-02 01:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `payment_reference` varchar(50) NOT NULL,
  `ref_number` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('gcash','paymaya','bank_transfer','cash') NOT NULL,
  `payment_date` datetime NOT NULL,
  `receipt_image` varchar(500) DEFAULT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `verified_by` int(10) UNSIGNED DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `client_id`, `payment_reference`, `ref_number`, `amount`, `payment_method`, `payment_date`, `receipt_image`, `status`, `verified_by`, `verified_at`, `notes`, `created_at`) VALUES
(5, 10, 2, 'PAY2025112286089B', '34134212345123433', 1500.00, 'bank_transfer', '2025-11-22 00:00:00', 'uploads/receipts/1763773960_8a2cf1a70457f7229085.jpg', 'pending', NULL, NULL, '', '2025-11-22 01:12:40'),
(20, 10, 2, 'PAY2025112296E33A', 'src_78nDo46L26RMS2gFzsxDsvhP', 1500.00, '', '2025-11-22 01:56:57', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 01:56:57'),
(21, 10, 2, 'PAY202511222BF622', 'src_3Zv8An2hmLQCNwGfpLsn558B', 1500.00, '', '2025-11-22 01:57:06', NULL, '', NULL, NULL, NULL, '2025-11-22 01:57:06'),
(22, 10, 2, 'PAY20251122124F20', 'PM_ZXx0V0pFSq7f', 1500.00, '', '2025-11-22 02:00:33', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:00:33'),
(23, 10, 2, 'PAY2025112225B714', 'PM_F6YnF42GGhXy', 1500.00, '', '2025-11-22 02:01:22', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:01:22'),
(24, 10, 2, 'PAY20251122443ACD', 'src_e324eWGyzZfDK4vWAKWNHGBb', 1500.00, '', '2025-11-22 02:24:04', NULL, '', NULL, NULL, NULL, '2025-11-22 02:24:04'),
(25, 10, 2, 'PAY20251122F3FBE0', 'PM_NwJVwX39k5cY', 1500.00, '', '2025-11-22 02:24:15', NULL, '', NULL, NULL, 'PayMongo Error: PayMongo API Error: metadata attributes cannot be nested.', '2025-11-22 02:24:15'),
(26, 10, 2, 'PAY2025112211F8F1', 'pi_aXQzQtcY8tWf8VsN1htub4Ki', 1500.00, '', '2025-11-22 02:27:45', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:27:45'),
(27, 10, 2, 'PAY202511222CA52B', 'pi_bmuAFzP7K7Ck4MMRw6Nwf3aM', 1500.00, '', '2025-11-22 02:27:46', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:27:46'),
(28, 10, 2, 'PAY202511224CFE18', 'pi_WMnxv7erHiqLELaaGAoPRcE4', 1500.00, '', '2025-11-22 02:27:48', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:27:48'),
(29, 10, 2, 'PAY20251122323028', 'pi_Ve7m9gbcVVGuP7wngFBSqxAg', 1500.00, '', '2025-11-22 02:29:23', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:29:23'),
(30, 10, 2, 'PAY202511224569B7', 'pi_LDMLQ4nFbmPmtmr3E9fVvW2D', 1500.00, '', '2025-11-22 02:29:24', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:29:24'),
(31, 10, 2, 'PAY202511225C9433', 'pi_7TPG9qEqxyBHTck9LXShCnks', 1500.00, '', '2025-11-22 02:29:25', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:29:25'),
(32, 10, 2, 'PAY20251122D26D9C', 'pi_1Gt7yTDggD6yjEtUdgKexbuM', 1500.00, '', '2025-11-22 02:29:33', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:29:33'),
(33, 10, 2, 'PAY202511223347A2', 'pi_gsMzK6HyCM4uNKsdUmWHrPbA', 1500.00, '', '2025-11-22 02:42:59', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 02:42:59'),
(34, 10, 2, 'PAY20251122F27BF1', 'pi_z9V65Z4WUyABAu31ndEamf9n', 1500.00, '', '2025-11-22 05:13:51', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 05:13:51'),
(35, 10, 2, 'PAY20251122223798', 'pi_ZHVx212M9FfCHN3jyLj9ctHf', 1500.00, '', '2025-11-22 05:13:54', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 05:13:54'),
(36, 10, 2, 'PAY20251122682C0A', 'pi_6ejv44QdJvSQj6uKvp4jLKob', 1500.00, '', '2025-11-22 05:13:58', NULL, 'pending', NULL, NULL, NULL, '2025-11-22 05:13:58'),
(37, 10, 2, 'PAY20251122AAC950', 'src_Df1rXNF1xhVyaoWH5hEXpRUX', 1500.00, 'gcash', '2025-11-22 05:14:02', NULL, 'verified', 1, '2025-11-23 14:02:55', NULL, '2025-11-22 05:14:02');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `status`, `status_message`, `active`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'administrator@farmease.app', NULL, NULL, 1, NULL, '2025-11-10 17:52:53', '2025-11-10 17:52:53', NULL),
(2, 'magnaye.rp@gmail.com', NULL, NULL, 1, NULL, '2025-11-10 17:55:42', '2025-11-10 17:56:32', NULL),
(3, '23-74604@g.batstate-u.edu.ph', NULL, NULL, 1, NULL, '2025-11-11 09:52:02', '2025-11-24 21:31:06', '2025-11-24 21:31:06'),
(4, 'sample@gmail.com', NULL, NULL, 1, NULL, '2025-11-14 05:33:20', '2025-11-14 05:33:20', NULL),
(5, 'earlsincombenido0@gmail.com', NULL, NULL, 1, NULL, '2025-11-25 03:55:13', '2025-11-25 03:55:41', NULL),
(6, 'abbbyygarcia@gmail.com', NULL, NULL, 1, NULL, '2025-11-25 03:56:27', '2025-11-25 03:56:52', NULL),
(7, 'albertpaytaren@gmail.com', NULL, NULL, 0, NULL, '2025-12-02 01:29:59', '2025-12-02 01:29:59', NULL),
(8, 'angelmaecortino@gmail.com', NULL, NULL, 0, NULL, '2025-12-05 10:41:04', '2025-12-05 10:41:04', NULL),
(9, '23-77063@g.batstate-u.edu.ph', NULL, NULL, 1, NULL, '2025-12-05 10:42:22', '2025-12-05 10:42:38', NULL),
(10, 'priscilocortino@gmail.com', NULL, NULL, 0, NULL, '2025-12-05 15:54:49', '2025-12-05 15:54:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `name`, `description`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(6, 'Cafe 2nd Floor', 'A cozy, elevated space on the second floor perfect for relaxing, enjoying coffee or meals, and socializing.', 'venues/1764562216_5b68e056d09c0e83b2e2.png', 'active', '2025-11-19 14:45:18', '2025-12-06 06:20:59'),
(7, 'Farm', 'A spacious, lush area filled with greenery, crops, and natural scenery, ideal for educational tours, outdoor activities, or a relaxing nature experience.', 'venues/1764562305_df1df8664bb810d5ec28.png', 'active', '2025-11-19 14:53:53', '2025-12-06 06:21:02'),
(8, 'Ceremony Area', 'Cerenomy', 'venues/1764562415_89e7b7146c1fb5230d3d.png', 'inactive', '2025-11-19 14:55:22', '2025-12-06 02:39:37'),
(10, 'King Size Bedroom', 'A spacious, luxurious room featuring a large king-sized bed, ample space for movement, and elegant furnishings.', 'venues/1764562106_c48ee447dac21559a718.png', 'inactive', '2025-12-01 04:08:27', '2025-12-06 05:38:14'),
(11, 'Lounge', 'A comfortable, stylish space designed for relaxation and socializing', 'venues/1764571861_fb7523643922e68f45ec.png', 'inactive', '2025-12-01 06:51:01', '2025-12-06 06:20:54'),
(12, 'Garden', 'A beautifully landscaped outdoor area designed for relaxation and enjoyment, featuring greenery, flowers, and sometimes seating spaces.', 'venues/1764571994_5f63d2f226a9372def9a.png', 'active', '2025-12-01 06:53:14', '2025-12-01 06:53:14'),
(13, 'Open Event Place', 'A spacious outdoor venue ideal for hosting gatherings, parties, or celebrations.', 'venues/1764572064_e3022e8525ef52928c0c.png', 'active', '2025-12-01 06:54:24', '2025-12-02 00:02:41'),
(14, 'KTV Room', 'A private, soundproof room designed for karaoke and entertainment, perfect for small groups or parties to sing, relax, and have fun.', 'venues/1764572276_a2e9de0cf6e0a9abbbee.png', 'inactive', '2025-12-01 06:57:56', '2025-12-06 05:38:23'),
(15, 'New Venue', 'This new venue includes beautiful places', 'venues/1764639438_d50326fbb02c17e74688.png', 'inactive', '2025-12-02 01:37:18', '2025-12-06 22:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `venue_images`
--

CREATE TABLE `venue_images` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `image_path` text NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `venue_images`
--

INSERT INTO `venue_images` (`id`, `venue_id`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
(9, 6, 'uploads/venues/gallery/1764636072_1dd24083a2894e92e74b.png', 1, '2025-12-02 00:41:12', '2025-12-02 00:41:12'),
(10, 7, 'uploads/venues/gallery/1764636128_3ed4a0346ae9dc7f00b7.png', 1, '2025-12-02 00:42:08', '2025-12-02 00:42:08'),
(11, 8, 'uploads/venues/gallery/1764636147_871a4f5ea6037cb3ca16.png', 1, '2025-12-02 00:42:27', '2025-12-02 00:42:27'),
(12, 10, 'uploads/venues/gallery/1764636169_4c1165c0a3d61903288f.png', 1, '2025-12-02 00:42:49', '2025-12-02 00:42:49'),
(13, 11, 'uploads/venues/gallery/1764636197_fe681cccb50190e0df34.png', 1, '2025-12-02 00:43:17', '2025-12-02 00:43:17'),
(14, 12, 'uploads/venues/gallery/1764636216_9aff556f4d93f076d1e3.png', 1, '2025-12-02 00:43:36', '2025-12-02 00:43:36'),
(15, 13, 'uploads/venues/gallery/1764636242_c8fdd02807e0e268601f.png', 1, '2025-12-02 00:44:02', '2025-12-02 00:44:02'),
(16, 14, 'uploads/venues/gallery/1764636262_733e69a35eda57f74334.png', 1, '2025-12-02 00:44:22', '2025-12-02 00:44:22'),
(17, 6, 'uploads/venues/gallery/1764636529_21771916006f7b5d2698.png', 1, '2025-12-02 00:48:49', '2025-12-02 00:48:49'),
(18, 12, 'uploads/venues/gallery/1764636574_1200f022b649806681f8.png', 1, '2025-12-02 00:49:34', '2025-12-02 00:49:34'),
(19, 12, 'uploads/venues/gallery/1764636609_a8a0cf6c889d41b2976b.png', 1, '2025-12-02 00:50:09', '2025-12-02 00:50:09'),
(20, 10, 'uploads/venues/gallery/1764636639_9e4de39481ee7b888cf7.png', 1, '2025-12-02 00:50:39', '2025-12-02 00:50:39'),
(21, 7, 'uploads/venues/gallery/1764636691_b48cc7b2bcb0771f21f2.png', 1, '2025-12-02 00:51:31', '2025-12-02 00:51:31'),
(22, 6, 'uploads/venues/gallery/1764636734_e6b09770fab7b5b6b595.png', 1, '2025-12-02 00:52:14', '2025-12-02 00:52:14'),
(23, 13, 'uploads/venues/gallery/1764636788_bacba792d25de4bbc2b7.png', 1, '2025-12-02 00:53:08', '2025-12-02 00:53:08'),
(24, 11, 'uploads/venues/gallery/1764636898_e54a7d26f15af5a6c0f3.png', 1, '2025-12-02 00:54:58', '2025-12-06 06:18:24'),
(25, 14, 'uploads/venues/gallery/1764636980_b212791e9b952228b4b2.png', 1, '2025-12-02 00:56:20', '2025-12-02 00:56:20'),
(26, 8, 'uploads/venues/gallery/1764637105_4ae5c0dac27eb035bdd0.png', 1, '2025-12-02 00:58:25', '2025-12-02 00:58:25'),
(27, 7, 'uploads/venues/gallery/1764639802_75ac6cb0198d91b6b26a.png', 1, '2025-12-02 01:43:22', '2025-12-02 01:43:22'),
(28, 7, 'uploads/venues/gallery/1764639802_483071846216f0f6d21d.png', 1, '2025-12-02 01:43:22', '2025-12-02 01:43:22'),
(29, 7, 'uploads/venues/gallery/1764639802_fd51601a7b04b566c4ff.png', 1, '2025-12-02 01:43:23', '2025-12-02 01:43:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `position` (`position`),
  ADD KEY `admins_user_id_foreign` (`user_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_secret` (`type`,`secret`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_permissions_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `auth_remember_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `idx_event_date` (`event_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_bookings_client_date` (`client_id`,`event_date`),
  ADD KEY `idx_bookings_package_venue` (`package_id`,`venue_id`);

--
-- Indexes for table `booking_addons`
--
ALTER TABLE `booking_addons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking_addon` (`booking_id`,`addon_id`),
  ADD KEY `addon_id` (`addon_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `clients_user_id_foreign` (`user_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contracts_booking` (`booking_id`),
  ADD KEY `fk_contracts_client` (`client_id`),
  ADD KEY `fk_contracts_created_by` (`created_by`);

--
-- Indexes for table `data_requests`
--
ALTER TABLE `data_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_client_id_foreign` (`client_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_read_idx` (`user_id`,`is_read`),
  ADD KEY `related_idx` (`related_type`,`related_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_packages_status` (`status`);

--
-- Indexes for table `package_venues`
--
ALTER TABLE `package_venues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_package_venue` (`package_id`,`venue_id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `idx_package_venues_primary` (`package_id`,`is_primary`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_reference` (`payment_reference`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `verified_by` (`verified_by`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_venues_status` (`status`);

--
-- Indexes for table `venue_images`
--
ALTER TABLE `venue_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_TO_VENUES` (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `booking_addons`
--
ALTER TABLE `booking_addons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `data_requests`
--
ALTER TABLE `data_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `package_venues`
--
ALTER TABLE `package_venues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `venue_images`
--
ALTER TABLE `venue_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`);

--
-- Constraints for table `booking_addons`
--
ALTER TABLE `booking_addons`
  ADD CONSTRAINT `booking_addons_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_addons_ibfk_2` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`id`);

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `fk_contracts_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contracts_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contracts_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `package_venues`
--
ALTER TABLE `package_venues`
  ADD CONSTRAINT `package_venues_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_venues_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `admins` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
