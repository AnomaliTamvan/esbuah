-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2026 at 05:36 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ramadhan_rehan`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int NOT NULL,
  `id_menu` int DEFAULT NULL,
  `id_transaksi` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_penjualan`
--

CREATE TABLE `laporan_penjualan` (
  `id_laporan` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `nomor_antrean` varchar(10) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `detail_pesanan` text NOT NULL,
  `total_bayar` int NOT NULL,
  `tanggal_selesai` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `laporan_penjualan`
--

INSERT INTO `laporan_penjualan` (`id_laporan`, `id_pesanan`, `nomor_antrean`, `nama_pelanggan`, `detail_pesanan`, `total_bayar`, `tanggal_selesai`) VALUES
(1, 7, 'A-26', 'pelanggan', 'Es Buah Campur x10', 150000, '2026-05-25 14:36:39'),
(2, 8, 'A-52', 'pelanggan', 'Es Buah Campur x3', 45000, '2026-05-25 14:39:08'),
(3, 9, 'A-93', 'pelanggan', 'Es Buah Campur x3', 45000, '2026-05-26 10:21:24'),
(4, 18, 'A-01', 'pelanggan', 'Es Buah Campur x1', 15000, '2026-05-29 08:18:03'),
(5, 27, 'A-82', 'Jamal Keren', 'Es Buah Campur x2, Es Kelapa x3', 60000, '2026-05-29 10:41:08'),
(6, 11, 'A-67', 'pelanggan', 'Es Buah Campur x1', 15000, '2026-05-29 10:42:12'),
(7, 12, 'A-58', 'pelanggan', 'Es Buah Campur x1', 15000, '2026-05-29 10:43:25'),
(8, 13, 'A-52', 'pelanggan', 'Es Buah Campur x2', 30000, '2026-05-29 10:44:00'),
(9, 29, 'A-40', 'Jamal Keren', 'Es Buah Campur x4', 60000, '2026-05-30 11:45:10'),
(10, 30, 'A-41', 'pelanggan', 'Es Kelapa x1', 10000, '2026-05-30 12:49:39'),
(11, 31, 'A-42', 'pelanggan', 'Es Kelapa x1', 10000, '2026-05-30 12:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama_menu` varchar(100) DEFAULT NULL,
  `harga_menu` int DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `kode`, `nama_menu`, `harga_menu`, `gambar`) VALUES
(7, 'MB-7', 'Es Buah Campur', 15000, '8.jpg'),
(8, 'MB-8', 'Es Kelapa', 10000, '4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `nomor_antrean` varchar(10) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `detail_pesanan` text NOT NULL,
  `total_bayar` int NOT NULL,
  `status` enum('menunggu','diproses','selesai') DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `nomor_antrean`, `nama_pelanggan`, `detail_pesanan`, `total_bayar`, `status`, `created_at`) VALUES
(7, 'A-26', 'pelanggan', 'Es Buah Campur x10', 150000, 'selesai', '2026-05-25 06:32:01'),
(8, 'A-52', 'pelanggan', 'Es Buah Campur x3', 45000, 'selesai', '2026-05-25 06:38:51'),
(9, 'A-93', 'pelanggan', 'Es Buah Campur x3', 45000, 'selesai', '2026-05-26 00:03:13'),
(10, 'A-29', 'pelanggan', 'Es Buah Campur x5', 75000, 'diproses', '2026-05-28 23:48:16'),
(11, 'A-67', 'pelanggan', 'Es Buah Campur x1', 15000, 'selesai', '2026-05-28 23:50:00'),
(12, 'A-58', 'pelanggan', 'Es Buah Campur x1', 15000, 'selesai', '2026-05-28 23:51:07'),
(13, 'A-52', 'pelanggan', 'Es Buah Campur x2', 30000, 'selesai', '2026-05-28 23:52:17'),
(14, 'A-80', 'pelanggan', 'Es Buah Campur x1', 15000, 'menunggu', '2026-05-28 23:56:37'),
(15, 'A-10', 'pelanggan', 'Es Buah Campur x4', 60000, 'menunggu', '2026-05-28 23:59:41'),
(16, 'A-43', 'pelanggan', 'Es Buah Campur x1', 15000, 'menunggu', '2026-05-29 00:04:28'),
(17, 'A-62', 'pelanggan', 'Es Buah Campur x1', 15000, 'menunggu', '2026-05-29 00:07:28'),
(18, 'A-01', 'pelanggan', 'Es Buah Campur x1', 15000, 'selesai', '2026-05-29 00:09:15'),
(19, 'A-03', 'pelanggan', 'Es Buah Campur x2', 30000, 'menunggu', '2026-05-29 00:09:28'),
(20, 'A-37', 'Jamal Keren', 'Es Buah Campur x1', 15000, 'menunggu', '2026-05-29 02:25:37'),
(21, 'A-65', 'Jamal Keren', 'Es Kelapa x5', 50000, 'menunggu', '2026-05-29 02:25:50'),
(22, 'A-63', 'pelanggan', 'Es Buah Campur x1', 15000, 'menunggu', '2026-05-29 02:27:11'),
(23, 'A-19', 'pelanggan', 'Es Buah Campur x2, Es Kelapa x1', 40000, 'menunggu', '2026-05-29 02:31:10'),
(24, 'A-69', 'pelanggan', 'Es Buah Campur x1', 15000, 'menunggu', '2026-05-29 02:31:18'),
(25, 'A-11', 'pelanggan', 'Es Kelapa x2', 20000, 'menunggu', '2026-05-29 02:36:07'),
(26, 'A-97', 'Jamal Keren', 'Es Buah Campur x2', 30000, 'menunggu', '2026-05-29 02:37:11'),
(27, 'A-82', 'Jamal Keren', 'Es Buah Campur x2, Es Kelapa x3', 60000, 'selesai', '2026-05-29 02:40:32'),
(28, 'A-51', 'Jamal Keren', 'Es Kelapa x3', 30000, 'menunggu', '2026-05-29 02:48:18'),
(29, 'A-40', 'Jamal Keren', 'Es Buah Campur x4', 60000, 'selesai', '2026-05-30 03:44:29'),
(30, 'A-41', 'pelanggan', 'Es Kelapa x1', 10000, 'selesai', '2026-05-30 04:32:44'),
(31, 'A-42', 'pelanggan', 'Es Kelapa x1', 10000, 'selesai', '2026-05-30 04:38:47'),
(32, 'A-43', 'pelanggan', 'Es Kelapa x1', 10000, 'menunggu', '2026-05-30 04:38:53'),
(33, 'A-65', 'pelanggan', 'Es Kelapa x1', 10000, 'menunggu', '2026-05-30 04:38:58'),
(34, 'A-66', 'Jamal Keren', 'Es Kelapa x1', 10000, 'menunggu', '2026-05-30 04:45:44'),
(35, 'A-67', 'Jamal Keren', 'Es Kelapa x1', 10000, 'menunggu', '2026-05-30 04:46:55'),
(36, 'A-90', 'Jamal Keren', 'Es Buah Campur x3, Es Kelapa x1', 55000, 'menunggu', '2026-05-30 04:48:19');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `id_petugas` int DEFAULT NULL,
  `kode_invoice` varchar(50) DEFAULT NULL,
  `tanggal_penjualan` datetime DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `status_pembayaran` enum('pending','lunas') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `transaksi`
--
DELIMITER $$
CREATE TRIGGER `otomatis_lapor_transaksi` AFTER UPDATE ON `transaksi` FOR EACH ROW BEGIN
    -- Jika status pembayaran berubah menjadi lunas
    IF NEW.status_pembayaran = 'lunas' AND OLD.status_pembayaran != 'lunas' THEN
        INSERT INTO laporan_penjualan (id_pesanan, nomor_antrean, nama_pelanggan, detail_pesanan, total_bayar, tanggal_selesai)
        VALUES (
            NULL, -- Karena ini dari tabel transaksi, bukan tabel pesanan
            NEW.kode_invoice, -- Kita masukkan kode invoice sebagai pengganti nomor antrean
            (SELECT nama FROM user WHERE id_user = NEW.id_pelanggan), -- Ambil nama pelanggan dari tabel user
            'Pembelian melalui Kasir/Aplikasi', -- Keterangan detail
            NEW.total_harga, 
            NEW.tanggal_penjualan
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','petugas','pelanggan') DEFAULT NULL,
  `status` enum('pending','active') DEFAULT NULL,
  `foto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `email`, `password`, `role`, `status`, `foto`, `created_at`) VALUES
(1, 'Rehan', 'admin', 'admin@example.com', '0192023a7bbd73250516f069df18b500', 'admin', 'active', 'avatar_1_1779686922.png', '2026-01-14 16:00:00'),
(2, 'Agus', 'petugas', 'petugas@example.com', '570c396b3fc856eceb8aa7357f32af1a', 'petugas', 'active', 'avatar_2_1779687424.png', '2026-01-14 16:00:00'),
(4, 'Jamal', 'Jamal Keren', 'jamal@exemple.com', '7592628bce37dd14e0b36ea66d5ba847', 'pelanggan', 'active', NULL, '2026-05-29 02:25:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `laporan_penjualan`
--
ALTER TABLE `laporan_penjualan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_penjualan`
--
ALTER TABLE `laporan_penjualan`
  MODIFY `id_laporan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
