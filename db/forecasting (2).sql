-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 05 Jul 2020 pada 00.42
-- Versi Server: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forecasting`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_rekap`
--

CREATE TABLE `detail_rekap` (
  `id_rek` int(11) NOT NULL,
  `id_daterek` int(11) DEFAULT NULL,
  `id_obat` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total` double(14,2) DEFAULT NULL,
  `diskon` float DEFAULT NULL,
  `subtotal` double(14,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_rekap`
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
(76, 72, 1, 2, 6000.00, 0, 6000.00, '2020-06-22 22:31:27', '2020-06-22 22:31:27'),
(90, 86, 7, 2, 100000.00, 0, 100000.00, '2020-07-05 05:23:31', '2020-07-05 05:23:31'),
(91, 86, 8, 4, 18000.00, 0, 18000.00, '2020-07-05 05:23:40', '2020-07-05 05:23:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_obat`
--

CREATE TABLE `kategori_obat` (
  `id_kategori` int(11) NOT NULL,
  `kode_kategori` char(5) NOT NULL,
  `nama_kategori` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kategori_obat`
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
-- Struktur dari tabel `manufaktur_obat`
--

CREATE TABLE `manufaktur_obat` (
  `id_manufaktur` int(11) NOT NULL,
  `kode_manufaktur` char(10) NOT NULL,
  `nama_manufaktur` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `manufaktur_obat`
--

INSERT INTO `manufaktur_obat` (`id_manufaktur`, `kode_manufaktur`, `nama_manufaktur`) VALUES
(1, 'cmb', 'combiphar'),
(2, 'gm', 'generik'),
(3, 'kbf', 'kalbe farma'),
(4, 'knx', 'konimex'),
(5, 'smt', 'samantor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_obat`
--

CREATE TABLE `stock_obat` (
  `id_stock` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `stock_obat`
--

INSERT INTO `stock_obat` (`id_stock`, `stock`, `created_at`, `update_at`) VALUES
(8, 50, '2020-06-27 20:07:10', '2020-06-27 20:07:10'),
(9, 100, '2020-06-27 20:07:30', '2020-06-27 20:07:30'),
(11, 1000, '2020-06-27 20:30:07', '2020-07-05 05:22:50'),
(12, 4000, '2020-06-27 23:16:35', '2020-06-27 23:16:35'),
(13, 200, '2020-06-28 08:52:02', '2020-06-28 08:52:02'),
(14, 20, '2020-06-28 08:52:13', '2020-06-28 08:52:13'),
(15, 24976, '2020-07-04 19:13:25', '2020-07-05 05:23:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_obat`
--

CREATE TABLE `table_obat` (
  `id_obat` int(11) NOT NULL,
  `nama_obat` varchar(50) NOT NULL,
  `bentuk_obat` varchar(25) NOT NULL,
  `segmentasi_obat` varchar(25) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_manufaktur` int(11) NOT NULL,
  `satuan` int(11) NOT NULL,
  `harga_obat` int(11) NOT NULL,
  `id_stock` int(11) DEFAULT NULL,
  `kode_obat` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `table_obat`
--

INSERT INTO `table_obat` (`id_obat`, `nama_obat`, `bentuk_obat`, `segmentasi_obat`, `id_kategori`, `id_manufaktur`, `satuan`, `harga_obat`, `id_stock`, `kode_obat`) VALUES
(1, 'betadine solution 5 ml', '1 botol ', 'ob', 1, 1, 1, 3000, 9, 'btd1'),
(2, 'cetrizine 200 mg', '1 strip @ 4 tablet', 'ob', 2, 1, 4, 4000, 12, 'crz1'),
(3, 'simvastatin 200 mg', '1 strip @10 tablet', 'ok', 3, 2, 10, 3500, 11, 'simva1'),
(4, 'amlodipin 500 mg', '1 strip @10 tablet', 'ok', 3, 2, 10, 4000, 8, 'amlo1'),
(5, 'intunal 200 mg', '1 strip @ 4 tablet', 'obt', 4, 3, 4, 4500, 13, 'intu1'),
(6, 'obh combi plus 30 ml', '1 botol', 'ob', 5, 4, 1, 11000, 14, 'obh1'),
(7, 'catflam 50 mg', '1 strip @10 tablet', 'obt', 6, 4, 10, 50000, 15, 'catf10'),
(8, 'catflam 50 mg', '1 tablet', 'obt', 6, 4, 1, 4500, 15, 'catf1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_rekap`
--

CREATE TABLE `table_rekap` (
  `id_daterek` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nota` varchar(20) DEFAULT NULL,
  `subtotal` double(14,2) DEFAULT NULL,
  `bayar` double(14,2) DEFAULT NULL,
  `kembali` double(14,2) DEFAULT NULL,
  `transaksi_selesai` varchar(1) DEFAULT NULL,
  `cara_bayar` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `table_rekap`
--

INSERT INTO `table_rekap` (`id_daterek`, `tanggal`, `nota`, `subtotal`, `bayar`, `kembali`, `transaksi_selesai`, `cara_bayar`, `created_at`, `updated_at`) VALUES
(1, '2019-11-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '2019-11-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '2019-11-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '2019-11-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '2019-11-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, '2019-12-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '2019-12-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, '2019-12-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, '2019-12-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '2019-12-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '2019-12-31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '2020-01-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, '2020-01-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, '2020-01-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, '2020-01-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, '2020-01-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, '2020-02-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, '2020-02-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, '2020-02-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, '2020-02-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, '2020-02-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, '2020-03-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '2020-03-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, '2020-03-18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, '2020-03-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, '2020-03-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, '2020-04-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, '2020-04-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, '2020-04-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, '2020-04-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, '2020-04-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, '2020-06-06', 'PO060620200001', 16000.00, 20000.00, 4000.00, 'Y', 'tunai', '2020-06-06 20:19:35', '2020-06-06 22:06:32'),
(65, '2020-06-06', 'PO060620200002', 3000.00, 5000.00, 2000.00, 'Y', 'tunai', '2020-06-06 22:08:39', '2020-06-06 22:08:47'),
(66, '2020-06-06', 'PO060620200003', 8000.00, 10000.00, 2000.00, 'Y', 'tunai', '2020-06-06 22:11:22', '2020-06-06 22:11:31'),
(67, '2020-06-06', 'PO060620200004', 3000.00, 5000.00, 2000.00, 'Y', 'tunai', '2020-06-06 22:12:47', '2020-06-06 22:12:52'),
(68, '2020-06-06', 'PO060620200005', 46000.00, 50000.00, 4000.00, 'Y', 'tunai', '2020-06-06 22:13:39', '2020-06-06 22:13:58'),
(70, '2020-06-21', 'PO210620200001', 8000.00, 10000.00, 2000.00, 'Y', 'tunai', '2020-06-21 17:59:01', '2020-06-21 17:59:08'),
(71, '2020-06-21', 'PO210620200002', 7000.00, 10000.00, 3000.00, 'Y', 'tunai', '2020-06-21 18:00:10', '2020-06-21 18:00:16'),
(72, '2020-06-22', 'PO220620200003', 32000.00, 35000.00, 3000.00, 'Y', 'tunai', '2020-06-22 13:16:34', '2020-06-22 13:16:48'),
(73, '2020-06-22', 'PO220620200004', 36000.00, 50000.00, 14000.00, 'Y', 'tunai', '2020-06-22 13:17:35', '2020-06-22 13:17:42'),
(74, '2020-06-16', 'PO220620200005', 8000.00, 10000.00, 2000.00, 'Y', 'tunai', '2020-06-22 22:04:59', '2020-06-22 22:05:05'),
(76, '2020-06-22', 'PO220620200001', 4000.00, 4000.00, 0.00, 'Y', 'tunai', '2020-06-22 22:18:29', '2020-06-22 22:18:35'),
(77, '2020-06-22', 'PO220620200002', 14000.00, 15000.00, 1000.00, 'Y', 'tunai', '2020-06-22 22:18:43', '2020-06-22 22:22:23'),
(86, '2020-07-05', 'PO050720200001', NULL, NULL, NULL, 'N', NULL, '2020-07-05 05:23:31', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `level` varchar(5) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(254) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `level`, `username`, `password`) VALUES
(1, 'admin', 'admin', 'admin1', '21232f297a57a5a743894a0e4a801fc3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_rekap`
--
ALTER TABLE `detail_rekap`
  ADD PRIMARY KEY (`id_rek`),
  ADD KEY `fk_foreign_id_daterek` (`id_daterek`),
  ADD KEY `fk_foreign_id_obat` (`id_obat`);

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
  MODIFY `id_rek` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `kategori_obat`
--
ALTER TABLE `kategori_obat`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `manufaktur_obat`
--
ALTER TABLE `manufaktur_obat`
  MODIFY `id_manufaktur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `stock_obat`
--
ALTER TABLE `stock_obat`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `table_obat`
--
ALTER TABLE `table_obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `table_rekap`
--
ALTER TABLE `table_rekap`
  MODIFY `id_daterek` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_rekap`
--
ALTER TABLE `detail_rekap`
  ADD CONSTRAINT `fk_foreign_id_daterek` FOREIGN KEY (`id_daterek`) REFERENCES `table_rekap` (`id_daterek`),
  ADD CONSTRAINT `fk_foreign_id_obat` FOREIGN KEY (`id_obat`) REFERENCES `table_obat` (`id_obat`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
