-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 05:28 AM
-- Server version: 10.4.6-MariaDB
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
-- Database: `quanlykho`
--

-- --------------------------------------------------------

--
-- Table structure for table `hangtonkho`
--

CREATE TABLE `hangtonkho` (
  `idSP` int(11) NOT NULL,
  `idKho` int(11) NOT NULL,
  `soLuong` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hangtonkho`
--

INSERT INTO `hangtonkho` (`idSP`, `idKho`, `soLuong`) VALUES
(1, 1, 50),
(2, 2, 100),
(3, 3, 150),
(4, 1, 70),
(5, 2, 60),
(6, 2, 450),
(9, 3, 550),
(14, 3, 400),
(17, 1, 899);

-- --------------------------------------------------------

--
-- Table structure for table `kho`
--

CREATE TABLE `kho` (
  `id` int(11) NOT NULL,
  `tenKho` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diaChi` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kho`
--

INSERT INTO `kho` (`id`, `tenKho`, `diaChi`) VALUES
(1, 'Kho Ha Noi', '123 Nguyen Trai, Hanoi'),
(2, 'Kho Ho Chi Minh', '456 Le Loi, Ho Chi Minh'),
(3, 'Kho Da Nang', '789 Tran Hung Dao, Da Nang');

-- --------------------------------------------------------

--
-- Table structure for table `nhacungcap`
--

CREATE TABLE `nhacungcap` (
  `id` int(11) NOT NULL,
  `tenNCC` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thongTinLienHe` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`id`, `tenNCC`, `thongTinLienHe`) VALUES
(1, 'Cong ty Vinamilk', 'HN, 19001000'),
(2, 'Cong ty Acecook', 'HCM, 19008080'),
(3, 'Cong ty Tuong An', 'DN, 18001008'),
(4, 'Cong ty Trung Nguyen', 'Dak Lak, 18001234'),
(5, 'Cong ty P/S', 'HCM, 18002222');

-- --------------------------------------------------------

--
-- Table structure for table `nhapkho`
--

CREATE TABLE `nhapkho` (
  `id` int(11) NOT NULL,
  `idSP` int(11) DEFAULT NULL,
  `idNCC` int(11) DEFAULT NULL,
  `idKho` int(11) NOT NULL,
  `soLuong` int(11) NOT NULL,
  `giaNhap` decimal(10,2) NOT NULL,
  `ngayNhap` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhapkho`
--

INSERT INTO `nhapkho` (`id`, `idSP`, `idNCC`, `idKho`, `soLuong`, `giaNhap`, `ngayNhap`) VALUES
(1, 1, 1, 1, 100, '20000.00', '2025-03-06 00:38:14'),
(2, 2, 1, 2, 200, '10000.00', '2025-03-06 00:38:14'),
(3, 3, 2, 3, 300, '4000.00', '2025-03-06 00:38:14'),
(4, 4, 3, 1, 150, '7000.00', '2025-03-06 00:38:14'),
(5, 5, 3, 2, 120, '16000.00', '2025-03-06 00:38:14'),
(6, 17, 1, 1, 999, '390000.00', '2025-03-06 01:05:44'),
(7, 6, 1, 2, 500, '10000.00', '2025-03-06 01:02:31'),
(8, 14, 1, 3, 500, '5000.00', '2025-03-06 01:06:30'),
(9, 9, 4, 3, 800, '45000.00', '2025-03-06 01:07:22');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `id` int(11) NOT NULL,
  `tenSP` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `motaSP` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `giaSP` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`id`, `tenSP`, `motaSP`, `giaSP`, `created_at`) VALUES
(1, 'Gao ST25', 'Gao dac san ST25', '25000.00', '2025-03-06 00:38:14'),
(2, 'Sua Vinamilk', 'Sua tuoi co duong', '12000.00', '2025-03-06 00:38:14'),
(3, 'Mi tom Hao Hao', 'Mi tom Hao Hao vi chua cay', '5000.00', '2025-03-06 00:38:14'),
(4, 'Banh mi', 'Banh mi viet nam', '8000.00', '2025-03-06 00:38:14'),
(5, 'Nuoc ngot Coca', 'Chai Coca-Cola 1.5L', '18000.00', '2025-03-06 00:38:14'),
(6, 'Tra xanh Olong', 'Tra xanh 0 do', '15000.00', '2025-03-06 00:38:14'),
(7, 'Keo Alpenliebe', 'Keo sua mem', '6000.00', '2025-03-06 00:38:14'),
(8, 'Pho goi', 'Pho goi An Lien', '10000.00', '2025-03-06 00:38:14'),
(9, 'Cafe Trung Nguyen', 'Cafe hat rang xay', '50000.00', '2025-03-06 00:38:14'),
(10, 'Dau an Tuong An', 'Chai 1 lit', '30000.00', '2025-03-06 00:38:14'),
(11, 'Bot giat Omo', 'Tui bot giat 2kg', '55000.00', '2025-03-06 00:38:14'),
(12, 'Nuoc rua chen Sunlight', 'Chai 800ml', '20000.00', '2025-03-06 00:38:14'),
(13, 'Dau goi Clear', 'Chai 650ml', '75000.00', '2025-03-06 00:38:14'),
(14, 'Sua dau nanh Fami', 'Hop 200ml', '7000.00', '2025-03-06 00:38:14'),
(15, 'Trung ga', 'Hop 10 qua', '35000.00', '2025-03-06 00:38:14'),
(16, 'Thit bo My', '500g', '150000.00', '2025-03-06 00:38:14'),
(17, 'Bia Heineken', 'Thung 24 lon', '400000.00', '2025-03-06 00:38:14'),
(18, 'Ruou Vodka Ha Noi', 'Chai 500ml', '65000.00', '2025-03-06 00:38:14'),
(19, 'Kem danh rang P/S', 'Tuyp 200g', '25000.00', '2025-03-06 00:38:14'),
(20, 'Nuoc muoi sinh ly', 'Chai 500ml', '5000.00', '2025-03-06 00:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idVaiTro` int(11) NOT NULL,
  `idKho` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`id`, `username`, `password`, `idVaiTro`, `idKho`, `created_at`) VALUES
