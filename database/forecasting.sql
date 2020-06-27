-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2020 at 05:35 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `forecasting2`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_rekap`
--

CREATE TABLE IF NOT EXISTS `detail_rekap` (
`id_rek` int(11) NOT NULL,
  `id_daterek` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=231 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_rekap`
--

INSERT INTO `detail_rekap` (`id_rek`, `id_daterek`, `id_obat`, `jumlah`, `total`) VALUES
(1, 1, 1, 3, 9000),
(2, 2, 1, 6, 18000),
(3, 3, 1, 5, 15000),
(4, 4, 1, 3, 9000),
(5, 5, 1, 4, 12000),
(6, 6, 1, 3, 9000),
(7, 7, 1, 5, 15000),
(8, 8, 1, 4, 12000),
(9, 9, 1, 6, 18000),
(10, 10, 1, 4, 12000),
(11, 11, 1, 5, 15000),
(12, 12, 1, 4, 12000),
(13, 13, 1, 3, 9000),
(14, 14, 1, 3, 9000),
(15, 15, 1, 6, 18000),
(16, 16, 1, 4, 12000),
(17, 17, 1, 5, 15000),
(18, 18, 1, 3, 9000),
(19, 19, 1, 6, 18000),
(20, 20, 1, 3, 9000),
(21, 21, 1, 4, 12000),
(22, 22, 1, 6, 18000),
(23, 23, 1, 4, 12000),
(24, 24, 1, 3, 9000),
(25, 25, 1, 2, 6000),
(26, 26, 1, 4, 12000),
(27, 27, 1, 5, 15000),
(28, 28, 1, 2, 6000),
(29, 29, 1, 3, 9000),
(30, 30, 1, 3, 9000),
(31, 31, 1, 4, 12000),
(32, 1, 2, 15, 60000),
(33, 2, 2, 20, 80000),
(34, 3, 2, 34, 136000),
(35, 4, 2, 24, 96000),
(36, 5, 2, 20, 80000),
(37, 6, 2, 24, 96000),
(38, 7, 2, 16, 64000),
(39, 8, 2, 17, 68000),
(40, 9, 2, 23, 92000),
(41, 10, 2, 24, 96000),
(42, 11, 2, 14, 56000),
(43, 12, 2, 26, 104000),
(44, 13, 2, 19, 76000),
(45, 14, 2, 25, 100000),
(46, 15, 2, 22, 88000),
(47, 16, 2, 28, 112000),
(48, 17, 2, 22, 88000),
(49, 18, 2, 22, 88000),
(50, 19, 2, 17, 68000),
(51, 20, 2, 26, 104000),
(52, 21, 2, 19, 76000),
(53, 22, 2, 19, 76000),
(54, 23, 2, 25, 100000),
(55, 24, 2, 31, 124000),
(56, 25, 2, 32, 128000),
(57, 26, 2, 17, 68000),
(58, 27, 2, 30, 120000),
(59, 28, 2, 28, 112000),
(60, 29, 2, 18, 72000),
(61, 30, 2, 19, 76000),
(62, 31, 2, 16, 64000),
(63, 1, 3, 21, 52500),
(64, 2, 3, 12, 30000),
(65, 3, 3, 18, 45000),
(66, 4, 3, 11, 27500),
(67, 5, 3, 25, 62500),
(68, 6, 3, 14, 35000),
(69, 7, 3, 31, 77500),
(70, 8, 3, 26, 65000),
(71, 9, 3, 23, 57500),
(72, 10, 3, 20, 50000),
(73, 11, 3, 18, 45000),
(74, 12, 3, 23, 57500),
(75, 13, 3, 23, 57500),
(76, 14, 3, 11, 27500),
(77, 15, 3, 14, 35000),
(78, 16, 3, 9, 22500),
(79, 17, 3, 32, 80000),
(80, 18, 3, 21, 52500),
(81, 19, 3, 33, 82500),
(82, 20, 3, 21, 52500),
(83, 21, 3, 25, 62500),
(84, 22, 3, 19, 47500),
(85, 23, 3, 17, 42500),
(86, 24, 3, 28, 70000),
(87, 25, 3, 25, 62500),
(88, 26, 3, 10, 25000),
(89, 27, 3, 12, 30000),
(90, 28, 3, 14, 35000),
(91, 29, 3, 9, 22500),
(92, 30, 3, 34, 85000),
(93, 31, 3, 22, 55000),
(94, 1, 4, 13, 45500),
(95, 2, 4, 17, 59500),
(96, 3, 4, 10, 35000),
(97, 4, 4, 14, 49000),
(98, 5, 4, 16, 56000),
(99, 6, 4, 14, 49000),
(100, 7, 4, 15, 52500),
(101, 8, 4, 14, 49000),
(102, 9, 4, 25, 87500),
(103, 10, 4, 14, 49000),
(104, 11, 4, 16, 56000),
(105, 12, 4, 16, 56000),
(106, 13, 4, 17, 59500),
(107, 14, 4, 14, 49000),
(108, 15, 4, 22, 77000),
(109, 16, 4, 26, 91000),
(110, 17, 4, 18, 63000),
(111, 18, 4, 20, 70000),
(112, 19, 4, 23, 80500),
(113, 20, 4, 28, 98000),
(114, 21, 4, 18, 63000),
(115, 22, 4, 13, 45500),
(116, 23, 4, 27, 94500),
(117, 24, 4, 14, 49000),
(118, 25, 4, 15, 52500),
(119, 26, 4, 14, 49000),
(120, 27, 4, 9, 31500),
(121, 28, 4, 8, 28000),
(122, 29, 4, 15, 52500),
(123, 30, 4, 12, 42000),
(124, 31, 4, 20, 70000),
(125, 1, 5, 32, 144000),
(126, 2, 5, 29, 130500),
(127, 3, 5, 43, 193500),
(128, 4, 5, 39, 175500),
(129, 5, 5, 51, 229500),
(130, 6, 5, 39, 175500),
(131, 7, 5, 22, 99000),
(132, 8, 5, 35, 157500),
(133, 9, 5, 34, 153000),
(134, 10, 5, 24, 108000),
(135, 11, 5, 21, 94500),
(136, 12, 5, 36, 162000),
(137, 13, 5, 22, 99000),
(138, 14, 5, 29, 130500),
(139, 15, 5, 39, 175500),
(140, 16, 5, 37, 166500),
(141, 17, 5, 47, 211500),
(142, 18, 5, 22, 99000),
(143, 19, 5, 41, 184500),
(144, 20, 5, 38, 171000),
(145, 21, 5, 31, 139500),
(146, 22, 5, 27, 121500),
(147, 23, 5, 47, 211500),
(148, 24, 5, 46, 207000),
(149, 25, 5, 24, 108000),
(150, 26, 5, 46, 207000),
(151, 27, 5, 40, 180000),
(152, 28, 5, 27, 121500),
(153, 29, 5, 28, 126000),
(154, 30, 5, 42, 189000),
(155, 31, 5, 46, 207000),
(156, 1, 6, 25, 275000),
(157, 2, 6, 23, 253000),
(158, 3, 6, 24, 264000),
(159, 4, 6, 20, 220000),
(160, 5, 6, 24, 264000),
(161, 6, 6, 20, 220000),
(162, 7, 6, 14, 154000),
(163, 8, 6, 18, 198000),
(164, 9, 6, 23, 253000),
(165, 10, 6, 24, 264000),
(166, 11, 6, 24, 264000),
(167, 12, 6, 18, 198000),
(168, 13, 6, 14, 154000),
(169, 14, 6, 11, 121000),
(170, 15, 6, 14, 154000),
(171, 16, 6, 31, 341000),
(172, 17, 6, 35, 385000),
(173, 18, 6, 20, 220000),
(174, 19, 6, 16, 176000),
(175, 20, 6, 17, 187000),
(176, 21, 6, 16, 176000),
(177, 22, 6, 26, 286000),
(178, 23, 6, 28, 308000),
(179, 24, 6, 21, 231000),
(180, 25, 6, 16, 176000),
(181, 26, 6, 18, 198000),
(182, 27, 6, 33, 363000),
(183, 28, 6, 16, 176000),
(184, 29, 6, 14, 154000),
(185, 30, 6, 24, 264000),
(186, 31, 6, 37, 407000),
(187, 1, 7, 78, 351000),
(188, 2, 7, 82, 369000),
(189, 3, 7, 120, 540000),
(190, 4, 7, 136, 612000),
(191, 5, 7, 91, 409500),
(192, 6, 7, 134, 603000),
(193, 7, 7, 133, 598500),
(194, 8, 7, 137, 616500),
(195, 9, 7, 113, 508500),
(196, 10, 7, 97, 436500),
(197, 11, 7, 133, 598500),
(198, 12, 7, 154, 693000),
(199, 13, 7, 111, 499500),
(200, 14, 7, 114, 513000),
(201, 15, 7, 82, 369000),
(202, 16, 7, 162, 729000),
(203, 17, 7, 101, 454500),
(204, 18, 7, 91, 409500),
(205, 19, 7, 131, 589500),
(206, 20, 7, 115, 517500),
(207, 21, 7, 141, 634500),
(208, 22, 7, 147, 661500),
(209, 23, 7, 121, 544500),
(210, 24, 7, 151, 679500),
(211, 25, 7, 75, 337500),
(212, 26, 7, 83, 373500),
(213, 27, 7, 105, 472500),
(214, 28, 7, 68, 306000),
(215, 29, 7, 107, 481500),
(216, 30, 7, 98, 441000),
(217, 31, 7, 100, 450000),
(218, 1, 8, 20, 100000),
(219, 2, 8, 10, 50000),
(220, 6, 8, 20, 100000),
(221, 9, 8, 30, 150000),
(222, 11, 8, 20, 100000),
(223, 15, 8, 20, 100000),
(224, 18, 8, 10, 50000),
(225, 19, 8, 10, 50000),
(226, 22, 8, 20, 100000),
(227, 26, 8, 20, 100000),
(228, 28, 8, 20, 100000),
(229, 30, 8, 10, 50000),
(230, 31, 8, 30, 150000);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_obat`
--

CREATE TABLE IF NOT EXISTS `kategori_obat` (
`id_kategori` int(11) NOT NULL,
  `kode_kategori` char(5) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori_obat`
