-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Okt 2025 pada 08.52
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id` int(11) NOT NULL,
  `id_penjualan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id`, `id_penjualan`, `id_produk`, `qty`, `subtotal`) VALUES
(1, 6, 3, 2, 4000.00),
(2, 6, 2, 2, 4000.00),
(3, 7, 3, 2, 4000.00),
(4, 8, 3, 2, 4000.00),
(5, 9, 3, 3, 6000.00),
(6, 10, 4, 2, 10000.00),
(7, 11, 9, 3, 6000.00),
(8, 11, 8, 2, 18000.00),
(9, 11, 5, 1, 3000.00),
(10, 12, 6, 1, 2000.00),
(11, 13, 8, 4, 36000.00),
(12, 14, 8, 2, 18000.00),
(13, 14, 3, 5, 10000.00),
(14, 14, 7, 25, 50000.00),
(15, 15, 3, 7, 14000.00),
(16, 16, 5, 15, 45000.00),
(17, 17, 20, 1, 10000.00),
(18, 17, 13, 3, 24000.00),
(19, 17, 10, 10, 80000.00),
(20, 18, 12, 9, 144000.00),
(21, 18, 14, 9, 45000.00),
(22, 19, 16, 5, 60000.00),
(23, 20, 11, 3, 6000.00),
(24, 20, 7, 3, 6000.00),
(25, 20, 15, 4, 12000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `alamat`, `telepon`, `created_at`) VALUES
(1, 'ndahhh', 'jl. apa aja deh yaa', '089999999', '2025-10-08 14:49:16'),
(7, 'neyyayuu', 'jl. civayunk', '0897654321', '2025-10-09 11:58:45'),
(8, 'ailakk', 'jl. jalann', '087689756', '2025-10-09 11:59:48'),
(9, 'dilluyy', 'jl. vinkyvinkuy', '091234578', '2025-10-09 12:00:44'),
(10, 'zahirumppp', 'jl. aduhh ini aja deh', '0876817659', '2025-10-09 12:05:06'),
(11, 'kikahhhh', 'jl. apa lagii yaa', '0873452891', '2025-10-09 12:06:34'),
(12, 'kanayyann', 'jl. deh ini mah ga usah yak', '07987432357', '2025-10-09 12:08:14'),
(13, 'sore', 'jl. g ush jln', '009887653', '2025-10-14 15:18:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`id`, `id_pelanggan`, `id_user`, `tanggal`, `total`, `created_at`) VALUES
(6, 1, 2, '2025-10-08', 8000.00, '2025-10-08 14:49:42'),
(7, NULL, 2, '2025-10-08', 4000.00, '2025-10-08 14:55:32'),
(8, 1, 2, '2025-10-09', 4000.00, '2025-10-09 00:37:01'),
(9, NULL, 2, '2025-10-09', 6000.00, '2025-10-09 04:53:07'),
(10, NULL, 2, '2025-10-09', 10000.00, '2025-10-09 11:48:20'),
(11, 7, 2, '2025-10-09', 27000.00, '2025-10-09 12:43:52'),
(12, NULL, 2, '2025-10-13', 2000.00, '2025-10-13 06:39:28'),
(13, 9, 2, '2025-10-14', 36000.00, '2025-10-14 02:57:32'),
(14, 8, 2, '2025-10-14', 78000.00, '2025-10-14 03:22:11'),
(15, 10, 8, '2025-10-14', 14000.00, '2025-10-14 14:35:07'),
(16, NULL, 8, '2025-10-14', 45000.00, '2025-10-14 14:36:11'),
(17, 13, 6, '2025-10-14', 114000.00, '2025-10-14 15:20:22'),
(18, 7, 8, '2025-10-14', 189000.00, '2025-10-14 15:27:21'),
(19, 10, 8, '2025-10-14', 60000.00, '2025-10-14 15:27:38'),
(20, 10, 8, '2025-10-15', 24000.00, '2025-10-15 14:13:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `harga`, `stok`, `created_at`) VALUES
(2, 'Roma Sari Gandum Sandwich Susu & Cokelat', 2000.00, 27, '2025-10-08 14:44:14'),
(3, 'Potabee Rumput Laut Panggang', 2000.00, 1, '2025-10-08 14:46:04'),
(4, 'Ultra Milk Strawbery', 5000.00, 16, '2025-10-09 04:54:28'),
(5, 'Le Minerale', 3000.00, 4, '2025-10-09 12:11:08'),
(6, 'Better Sandwich Biscuit Vanilla Cream', 2000.00, 21, '2025-10-09 12:29:16'),
(7, 'Nabati Wafer Richeese', 2000.00, 57, '2025-10-09 12:31:54'),
(8, 'Cimory UHT Milk 250ml Matcha', 9000.00, 12, '2025-10-09 12:37:52'),
(9, ' JetZ Sweet Stick Chocolate', 2000.00, 16, '2025-10-09 12:40:39'),
(10, 'Good Day Originale Cappuccino', 8000.00, 29, '2025-10-14 14:49:25'),
(11, 'Taro Potato BBQ', 2000.00, 19, '2025-10-14 14:51:14'),
(12, 'Pocari Sweat 350 ml', 16000.00, 32, '2025-10-14 14:55:29'),
(13, 'Pejoy Chocolate 30 gr', 8000.00, 24, '2025-10-14 14:58:22'),
(14, 'Pocky Cookies & Cream 22 gr', 5000.00, 22, '2025-10-14 15:00:54'),
(15, 'Yakult 1 pcs', 3000.00, 29, '2025-10-14 15:02:53'),
(16, 'Ichitan Thai Milk Tea', 12000.00, 26, '2025-10-14 15:07:10'),
(17, 'Boncabe Kulit Pangsit Level 10', 2000.00, 11, '2025-10-14 15:08:22'),
(18, 'Sari Roti Isi Cokelat 55g', 7000.00, 5, '2025-10-14 15:12:37'),
(19, 'Lemonilo Brownies Crispy Chocochips 35g', 15000.00, 3, '2025-10-14 15:14:02'),
(20, 'Mamasuka Rumput Laut Panggang (nori) 5g ', 10000.00, 1, '2025-10-14 15:15:49'),
(21, 'Oatside Oat Milk Coffee 200ml', 9000.00, 31, '2025-10-15 14:46:00'),
(22, 'Cimory Eat Milk Pudding Choco Hazelnut 80g', 9000.00, 12, '2025-10-15 14:50:14'),
(23, 'Larutan Cap Kaki Tiga 350ml', 7000.00, 15, '2025-10-15 14:54:09'),
(24, 'Indomilk Susu Cair Uht Kids Banana 115ml', 4000.00, 10, '2025-10-15 14:55:54'),
(25, 'Kin Bulgarian Yogurt Smooth & Creamy Black 45g', 3500.00, 8, '2025-10-15 14:58:57'),
(26, '5 Days Choco Banana 60g', 7500.00, 9, '2025-10-15 15:02:18'),
(27, 'Snack Soes Coklat 80g', 11500.00, 7, '2025-10-15 15:05:12'),
(28, 'Happydent Chewing Gum Citrus / Sweet 28g', 8900.00, 21, '2025-10-15 15:07:10'),
(29, 'Yupi Candy Gummy Lunch 93g', 15900.00, 27, '2025-10-15 15:09:37'),
(30, 'Delfi Chocolate Chic Choc 40g', 10800.00, 31, '2025-10-15 15:11:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-10-08 13:53:38'),
(2, 'yyoni', '$2y$10$xDmmCuDsc4beX7TuOqXH5Ow0.r5.vjcN.5uJuC1/XvJUxlWRdVnwC', 'admin', '2025-10-08 14:37:35'),
(6, 'chann', '$2y$10$La5CFaiIYkACauuihXZy/uTh9INeRcBd2tV1VIWcqNvodXnx.koXO', 'admin', '2025-10-09 04:58:13'),
(8, 'kunyuk', '$2y$10$PSeGAi98Uvo4uxjQHEc2fOxzGj8.xftKRG7Ah9ZTZ11muliFUgmXm', 'kasir', '2025-10-14 14:32:58');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_penjualan` (`id_penjualan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id`),
  ADD CONSTRAINT `detail_penjualan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`);

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`),
  ADD CONSTRAINT `penjualan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