(1, 'admin', '1', 1, NULL, '2025-03-06 00:38:14'),
(2, 'quanly_hn', '1', 2, 1, '2025-03-06 00:38:14'),
(3, 'quanly_hcm', '1', 2, 2, '2025-03-06 00:38:14'),
(4, 'quanly_dn', '1', 2, 3, '2025-03-06 00:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `vaitro`
--

CREATE TABLE `vaitro` (
  `id` int(11) NOT NULL,
  `tenVaiTro` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vaitro`
--

INSERT INTO `vaitro` (`id`, `tenVaiTro`) VALUES
(1, 'Admin'),
(2, 'Quan ly kho');

-- --------------------------------------------------------

--
-- Table structure for table `xuatkho`
--

CREATE TABLE `xuatkho` (
  `id` int(11) NOT NULL,
  `idSP` int(11) DEFAULT NULL,
  `idKho` int(11) NOT NULL,
  `soLuong` int(11) NOT NULL,
  `ngayXuat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `xuatkho`
--

INSERT INTO `xuatkho` (`id`, `idSP`, `idKho`, `soLuong`, `ngayXuat`) VALUES
(1, 1, 1, 50, '2025-03-06 00:38:14'),
(2, 2, 2, 100, '2025-03-06 00:38:14'),
(3, 3, 3, 150, '2025-03-06 00:38:14'),
(4, 4, 1, 80, '2025-03-06 00:38:14'),
(5, 5, 2, 60, '2025-03-06 00:38:14'),
(6, 17, 1, 100, '2025-03-06 00:46:58'),
(7, 6, 2, 50, '2025-03-06 01:04:41'),
(8, 14, 3, 100, '2025-03-06 01:07:44'),
(9, 9, 3, 250, '2025-03-06 01:07:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hangtonkho`
--
ALTER TABLE `hangtonkho`
  ADD PRIMARY KEY (`idSP`,`idKho`),
  ADD KEY `idKho` (`idKho`);

--
-- Indexes for table `kho`
--
ALTER TABLE `kho`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nhapkho`
--
ALTER TABLE `nhapkho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSP` (`idSP`),
  ADD KEY `idNCC` (`idNCC`),
  ADD KEY `idKho` (`idKho`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idVaiTro` (`idVaiTro`),
  ADD KEY `idKho` (`idKho`);

--
-- Indexes for table `vaitro`
--
ALTER TABLE `vaitro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenVaiTro` (`tenVaiTro`);

--
-- Indexes for table `xuatkho`
--
ALTER TABLE `xuatkho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSP` (`idSP`),
  ADD KEY `idKho` (`idKho`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kho`
--
ALTER TABLE `kho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nhapkho`
--
ALTER TABLE `nhapkho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vaitro`
--
ALTER TABLE `vaitro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `xuatkho`
--
ALTER TABLE `xuatkho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hangtonkho`
--
ALTER TABLE `hangtonkho`
  ADD CONSTRAINT `hangtonkho_ibfk_1` FOREIGN KEY (`idSP`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hangtonkho_ibfk_2` FOREIGN KEY (`idKho`) REFERENCES `kho` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nhapkho`
--
ALTER TABLE `nhapkho`
  ADD CONSTRAINT `nhapkho_ibfk_1` FOREIGN KEY (`idSP`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nhapkho_ibfk_2` FOREIGN KEY (`idNCC`) REFERENCES `nhacungcap` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `nhapkho_ibfk_3` FOREIGN KEY (`idKho`) REFERENCES `kho` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `taikhoan_ibfk_1` FOREIGN KEY (`idVaiTro`) REFERENCES `vaitro` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `taikhoan_ibfk_2` FOREIGN KEY (`idKho`) REFERENCES `kho` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `xuatkho`
--
ALTER TABLE `xuatkho`
  ADD CONSTRAINT `xuatkho_ibfk_1` FOREIGN KEY (`idSP`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `xuatkho_ibfk_2` FOREIGN KEY (`idKho`) REFERENCES `kho` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