--

INSERT INTO `kategori_obat` (`id_kategori`, `kode_kategori`, `nama_kategori`) VALUES
(1, 'alg', 'alergi'),
(2, 'anl', 'anti la'),
(3, 'an', 'anti nyeri'),
(4, 'dm', 'demam'),
(5, 'jt', 'jantung'),
(6, 'kf', 'kab'),
(7, 'p3k', 'pertolongan pertama');

-- --------------------------------------------------------

--
-- Table structure for table `manufaktur_obat`
--

CREATE TABLE IF NOT EXISTS `manufaktur_obat` (
`id_manufaktur` int(11) NOT NULL,
  `kode_manufaktur` char(10) NOT NULL,
  `nama_manufaktur` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manufaktur_obat`
--

INSERT INTO `manufaktur_obat` (`id_manufaktur`, `kode_manufaktur`, `nama_manufaktur`) VALUES
(1, 'cmb', 'combiphar'),
(2, 'gm', 'generik'),
(3, 'kbf', 'kalbe farma'),
(4, 'knx', 'konimex'),
(5, 'smt', 'samantor');

-- --------------------------------------------------------

--
-- Table structure for table `stock_obat`
--

CREATE TABLE IF NOT EXISTS `stock_obat` (
`id_stock` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_obat`
--

INSERT INTO `stock_obat` (`id_stock`, `stock`, `created_at`, `update_at`) VALUES
(1, 30, '2020-06-26 00:00:00', '2020-06-26 00:00:00'),
(2, 40, '2020-06-26 00:00:00', '2020-06-26 00:00:00'),
(3, 100, '2020-06-26 00:00:00', '2020-06-26 00:00:00'),
(4, 100, '2020-06-26 00:00:00', '2020-06-26 00:00:00'),
(5, 40, '2020-06-26 00:00:00', '2020-06-26 00:00:00'),
(6, 20, '2020-06-26 00:00:00', '2020-06-26 00:00:00'),
(7, 150, '2020-06-26 00:00:00', '2020-06-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `table_obat`
--

CREATE TABLE IF NOT EXISTS `table_obat` (
`id_obat` int(11) NOT NULL,
  `nama_obat` varchar(50) NOT NULL,
  `bentuk_obat` varchar(25) NOT NULL,
  `segmentasi_obat` varchar(25) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_manufaktur` int(11) NOT NULL,
  `satuan` int(11) NOT NULL,
  `harga_obat` int(11) NOT NULL,
  `id_stock` int(11) NOT NULL,
  `kode_obat` char(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `table_obat`
--

INSERT INTO `table_obat` (`id_obat`, `nama_obat`, `bentuk_obat`, `segmentasi_obat`, `id_kategori`, `id_manufaktur`, `satuan`, `harga_obat`, `id_stock`, `kode_obat`) VALUES
(1, 'betadine solution 5 ml', '1 botol ', 'ob', 1, 1, 1, 3000, 1, 'btd1'),
(2, 'cetrizine 200 mg', '1 strip @ 4 tablet', 'ob', 2, 1, 4, 4000, 2, 'crz1'),
(3, 'simvastatin 200 mg', '1 strip @10 tablet', 'ok', 3, 2, 10, 3500, 3, 'simva1'),
(4, 'amlodipin 500 mg', '1 strip @10 tablet', 'ok', 3, 2, 10, 4000, 4, 'amlo1'),
(5, 'intunal 200 mg', '1 strip @ 4 tablet', 'obt', 4, 3, 4, 4500, 5, 'intu1'),
(6, 'obh combi plus 30 ml', '1 botol', 'ob', 5, 4, 1, 11000, 6, 'obh1'),
(7, 'catflam 50 mg', '1 strip @10 tablet', 'obt', 6, 4, 10, 5000, 7, 'catf10'),
(8, 'catflam 50 mg', '1 tablet', 'obt', 6, 4, 1, 4500, 7, 'catf1');

-- --------------------------------------------------------

--
-- Table structure for table `table_rekap`
--

CREATE TABLE IF NOT EXISTS `table_rekap` (
`id_daterek` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nota` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `table_rekap`
--

INSERT INTO `table_rekap` (`id_daterek`, `tanggal`, `nota`) VALUES
(1, '2019-11-01', NULL),
(2, '2019-11-07', NULL),
(3, '2019-11-13', NULL),
(4, '2019-11-19', NULL),
(5, '2019-11-25', NULL),
(6, '2019-12-01', NULL),
(7, '2019-12-07', NULL),
(8, '2019-12-13', NULL),
(9, '2019-12-19', NULL),
(10, '2019-12-25', NULL),
(11, '2019-12-31', NULL),
(12, '2020-01-06', NULL),
(13, '2020-01-12', NULL),
(14, '2020-01-18', NULL),
(15, '2020-01-24', NULL),
(16, '2020-01-30', NULL),
(17, '2020-02-05', NULL),
(18, '2020-02-11', NULL),
(19, '2020-02-17', NULL),
(20, '2020-02-23', NULL),
(21, '2020-02-29', NULL),
(22, '2020-03-06', NULL),
(23, '2020-03-12', NULL),
(24, '2020-03-18', NULL),
(25, '2020-03-24', NULL),
(26, '2020-03-30', NULL),
(27, '2020-04-05', NULL),
(28, '2020-04-11', NULL),
(29, '2020-04-17', NULL),
(30, '2020-04-23', NULL),
(31, '2020-04-29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id_user` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `level` varchar(5) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(254) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `level`, `username`, `password`) VALUES
(1, 'admin', 'admin', 'admin1', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_rekap`
--
ALTER TABLE `detail_rekap`
 ADD PRIMARY KEY (`id_rek`);

--
-- Indexes for table `kategori_obat`
--
ALTER TABLE `kategori_obat`
 ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `manufaktur_obat`
--
ALTER TABLE `manufaktur_obat`
 ADD PRIMARY KEY (`id_manufaktur`);

--
-- Indexes for table `stock_obat`
--
ALTER TABLE `stock_obat`
 ADD PRIMARY KEY (`id_stock`);

--
-- Indexes for table `table_obat`
--
ALTER TABLE `table_obat`
 ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `table_rekap`
--
ALTER TABLE `table_rekap`
 ADD PRIMARY KEY (`id_daterek`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_rekap`
--
ALTER TABLE `detail_rekap`
MODIFY `id_rek` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=231;
--
-- AUTO_INCREMENT for table `kategori_obat`
--
ALTER TABLE `kategori_obat`
MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `manufaktur_obat`
--
ALTER TABLE `manufaktur_obat`
MODIFY `id_manufaktur` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `stock_obat`
--
ALTER TABLE `stock_obat`
MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `table_obat`
--
ALTER TABLE `table_obat`
MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `table_rekap`
--
ALTER TABLE `table_rekap`
MODIFY `id_daterek` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
