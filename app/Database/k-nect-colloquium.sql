-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 10:21 AM
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
-- Database: `k-nect-colloquium`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `user_id` int(50) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `municipality` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `zone_purok` int(10) DEFAULT NULL,
  `zip_code` int(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`user_id`, `barangay`, `municipality`, `province`, `region`, `zone_purok`, `zip_code`, `created_at`, `updated_at`) VALUES
(1, '051716011', '051716000', '051700000', '050000000', 5, NULL, '2026-02-10 09:00:44', '2026-02-10 07:01:59'),
(3, '051716007', '051716000', '051700000', '050000000', 1, NULL, '2026-02-10 07:40:39', '2026-02-10 07:40:39');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangay`
--

CREATE TABLE `barangay` (
  `barangay_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `google_calendar_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulletin_post_tags`
--

CREATE TABLE `bulletin_post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulletin_tags`
--

CREATE TABLE `bulletin_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `barangay_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `visibility` enum('SK','KK') DEFAULT 'SK',
  `barangay_id` int(11) DEFAULT NULL COMMENT 'Barangay restriction (NULL = city-wide)',
  `visibility_scope` enum('all','specific_barangay') DEFAULT 'all' COMMENT 'Visibility scope: all barangays or specific barangay',
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `downloadable` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_category`
--

CREATE TABLE `document_category` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_tag`
--

CREATE TABLE `document_tag` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `target_participants` int(11) NOT NULL COMMENT 'Target number of participants for the event (REQUIRED for analytics)',
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
  `sms_recipient_roles` text DEFAULT NULL COMMENT 'JSON array of recipient roles (all_officials, chairman, secretary, treasurer)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `status` enum('sent','failed','delivered') NOT NULL DEFAULT 'sent',
  `created_by` varchar(50) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `event_id` int(11) UNSIGNED DEFAULT NULL,
  `sent_by` int(11) UNSIGNED DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sms_logs`
--

INSERT INTO `sms_logs` (`id`, `phone_number`, `message`, `status`, `created_by`, `response`, `event_id`, `sent_by`, `sent_at`, `created_at`, `updated_at`) VALUES
(1, '+639451971854', 'Thank you, Christian Luzano!\n\nYour K-NECT profiling has been completed successfully.\n\nUsername: christiannico\n\nYour account is now pending approval by SK Officials. You will be notified once approved.\n\nThank you for registering!', 'sent', '25-1011-01', '{\"data\":{\"success\":true,\"message\":\"SMS added to queue for processing\",\"smsBatchId\":\"698ad7ef3fb6a1c4c58e583e\",\"recipientCount\":1}}', NULL, NULL, '2026-02-10 15:02:07', NULL, NULL),
(2, '+639451971854', 'Congratulations Christian Luzano!\nYou are now an SK CHAIRPERSON for Barangay San Francisco (Pob.).\n\nSK Login Credentials:\nUsername: SK_ChristianLuzano\nPassword: 6e4d657b\n\nLogin at K-NECT to access SK features. Welcome!', 'sent', '25-1011-01', '{\"data\":{\"success\":true,\"message\":\"SMS added to queue for processing\",\"smsBatchId\":\"698add423fb6a1c4c58e97cd\",\"recipientCount\":1}}', NULL, NULL, '2026-02-10 15:24:50', NULL, NULL),
(3, '+639451971854', 'Congratulations Christian Luzano!\nYou are now an SK CHAIRPERSON for Barangay San Francisco (Pob.).\n\nSK Login Credentials:\nUsername: SK_ChristianLuzano\nPassword: df043d9e\n\nLogin at K-NECT to access SK features. Welcome!', 'sent', '25-1011-01', '{\"data\":{\"success\":true,\"message\":\"SMS added to queue for processing\",\"smsBatchId\":\"698ade583fb6a1c4c58ea23c\",\"recipientCount\":1}}', NULL, NULL, '2026-02-10 15:29:28', NULL, NULL),
(4, '+639451971854', 'Congratulations Christian Luzano!\nYou are now an SK CHAIRPERSON for Barangay San Francisco (Pob.).\n\nSK Login Credentials:\nUsername: SK_ChristianLuzano\nPassword: 20c24979\n\nLogin at K-NECT to access SK features. Welcome!', 'sent', '25-1011-01', '{\"data\":{\"success\":true,\"message\":\"SMS added to queue for processing\",\"smsBatchId\":\"698adef63fb6a1c4c58ea8d9\",\"recipientCount\":1}}', NULL, NULL, '2026-02-10 15:32:06', NULL, NULL),
(5, '+639123456789', 'Thank you, Jon Mare Edric Lontayao!\n\nYour K-NECT profiling has been completed successfully.\n\nUsername: jonmare\n\nYour account is now pending approval by SK Officials. You will be notified once approved.\n\nThank you for registering!', 'sent', NULL, '{\"data\":{\"success\":true,\"message\":\"SMS added to queue for processing\",\"smsBatchId\":\"698ae0fd3fb6a1c4c58ebe52\",\"recipientCount\":1}}', NULL, NULL, '2026-02-10 15:40:45', NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `system_logo`
--

INSERT INTO `system_logo` (`id`, `logo_type`, `logo_name`, `file_path`, `file_size`, `mime_type`, `dimensions`, `is_active`, `barangay_id`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 'iriga_city', 'iriga_city_logo_1761212662.png', 'uploads/logos/iriga_city_logo_1770705888.png', 65452, 'image/png', '250x250', 1, NULL, '1', '2026-02-10 06:44:48', '2026-02-10 06:44:48'),
(2, 'pederasyon', 'barangay_logo_1762352266.png', 'uploads/logos/pederasyon_logo_1770705931.png', 733509, 'image/png', '1230x1230', 1, NULL, '1', '2026-02-10 06:45:31', '2026-02-10 06:45:31');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` varchar(50) NOT NULL DEFAULT 'string',
  `description` varchar(255) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'default_region_code', '050000000', 'string', 'Default location setting', 'PED_DessamareLontayao', '2026-02-10 14:43:52', '2026-02-10 14:43:53'),
