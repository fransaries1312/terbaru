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
  `kode_obat` char(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `table_obat`
--

INSERT INTO `table_obat` (`id_obat`, `nama_obat`, `bentuk_obat`, `segmentasi_obat`, `id_kategori`, `id_manufaktur`, `satuan`, `harga_obat`, `kode_obat`) VALUES
(1, 'betadine solution 5 ml', '1 botol ', 'ob', 1, 1, 1, 3000, 'btd1'),
(2, 'cetrizine 200 mg', '1 strip @ 4 tablet', 'ob', 2, 1, 1, 4000, 'crz1'),
(3, 'simvastatin 200 mg', '1 strip @10 tablet', 'ok', 3, 2, 1, 3500, 'simva1'),
(4, 'amlodipin 500 mg', '1 strip @10 tablet', 'ok', 3, 2, 1, 4000, 'amlo1'),
(5, 'intunal 200 mg', '1 strip @ 4 tablet', 'obt', 4, 3, 1, 4500, 'intu1'),
(6, 'obh combi plus 30 ml', '1 botol', 'ob', 5, 4, 1, 11000, 'obh1'),
(7, 'catflam 50 mg', '1 strip @10 tablet', 'obt', 6, 4, 1, 5000, 'catf10'),
(8, 'catflam 50 mg', '1 tablet', 'obt', 6, 4, 10, 4500, 'catf1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_obat`
--
ALTER TABLE `table_obat`
 ADD PRIMARY KEY (`id_obat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `table_obat`
--
ALTER TABLE `table_obat`
MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
