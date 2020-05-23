-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2020 at 04:34 PM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `manufaktur_obat`
--
ALTER TABLE `manufaktur_obat`
 ADD PRIMARY KEY (`id_manufaktur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `manufaktur_obat`
--
ALTER TABLE `manufaktur_obat`
MODIFY `id_manufaktur` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
