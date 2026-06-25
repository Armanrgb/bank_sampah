-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql307.infinityfree.com
-- Generation Time: Jan 05, 2026 at 12:19 PM
-- Server version: 11.4.9-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40701701_db_banksampah`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sampah`
--

CREATE TABLE `jenis_sampah` (
  `id` int(11) NOT NULL,
  `nama_sampah` varchar(50) DEFAULT NULL,
  `harga_per_kg` int(11) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_sampah`
--

INSERT INTO `jenis_sampah` (`id`, `nama_sampah`, `harga_per_kg`, `icon`) VALUES
(1, 'Plastik', 41200, 'fa-bottle-water'),
(2, 'Kertas', 2000, 'fa-newspaper'),
(3, 'Kardus', 2500, 'fa-box-open'),
(4, 'Besi', 5000, 'fa-hammer'),
(5, 'Tembaga', 65000, 'fa-coins'),
(6, 'Botol Kaca', 1000, 'fa-wine-bottle');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tipe` enum('setor','tarik') NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `berat` decimal(10,2) DEFAULT 0.00,
  `jumlah` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `user_id`, `tipe`, `keterangan`, `berat`, `jumlah`, `tanggal`) VALUES
(1, 1, 'setor', 'Setor Plastik', '10.00', 30000, '2025-12-16 18:08:22'),
(2, 1, 'tarik', 'Tarik via Gopay', '0.00', 30000, '2025-12-16 18:08:42'),
(3, 1, 'setor', 'Setor Plastik', '13.00', 39000, '2025-12-17 07:37:39'),
(4, 3, 'setor', 'Setor Plastik', '2.50', 7500, '2025-12-17 07:40:31'),
(5, 3, 'setor', 'Setor Botol Kaca', '2.50', 2500, '2025-12-17 07:41:52'),
(6, 3, 'tarik', 'Tarik via Gopay', '0.00', 10000, '2025-12-17 07:42:21'),
(7, 3, 'setor', 'Setor Plastik', '10.00', 30000, '2025-12-18 14:40:02'),
(8, 3, 'setor', 'Setor Kardus', '10.00', 25000, '2025-12-18 14:43:18'),
(9, 1, 'setor', 'Setor Besi', '10.00', 50000, '2025-12-18 14:47:58'),
(10, 1, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-18 15:12:09'),
(11, 3, 'setor', 'Setor Besi', '10.00', 50000, '2025-12-18 15:26:51'),
(12, 3, 'tarik', 'Tarik via OVO', '0.00', 100000, '2025-12-18 15:31:34'),
(13, 3, 'setor', 'Setor Botol Kaca', '16.00', 16000, '2025-12-18 16:13:08'),
(14, 3, 'setor', 'Setor Plastik', '12.00', 48000, '2025-12-20 21:12:47'),
(15, 3, 'setor', 'Setor Plastik', '12.00', 48000, '2025-12-20 21:15:51'),
(16, 3, 'tarik', 'Tarik via Dana', '0.00', 50000, '2025-12-20 21:16:03'),
(17, 3, 'tarik', 'Tarik via Gopay', '0.00', 20000, '2025-12-20 21:17:16'),
(18, 3, 'setor', 'Setor Plastik', '12.00', 48000, '2025-12-20 21:17:50'),
(19, 3, 'tarik', 'Tarik via Cash', '0.00', 50000, '2025-12-20 21:18:00'),
(20, 3, 'tarik', 'Tarik via Dana', '0.00', 20000, '2025-12-20 21:19:59'),
(21, 3, 'tarik', 'Tarik via Dana', '0.00', 20000, '2025-12-20 21:37:30'),
(22, 3, 'setor', 'Setor Plastik', '12.00', 48000, '2025-12-20 21:49:38'),
(23, 3, 'tarik', 'Tarik via DANA', '0.00', 20000, '2025-12-20 21:51:01'),
(24, 3, 'tarik', 'Tarik via Cash', '0.00', 20000, '2025-12-20 21:51:21'),
(25, 3, 'tarik', 'Tarik via DANA', '0.00', 10000, '2025-12-20 21:53:03'),
(26, 3, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-20 21:53:17'),
(27, 3, 'tarik', 'Tarik via DANA', '0.00', 20000, '2025-12-20 21:53:24'),
(28, 3, 'tarik', 'Tarik via DANA', '0.00', 20000, '2025-12-20 21:54:39'),
(29, 3, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-20 21:55:50'),
(30, 3, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-20 21:56:09'),
(31, 3, 'tarik', 'Tarik via DANA', '0.00', 20000, '2025-12-20 21:57:58'),
(32, 3, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-20 21:58:26'),
(33, 3, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-20 21:58:51'),
(34, 3, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-20 22:00:30'),
(35, 3, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-20 22:01:58'),
(36, 3, 'tarik', 'Tarik via DANA', '0.00', 20000, '2025-12-20 22:04:32'),
(37, 3, 'setor', 'Setor Plastik', '10.00', 40000, '2025-12-20 22:06:49'),
(38, 3, 'tarik', 'Tarik via DANA', '0.00', 20000, '2025-12-20 22:06:59'),
(39, 3, 'setor', 'Setor Plastik', '10.00', 412000, '2025-12-23 17:25:23'),
(40, 3, 'setor', 'Setor Plastik', '20.00', 824000, '2025-12-23 17:25:32'),
(41, 1, 'setor', 'Setor Kardus', '20.00', 50000, '2025-12-23 17:27:18'),
(42, 3, 'setor', 'Setor Tembaga', '20.00', 1300000, '2025-12-23 17:27:48'),
(43, 1, 'setor', 'Setor Kardus', '20.00', 50000, '2025-12-23 17:28:58'),
(44, 1, 'setor', 'Setor Plastik', '1.00', 41200, '2025-12-23 17:33:14'),
(45, 3, 'tarik', 'Tarik via DANA', '0.00', 20000, '2025-12-23 17:50:52'),
(46, 3, 'tarik', 'Tarik via GoPay', '0.00', 20000, '2025-12-23 18:14:49'),
(47, 10, 'setor', 'Setor Plastik', '10.00', 412000, '2025-12-24 11:23:08'),
(48, 10, 'tarik', 'Tarik via GoPay', '0.00', 50000, '2025-12-24 11:23:15'),
(49, 11, 'setor', 'Setor Plastik', '10.00', 412000, '2025-12-26 10:20:18'),
(50, 11, 'setor', 'Setor Tembaga', '1.00', 65000, '2025-12-26 10:20:39'),
(51, 11, 'setor', 'Setor Tembaga', '1.00', 65000, '2025-12-26 10:20:40'),
(52, 11, 'tarik', 'Tarik via DANA', '0.00', 200000, '2025-12-26 10:21:21'),
(53, 3, 'tarik', 'Tarik via DANA', '0.00', 50000, '2025-12-29 21:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `saldo` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `no_hp`, `foto`, `password`, `saldo`, `created_at`, `role`, `reset_token`, `reset_expire`) VALUES
(1, 'Arman Ramadhan ', 'kucinggarong0678@gmail.com', '085714212831', 'profile_1_1766864798.png', '$2y$10$1WIa1BGAMB3qZ6xREkwIb.Ex58ZzlVhFIOUYvnQUW7nTgfhi0yGj.', 270200, '2025-12-16 17:32:23', 'admin', NULL, NULL),
(3, 'dafa', 'dafa21@gmail.com', '087767656602', 'profile_3_1767044286.png', '$2y$10$qg3JVpF1cFsF05nVNrHnZO7iOigg4p3xKiJS9AsKZhgE6WLKzRUk6', 2669000, '2025-12-17 06:49:39', 'user', '371dab5dbd0f5df81d5ac46aac285114f2f1c2061e0c9f5c425a92cf0cae26ec', '2025-12-19 21:11:09'),
(10, 'Indy', 'indy21@gmail.com', '085714212831', 'profile_10_1766581654.jpg', '$2y$10$SyyvQG.9MnbE4Y/EuubUeuO3F8jZnt05cT0lo8jColLF1UqIMPI5O', 362000, '2025-12-24 11:20:59', '', NULL, NULL),
(11, 'haykal azizi', 'hakal@gmail.com', '082180755256', NULL, '$2y$10$W.egwVVVOFt8Xc3kr7x.vePyQU69DQMwsg2EzyhSTu.aDWJdtgc46', 342000, '2025-12-26 10:19:14', '', NULL, NULL),
(12, 'haha', 'haha@gmail.com', '8888454804876', NULL, '$2y$10$m5RrdKzen36UGrALF28w3.Dw3..TggrpEbf468pvoPtvB21.sPeZK', 0, '2025-12-27 18:36:05', '', NULL, NULL),
(13, 'asep', 'asep123@gmail.com', '0808080808', NULL, '$2y$10$atX5nMyN4eYPnfZIy4CnleWPqiA0xySn3zOYW3Kq.z8eh7B1y52ie', 0, '2026-01-05 08:20:18', '', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
