-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2025 at 07:12 AM
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
-- Database: `k-nect`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `user_id` int(50) NOT NULL,
  `barangay` tinyint(2) NOT NULL,
  `municipality` tinyint(2) NOT NULL,
  `province` tinyint(2) NOT NULL,
  `region` tinyint(2) NOT NULL,
  `zone_purok` int(10) DEFAULT NULL,
  `zip_code` int(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`user_id`, `barangay`, `municipality`, `province`, `region`, `zone_purok`, `zip_code`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, NULL, '2025-09-10 16:47:18', '2025-09-10 16:47:18'),
(2, 1, 1, 1, 1, 1, NULL, '2025-09-10 16:50:13', '2025-09-10 16:50:13');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `rfid_code` varchar(255) DEFAULT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `time-in_am` datetime DEFAULT NULL,
  `time-out_am` datetime DEFAULT NULL,
  `time-in_pm` datetime DEFAULT NULL,
  `time-out_pm` datetime DEFAULT NULL,
  `status_am` varchar(10) DEFAULT NULL,
  `status_pm` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(50) NOT NULL,
  `performed_by` varchar(100) NOT NULL,
  `performed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangay`
--

CREATE TABLE `barangay` (
  `barangay_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `google_calendar_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay`
--

INSERT INTO `barangay` (`barangay_id`, `name`, `google_calendar_id`) VALUES
(0, 'City-wide', 'knect.system@gmail.com'),
(1, 'Antipolo', '0d4ec11e731f32ac50758c661184a8da85ee84f76462a05ae868521edd35f4ca@group.calendar.google.com'),
(2, 'Cristo Rey', '9958531a6befc783254ed3824dfa7f7e989e6552a8fc18982d02d8e1602da9f3@group.calendar.google.com'),
(3, 'Del Rosario (Banao)', 'c7e13000dc0ccb2d6709cbe5912c2752f79f647a98ed58a21e0bc362340b30c4@group.calendar.google.com'),
(4, 'Francia', ''),
(5, 'La Anunciacion', ''),
(6, 'La Medalla', ''),
(7, 'La Purisima', ''),
(8, 'La Trinidad', ''),
(9, 'Niño Jesus', ''),
(10, 'Perpetual Help', ''),
(11, 'Sagrada', ''),
(12, 'Salvacion', ''),
(13, 'San Agustin', ''),
(14, 'San Andres', ''),
(15, 'San Antonio', ''),
(16, 'San Francisco', ''),
(17, 'San Isidro', ''),
(18, 'San Jose', ''),
(19, 'San Juan', ''),
(20, 'San Miguel', ''),
(21, 'San Nicolas', ''),
(22, 'San Pedro', ''),
(23, 'San Rafael', ''),
(24, 'San Ramon', ''),
(25, 'San Roque', ''),
(26, 'Santiago', ''),
(27, 'San Vicente Norte', ''),
(28, 'San Vicente Sur', ''),
(29, 'Sta. Cruz Norte', ''),
(30, 'Sta. Cruz Sur', ''),
(31, 'Sta. Elena', ''),
(32, 'Sta. Isabel', ''),
(33, 'Sta. Maria', ''),
(34, 'Sta. Teresita', ''),
(35, 'Sto. Domingo', ''),
(36, 'Sto. Niño', '');

-- --------------------------------------------------------

--
-- Table structure for table `bulletin_categories`
--

CREATE TABLE `bulletin_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(7) DEFAULT '#3B82F6',
  `icon` varchar(50) DEFAULT 'newspaper',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulletin_posts`
--

CREATE TABLE `bulletin_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `excerpt` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `barangay_id` int(11) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `visibility` enum('public','barangay','city') DEFAULT 'public',
  `is_featured` tinyint(1) DEFAULT 0,
  `is_urgent` tinyint(1) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bulletin_posts`
--

INSERT INTO `bulletin_posts` (`id`, `title`, `content`, `excerpt`, `featured_image`, `category_id`, `author_id`, `barangay_id`, `status`, `visibility`, `is_featured`, `is_urgent`, `view_count`, `published_at`, `created_at`, `updated_at`) VALUES
(0, 'No Classes to All Level', 'Memorandum 1234', NULL, NULL, NULL, 1, 1, 'published', 'barangay', 0, 0, 0, '2025-09-11 04:42:59', '2025-09-11 04:42:59', '2025-09-11 04:42:59'),
(0, 'No Classes', 'No Classes in all levels', NULL, '1757566251_db28a636c443e0c40ca4.jpg', NULL, 1, 1, 'published', 'barangay', 1, 0, 0, '2025-09-11 04:50:51', '2025-09-11 04:50:51', '2025-09-11 04:50:51');

-- --------------------------------------------------------

--
-- Table structure for table `bulletin_post_tags`
--

CREATE TABLE `bulletin_post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulletin_tags`
--

CREATE TABLE `bulletin_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `filepath` varchar(255) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `uploaded_at` datetime NOT NULL,
  `filesize` int(11) NOT NULL,
  `mimetype` varchar(100) NOT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `visibility` enum('SK','KK') DEFAULT 'SK',
  `approver` varchar(100) DEFAULT NULL,
  `approval_at` datetime DEFAULT NULL,
  `approval_comment` text DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `downloadable` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_category`
--

CREATE TABLE `document_category` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_shares`
--

CREATE TABLE `document_shares` (
  `id` int(11) UNSIGNED NOT NULL,
  `document_id` int(11) UNSIGNED NOT NULL,
  `shared_by` varchar(100) NOT NULL,
  `shared_with` varchar(100) NOT NULL,
  `permissions` enum('view','download','edit','admin') NOT NULL DEFAULT 'view',
  `expires_at` datetime DEFAULT NULL,
  `shared_at` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_tag`
--

CREATE TABLE `document_tag` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_versions`
--

CREATE TABLE `document_versions` (
  `id` int(11) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `version_number` int(10) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `uploaded_at` datetime NOT NULL,
  `filesize` int(11) NOT NULL,
  `mimetype` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `barangay_id` int(11) UNSIGNED NOT NULL,
  `google_event_id` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Draft','Scheduled','Published','cancelled','postponed') DEFAULT 'Draft',
  `publish_date` datetime DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `event_banner` varchar(255) DEFAULT NULL,
  `category` enum('health','education','economic empowerment','social inclusion and equity','peace building and security','governance','active citizenship','environment','global mobility','others') DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `scheduling_enabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Toggle for scheduling option',
  `scheduled_publish_datetime` datetime DEFAULT NULL COMMENT 'Scheduled publish date and time',
  `sms_notification_enabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Toggle for SMS notification',
  `sms_recipient_scope` enum('all_barangays','specific_barangays') DEFAULT NULL COMMENT 'Scope for SMS recipients',
  `sms_recipient_barangays` text DEFAULT NULL COMMENT 'JSON array of specific barangay IDs for SMS',
  `sms_recipient_roles` text DEFAULT NULL COMMENT 'JSON array of recipient roles (all_officials, chairperson, secretary, treasurer)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `barangay_id`, `google_event_id`, `title`, `description`, `status`, `publish_date`, `start_datetime`, `end_datetime`, `location`, `event_banner`, `category`, `created_by`, `created_at`, `updated_at`, `scheduling_enabled`, `scheduled_publish_datetime`, `sms_notification_enabled`, `sms_recipient_scope`, `sms_recipient_barangays`, `sms_recipient_roles`) VALUES
(1, 1, 'eh0m4iv4rlvqir2fiigp6hsfg4', 'KK Assembly', 'KK Assembly', 'Published', '2025-09-11 12:32:09', '2025-09-13 13:00:00', '2025-09-13 17:00:00', 'Barangay Hall', '1757565129_11b1ba19d1add8b9ecbe.jpg', 'health', 25, '2025-09-11 04:32:09', '2025-09-11 04:32:10', 0, NULL, 0, NULL, NULL, NULL),
(2, 1, NULL, 'aaa', 'aa', 'Draft', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'aaa', NULL, 'health', 25, '2025-09-11 04:32:28', '2025-09-11 04:32:28', 0, NULL, 0, NULL, NULL, NULL),
(3, 1, 'si7jnuc4me7e22etbaf85q378o', 'SK Meeting', 'SK Meeting', 'Published', '2025-09-11 12:38:21', '2025-09-12 13:00:00', '2025-09-12 15:00:00', 'SK Office', '1757565501_2b651259f0cf3e7195db.jpg', 'governance', 25, '2025-09-11 04:38:21', '2025-09-11 04:38:22', 0, NULL, 0, NULL, NULL, NULL),
(4, 0, '70aroj4ucrc6g7pgugj6ljoft8', 'All SK in Iriga City Meeting', 'Meeting', 'Published', '2025-09-11 12:53:03', '2025-09-20 13:00:00', '2025-09-20 15:00:00', 'Iriga City Hall', '1757566383_1fff517ec535cf546757.jpg', 'governance', 25, '2025-09-11 04:53:03', '2025-09-11 04:53:04', 0, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_attendance`
--

CREATE TABLE `event_attendance` (
  `event_attendance_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `start_attendance_am` time DEFAULT NULL,
  `end_attendance_am` time DEFAULT NULL,
  `start_attendance_pm` time DEFAULT NULL,
  `end_attendance_pm` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `position_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `position_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_logo`
--

CREATE TABLE `system_logo` (
  `id` int(11) NOT NULL,
  `logo_type` enum('iriga_city','municipality','barangay','sk','pederasyon') NOT NULL,
  `logo_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `dimensions` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `barangay_id` int(11) DEFAULT NULL,
  `uploaded_by` varchar(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logo`
--

INSERT INTO `system_logo` (`id`, `logo_type`, `logo_name`, `file_path`, `file_size`, `mime_type`, `dimensions`, `is_active`, `barangay_id`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 'iriga_city', 'Iriga City Logo.png', 'uploads/logos/iriga_city_logo_1757566524.png', 65452, 'image/png', '250x250', 1, NULL, '1', '2025-09-11 04:55:24', '2025-09-11 04:55:24'),
(2, 'pederasyon', 'SK pederasyon Logo.jpg', 'uploads/logos/pederasyon_logo_1757566525.jpg', 551341, 'image/jpeg', '2014x2015', 1, NULL, '1', '2025-09-11 04:55:25', '2025-09-11 04:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(50) NOT NULL,
  `rfid_code` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `suffix` varchar(5) DEFAULT NULL,
  `sex` tinyint(1) NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(50) NOT NULL,
  `sk_username` varchar(50) DEFAULT NULL,
  `sk_password` varchar(255) DEFAULT NULL,
  `ped_username` varchar(50) DEFAULT NULL,
  `ped_password` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `ped_position` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `user_type` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `rfid_code`, `user_id`, `last_name`, `first_name`, `middle_name`, `suffix`, `sex`, `birthdate`, `email`, `sk_username`, `sk_password`, `ped_username`, `ped_password`, `phone_number`, `username`, `password`, `position`, `ped_position`, `status`, `user_type`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, NULL, '25-123456', 'Luzano', 'Christian Nico', 'Brizuela', '', 1, '2004-01-22', 'christiannicoluzano15@gmail.com', 'SK_ChristianNicoLuzano', '$2y$12$EgKmHG8kL2vmExKD8y5EXeRTXEy20vr.i/Px0O0iTRVKVMOgEnhfG', 'PED_ChristianNicoLuzano', '$2y$12$SCEEMQ0cEc4gKJDg/ILg9OFfMJrYSh.h8jbMx26d6CaQZhb3oQ7bm', '+639451971854', 'christiannico', '$2y$12$.FoWQgxfrr2x7lpcGj9lguHp5iOyZnzaUp/3S2hDkMjiWKv/Vi.eq', 1, 1, 2, 3, 1, '2025-09-11 12:51:33', '2025-09-11 04:51:33', '2025-09-11 04:51:33'),
(2, NULL, '25-099721', 'Lontayao', 'Jon Mare Edric', 'Parma', '', 1, '2003-05-29', 'jolontayao@gmail.com', 'SK_JonMareEdricLontayao', '1ff74753', NULL, NULL, '+639123456789', 'jonmare', '$2y$12$fGou1awNthfTDVlnbUOxH.2Qo4yG5wQp/rIsYUutyTTd/1FAnqdSO', NULL, NULL, 2, 1, 1, NULL, '2025-09-11 04:53:59', '2025-09-11 04:53:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_ext_info`
--

CREATE TABLE `user_ext_info` (
  `user_id` int(50) NOT NULL,
  `civil_status` tinyint(1) NOT NULL,
  `youth_classification` tinyint(1) NOT NULL,
  `age_group` tinyint(1) NOT NULL,
  `work_status` tinyint(1) NOT NULL,
  `educational_background` tinyint(1) NOT NULL,
  `sk_voter` tinyint(1) NOT NULL,
  `sk_election` tinyint(1) NOT NULL,
  `national_voter` tinyint(1) NOT NULL,
  `kk_assembly` tinyint(1) NOT NULL,
  `how_many_times` tinyint(1) DEFAULT NULL,
  `no_why` tinyint(1) DEFAULT NULL,
  `birth_certificate` varchar(255) DEFAULT NULL,
  `upload_id` varchar(255) DEFAULT NULL,
  `upload_id-back` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_ext_info`
--

INSERT INTO `user_ext_info` (`user_id`, `civil_status`, `youth_classification`, `age_group`, `work_status`, `educational_background`, `sk_voter`, `sk_election`, `national_voter`, `kk_assembly`, `how_many_times`, `no_why`, `birth_certificate`, `upload_id`, `upload_id-back`, `profile_picture`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 2, 6, 1, 1, 1, 1, 3, NULL, 'birthcert_68c1ab9599a37.jpg', 'idpic_68c1ab9599df9.png', 'idback_68c1ab959a1bf.png', 'profilepic_68c1ab959a571.jpg', '', '2025-09-10 16:47:18', '2025-09-10 16:47:18'),
(2, 1, 1, 2, 2, 6, 1, 1, 1, 1, 1, NULL, 'birthcert_68c1ac44dbb03.jpg', 'idpic_68c1ac44dbeb3.png', '', 'profilepic_68c1ac44dc30c.jpg', '', '2025-09-10 16:50:13', '2025-09-10 16:50:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_logs_document_id` (`document_id`);

--
-- Indexes for table `barangay`
--
ALTER TABLE `barangay`
  ADD PRIMARY KEY (`barangay_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_category`
--
ALTER TABLE `document_category`
  ADD PRIMARY KEY (`document_id`,`category_id`),
  ADD KEY `fk_document_category_category_id` (`category_id`);

--
-- Indexes for table `document_shares`
--
ALTER TABLE `document_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_document_shares_document_id` (`document_id`);

--
-- Indexes for table `document_versions`
--
ALTER TABLE `document_versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_attendance`
--
ALTER TABLE `event_attendance`
  ADD PRIMARY KEY (`event_attendance_id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `system_logo`
--
ALTER TABLE `system_logo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfid_code` (`rfid_code`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_ext_info`
--
ALTER TABLE `user_ext_info`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barangay`
--
ALTER TABLE `barangay`
  MODIFY `barangay_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_shares`
--
ALTER TABLE `document_shares`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_versions`
--
ALTER TABLE `document_versions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_attendance`
--
ALTER TABLE `event_attendance`
  MODIFY `event_attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_logo`
--
ALTER TABLE `system_logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_address_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_logs_document_id` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_category`
--
ALTER TABLE `document_category`
  ADD CONSTRAINT `fk_document_category_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_category_document_id` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_shares`
--
ALTER TABLE `document_shares`
  ADD CONSTRAINT `document_shares_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_shares_document_id` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_ext_info`
--
ALTER TABLE `user_ext_info`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