(2, 'default_region_name', 'Bicol Region', 'string', 'Default location setting', 'PED_DessamareLontayao', '2026-02-10 14:43:52', '2026-02-10 14:43:53'),
(3, 'default_province_code', '051700000', 'string', 'Default location setting', 'PED_DessamareLontayao', '2026-02-10 14:43:52', '2026-02-10 14:43:53'),
(4, 'default_province_name', 'Camarines Sur', 'string', 'Default location setting', 'PED_DessamareLontayao', '2026-02-10 14:43:52', '2026-02-10 14:43:53'),
(5, 'default_municipality_code', '051716000', 'string', 'Default location setting', 'PED_DessamareLontayao', '2026-02-10 14:43:52', '2026-02-10 14:43:53'),
(6, 'default_municipality_name', 'City of Iriga', 'string', 'Default location setting', 'PED_DessamareLontayao', '2026-02-10 14:43:52', '2026-02-10 14:43:53');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `gender` varchar(50) DEFAULT NULL,
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
  `deactivation_reason` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `rfid_code`, `user_id`, `last_name`, `first_name`, `middle_name`, `suffix`, `sex`, `gender`, `birthdate`, `email`, `sk_username`, `sk_password`, `ped_username`, `ped_password`, `phone_number`, `username`, `password`, `position`, `ped_position`, `status`, `user_type`, `is_active`, `deactivation_reason`, `last_login`, `created_at`, `updated_at`) VALUES
(1, NULL, '0210202601', 'Luzano', 'Christian Nico', 'Brizuela', NULL, 1, NULL, '2004-01-22', 'chluzano@my.cspc.edu.ph', NULL, NULL, 'PED_Nico', '$2y$12$vQ2KRmAwbdyn2b48/Sd1uetICpUMvCkPjnM5JCzjouQ5wVSz6y.em', '+639451971854', 'christiannico', '$2y$12$An.gpTamGfDvH3mxsuld4.F9qQ9q7plqQqWBjnHN9UO2z9rFOkGoe', 1, 1, 2, 3, 1, NULL, '2026-02-10 17:03:09', '2026-02-10 09:03:09', '2026-02-10 09:03:09'),
(3, NULL, NULL, 'Lontayao', 'Jon Mare Edric', 'Parma', '', 1, '1', '2003-05-29', 'jolontayo@my.cspc.edu.ph', NULL, NULL, NULL, NULL, '+639123456789', 'jonmare', '$2y$12$An.gpTamGfDvH3mxsuld4.F9qQ9q7plqQqWBjnHN9UO2z9rFOkGoe', NULL, NULL, 1, 1, 1, NULL, NULL, '2026-02-10 07:40:39', '2026-02-10 07:40:39');

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
  `agreement` int(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_ext_info`
--

INSERT INTO `user_ext_info` (`user_id`, `civil_status`, `youth_classification`, `age_group`, `work_status`, `educational_background`, `sk_voter`, `sk_election`, `national_voter`, `kk_assembly`, `how_many_times`, `no_why`, `birth_certificate`, `upload_id`, `upload_id-back`, `profile_picture`, `reason`, `agreement`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 2, 7, 1, 1, 1, 1, 2, NULL, 'birthcert_698ad7e6df7a9.jpg', 'idpic_698ad7e6dfdeb.png', 'idback_698ad7e6e04ff.png', 'profilepic_698ad7e6e0bb5.jpg', '', NULL, '2026-02-10 07:01:59', '2026-02-10 07:01:59'),
(3, 1, 1, 2, 2, 6, 1, 1, 1, 1, 1, NULL, 'birthcert_698ae0f71f7e5.jpg', 'idpic_698ae0f71fcf2.png', 'idback_698ae0f7200b0.png', 'profilepic_698ae0f7205ef.jpg', '', NULL, '2026-02-10 07:40:39', '2026-02-10 07:40:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_otp`
--

CREATE TABLE `user_otp` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(50) NOT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `otp_type` enum('sms','email') DEFAULT NULL,
  `otp_verified` tinyint(1) NOT NULL DEFAULT 0,
  `otp_attempts` int(11) NOT NULL DEFAULT 0,
  `otp_last_request` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`user_id`),
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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulletin_categories`
--
ALTER TABLE `bulletin_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulletin_posts`
--
ALTER TABLE `bulletin_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulletin_tags`
--
ALTER TABLE `bulletin_tags`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`document_id`);

--
-- Indexes for table `document_tag`
--
ALTER TABLE `document_tag`
  ADD PRIMARY KEY (`document_id`);

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
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_logo`
--
ALTER TABLE `system_logo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_ext_info`
--
ALTER TABLE `user_ext_info`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_otp`
--
ALTER TABLE `user_otp`
  ADD PRIMARY KEY (`id`),
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
-- AUTO_INCREMENT for table `bulletin_categories`
--
ALTER TABLE `bulletin_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulletin_posts`
--
ALTER TABLE `bulletin_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulletin_tags`
--
ALTER TABLE `bulletin_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `document_category`
--
ALTER TABLE `document_category`
  MODIFY `document_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_tag`
--
ALTER TABLE `document_tag`
  MODIFY `document_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_attendance`
--
ALTER TABLE `event_attendance`
  MODIFY `event_attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_logo`
--
ALTER TABLE `system_logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_otp`
--
ALTER TABLE `user_otp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_otp`
--
ALTER TABLE `user_otp`
  ADD CONSTRAINT `user_otp_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
