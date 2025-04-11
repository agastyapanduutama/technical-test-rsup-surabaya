-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 11, 2025 at 05:47 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rsup_surabaya`
--

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `expired_at` datetime NOT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `token`
--

INSERT INTO `token` (`id`, `id_user`, `expired_at`, `token`, `created_at`) VALUES
(1, 1, '2025-04-11 05:14:25', '5cba6afca84475110b0aa96f5fd5883b22173b44e69d17353c33e8f4aa99a144-Mw==', '2025-04-11 04:14:25'),
(3, 1, '2025-04-11 06:41:20', 'a212e389cef2c0f2e730cec9096792a06c26ee59153941f788f666a984b9ca19-Mw==', '2025-04-11 05:41:20');

-- --------------------------------------------------------

--
-- Table structure for table `t_insiden`
--

CREATE TABLE `t_insiden` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `nama_insiden` text NOT NULL,
  `lokasi` text NOT NULL,
  `keterangan` text NOT NULL,
  `waktu_insiden` datetime NOT NULL,
  `status_insiden` int NOT NULL COMMENT '0 = belum di proses\r\n1 = ditindak\r\n2 = selesai\r\n',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_insiden`
--

INSERT INTO `t_insiden` (`id`, `id_user`, `nama_insiden`, `lokasi`, `keterangan`, `waktu_insiden`, `status_insiden`, `created_at`, `updated_at`) VALUES
(1, 1, 'Terjepit Tang Potong', 'Perpustakaan ', 'Terjepit tang potong karena hal tidak terduka', '2025-04-17 21:53:09', 0, '2025-04-10 21:53:39', '2025-04-10 21:53:39'),
(2, 1, 'Terjepit Tang Meja', 'Aula ', 'Terjadi ketika pemindahan meja dari ruangan a ke ruangan b, tangan terluka ringan namun sudah di atasi oleh tim medis', '2025-04-17 21:53:09', 2, '2025-04-10 21:54:27', '2025-04-11 12:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `t_insiden_berkas`
--

CREATE TABLE `t_insiden_berkas` (
  `id` int NOT NULL,
  `id_insiden` int NOT NULL,
  `nama_berkas` varchar(100) NOT NULL,
  `berkas` varchar(70) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `status` int NOT NULL COMMENT '0 = checked\r\n1 = valid\r\n2 = false',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_insiden_berkas`
--

INSERT INTO `t_insiden_berkas` (`id`, `id_insiden`, `nama_berkas`, `berkas`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(4, 1, 'Technical Test Full Stack Developer RSUP SURABAYA.pdf', 'aa3ccd19ce6dceb40f3f70dbd36a61c3.pdf', NULL, 0, '2025-04-11 11:23:34', '2025-04-11 12:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `t_log_login`
--

CREATE TABLE `t_log_login` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `request_code` varchar(100) NOT NULL,
  `info` text NOT NULL,
  `token` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uniq_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_menu`
--

CREATE TABLE `t_menu` (
  `id` int NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `keterangan` text NOT NULL,
  `link` text NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_menu`
--

INSERT INTO `t_menu` (`id`, `nama_menu`, `keterangan`, `link`, `status`) VALUES
(2, 'Pengguna', 'Testing Menu', 'user', 1),
(3, 'Menu', '', 'menu', 1),
(5, 'Pelaporan Insiden', '', 'insiden', 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_role`
--

CREATE TABLE `t_role` (
  `id` int NOT NULL,
  `nama_role` varchar(100) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hak_akses` int NOT NULL COMMENT '1 = pembaca\r\n2 = editor\r\n3 = akses penuh'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_role`
--

INSERT INTO `t_role` (`id`, `nama_role`, `keterangan`, `hak_akses`) VALUES
(1, 'Admin', 'Role Administrator', 3),
(2, 'Editor', 'Mengelola Insiden dan Mengambil Tindakan Lanjut 1', 2),
(3, 'Pengguna', 'Role Pengguna, mengeola insiden', 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_role_akses`
--

CREATE TABLE `t_role_akses` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_role` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_role_akses`
--

INSERT INTO `t_role_akses` (`id`, `id_user`, `id_role`) VALUES
(2, 1, 1),
(3, 9, 2),
(4, 10, 3);

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE `t_user` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`id`, `username`, `password`, `nama_user`, `keterangan`, `status`) VALUES
(1, 'admin', '$2y$10$dYTjI1xRIbH8z38IjHUhkeyN00l1tTXKdgm90qCEyOj665iXMtlRi', 'Administrator', '', '1'),
(9, 'editor', '$2y$10$b.8lzv0DljVAZg3lMMjaou1SlM/M/pJYrDHRTkhL2Wm6Boq3JoZ8m', 'editor', 'editor', '1'),
(10, 'pengguna', '$2y$10$ZnlNmwQCgwgUtdFEnlYCfezwofT5hG8gO6k5I3/fTHitOy8uGldja', 'pengguna', 'pengguna', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_insiden`
--
ALTER TABLE `t_insiden`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_insiden_berkas`
--
ALTER TABLE `t_insiden_berkas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_log_login`
--
ALTER TABLE `t_log_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_menu`
--
ALTER TABLE `t_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_role`
--
ALTER TABLE `t_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_role_akses`
--
ALTER TABLE `t_role_akses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_insiden`
--
ALTER TABLE `t_insiden`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `t_insiden_berkas`
--
ALTER TABLE `t_insiden_berkas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_log_login`
--
ALTER TABLE `t_log_login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_menu`
--
ALTER TABLE `t_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `t_role`
--
ALTER TABLE `t_role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `t_role_akses`
--
ALTER TABLE `t_role_akses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
