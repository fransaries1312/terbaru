-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2020 at 07:54 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `forecasting`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_rekap`
--

CREATE TABLE IF NOT EXISTS `detail_rekap` (
`id_rek` int(11) NOT NULL,
  `id_daterek` int(11) DEFAULT NULL,
  `id_obat` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total` double(14,2) DEFAULT NULL,
  `diskon` float DEFAULT NULL,
  `subtotal` double(14,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_rekap`
--

INSERT INTO `detail_rekap` (`id_rek`, `id_daterek`, `id_obat`, `jumlah`, `total`, `diskon`, `subtotal`, `created_at`, `updated_at`) VALUES
(38, 64, 1, 5, 15000.00, 20, 12000.00, '2020-06-06 21:09:01', NULL),
(39, 64, 2, 1, 4000.00, 0, 4000.00, '2020-06-06 21:09:26', NULL),
(40, 65, 1, 1, 3000.00, 0, 3000.00, '2020-06-06 22:08:39', '2020-06-06 22:08:39'),
(41, 66, 2, 2, 8000.00, 0, 8000.00, '2020-06-06 22:11:22', '2020-06-06 22:11:22'),
(42, 67, 1, 1, 3000.00, 0, 3000.00, '2020-06-06 22:12:48', '2020-06-06 22:12:48'),
(43, 68, 2, 10, 40000.00, 0, 40000.00, '2020-06-06 22:13:39', '2020-06-06 22:13:39'),
(44, 68, 1, 2, 6000.00, 0, 6000.00, '2020-06-06 22:13:48', '2020-06-06 22:13:48'),
(46, 70, 2, 2, 8000.00, 0, 8000.00, '2020-06-21 17:59:02', '2020-06-21 17:59:02'),
(47, 71, 3, 2, 7000.00, 0, 7000.00, '2020-06-21 18:00:10', '2020-06-21 18:00:10'),
(48, 72, 2, 8, 32000.00, 0, 32000.00, '2020-06-22 13:16:34', '2020-06-22 13:16:34'),
(49, 73, 2, 9, 36000.00, 0, 36000.00, '2020-06-22 13:17:36', '2020-06-22 13:17:36'),
(50, 74, 2, 2, 8000.00, 0, 8000.00, '2020-06-22 22:04:59', '2020-06-22 22:04:59'),
(60, 76, 2, 1, 4000.00, 0, 4000.00, '2020-06-22 22:18:29', '2020-06-22 22:18:29'),
(61, 77, 2, 2, 8000.00, 0, 8000.00, '2020-06-22 22:18:43', '2020-06-22 22:18:43'),
(63, 77, 3, 2, 7000.00, 0, 7000.00, '2020-06-22 22:18:55', '2020-06-22 22:18:55'),
(65, 77, 1, 2, 6000.00, 0, 6000.00, '2020-06-22 22:20:31', '2020-06-22 22:20:31'),
(67, 77, 1, 2, 6000.00, 0, 6000.00, '2020-06-22 22:22:03', '2020-06-22 22:22:03'),
(68, 72, 2, 2, 8000.00, 0, 8000.00, '2020-06-22 22:22:46', '2020-06-22 22:22:46'),
(69, 72, 1, 2, 6000.00, 0, 6000.00, '2020-06-22 22:24:22', '2020-06-22 22:24:22'),
(71, 72, 7, 2, 10000.00, 0, 10000.00, '2020-06-22 22:24:49', '2020-06-22 22:24:49'),
(72, 72, 1, 2, 6000.00, 0, 6000.00, '2020-06-22 22:27:30', '2020-06-22 22:27:30'),
(73, 72, 7, 2, 10000.00, 0, 10000.00, '2020-06-22 22:27:36', '2020-06-22 22:27:36'),
(74, 72, 4, 3, 12000.00, 0, 12000.00, '2020-06-22 22:29:07', '2020-06-22 22:29:07'),
(75, 72, 1, 2, 6000.00, 0, 6000.00, '2020-06-22 22:30:20', '2020-06-22 22:30:20'),
(76, 72, 1, 2, 6000.00, 0, 6000.00, '2020-06-22 22:31:27', '2020-06-22 22:31:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_rekap`
--
ALTER TABLE `detail_rekap`
 ADD PRIMARY KEY (`id_rek`), ADD KEY `fk_foreign_id_daterek` (`id_daterek`), ADD KEY `fk_foreign_id_obat` (`id_obat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_rekap`
--
ALTER TABLE `detail_rekap`
MODIFY `id_rek` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_rekap`
--
ALTER TABLE `detail_rekap`
ADD CONSTRAINT `fk_foreign_id_daterek` FOREIGN KEY (`id_daterek`) REFERENCES `table_rekap` (`id_daterek`),
ADD CONSTRAINT `fk_foreign_id_obat` FOREIGN KEY (`id_obat`) REFERENCES `table_obat` (`id_obat`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
