-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 12, 2025 at 02:39 AM
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
-- Database: `u760074635_eiheec`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`user_id`, `barangay`, `municipality`, `province`, `region`, `zone_purok`, `zip_code`, `created_at`, `updated_at`) VALUES
(1, 16, 1, 1, 1, 5, NULL, '2025-10-11 13:56:52', '2025-10-11 13:56:52'),
(2, 16, 1, 1, 1, 1, NULL, '2025-10-11 15:54:29', '2025-10-10 16:25:27');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangay`
--

CREATE TABLE `barangay` (
  `barangay_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `google_calendar_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `barangay`
--

INSERT INTO `barangay` (`barangay_id`, `name`, `google_calendar_id`) VALUES
(0, 'City-wide', 'knect.system@gmail.com'),
(1, 'Antipolo', 'd0ac19eeb63741361dfcad4fa5607b6b91d3fe9dcbe9b4844dafd97376d7f4c8@group.calendar.google.com'),
(2, 'Cristo Rey', '107a1318bd2e78831a4286cbd280763d635e331ff135b178a7d7301136237f28@group.calendar.google.com'),
(3, 'Del Rosario (Banao)', '990a42af5c09d1e4f1442d1fa2e8b0bcb2d9198967314b20b820ad081e91c0bb@group.calendar.google.com'),
(4, 'Francia', 'ed74bbce7e85a5544a1861b81b3050f86f8883cdfd7e365fb50d53f19540e01f@group.calendar.google.com'),
(5, 'La Anunciacion', 'f47cfb14a03263384bd8084e6cf7f892d49a1d55ae9c4d8f765677d02fa1f4f2@group.calendar.google.com'),
(6, 'La Medalla', '093b2726294d92d991c3e048986e1ec2cb5a63062d4c8ac25b55087484c5e364@group.calendar.google.com'),
(7, 'La Purisima', '401631b10ac608956fd9271fbd86af5f3a46132c6ed7c82f8b247cffd89d6793@group.calendar.google.com'),
(8, 'La Trinidad', '2e35174dc30c5b909d46ee6466ffc99003b14a5f0474b7930bd590851ae159b7@group.calendar.google.com'),
(9, 'Niño Jesus', '42dd056b2417b98b6e40208574d5de38b22582c9f76ea69cef06fb479ff943c1@group.calendar.google.com'),
(10, 'Perpetual Help', 'dce86939def857e6e4337b23170e7ae0af96844cd74eb8e1ff1cb180a51bc567@group.calendar.google.com'),
(11, 'Sagrada', 'd7bcc057c133fec70bc068583c08bb25e870fafd5687d388c1ecbb3313223a1d@group.calendar.google.com'),
(12, 'Salvacion', '6bb517596e760333f23e6a1ede144e41d2d57925e985660f65490b5f65380a2c@group.calendar.google.com'),
(13, 'San Agustin', '91f57b966c4ab69f32b4490c2ba0fcf4bc4c4c3cd2735b95943999606428e821@group.calendar.google.com'),
(14, 'San Andres', 'ba2a8ba0cd0ed62694bf6737e50a30daff159221431e08ef6d244f2534def868@group.calendar.google.com'),
(15, 'San Antonio', 'd6bdb0d8f3e8bb04e70984d6f9dedb251298e229430fbad3f625a7a5e97fcf7d@group.calendar.google.com'),
(16, 'San Francisco', '69bc36558ec147914f9417ac6f1de912e16fd4b7ca6a07cf0fb28acb63e3f43e@group.calendar.google.com'),
(17, 'San Isidro', '73a14d96ac30d3acdda0203157b3bad611583084d477003a0aa2dc8386e3d191@group.calendar.google.com'),
(18, 'San Jose', 'c5c6adec2fc660c4d62061c4055a2137ca95558c8593eb2ce94fb7eff19fc8d5@group.calendar.google.com'),
(19, 'San Juan', '2c3e0e989cd7b891eb16beac22a1b519a48a48a21bf4f49895b337ed40862eca@group.calendar.google.com'),
(20, 'San Miguel', '091c75ac2fe37e44d6315ef1c18498f0fe6fd509aaa4e6b75af5b16401a112de@group.calendar.google.com'),
(21, 'San Nicolas', '81dd3ffb265fce0805c05847674065b9fe4e3ba57b2e110e16a32a5ceef02e64@group.calendar.google.com'),
(22, 'San Pedro', '2351e6955bdcc61c0c1313485439b9d6c85e6ee8680ed3f51fdc1862a797df62@group.calendar.google.com'),
(23, 'San Rafael', '2c583f6d0ec7f012e99a478d5089ac40cc68c59f5daca0ea0926424a1b58d230@group.calendar.google.com'),
(24, 'San Ramon', 'e656b56a3f0019d321155764f35c966545265e4426216ae7d014b4639e167b94@group.calendar.google.com'),
(25, 'San Roque', '3a1d59e9e453fb7f32277b8ca4808026912786f368dafb26f744df35b5d9f82e@group.calendar.google.com'),
(26, 'Santiago', '6587c1cd2a00f23b47d189cc4e8dd0cc281b921f3d842d3c2b2fed63ba49ecfd@group.calendar.google.com'),
(27, 'San Vicente Norte', 'daf6c5672c32f78cf74f319dea63df85a4829b4914abfb4a835eda79fc7c8978@group.calendar.google.com'),
(28, 'San Vicente Sur', '1544f581458f379d0bf4e2d127750b4832990b2c7b016502fcaafa783c58d973@group.calendar.google.com'),
(29, 'Sta. Cruz Norte', 'f6fe0ed2f6977b8cddf922972823bf818307545d78f4f36589ceb258dd96aef3@group.calendar.google.com'),
(30, 'Sta. Cruz Sur', 'de7300f0cc93501520792ab18d5e0d3e878732679c0365254b70bcbaba8e7e84@group.calendar.google.com'),
(31, 'Sta. Elena', 'c2230fe3c6bd956251f58cc7696752271b285fbdec1e71812974497eff336292@group.calendar.google.com'),
(32, 'Sta. Isabel', 'ed1296b6e7c764ad2f30e9a77a340ddff627068100920ae2ebc835d27fcf1ed0@group.calendar.google.com'),
(33, 'Sta. Maria', '530a743b8989c2c25190c8ca673d3a027e5613104912d40c0b45ecff35e2762b@group.calendar.google.com'),
(34, 'Sta. Teresita', 'a06b89684964907ae53463c7f911fef76c62e346a3663828d16d69e99f7588a7@group.calendar.google.com'),
(35, 'Sto. Domingo', 'be22b5b75d54b84aae21b7f1254312edf7b828334aa814fc5a97201c65fa8a8e@group.calendar.google.com'),
(36, 'Sto. Niño', '8704f843d849f449bfd31d7fd15dea23b538231e878e9508cefbcc7c5b237802@group.calendar.google.com');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulletin_post_tags`
--

