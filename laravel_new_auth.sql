-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2019 at 03:00 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_new_auth`
--

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `country_name`, `created_at`, `updated_at`, `status`) VALUES
(1, 'India', '2019-06-26 18:30:00', '2019-06-26 18:30:00', '1'),
(2, 'Australia', '2019-06-27 11:38:18', '2019-06-27 11:38:18', '1'),
(3, 'America', '2019-06-27 12:10:21', '2019-06-27 12:48:02', '0'),
(4, 'Uk', '2019-06-27 12:12:01', '2019-06-27 12:45:01', '0'),
(5, 'Japan', '2019-06-27 12:21:06', '2019-06-27 12:49:50', '0'),
(6, 'newd', '2019-06-27 12:24:01', '2019-06-27 12:44:19', '0');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(8, '2014_10_12_000000_create_users_table', 1),
(9, '2014_10_12_100000_create_password_resets_table', 1),
(10, '2019_06_26_045945_create_permissions_table', 1),
(11, '2019_06_26_050304_create_roles_table', 1),
(12, '2019_06_26_050749_create_users_permissions_table', 1),
(13, '2019_06_26_050853_create_users_roles_table', 1),
(14, '2019_06_26_051002_create_roles_permissions_table', 1),
(16, '2019_06_14_051454_create_country_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `slug`, `name`, `created_at`, `updated_at`) VALUES
(1, 'create-user', 'Create User', '2019-06-26 21:41:44', '2019-06-26 21:41:44');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `created_at`, `updated_at`) VALUES
(1, 'union', 'Union', '2019-06-26 21:41:44', '2019-06-26 21:41:44'),
(2, 'branch', 'Branch', '2019-06-26 21:44:18', '2019-06-26 21:44:18'),
(3, 'member', 'Member', '2019-06-26 21:44:18', '2019-06-26 21:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles_permissions`
--

INSERT INTO `roles_permissions` (`role_id`, `permission_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Union', 'union@gmail.com', NULL, '$2y$10$6Ea4/9xzUnDY8VFcJ43PreygZPHWnLyJZYsVla4vo0xm3UwlBs1MG', NULL, '2019-06-26 21:41:45', '2019-06-26 21:41:45'),
(2, 'Branch', 'branch@gmail.com', NULL, '$2y$10$QrBmw3ZD1wzGcSieIP33k.8Ckb4aB3I2UZHcF7SH1fkkIKf0WDGwG', NULL, '2019-06-26 21:41:46', '2019-06-26 21:41:46'),
(3, 'Member', 'member@gmail.com', NULL, '$2y$10$AG1abJiCDUTjyVlLStxvFeNfgh3Olr4P.YFBjcLzhKD9E3XRUA4SO', NULL, '2019-06-26 21:41:46', '2019-06-26 21:41:46'),
(23, 'Murugan', 'murugan.bizsoft1@gmail.com', NULL, '$2y$10$9h3Ugt2gI4ytCF8BH5OMJ.JYm.Z51s/vJXCDfP95iY0QZZZmLL9PG', NULL, '2019-06-27 04:13:00', '2019-06-27 04:13:00'),
(24, 'Muruganp', 'murugan.bizsoft2@gmail.com', NULL, '$2y$10$3m0egMeBGX6YRzt0DEx8H.oZRto9T.lw34pgset2Isruz343yHHR6', NULL, '2019-06-27 04:16:27', '2019-06-27 04:16:27'),
(25, 'Murugancs', 'murugan.bizsoft3@gmail.com', NULL, '$2y$10$wOrTZb8fYcyFzaYH0h3V.e6z2cT9h7qkfNvzBcYS2YF8YoX3voRZm', NULL, '2019-06-27 04:18:25', '2019-06-27 04:18:25'),
(26, 'Murugancs', 'axxaaaaaaaa44444444@gmail.com', NULL, '$2y$10$Tq7TkuAXpGwvHktLQoPjJOEQadrdltzTDRVXJ/qigbPqFHTcAN.Fi', NULL, '2019-06-27 04:21:00', '2019-06-27 04:21:00'),
(27, 'Murugancs', 'murugan.bizsoft4@gmail.com', NULL, '$2y$10$6S5RchNfVolhscE4AA/az.i5.vrNGxc5FCtLHISJisma.0sKpOD7C', NULL, '2019-06-27 04:22:58', '2019-06-27 04:22:58'),
(28, 'gggggggggg', 'murudhfgdhhfjfjfjf67867867868@gmail.com', NULL, '$2y$10$7uJBkNAvGJRA6LjAWw9.oO6pvOKBkVUHmlBZReVjiF.0MPfQDYq6e', NULL, '2019-06-27 04:24:47', '2019-06-27 04:24:47'),
(29, 'Murugan', 'murugan.bizsoft5@gmail.com', NULL, '$2y$10$W1cORphKysNDHMuQUWC72uWuJqsHVO/ck/1DTBNYTa2JJdBoUl3E6', NULL, '2019-06-27 04:25:33', '2019-06-27 04:25:33'),
(30, 'test useer', 'murugantest@gmail.com', NULL, '$2y$10$9rF2iawz3o/onLS52MBsMuybJnFvL9rbqGxEv8aothBTGAFGnAps2', NULL, '2019-06-27 04:30:02', '2019-06-27 04:30:02'),
(31, 'Raj kumar', 'rajkumar.bizsoft@gmail.com', NULL, '$2y$10$vGMey..ftoEkNRlRUpUBKOhfu4WegKyt4BXNC4hYeQ7QA7W0g6arC', NULL, '2019-06-27 04:31:32', '2019-06-27 04:31:32'),
(32, 'Muruganpcs', 'murugan.bizsoft@gmail.com', NULL, '$2y$10$MO/FRu7sHVl9hl4uK3lFmuX7y9sQSBS0k7cN1l2u/87HUP9WIQMdm', NULL, '2019-06-27 04:38:08', '2019-06-27 04:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `users_permissions`
--

CREATE TABLE `users_permissions` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_permissions`
--

INSERT INTO `users_permissions` (`user_id`, `permission_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(23, 3),
(24, 3),
(25, 3),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(30, 3),
(31, 3),
(32, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `roles_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_permissions`
--
ALTER TABLE `users_permissions`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `users_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `users_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD CONSTRAINT `roles_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_permissions`
--
ALTER TABLE `users_permissions`
  ADD CONSTRAINT `users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD CONSTRAINT `users_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
