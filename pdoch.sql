-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2026 at 05:07 AM
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
-- Database: `pdoch`
--

-- --------------------------------------------------------

--
-- Table structure for table `churchs`
--

CREATE TABLE `churchs` (
  `id` int(11) NOT NULL,
  `id_denomination` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `address` text DEFAULT NULL,
  `id_user_created` int(11) NOT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `churchs`
--

INSERT INTO `churchs` (`id`, `id_denomination`, `name`, `address`, `id_user_created`, `id_user_updated`, `created_date`, `updated_date`) VALUES
(1, 1, 'GKI Citra Harmoni', 'Perumahan Citra Harmoni Blok A', 1, NULL, '2026-06-13 17:54:49', NULL),
(2, 2, 'GBI Citra Harmoni', 'Perumahan Citra Harmoni Blok B', 1, NULL, '2026-06-13 17:54:49', NULL),
(3, 3, 'GMS Surabaya', 'Jl. Tegalsari No. 41, Surabaya', 1, NULL, '2026-06-13 17:54:49', NULL),
(4, 2, 'Bethany Nginden', 'Jalan Nginden Intan Timur I No.29, Nginden Jangkungan, Kecamatan Sukolilo, Kota Surabaya, Jawa Timur', 2, NULL, '2026-06-13 18:56:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `denominations`
--

CREATE TABLE `denominations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `id_user_created` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `id_user_updated` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `denominations`
--

INSERT INTO `denominations` (`id`, `name`, `id_user_created`, `created_date`, `id_user_updated`, `updated_date`) VALUES
(1, 'Gereja Kristen Indonesia (GKI)', 1, '2026-06-13 17:54:49', NULL, NULL),
(2, 'Gereja Bethel Indonesia (GBI)', 1, '2026-06-13 17:54:49', NULL, NULL),
(3, 'Gereja Mawar Sharon (GMS)', 1, '2026-06-13 17:54:49', NULL, NULL),
(4, 'Katolik Roma', 1, '2026-06-13 17:54:49', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `id` int(11) NOT NULL,
  `coordinator_user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `id_user_created` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `id_user_updated` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `coordinator_user_id`, `name`, `description`, `id_user_created`, `created_date`, `id_user_updated`, `updated_date`) VALUES
(1, 11, 'Music', 'Divisi yang mengatur musik, WL, singer, dan pemusik ibadah.', 1, '2026-06-13 17:54:49', 2, '2026-06-13 19:02:16'),
(2, 10, 'Multimedia & Sound', 'Divisi yang mengelola sound system, live streaming, dan proyektor.', 1, '2026-06-13 17:54:49', 2, '2026-06-13 19:01:22'),
(3, 5, 'Publikasi & Dokumentasi', 'Divisi yang mendokumentasikan kegiatan dan mempublikasikannya di sosial media.', 1, '2026-06-13 17:54:49', 2, '2026-06-13 19:02:33'),
(4, 3, 'Conqueror (Internal Dewasa Muda)', 'Pengurus yang mengorganisir persekutuan dewasa muda.', 1, '2026-06-13 17:54:49', 2, '2026-06-13 18:57:52'),
(5, 9, 'Internal Lansia', 'Pengurus pelayanan rohani untuk usia lanjut.', 1, '2026-06-13 17:54:49', 2, '2026-06-13 18:59:56'),
(6, 12, 'Worship Leader and Singer', NULL, 2, '2026-06-13 19:04:09', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `division_member`
--

CREATE TABLE `division_member` (
  `id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `id_user_created` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `id_user_updated` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `division_member`
--

INSERT INTO `division_member` (`id`, `division_id`, `user_id`, `role`, `id_user_created`, `created_date`, `id_user_updated`, `updated_date`) VALUES
(1, 1, 2, 'Pianist, Guitarist', 1, '2026-06-13 17:54:49', 2, '2026-06-13 19:07:08'),
(2, 4, 2, 'System Information Technology', 1, '2026-06-13 17:54:49', 2, '2026-06-13 18:58:55'),
(3, 4, 5, 'Anggota', 2, '2026-06-13 19:06:19', 2, '2026-06-13 19:06:48'),
(4, 4, 6, 'Anggota', 2, '2026-06-13 19:06:37', NULL, NULL),
(5, 4, 7, 'Anggota', 2, '2026-06-13 19:06:39', NULL, NULL),
(6, 4, 8, 'Anggota', 2, '2026-06-13 19:06:42', NULL, NULL),
(7, 1, 3, 'Anggota', 2, '2026-06-13 19:07:14', NULL, NULL),
(8, 2, 2, 'Soundman', 2, '2026-06-13 19:07:31', NULL, NULL),
(9, 3, 2, 'Video Editor and Youtube Manager', 2, '2026-06-13 19:07:59', NULL, NULL),
(10, 4, 3, 'Coordinator', 2, '2026-06-13 19:18:16', NULL, NULL),
(11, 5, 9, 'Coordinator', 2, '2026-06-13 19:19:00', NULL, NULL),
(12, 2, 10, 'Coordinator', 2, '2026-06-13 19:19:12', NULL, NULL),
(13, 1, 11, 'Coordinator', 2, '2026-06-13 19:19:26', NULL, NULL),
(14, 3, 11, 'Coordinator', 2, '2026-06-13 19:19:39', NULL, NULL),
(15, 6, 12, 'Coordinator', 2, '2026-06-13 19:19:49', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `coordinator_user_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `time_start` datetime NOT NULL,
  `time_end` datetime NOT NULL,
  `description` text DEFAULT NULL,
  `id_user_created` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `id_user_updated` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `coordinator_user_id`, `name`, `time_start`, `time_end`, `description`, `id_user_created`, `created_date`, `id_user_updated`, `updated_date`) VALUES
(1, NULL, 'Ibadah Bulanan PDOCH', '2026-06-11 19:00:00', '2026-06-11 21:00:00', 'Ibadah persekutuan doa rutin bulanan PDOCH di hari Kamis ke-2.', 1, '2026-06-13 17:55:13', NULL, NULL),
(2, 2, 'Ibadah Dewasa Muda', '2026-06-03 19:00:00', '2026-06-03 21:00:00', 'Ibadah persekutuan rutin Dewasa Muda PDOCH.', 1, '2026-06-13 17:55:13', 2, '2026-06-13 19:08:58'),
(3, NULL, 'Ibadah Dewasa Muda', '2026-06-17 19:00:00', '2026-06-17 21:00:00', 'Ibadah persekutuan rutin Dewasa Muda PDOCH.', 1, '2026-06-13 17:55:13', NULL, NULL),
(4, NULL, 'Ibadah Dewasa Muda', '2026-06-24 19:00:00', '2026-06-24 21:00:00', 'Ibadah persekutuan rutin Dewasa Muda PDOCH.', 1, '2026-06-13 17:55:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_evaluations`
--

CREATE TABLE `event_evaluations` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `pic_user_id` int(11) DEFAULT NULL,
  `evaluation` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_evaluations`
--

INSERT INTO `event_evaluations` (`id`, `event_id`, `pic_user_id`, `evaluation`, `created_at`) VALUES
(1, 1, 2, 'coba eval dari leon', '2026-06-13 12:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `created_by`, `created_at`) VALUES
(1, 'Jangan lupa nonton F1 hari Sabtu 13 Juni 2026!', 'Gazz gak ziehh', 'news_1781353033_6a2d4a4932654.jpg', 1, '2026-06-13 11:36:48'),
(2, 'Dicari Pemain Trumpet', 'Bagi saudara yang memiliki minat untuk pelayanan di bidang musik (trumpet) segera info ke pak santo di nomor 0812-3456-xxxx.', NULL, 1, '2026-06-13 11:36:48');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `id_user_created` int(11) NOT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `id_user_created`, `id_user_updated`, `created_date`, `updated_date`) VALUES
(1, 'Admin', 1, NULL, '2026-06-13 17:54:49', NULL),
(2, 'Jemaat', 1, NULL, '2026-06-13 17:54:49', NULL),
(3, 'Pelayan', 1, NULL, '2026-06-13 17:54:49', NULL),
(4, 'Aktivis', 1, NULL, '2026-06-13 17:54:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_schedules`
--

CREATE TABLE `service_schedules` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `type` enum('pelayanan','latihan') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_schedules`
--

INSERT INTO `service_schedules` (`id`, `user_id`, `role`, `date`, `type`, `location`, `notes`, `description`, `created_at`) VALUES
(1, 2, 'Pianist', '2026-07-09', 'pelayanan', 'Ruang Doa Lt. 2', 'Harap hadir 1 jam sebelum ibadah', 'Ibadah Bulanan PDOCH', '2026-06-13 11:36:48'),
(2, 2, 'Pianist (Latihan)', '2026-07-05', 'latihan', 'Ruang Musik Lt.2', 'Latihan awal bersama WL', 'Persiapan lagu ibadah bulanan', '2026-06-13 11:36:48'),
(3, 2, 'Pianist (Latihan)', '2026-07-08', 'latihan', 'Ruang Utama', 'General Rehearsal (GR)', 'Latihan akhir bersama tim multimedia', '2026-06-13 11:36:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_church` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `photo` varchar(255) DEFAULT NULL,
  `id_user_created` int(11) NOT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_church`, `name`, `username`, `password`, `birth_date`, `phone_number`, `address`, `image`, `gender`, `status`, `photo`, `id_user_created`, `id_user_updated`, `created_date`, `updated_date`) VALUES
(1, 1, 'Admin PDOCH', 'admin', '$2y$10$.PdEMG/cDymsPssXeiMiL.I2ho0kTRp4RsXcAYTezsC/19wG3vVYe', '2026-06-13', '08987654321', 'Gereja PDOCH Sidoarjo', 'user_1_1781350829.jpg', 'F', 'active', NULL, 1, 1, '2026-06-13 17:54:49', '2026-06-13 18:40:29'),
(2, 4, 'Leon Susanto', 'leonsusanto38', '$2y$10$URtd4lTISI0cnUcDVA24b.f8RZLEr2TSKsuEDqrxUbaoM3UVa/88S', '2001-08-03', '081290145685', 'Citra Harmoni Blok A2 No. 34', 'user_2_1781354567.jpg', 'M', 'active', NULL, 1, 2, '2026-06-13 17:54:49', '2026-06-13 19:42:47'),
(3, NULL, 'Frenzo Monkales', 'frenzo889988', '$2y$10$P0TtKE.5U5rGARlP6qEIneDPf4l0qQBs/A4Xe1GexDm3O90S1q55m', '1996-08-08', NULL, 'Citra Harmoni Blok A No. 16', NULL, 'M', 'active', NULL, 2, NULL, '2026-06-13 18:47:56', NULL),
(4, NULL, 'Priska Novina', 'priska_novina', '$2y$10$vS/dcIqsoHuVb4iWhTRxYeXr7S16BinQXAQ6MvCm2o/cxGCZR4fqq', NULL, NULL, 'Citra Harmoni Blok A No. 16', NULL, 'F', 'active', NULL, 2, NULL, '2026-06-13 18:48:40', NULL),
(5, NULL, 'Dyllen Monkales', 'dmonkales', '$2y$10$K99yCr61MqoWXT5ZlMPPkenduS4juPAkbGsTU4saNQLqCRnzmp4Vm', NULL, NULL, NULL, NULL, 'M', 'active', NULL, 2, NULL, '2026-06-13 18:49:04', NULL),
(6, NULL, 'Daniel Tanto Purnomo', 'danto5', '$2y$10$NRgg2.xD9uM5Tq5ZoiKhaeqRlIazVUd0feuR0zHyeyLFAESEdwF1q', '2002-01-27', NULL, 'Citra Harmoni Blok H', NULL, 'M', 'active', NULL, 2, NULL, '2026-06-13 18:49:55', NULL),
(7, NULL, 'Lay Okidharma', 'cornelay', '$2y$10$EFLNJQ0BBez.EzQtZZGVD.C6w92f1JCmagcP9KOu1OI08z29kq5gK', NULL, NULL, NULL, NULL, 'M', 'active', NULL, 2, NULL, '2026-06-13 18:51:58', NULL),
(8, NULL, 'Stanley Nugraha', 'stanley_nugraha', '$2y$10$GefCSWUCzVxIXCM44NGET.0DAw7zrKp9eYTKnfkbvJgOj.cXF1juS', NULL, NULL, 'Sukodono', NULL, 'M', 'active', NULL, 2, NULL, '2026-06-13 18:52:39', NULL),
(9, NULL, 'Reny Eka Puspita', 'reny_puspita', '$2y$10$VnxUFeH8jfvRzZ9WKcmp5eV48kQLt3fM9eF.VUYNPZmKBwU0aL2XO', NULL, NULL, 'Citra Harmoni Blok A', NULL, 'F', 'active', NULL, 2, NULL, '2026-06-13 18:59:33', NULL),
(10, 4, 'Soegiarto Kurniawan', 'soegik', '$2y$10$/OyU.ZwbdeXtB0QOhyIN7uBHBceKrhoFxLC8hg6x.CBXePFzt5kSK', NULL, NULL, 'Pejaya Anugerah', NULL, 'M', 'active', NULL, 2, NULL, '2026-06-13 19:00:43', NULL),
(11, NULL, 'Andreas Susanto', 'susanto', '$2y$10$P6bgqbs0DNr8x9ep5G5KAegZLCvLPElUD09YivfPkODkQNGlxmupS', NULL, NULL, NULL, NULL, 'M', 'active', NULL, 2, NULL, '2026-06-13 19:01:48', NULL),
(12, NULL, 'Carolina Louisa', 'carolina_louisa', '$2y$10$GyZmDIuTf4kmXHx4gAgyR.kqhTjlnX0PH2gjh5gK5.aoEM49SW1SK', NULL, NULL, 'Citra Harmoni Blok A', NULL, 'F', 'active', NULL, 2, NULL, '2026-06-13 19:03:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_user_created` int(11) NOT NULL,
  `id_user_updated` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `id_user`, `id_role`, `id_user_created`, `id_user_updated`, `created_date`, `updated_date`) VALUES
(1, 1, 1, 1, NULL, '2026-06-13 17:54:49', NULL),
(3, 2, 1, 1, NULL, '2026-06-13 18:02:46', NULL),
(4, 2, 4, 1, NULL, '2026-06-13 18:02:46', NULL),
(5, 2, 2, 1, NULL, '2026-06-13 18:02:46', NULL),
(6, 2, 3, 1, NULL, '2026-06-13 18:02:46', NULL),
(8, 3, 1, 2, NULL, '2026-06-13 18:48:05', NULL),
(9, 3, 4, 2, NULL, '2026-06-13 18:48:05', NULL),
(10, 3, 2, 2, NULL, '2026-06-13 18:48:05', NULL),
(11, 3, 3, 2, NULL, '2026-06-13 18:48:05', NULL),
(15, 6, 4, 2, NULL, '2026-06-13 18:50:04', NULL),
(16, 6, 2, 2, NULL, '2026-06-13 18:50:04', NULL),
(17, 6, 3, 2, NULL, '2026-06-13 18:50:04', NULL),
(18, 4, 1, 2, NULL, '2026-06-13 18:50:19', NULL),
(19, 4, 4, 2, NULL, '2026-06-13 18:50:19', NULL),
(20, 4, 2, 2, NULL, '2026-06-13 18:50:19', NULL),
(21, 4, 3, 2, NULL, '2026-06-13 18:50:19', NULL),
(22, 7, 2, 2, NULL, '2026-06-13 18:51:58', NULL),
(23, 5, 1, 2, NULL, '2026-06-13 18:52:08', NULL),
(24, 5, 4, 2, NULL, '2026-06-13 18:52:08', NULL),
(25, 5, 2, 2, NULL, '2026-06-13 18:52:08', NULL),
(26, 5, 3, 2, NULL, '2026-06-13 18:52:08', NULL),
(28, 8, 4, 2, NULL, '2026-06-13 18:55:44', NULL),
(29, 8, 2, 2, NULL, '2026-06-13 18:55:44', NULL),
(30, 8, 3, 2, NULL, '2026-06-13 18:55:44', NULL),
(31, 9, 2, 2, NULL, '2026-06-13 18:59:33', NULL),
(32, 10, 2, 2, NULL, '2026-06-13 19:00:43', NULL),
(33, 11, 2, 2, NULL, '2026-06-13 19:01:48', NULL),
(34, 12, 2, 2, NULL, '2026-06-13 19:03:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `churchs`
--
ALTER TABLE `churchs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_church_denomination` (`id_denomination`),
  ADD KEY `fk_church_created` (`id_user_created`),
  ADD KEY `fk_church_updated` (`id_user_updated`);

--
-- Indexes for table `denominations`
--
ALTER TABLE `denominations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_denom_created` (`id_user_created`),
  ADD KEY `fk_denom_updated` (`id_user_updated`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_div_coordinator` (`coordinator_user_id`),
  ADD KEY `fk_div_created` (`id_user_created`),
  ADD KEY `fk_div_updated` (`id_user_updated`);

--
-- Indexes for table `division_member`
--
ALTER TABLE `division_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dm_division` (`division_id`),
  ADD KEY `fk_dm_user` (`user_id`),
  ADD KEY `fk_dm_created` (`id_user_created`),
  ADD KEY `fk_dm_updated` (`id_user_updated`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_evt_coordinator` (`coordinator_user_id`),
  ADD KEY `fk_evt_created` (`id_user_created`),
  ADD KEY `fk_evt_updated` (`id_user_updated`);

--
-- Indexes for table `event_evaluations`
--
ALTER TABLE `event_evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `pic_user_id` (`pic_user_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_role_created` (`id_user_created`),
  ADD KEY `fk_role_updated` (`id_user_updated`);

--
-- Indexes for table `service_schedules`
--
ALTER TABLE `service_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_church` (`id_church`),
  ADD KEY `fk_user_created` (`id_user_created`),
  ADD KEY `fk_user_updated` (`id_user_updated`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ur_user` (`id_user`),
  ADD KEY `fk_ur_role` (`id_role`),
  ADD KEY `fk_ur_created` (`id_user_created`),
  ADD KEY `fk_ur_updated` (`id_user_updated`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `churchs`
--
ALTER TABLE `churchs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `denominations`
--
ALTER TABLE `denominations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `division_member`
--
ALTER TABLE `division_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_evaluations`
--
ALTER TABLE `event_evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_schedules`
--
ALTER TABLE `service_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `churchs`
--
ALTER TABLE `churchs`
  ADD CONSTRAINT `fk_church_created` FOREIGN KEY (`id_user_created`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_church_denomination` FOREIGN KEY (`id_denomination`) REFERENCES `denominations` (`id`),
  ADD CONSTRAINT `fk_church_updated` FOREIGN KEY (`id_user_updated`) REFERENCES `users` (`id`);

--
-- Constraints for table `denominations`
--
ALTER TABLE `denominations`
  ADD CONSTRAINT `fk_denom_created` FOREIGN KEY (`id_user_created`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_denom_updated` FOREIGN KEY (`id_user_updated`) REFERENCES `users` (`id`);

--
-- Constraints for table `divisions`
--
ALTER TABLE `divisions`
  ADD CONSTRAINT `fk_div_coordinator` FOREIGN KEY (`coordinator_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_div_created` FOREIGN KEY (`id_user_created`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_div_updated` FOREIGN KEY (`id_user_updated`) REFERENCES `users` (`id`);

--
-- Constraints for table `division_member`
--
ALTER TABLE `division_member`
  ADD CONSTRAINT `fk_dm_created` FOREIGN KEY (`id_user_created`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_dm_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`),
  ADD CONSTRAINT `fk_dm_updated` FOREIGN KEY (`id_user_updated`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_dm_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_evt_coordinator` FOREIGN KEY (`coordinator_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_evt_created` FOREIGN KEY (`id_user_created`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_evt_updated` FOREIGN KEY (`id_user_updated`) REFERENCES `users` (`id`);

--
-- Constraints for table `event_evaluations`
--
ALTER TABLE `event_evaluations`
  ADD CONSTRAINT `event_evaluations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_evaluations_ibfk_2` FOREIGN KEY (`pic_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `fk_role_created` FOREIGN KEY (`id_user_created`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_role_updated` FOREIGN KEY (`id_user_updated`) REFERENCES `users` (`id`);

--
-- Constraints for table `service_schedules`
--
ALTER TABLE `service_schedules`
  ADD CONSTRAINT `service_schedules_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_church` FOREIGN KEY (`id_church`) REFERENCES `churchs` (`id`),
  ADD CONSTRAINT `fk_user_created` FOREIGN KEY (`id_user_created`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user_updated` FOREIGN KEY (`id_user_updated`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `fk_ur_created` FOREIGN KEY (`id_user_created`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_ur_role` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `fk_ur_updated` FOREIGN KEY (`id_user_updated`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_ur_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