CREATE TABLE `bulletin_post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulletin_tags`
--

CREATE TABLE `bulletin_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_category`
--

CREATE TABLE `document_category` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_tag`
--

CREATE TABLE `document_tag` (
  `document_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `status` enum('sent','failed','delivered') NOT NULL DEFAULT 'sent',
  `response` text DEFAULT NULL,
  `event_id` int(11) UNSIGNED DEFAULT NULL,
  `sent_by` int(11) UNSIGNED DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `rfid_code`, `user_id`, `last_name`, `first_name`, `middle_name`, `suffix`, `sex`, `gender`, `birthdate`, `email`, `sk_username`, `sk_password`, `ped_username`, `ped_password`, `phone_number`, `username`, `password`, `position`, `ped_position`, `status`, `user_type`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, NULL, '25-0000-01', 'Lontayao', 'Dessa', 'Mare Parma', '', 2, '2', '2001-07-15', 'lontayaodessamare@gmail.com', 'SK_DessaLontayao', '14cb73a4', 'PED_DessaLontayao', '05daa9d7', '+639766609928', 'SKPED_Dessa', '$2y$10$24jj5JtRbNtHwFvhUDa.8ORMMuJvWHC4OR3lCcRcjcdSAifWo97Te', 1, 1, 2, 3, 1, '2025-10-11 14:04:03', '2025-10-11 15:56:10', '2025-10-11 23:40:35'),
(2, NULL, '25-0000-00', 'Luzano', 'Christian Nico', 'Brizuela', '', 1, '', '2004-01-22', 'christiannicoluzano15@gmail.com', 'SK_ChristianNicoLuzano', '$2y$12$ycCzxrHnrKGRbqUhyG/aBuP16BqyTZ5RGgm6downqr1ibwPjyvBpu', 'PED_ChristianNicoLuzano', '$2y$10$fIfYrWI8zNTIVvOX.uJHVeAEpjKtMsnnZ3eBoKNGRXnTkdoUrMrfy', '+639451971854', 'ChristianNico', '$2y$12$UKC0JakOWV436jpNTNOwn.IHK8cp4Flm6PTxWgVeDClIvqSFYs5wK', NULL, NULL, 2, 3, 1, '2025-10-12 10:36:55', '2025-10-12 02:36:55', '2025-10-12 10:36:55');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `user_ext_info`
--

INSERT INTO `user_ext_info` (`user_id`, `civil_status`, `youth_classification`, `age_group`, `work_status`, `educational_background`, `sk_voter`, `sk_election`, `national_voter`, `kk_assembly`, `how_many_times`, `no_why`, `birth_certificate`, `upload_id`, `upload_id-back`, `profile_picture`, `reason`, `agreement`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, 1, 7, 1, 1, 1, 1, 2, NULL, 'birthcert_68e9f1a4ac197.png', 'idpic_68e9f1a4ac203.png', 'idback_68e9f1a4ac256.png', 'profilepic_68e9f1a4ac2a9.jpg', '', NULL, '2025-10-11 13:56:52', '2025-10-11 13:56:52'),
(2, 1, 1, 2, 2, 6, 1, 1, 1, 1, 1, NULL, 'birthcert_68e933773252e.jpg', 'idpic_68e9337732902.png', 'idback_68e9337732ca4.png', 'profilepic_68e9337733011.png', '', NULL, '2025-10-10 16:25:27', '2025-10-10 16:25:27');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
-- AUTO_INCREMENT for table `document_versions`
--
ALTER TABLE `document_versions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_logo`
--
ALTER TABLE `system_logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
