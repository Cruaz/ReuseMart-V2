-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2025 at 04:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `p3l`
--

-- --------------------------------------------------------

--
-- Table structure for table `alamat`
--

CREATE TABLE `alamat` (
  `id_alamat` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `label_alamat` varchar(255) NOT NULL,
  `deskripsi_alamat` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alamat`
--

INSERT INTO `alamat` (`id_alamat`, `id_pembeli`, `label_alamat`, `deskripsi_alamat`, `is_default`) VALUES
(1, 1, 'Rumah', 'Jl. Gejayan No. 45B, Kel. Condongcatur, Kec. Depok, Kab. Sleman, DI Yogyakarta 55281', 1),
(2, 1, 'Rumah', 'Jl. Ring Road Selatan No. 21, Kel. Banguntapan, Kec. Banguntapan, Kab. Bantul, DI Yogyakarta 55191', 1),
(3, 9, 'Rumah', 'Jl. Magelang Km 4.2 No. 8A, Kel. Sinduadi, Kec. Mlati, Kab. Sleman, DI Yogyakarta 55284', 1),
(4, 4, 'Kantor', 'Jl. Kaliurang Km 5.2 No. 18, Caturtunggal, Kec. Depok, Kab. Sleman, Daerah Istimewa Yogyakarta 55281', 0),
(5, 4, 'Rumah', 'Jl. Godean Km 5 No. 17, Kel. Sidokarto, Kec. Godean, Kab. Sleman, DI Yogyakarta 55264', 1),
(6, 5, 'Rumah', 'Jl. Wates Km 3.5 No. 31, Kel. Gamping, Kec. Gamping, Kab. Sleman, DI Yogyakarta 55294', 1),
(7, 8, 'Rumah', 'Jl. Affandi No. 26, Kel. Demangan, Kec. Gondokusuman, Kota Yogyakarta, DI Yogyakarta 55221', 1),
(8, 7, 'Rumah', 'Jl. Tamansiswa No. 39C, Kel. Wirogunan, Kec. Mergangsan, Kota Yogyakarta, DI Yogyakarta 55151', 1),
(9, 6, 'Rumah', 'Jl. Imogiri Timur Km 6 No. 11, Kel. Bangunharjo, Kec. Sewon, Kab. Bantul, DI Yogyakarta 55187', 1),
(10, 2, 'Rumah', 'Jl. Parangtritis Km 5.8 No. 18, Kel. Glugo, Kec. Kasihan, Kab. Bantul, DI Yogyakarta 55184', 1),
(11, 3, 'Rumah', 'Jl. Prawirotaman No. 10, Kel. Brontokusuman, Kec. Mergangsan, Kota Yogyakarta, DI Yogyakarta 55153', 1),
(12, 11, 'testtt', 'test', 0),
(13, 11, '1111', '1111', 0),
(14, 11, '222222', '1111111', 0),
(15, 16, 'test1', 'test1', 1),
(16, 16, 'a2', 'a2', 0),
(17, 17, '2222', '2222', 1),
(18, 11, 'Kantor', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 0),
(19, 11, 'Kantor', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

CREATE TABLE `badge` (
  `id_badge` int(11) NOT NULL,
  `id_penitip` int(11) NOT NULL,
  `nama_badge` varchar(255) NOT NULL,
  `tanggal_mulai_badge` date NOT NULL,
  `tanggal_berakhir_badge` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badge`
--

INSERT INTO `badge` (`id_badge`, `id_penitip`, `nama_badge`, `tanggal_mulai_badge`, `tanggal_berakhir_badge`) VALUES
(1, 11, 'Top Donatur', '2025-04-01', '2025-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga_barang` float NOT NULL,
  `kategori_barang` varchar(255) NOT NULL,
  `status_garansi_barang` tinyint(1) NOT NULL,
  `tanggal_habis_garansi` date DEFAULT NULL,
  `deskripsi_barang` varchar(255) NOT NULL,
  `review_barang` int(11) DEFAULT NULL,
  `berat_barang` float NOT NULL,
  `status_barang` varchar(255) DEFAULT NULL,
  `gambar_barang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `id_transaksi`, `nama_barang`, `harga_barang`, `kategori_barang`, `status_garansi_barang`, `tanggal_habis_garansi`, `deskripsi_barang`, `review_barang`, `berat_barang`, `status_barang`, `gambar_barang`) VALUES
(1, 1, 'Mouse Logitech M170', 250000, 'Elektronik & Gadget', 1, '2026-05-07', 'Mouse wireless nyaman', 5, 0.2, NULL, 'mouse1.jpg'),
(2, 2, 'Keyboard Fantech', 175000, 'Elektronik & Gadget', 1, '2025-07-03', 'Keyboard mekanik', 4, 0.6, NULL, 'keyboard1.jpg'),
(3, 3, 'Power Bank 10000mAh', 220000, 'Elektronik & Gadget', 1, '2025-08-20', 'Fast charging', 5, 0.4, NULL, 'powerbank1.jpg'),
(4, 3, 'Lampu LED', 200000, 'Perabotan Rumah Tangga', 0, NULL, 'Terang dan hemat', 4, 0.3, NULL, 'lampu1.jpg'),
(5, 4, 'Flashdisk 64GB', 99000, 'Elektronik & Gadget', 0, NULL, 'USB 3.0', 4, 0.05, NULL, 'flashdisk1.jpg'),
(6, 5, 'Headset Gaming', 310000, 'Elektronik & Gadget', 1, '2025-04-03', 'Suara jernih', 5, 0.7, NULL, 'headset1.jpg'),
(7, 6, 'Monitor 24 inch', 600000, 'Elektronik & Gadget', 1, '2025-05-30', 'Full HD display', 5, 3.5, NULL, 'monitor1.jpg'),
(8, 7, 'Charger 20W', 110000, 'Elektronik & Gadget', 0, NULL, 'Charger cepat', 5, 0.1, NULL, 'charger1.jpg'),
(9, 8, 'Speaker Bluetooth', 250000, 'Elektronik & Gadget', 1, '2025-07-31', 'Bass mantap', 5, 0.8, NULL, 'speaker1.jpg'),
(10, 9, 'Printer Canon', 720000, 'Elektronik & Gadget', 1, '2026-07-14', 'Cetak cepat dan jelas', 5, 4.2, NULL, 'printer1.jpg'),
(12, NULL, 'Kaos Putih Polos', 40000, 'Pakaian & Aksesori', 1, '2025-05-29', 'Kaos cocok untuk laki-laki maupun perempuan', 3, 0.16, NULL, 'kaos1.jpg'),
(16, NULL, 'Tas sekolah hitam', 35000, 'Pakaian & Aksesori', 0, NULL, 'Tas sekolah bisa dipakai untuk semua umur', NULL, 1.2, NULL, 'tas1.jpg'),
(17, NULL, 'Sekop besi', 40000, 'Perlengkapan Taman & Outdoor', 0, NULL, 'Sekop besi kuat dan tahan lama', NULL, 3, NULL, 'sekop1.jpg'),
(18, NULL, 'Popok Bayi', 20000, 'Perlengkapan Bayi & Anak', 0, NULL, 'Popok bayi satu kardus isi 20 yang belum terpakai', NULL, 1, NULL, 'popok1.jpg'),
(19, NULL, 'Rak buku kayu', 90000, 'Perabotan Rumah Tangga', 0, NULL, 'Rak buku kayu masih awet dan tahan lama', NULL, 19, NULL, 'rak1.jpg'),
(20, NULL, 'Buku-buku materi pelajaran sekolah dasar', 50000, 'Buku, Alat Tulis, & Peralatan Sekolah', 0, NULL, '10 buku materi pelajaran anak sekolah dasar', NULL, 4, NULL, 'buku1.jpg'),
(21, NULL, 'Botol susu anak', 10000, 'Perlengkapan Bayi & Anak', 0, NULL, 'Botol susu kaca higienis', NULL, 0.5, NULL, 'botolsusu1.jpg'),
(23, NULL, 'Mainan lego', 80000, 'Hobi, Mainan, & Koleksi', 0, NULL, 'Mainan lego mobil untuk anak-anak', NULL, 0.8, NULL, 'lego1.jpg'),
(24, NULL, 'Barang24', 11, '2', 1, '2025-06-07', '11111', 11, 11, NULL, 'lego1.jpg'),
(26, NULL, 'Laptop', 2500000, 'Elektronik & Gadget', 1, '2026-10-07', 'Laptop bekas', 5, 2.4, NULL, 'ydZ4Nn8tAAKG4utfpTwd4OnqyJA7NbEYBQNt8WsP.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `id_pembeli` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diskusi`
--

CREATE TABLE `diskusi` (
  `id_diskusi` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `pertanyaan_diskusi` varchar(255) NOT NULL,
  `jawaban_diskusi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diskusi`
--

INSERT INTO `diskusi` (`id_diskusi`, `id_pembeli`, `id_barang`, `id_pegawai`, `pertanyaan_diskusi`, `jawaban_diskusi`) VALUES
(1, 3, 1, NULL, 'Apakah mouse ini support macOS?', 'Ya, mouse ini bisa digunakan di macOS tanpa driver tambahan.'),
(2, 2, 3, NULL, 'Powerbank ini bisa untuk fast charging Samsung?', 'Bisa, mendukung fast charging untuk sebagian besar device.'),
(3, 2, 4, NULL, 'Lampunya apakah bisa digantung di dinding?', 'Bisa, sudah ada slot gantungan di bagian belakang.'),
(4, 9, 5, NULL, 'Apakah flashdisk ini support USB 3.0?', 'Ya, flashdisk ini sudah USB 3.0 dengan kecepatan tinggi.'),
(5, 1, 7, NULL, 'Berapa refresh rate monitor ini?', 'Monitor ini punya refresh rate 75Hz.'),
(6, 4, 8, NULL, 'Apakah charger ini compatible dengan iPhone 13?', 'Ya, bisa digunakan untuk iPhone 13 dengan kabel yang sesuai.'),
(10, 3, 19, NULL, 'Berapa ukuran rak buku ini?', 'Tinggi 120cm, lebar 60cm, dan kedalaman 30cm.'),
(14, 11, 12, NULL, 'Testing', NULL),
(15, 11, 12, NULL, 'Test', NULL),
(16, 11, 12, NULL, 'aaaaaa', NULL),
(17, 11, 12, NULL, 'aaaaaaaaaaa', NULL),
(18, 11, 23, NULL, 'aaaaaaaaaaaa', NULL),
(19, 11, 23, NULL, 'testtttttt', NULL),
(20, 14, 12, NULL, 'aaaaaaaaaa', NULL),
(21, 17, 12, NULL, 'Test pertanyaan', NULL),
(22, 11, 24, NULL, 'Test', 'Halo');

-- --------------------------------------------------------

--
-- Table structure for table `donasi`
--

CREATE TABLE `donasi` (
  `id_barang` int(11) NOT NULL,
  `id_request` int(11) NOT NULL,
  `tanggal_donasi` date NOT NULL,
  `nama_penerima` varchar(255) NOT NULL,
  `status_donasi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donasi`
--

INSERT INTO `donasi` (`id_barang`, `id_request`, `tanggal_donasi`, `nama_penerima`, `status_donasi`) VALUES
(12, 3, '2025-04-30', 'Budi Hartono', 'Disetujui'),
(16, 1, '2025-04-11', 'Thomas sucipto', 'Disetujui'),
(17, 5, '2025-03-28', 'Beryl Austevann', 'Ditolak'),
(18, 7, '2025-03-25', 'Furina', 'Disetujui'),
(19, 8, '2025-04-03', 'Natasha', 'DItolak'),
(20, 11, '2025-04-05', 'Nilou', 'Disetujui'),
(21, 12, '2025-03-25', 'Rutni Sari', 'Ditolak');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(1, 'Owner'),
(2, 'Admin'),
(3, 'Customer Service'),
(4, 'Gudang'),
(5, 'Hunter'),
(6, 'Kurir');

-- --------------------------------------------------------

--
-- Table structure for table `komisi`
--

CREATE TABLE `komisi` (
  `id_transaksi` int(11) NOT NULL,
  `id_penitip` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `komisi_hunter` float NOT NULL,
  `komisi_reusemart` float NOT NULL,
  `bonus_penitip` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `komisi`
--

INSERT INTO `komisi` (`id_transaksi`, `id_penitip`, `id_pegawai`, `komisi_hunter`, `komisi_reusemart`, `bonus_penitip`) VALUES
(1, 1, 5, 0, 50000, 0),
(2, 15, 7, 8750, 26250, 2625),
(4, 4, 6, 0, 19800, 0),
(7, 7, 5, 0, 22000, 0),
(8, 8, 6, 0, 37500, 0);

-- --------------------------------------------------------

--
-- Table structure for table `merchandise`
--

CREATE TABLE `merchandise` (
  `id_merchandise` int(11) NOT NULL,
  `nama_merchandise` varchar(255) NOT NULL,
  `poin_redeem` int(11) NOT NULL,
  `stok_merchandise` int(11) NOT NULL,
  `gambar_merchandise` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merchandise`
--

INSERT INTO `merchandise` (`id_merchandise`, `nama_merchandise`, `poin_redeem`, `stok_merchandise`, `gambar_merchandise`) VALUES
(1, 'Ballpoin', 100, 49, 'balpoin.jpeg'),
(2, 'Stiker', 100, 75, 'stiker.jpeg'),
(3, 'Mug', 250, 40, 'topi.jpeg'),
(4, 'Topi', 250, 80, 'payung.jpeg'),
(5, 'Tumblr', 500, 40, 'tumbler.jpeg'),
(6, 'T-shirt', 500, 40, 't-shirt.jpeg'),
(7, 'Jam dinding', 500, 35, 'jam.jpeg'),
(8, 'Tas travel', 1000, 30, 'travel.jpeg'),
(9, 'Payung', 1000, 40, 'payung.jpeg'),
(10, '1111', 11, 1, 'stiker.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_05_09_083729_create_personal_access_tokens_table', 1),
(2, '2025_05_09_140021_create_password_reset_tokens_table', 1),
(3, '2025_05_10_065506_add_remember_token', 1);

-- --------------------------------------------------------

--
-- Table structure for table `organisasi`
--

CREATE TABLE `organisasi` (
  `id_organisasi` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` text NOT NULL,
  `alamat_organisasi` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `fcm_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organisasi`
--

INSERT INTO `organisasi` (`id_organisasi`, `email`, `username`, `password`, `foto`, `alamat_organisasi`, `remember_token`, `fcm_token`) VALUES
(1, 'pedulilindungi@gmail.com', 'Peduli Lindungi', 'pedul12345', 'h2jvuMI7808jWMXlDyQd2BnnADs2qD2bazxZCdiV.jpg', 'Jl. Mawar 166, Kembang, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(2, 'sehatselamat@gmail.com', 'Sehat Selamat', 'sehat1213213', '2.jpg', 'Jl. Babarsari 9-13, Janti, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(3, 'langkahbaik@gmail.com', 'Langkah Baik', 'langkah1123132', '3.jpg', 'Jl. Kembang 198-110, Kembang, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(4, 'pelitaharapannusantara', 'Pelita Harapan Nusantara', 'pelita11231', '4.jpg', 'Jl. Anggrek 104-107, Sambelegi Kidul, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(5, 'amanbersama@gmail.com', 'Aman Bersama', 'aman1123213', '5.jpg', 'Jl. Anggrek 85-97, Sambelegi Lor, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(6, 'Jejak Baik@gmail.com', 'Jejak Baik', 'jejak12321312', '6.jpg', 'Jl. Ringinsari 32, Nanggulan, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(7, 'nusacare@gmail.com', 'Nusacare', 'nusacare11231', '7.jpg', 'Jl. Matraman 4-24, Nanggulan, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(8, 'peduliharmoni@gmail.com', 'Peduli Harmoni', 'peduliharmoni1', '8.jpg', 'Jl. Nanggulan 36-24, Nanggulan, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55282', NULL, NULL),
(9, 'harapin@gmail.com', 'Harap.in', 'harap1213241', '9.jpg', 'Jl. Onggomertan, Nayan, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(10, 'lindungiid@gmail.com', 'Lindung.ID', 'lindungi12342', '10.jpg', 'Jl. Corongan 10, Corongan, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', NULL, NULL),
(11, 'forkomi@gmail.com', 'Forkomi', 'forkomi12342', 'organisasi11.jpg', 'Jl. Jayeng Prawiran 24-20, Purwokinanti, Pakualaman, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55166', NULL, NULL),
(12, 'yayasanpedulikasih@gmail.com', 'Yayasan peduli kasih', 'yayasan1234', 'organisasi12.jpg', 'Ps. Beringharjo, Jl. Pabringan No.19, Ngupasan, Kec. Gondomanan, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55122', NULL, NULL),
(13, 'himaforka@gmail.com', 'Himaforka', 'himaforka123', 'organisasi13.jpg', 'Gg. Bayu, Jomblangan, Banguntapan, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198', NULL, NULL),
(14, 'pedulijawa@gmail.com', 'Peduli jawa', 'jawajawajawa', '5SD2aNvdUNdTrywbzY9TPROcqnw0iXtaonyPUE5R.jpg', 'jawajawa', NULL, NULL),
(15, 'aa@gmail.com', 'aa', 'aaaa1aa1a1a1a', 'T7WX8W9CxPiMWJxptuleta1vZQ6g7PlwdKPCGvhS.jpg', '12345', NULL, NULL),
(16, 'samwijaya48@gmail.com', 'samuel', '$2y$12$Yd8Y0iVU6aEI6BAeFYRcy.989EOSAR0KGVbJxXICZKS7a2fJvhhxe', 'pScbtFDK6fiMGKdXWtgdwrehJWbaVIXF0tlN4UKm.jpg', '11111111111', 'XTPTrBSVbPQ6z1yUx1nuhSl1FCLrjJKXWmjPBP6tj9j2yF6Ank9ESsHs2GxD', NULL),
(17, '11111@gmail.com', '111111', '$2y$12$R0nKaA6xxUQC8L8mim1JCuprNagdo9zUTiTcGuolLP.ZcozynyVHy', '3bg9ydoftLnX9BKauP8hKHrNQfNzJtVwyJNKtzHs.png', '11111111111', NULL, NULL),
(18, 'test@gmail.com', '11111111', '11111111', 'rtSSLhIVsAr4K3b7UKiDzDckKUyPUPU1pXSLZnRd.jpg', '22222222\r\n								\r\n									\r\n								\r\n								\r\n									Update\r\n									Delete', NULL, NULL),
(19, 'wijayasam48@gmail.com', 'SamuelOrganisasi', '$2y$12$AolItCBWjlnfNEvmZr94TujyGb053cuDF1mnVDShRbjEunuNTQy2y', 'u3KoKlooV37LRleGt78GxB5pa8v4vQSwcOWHpCoh.jpg', '11111111111', 'vaG7Xcqp2klbii4ox6uFDelZkeBJaUb2eNKCgWR20pvET7Vst5cZi6t36lTT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('Natsy488@gmail.com', '$2y$12$FppoyM5FYwRNUs9y4q8bzepV8PXS80U7yu9p3wLbgcZ/PAW/Mrsv2', '2025-05-13 01:04:04');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_lahir_pegawai` date NOT NULL,
  `nomor_telepon_pegawai` varchar(255) NOT NULL,
  `fcm_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `id_jabatan`, `username`, `password`, `tanggal_lahir_pegawai`, `nomor_telepon_pegawai`, `fcm_token`) VALUES
(1, 1, 'Rina Ayu Lestari', '$2y$12$oNsQXN9BC87OBwof1/9IqulBlf.opRP7ayxSwQ.WabnTSs.KtCejW', '1994-03-15', '081234567890', NULL),
(2, 2, 'Budi Santoso', '$2y$12$/0Aoq.iLu7iF48CCfo132Omj5wvYJ/3ofnEVoxKBdF9.8wAKYCjj6', '1989-07-22', '085711223344', NULL),
(3, 3, 'Siti Nurhaliza', '$2y$12$pZR/F2bwMm9kk08mMIJShuplamWPLj80nvAdAyNiFYmQzE9Z7kDSi', '1996-10-01', '082177889900', NULL),
(4, 3, 'Deni Pratama', '$2y$12$iFKvBOiduR/OBHNemcc2HOfl0mUWhcYCT0KZ3Mep.T7tDKGldU5tK', '1992-01-19', '088822334455', NULL),
(5, 4, 'Fitri Handayani', '$2y$12$lZc95dnk3a3L11Ibs5ZkE.yfncfWigJ6XP3Z7hYtQ.2cnfQxxpzgC', '1995-06-03', '081399887766', NULL),
(6, 4, 'Agus Widodo', '$2y$12$eQSob1uTHDP4zwOlx.NY7OvIFLPOyqf2WD3HFmlRW.mYu/UGHNOru', '1987-11-09', '087733445566', NULL),
(7, 5, 'Lina Marlina', '$2y$12$RbiNi6Ao24VNopAVPzpZrO2EKp7g9pkd6rAGBebVxB6vTm9ofh4YS', '1993-09-27', '085212345678', NULL),
(8, 5, 'Yoga Firmansyah', '$2y$12$Uw4KKKxTECUlAhtROGKAGOQ8NXKxSIT0lHIB33jPe4LOUshxkcqAu', '1990-12-12', '081999990001', 'c0S_IYJhQMOIfcL82guvCE:APA91bFmdo17SD_OQHsh_m1f1VzQNodlKgEPR7ntPPBOp4xarl0h8qnVCfKQWvG8qR1iUm7HPdtGH050MK_bX4sX-pCddYLjvUPO8T98XMpamuA2ibokMH8'),
(9, 6, 'Andi Kurniawan', '$2y$12$p1vBa3YmK6wBFbjtDs11IuIIWK9.OmGR9A5TKbPMhaWpbstbsCvYW', '1988-04-30', '081287654321', NULL),
(10, 6, 'Rizky Hidayat', '$2y$12$Ps37Dd1hnUSQ98p42UCZIexAujSj6FayeUmBKpRqAxOFn3WhVH7bW', '1991-08-14', '089655557777', NULL),
(11, 2, 'Sen', '11111111', '2025-06-07', '111111111', NULL),
(12, 2, 'SenSen', '$2y$12$xdG6EKDcIsvA628kwA5g1.XX1wFyS5ly0fp54A6wSim0U0W3SfWBW', '2025-05-29', '1111111111', NULL),
(13, 3, 'Testing', '$2y$12$OsDirl75WcbCTBsI/G/DxupDKR3qJA/oBFPdYoGPdkjDE5bGeWFzG', '2025-05-13', '11111111', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembeli`
--

CREATE TABLE `pembeli` (
  `id_pembeli` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` text DEFAULT NULL,
  `poin_pembeli` int(11) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `fcm_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `username`, `email`, `password`, `foto`, `poin_pembeli`, `remember_token`, `fcm_token`) VALUES
(1, 'Andi Saputra', 'andi.s@gmail.com', 'andi12345', NULL, NULL, NULL, NULL),
(2, 'Rina Marlina', 'rina.marlina@yahoo.com', 'rina2024', NULL, NULL, NULL, NULL),
(3, 'Bagus Prasetyo', 'bagus.p@email.com', 'bgspass!', NULL, NULL, NULL, NULL),
(4, 'Dwi Lestari', 'dwi.lestari@outlook.com', 'lestari321', NULL, NULL, NULL, NULL),
(5, 'Fajar Ramadhan', 'fajar.r@gmail.com', 'fajar0987', NULL, NULL, NULL, NULL),
(6, 'Sinta Dewi', 'sinta.dewi@gmail.com', 'sinta$pass', NULL, NULL, NULL, NULL),
(7, 'Yoga Wirawan', 'yoga.w@gmail.com', 'wirawan88', NULL, NULL, NULL, NULL),
(8, 'Laila Khairunnisa', 'laila.nisa@yahoo.com', 'laila123', NULL, NULL, NULL, NULL),
(9, 'Aldi Firmansyah', 'aldi.firman@outlook.com', 'firm@123', NULL, NULL, NULL, NULL),
(11, 'diko', 'rvipe783@gmail.com', '$2y$12$cJ.zI5U19qlDZmUjKlpAeegNuLN2hoKGfseQj8NHt9Q9aJIZ7ugPm', 'wo7FBJ1OyxFPqlaYA45PZn54MjR9AtWpm2Dg0qmi.jpg', 3900, NULL, 'c0S_IYJhQMOIfcL82guvCE:APA91bFmdo17SD_OQHsh_m1f1VzQNodlKgEPR7ntPPBOp4xarl0h8qnVCfKQWvG8qR1iUm7HPdtGH050MK_bX4sX-pCddYLjvUPO8T98XMpamuA2ibokMH8'),
(12, 'Nia Ayu Permata', 'niapermata@email.com', '12345', NULL, NULL, NULL, NULL),
(14, 'Beryl', 'beryl@gmail.com', '$2y$12$4dUy1nFx/6TGd3.jHlgkEeHSl.YOiyR769ceP/QoTjrLKPhG8zfm2', NULL, NULL, NULL, NULL),
(16, 'Natsy', 'Natsy488@gmail.com', '$2y$12$RF4nYPYrYCHqVC55Uur6KeOrWiERfEB9f/Xns0fr2nAUcKF0QfT9K', '2RyelfmtQ20kGwC1do3D8MrWvYkp7ZuVz9iAqccB.jpg', 6427, 'dzHK3FbKW9eI7R1GVnUVLVYKzsePnT8ETSzsSO7x8oRItNkLYPrWo6e6eCiy', 'e8MnDabTTJmBNPZW3FOdSf:APA91bEvQ5R0VyGp0n3yvS3aF85SZjDevlEo84XFsZZC1bFfTBOCPzt9Pf9ujz4ICTZgQu1jUGoWzyfxtcKbeEoG1zrzCZtJbmdnY-Mw0IsHXN-3fnNKaPo'),
(17, 'Testing1', 'testing1@gmail.com', '$2y$12$yQQK/G3HmCU1x.aNEHq9Vucr1gS4E91xZFomhTAaaxVIol8wl/8S.', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penitip`
--

CREATE TABLE `penitip` (
  `id_penitip` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `saldo` float DEFAULT NULL,
  `poin_performa` int(11) DEFAULT NULL,
  `status_badge` tinyint(1) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `fcm_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penitip`
--

INSERT INTO `penitip` (`id_penitip`, `username`, `email`, `password`, `nik`, `foto`, `saldo`, `poin_performa`, `status_badge`, `remember_token`, `fcm_token`) VALUES
(1, 'Arman Pratama', 'arman@gmail.com', '$2y$12$mNOisdu9G/mBPICsUBeKT.WTIB9i5vIY1B7cbjjeAvmJ1m7WllTZ2', '3176010101010001', 'hYyxsIU5JkWZuroMUeuh6fX42zwzUk3SLs9Ca6Yb.png', 12000, 11, 0, NULL, NULL),
(2, 'Sari Melati', 'sari@gmail.com', 'sari456', '3276030303030003', '', NULL, 25, 0, NULL, NULL),
(3, 'Riko Maulana', 'riko@gmail.com', '$2y$12$b6Rz2fnYogZUQVHGz0UctuVr81Rpdn4CZTToQPeOD0MauIxmioQKS', '3276030303030005', '', 0, 16, 0, NULL, NULL),
(4, 'Tika Nurhasanah', 'tika@gmail.com', 'tika321', '3276030303030007', '', NULL, 4, 0, NULL, NULL),
(5, 'Hendra Kurniawan', 'hendra@gmail.com', 'hendra654', '3276030303030009', '', NULL, 9, 0, NULL, NULL),
(6, 'Bella Fitriani', 'bella@gmail.com', 'bella999', '3276030303030010', '', NULL, 8, 0, NULL, NULL),
(7, 'Dimas Rasyid', 'dimas@gmail.com', 'dimas852', '3276030303030103', '', NULL, 0, 0, NULL, NULL),
(8, 'Gina Novitasari', 'gina@gmail.com', 'gina753', '3276030303031103', '', NULL, 2, 0, NULL, NULL),
(9, 'Yusuf Alamsyah', 'yusuf@gmail.com', 'yusuf456', '3276030303032223', '', NULL, 1, 0, NULL, NULL),
(10, 'Anisa Rahmawati	', 'anisa@gmail.com', 'anisa000', '3276030202030003', '', NULL, 5, 0, NULL, NULL),
(11, 'hitori Gotoh', 'hitori@gmail.com', 'Bocchi@Rock', '3276030404030003', '', NULL, 250, 1, NULL, NULL),
(12, 'Samuel', 'samuel@gmail.com', 'Natsy@C', '3276050505030003', '', NULL, 0, 0, NULL, NULL),
(14, 'daniel', 'daniel@gmail.com', '$2y$12$/A45yn9tPxMzdsRTG3PudOF4guPowxm2ftMRBSF2Ag0aR7HaRa29e', '3276070707030003', 'TLqVKQZ7wxay7tehxpWGOJbYsbzZVVmTmGxVKH4S.jpg', 1111110, 11, 0, NULL, NULL),
(15, 'SenSen', 'sen2jr2@gmail.com', '$2y$12$KlVx1zrEfa3w00qPV/0aR.PSSZmcWDPopHHGNGEVFNrbVeMe3uy/u', '2222222', 'Mc21dcHeQeVaXQAZQofrkhJFGccvChGPoZflRjgV.jpg', 9375000, 20, 0, NULL, 'c0S_IYJhQMOIfcL82guvCE:APA91bFmdo17SD_OQHsh_m1f1VzQNodlKgEPR7ntPPBOp4xarl0h8qnVCfKQWvG8qR1iUm7HPdtGH050MK_bX4sX-pCddYLjvUPO8T98XMpamuA2ibokMH8'),
(16, 'Test', 'Test@gmail.com', '$2y$12$J9OrBhU6THvkbYVLFV05g.GFuNP3QrRVuub1kBVlg7Jnl9HM4RGb2', '1111111111111111', 'SBuNoR919A2Vyh1jOtL5fieFjVSxvylTd1P48cLK.jpg', NULL, 11, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penitipan`
--

CREATE TABLE `penitipan` (
  `id_penitipan` int(11) NOT NULL,
  `id_penitip` int(11) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `peg_id_pegawai` int(11) DEFAULT NULL,
  `tanggal_penitipan` date NOT NULL,
  `masa_penitipan` date NOT NULL,
  `batas_pengambilan` date NOT NULL,
  `status_ambil` varchar(255) DEFAULT NULL,
  `status_perpanjangan` tinyint(1) DEFAULT NULL,
  `tanggal_konfirmasi_pengambilan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penitipan`
--

INSERT INTO `penitipan` (`id_penitipan`, `id_penitip`, `id_pegawai`, `peg_id_pegawai`, `tanggal_penitipan`, `masa_penitipan`, `batas_pengambilan`, `status_ambil`, `status_perpanjangan`, `tanggal_konfirmasi_pengambilan`) VALUES
(1, 15, 5, NULL, '2025-07-01', '2026-07-25', '2026-08-01', NULL, NULL, NULL),
(2, 15, NULL, 7, '2025-04-03', '2025-07-02', '2025-07-09', NULL, NULL, NULL),
(3, 15, 6, NULL, '2025-04-04', '2025-07-03', '2025-07-10', NULL, NULL, NULL),
(4, 15, 6, NULL, '2025-03-02', '2025-07-20', '2025-07-27', NULL, NULL, NULL),
(5, 5, NULL, 7, '2025-04-07', '2025-05-07', '2025-05-14', NULL, NULL, NULL),
(6, 6, 6, NULL, '2025-04-09', '2025-08-09', '2025-09-16', NULL, NULL, NULL),
(7, 7, 5, NULL, '2025-04-10', '2025-05-10', '2025-05-17', NULL, NULL, NULL),
(8, 8, 6, NULL, '2025-04-13', '2025-05-13', '2025-05-20', NULL, NULL, NULL),
(9, 9, NULL, 8, '2025-04-15', '2025-05-15', '2025-05-22', NULL, NULL, NULL),
(10, 10, 5, NULL, '2025-04-17', '2025-09-17', '2025-09-24', NULL, NULL, NULL),
(11, 2, NULL, 7, '2025-04-09', '2025-08-09', '2025-08-16', NULL, NULL, NULL),
(12, 1, 5, NULL, '2025-04-11', '2025-05-11', '2025-05-18', NULL, NULL, NULL),
(13, 10, 1, NULL, '2025-03-27', '2025-09-27', '2025-10-04', NULL, NULL, NULL),
(14, 11, NULL, 8, '2025-03-28', '2025-08-28', '2025-09-05', NULL, NULL, NULL),
(15, 11, 6, NULL, '2025-03-03', '2025-08-03', '2025-09-10', NULL, NULL, NULL),
(16, 1, 5, NULL, '2025-03-01', '2025-04-01', '2025-04-08', NULL, NULL, NULL),
(17, 1, 5, NULL, '2025-03-01', '2025-04-01', '2025-04-08', NULL, NULL, NULL),
(18, 8, 6, NULL, '2025-03-02', '2025-04-02', '2025-04-09', NULL, NULL, NULL),
(19, 5, 6, NULL, '2025-03-02', '2025-04-02', '2025-04-09', NULL, NULL, NULL),
(20, 10, 5, NULL, '2025-03-03', '2025-04-10', '2025-04-10', NULL, NULL, NULL),
(21, 9, 6, NULL, '2025-03-03', '2025-04-03', '2025-04-10', NULL, NULL, NULL),
(22, 7, NULL, 8, '2025-03-04', '2025-09-04', '2025-09-11', NULL, NULL, NULL),
(23, 6, 5, NULL, '2025-03-05', '2025-04-05', '2025-04-12', NULL, NULL, NULL),
(24, 15, 6, NULL, '2025-06-02', '2025-08-01', '2025-08-08', NULL, NULL, '2025-06-03');

-- --------------------------------------------------------

--
-- Table structure for table `penitipanbarang`
--

CREATE TABLE `penitipanbarang` (
  `id_penitipan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penitipanbarang`
--

INSERT INTO `penitipanbarang` (`id_penitipan`, `id_barang`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(12, 12),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(23, 23),
(24, 26);

-- --------------------------------------------------------

--
-- Table structure for table `penukaranpoin`
--

CREATE TABLE `penukaranpoin` (
  `id_penukaran` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `id_merchandise` int(11) NOT NULL,
  `tanggal_penukaran` date NOT NULL,
  `jumlah_poin_terpakai` int(11) NOT NULL,
  `tanggal_pengambilan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penukaranpoin`
--

INSERT INTO `penukaranpoin` (`id_penukaran`, `id_pembeli`, `id_merchandise`, `tanggal_penukaran`, `jumlah_poin_terpakai`, `tanggal_pengambilan`) VALUES
(1, 1, 1, '2025-04-09', 100, '2025-04-10'),
(2, 2, 2, '2025-04-11', 100, '2025-04-12'),
(3, 3, 3, '2025-04-13', 250, '2025-04-14'),
(4, 4, 4, '2025-04-15', 250, '2025-04-16'),
(5, 5, 5, '2025-04-17', 500, '2025-04-18'),
(6, 6, 6, '2025-04-18', 500, '2025-04-19'),
(7, 7, 7, '2025-04-20', 500, '2025-04-21'),
(8, 8, 8, '2025-04-22', 1000, '2025-04-23'),
(9, 9, 9, '2025-04-25', 1000, '2025-04-26'),
(10, 16, 1, '2025-06-10', 100, '2025-06-10');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Pembeli', 16, 'mobile-token', '99610b5aa7449ae9f36909c6fb5d21f93e787a7f69c3c01e2a128a9ec1ac0517', '[\"*\"]', NULL, NULL, '2025-06-01 23:35:45', '2025-06-01 23:35:45'),
(2, 'App\\Models\\Pembeli', 16, 'mobile-token', '4fa05f41e92d7991518355a0de5b40e68730fedc201e8292ad5e145663bf60b8', '[\"*\"]', NULL, NULL, '2025-06-01 23:39:19', '2025-06-01 23:39:19'),
(3, 'App\\Models\\Pembeli', 16, 'mobile-token', '370bccf2268ceb2cfdf3e1bfe93a778d5994ef33b0ef68c1589a20b025a41e1d', '[\"*\"]', NULL, NULL, '2025-06-01 23:40:23', '2025-06-01 23:40:23'),
(4, 'App\\Models\\Pembeli', 16, 'mobile-token', '9a97f74de88e47518ed5bf1a41bfe1200666980cb13b7cbad01518e34630609f', '[\"*\"]', '2025-06-01 23:44:07', NULL, '2025-06-01 23:44:07', '2025-06-01 23:44:07'),
(5, 'App\\Models\\Pegawai', 9, 'mobile-token', '92556292278842bf537c910fe9d150117789ae6d2c04376f3d3d5475578c83f4', '[\"*\"]', '2025-06-01 23:52:23', NULL, '2025-06-01 23:52:23', '2025-06-01 23:52:23'),
(6, 'App\\Models\\Pegawai', 9, 'mobile-token', '58c15b15f5d6738f0e20656ec1f3af2fcf5aef0f078f7b58bb91f94393a02b15', '[\"*\"]', '2025-06-01 23:52:59', NULL, '2025-06-01 23:52:59', '2025-06-01 23:52:59'),
(7, 'App\\Models\\Pegawai', 9, 'mobile-token', '39078ee76fb85ade0bd3d060495a76e017384b35fcf4d4b9645e723d2a0eb4ae', '[\"*\"]', '2025-06-01 23:53:34', NULL, '2025-06-01 23:53:34', '2025-06-01 23:53:34'),
(8, 'App\\Models\\Pembeli', 16, 'mobile-token', '13625ff457bb6cbeac71771bb394a2ab38342f12201e1d7fca31538fd05c9ba2', '[\"*\"]', '2025-06-02 00:10:35', NULL, '2025-06-01 23:54:07', '2025-06-02 00:10:35'),
(9, 'App\\Models\\Pembeli', 16, 'mobile-token', '556d6ce0b6be3fd52fbb193a937e68db20469db6790635944196f94efddf8627', '[\"*\"]', '2025-06-02 00:21:02', NULL, '2025-06-02 00:10:47', '2025-06-02 00:21:02'),
(10, 'App\\Models\\Penitip', 15, 'mobile-token', '18d980e2364dd47323348bb1f5c445ada4d066e4fc93282e354068067b9b8ba5', '[\"*\"]', '2025-06-02 01:01:27', NULL, '2025-06-02 00:21:32', '2025-06-02 01:01:27'),
(11, 'App\\Models\\Penitip', 15, 'mobile-token', 'bf9d43f589ac2d1ae2cd5ce95de502f53cb11b35e455100137f39101b504ebed', '[\"*\"]', '2025-06-02 01:47:32', NULL, '2025-06-02 01:01:38', '2025-06-02 01:47:32'),
(12, 'App\\Models\\Penitip', 15, 'mobile-token', 'e22af9978deb8c51f4ce0870c1c93bbfbe3bb5cf80e6962b81bc6c38437c8edc', '[\"*\"]', NULL, NULL, '2025-06-02 23:10:05', '2025-06-02 23:10:05'),
(13, 'App\\Models\\Penitip', 15, 'mobile-token', 'af9d5826f32247151ceb4352cc645a683c9d63635bab000f8fd12aa77bd76206', '[\"*\"]', '2025-06-07 01:10:19', NULL, '2025-06-02 23:24:20', '2025-06-07 01:10:19'),
(14, 'App\\Models\\Penitip', 15, 'mobile-token', '3fcdefe5ceaa0ee67dac10fceae474229ee7fd1117c6d413e1fcabd106ffd60a', '[\"*\"]', '2025-06-07 08:44:08', NULL, '2025-06-07 01:19:11', '2025-06-07 08:44:08'),
(15, 'App\\Models\\Pegawai', 7, 'mobile-token', '939d1dfba7578864a0f93b45d2f7400f157187c74ec5a9ed049f9061298d0dc4', '[\"*\"]', NULL, NULL, '2025-06-07 17:30:59', '2025-06-07 17:30:59'),
(16, 'App\\Models\\Pegawai', 8, 'mobile-token', 'e827725b6003e79b00a3a94eae39096ceb54a2746118a59ef6c8ecd305551092', '[\"*\"]', '2025-06-07 17:33:10', NULL, '2025-06-07 17:32:56', '2025-06-07 17:33:10'),
(17, 'App\\Models\\Pegawai', 8, 'mobile-token', 'daedba04183dd0c896033ae6668bf102db84fa529fab041a8013a1392e5cbb87', '[\"*\"]', '2025-06-07 19:26:31', NULL, '2025-06-07 17:38:59', '2025-06-07 19:26:31'),
(18, 'App\\Models\\Pembeli', 11, 'mobile-token', 'a5532f2442113bc6e9ec51897e806ea32eabf07d25b008b8e25bea57e299c7df', '[\"*\"]', '2025-06-07 21:13:38', NULL, '2025-06-07 21:13:21', '2025-06-07 21:13:38'),
(19, 'App\\Models\\Pembeli', 11, 'mobile-token', '7e49eb5880680c555eb4eae61fa705d828d4236c73228f036f321326ca40063b', '[\"*\"]', '2025-06-07 21:13:41', NULL, '2025-06-07 21:13:28', '2025-06-07 21:13:41'),
(20, 'App\\Models\\Pembeli', 11, 'mobile-token', '50af0ab69bbcf31a8a92760daf8aac7e07e325f6d9707d26434a465e2240712c', '[\"*\"]', '2025-06-07 21:13:43', NULL, '2025-06-07 21:13:32', '2025-06-07 21:13:43'),
(21, 'App\\Models\\Pembeli', 11, 'mobile-token', '03c05c99ce2d1b4b5302fc800603a24d7ddce679f11571be8209b9aa0574f9cb', '[\"*\"]', '2025-06-07 23:59:03', NULL, '2025-06-07 21:13:34', '2025-06-07 23:59:03'),
(22, 'App\\Models\\Pembeli', 11, 'mobile-token', '25db8d3bce391538bace793ed4083b6c4579adafa449becf9bb4433fb0f5fd2d', '[\"*\"]', '2025-06-09 21:23:00', NULL, '2025-06-07 23:59:59', '2025-06-09 21:23:00'),
(23, 'App\\Models\\Pembeli', 16, 'mobile-token', '7aea6eed24cc4571c6535806386b33acdf73097cdcda82783ddbd4d7833c533f', '[\"*\"]', '2025-06-08 18:55:57', NULL, '2025-06-08 18:39:02', '2025-06-08 18:55:57'),
(24, 'App\\Models\\Pegawai', 9, 'mobile-token', 'd9904d5cf797b7aa8653e2b662c69d625cc1380fa2fef001cf6c2fd7fa9ffb12', '[\"*\"]', '2025-06-08 19:09:13', NULL, '2025-06-08 18:56:09', '2025-06-08 19:09:13'),
(25, 'App\\Models\\Pegawai', 7, 'mobile-token', '4f2fee954bf3144c062a80f964361c00c152ab742790c5024b3f21ae2314c7f1', '[\"*\"]', '2025-06-08 19:16:11', NULL, '2025-06-08 19:10:41', '2025-06-08 19:16:11'),
(26, 'App\\Models\\Pegawai', 7, 'mobile-token', '8afef1f18327b470de66c1576478de208089f4ac6eb6212f3a69b763dd77f8f2', '[\"*\"]', '2025-06-08 19:22:57', NULL, '2025-06-08 19:17:50', '2025-06-08 19:22:57'),
(27, 'App\\Models\\Pegawai', 7, 'mobile-token', '1dbf78e659715d38f5a44dce00a9bbf8a164402691896cea123ca27290474a5e', '[\"*\"]', '2025-06-08 19:43:17', NULL, '2025-06-08 19:23:37', '2025-06-08 19:43:17'),
(28, 'App\\Models\\Pegawai', 9, 'mobile-token', '9c1c55a93a8d6600cb99e54e1d5bd4c452e2f3f0aaa07c5a22394849212a4b36', '[\"*\"]', '2025-06-08 19:44:59', NULL, '2025-06-08 19:43:38', '2025-06-08 19:44:59'),
(29, 'App\\Models\\Pegawai', 7, 'mobile-token', 'a918960ba2c81d693c236022f29b3f81685f7586a5525882a3ac2db67de2d54f', '[\"*\"]', '2025-06-08 19:58:29', NULL, '2025-06-08 19:45:22', '2025-06-08 19:58:29'),
(30, 'App\\Models\\Pegawai', 7, 'mobile-token', 'e4f45f5dd1770b7c79f9078040e9544d95228f75c7d93ba74d99b3fbd426b90b', '[\"*\"]', '2025-06-08 19:59:03', NULL, '2025-06-08 19:59:02', '2025-06-08 19:59:03'),
(31, 'App\\Models\\Pegawai', 7, 'mobile-token', '9b403380111cedff29dd060a8a747069dec42daef9903bab59041743317ee0d4', '[\"*\"]', '2025-06-08 21:21:13', NULL, '2025-06-08 20:10:59', '2025-06-08 21:21:13'),
(32, 'App\\Models\\Pegawai', 7, 'mobile-token', 'a58aff1b5a3852a9e689103649051553afead9abe937ff777235f2a8d594adde', '[\"*\"]', '2025-06-08 21:21:36', NULL, '2025-06-08 21:21:35', '2025-06-08 21:21:36'),
(33, 'App\\Models\\Pegawai', 7, 'mobile-token', '2355b70b60ed99217206c8368e1e154873ebd43700a262c9f0d8445f0875d4f5', '[\"*\"]', '2025-06-08 21:24:28', NULL, '2025-06-08 21:23:03', '2025-06-08 21:24:28'),
(34, 'App\\Models\\Pegawai', 7, 'mobile-token', 'ef6c3f84386d5dc2633ac64e98cd5685f67e7c880a6e3a597795bf28cf035d1f', '[\"*\"]', '2025-06-08 21:29:28', NULL, '2025-06-08 21:24:43', '2025-06-08 21:29:28'),
(35, 'App\\Models\\Pegawai', 7, 'mobile-token', '00d9b519b3f5724b746489d969647cb96ddeebbb9d2f28b60a773d6b56283a3f', '[\"*\"]', '2025-06-08 21:30:51', NULL, '2025-06-08 21:29:51', '2025-06-08 21:30:51'),
(36, 'App\\Models\\Pegawai', 7, 'mobile-token', '05417d7805503ccf0c8e1b8b4eb854b65d4486a9161f8b7f0b4863d93ad5364c', '[\"*\"]', '2025-06-08 21:36:16', NULL, '2025-06-08 21:31:10', '2025-06-08 21:36:16'),
(37, 'App\\Models\\Pegawai', 7, 'mobile-token', 'cc8e43d08408ba048241e964b1713fcce8ea887ca67e0842b1728e9d11d90ec9', '[\"*\"]', '2025-06-08 21:37:53', NULL, '2025-06-08 21:36:31', '2025-06-08 21:37:53'),
(38, 'App\\Models\\Pegawai', 7, 'mobile-token', '1b0e606d04dbbc4d9b55afb0c51a3d829fd6923f0db636458dd0eb76cc0efedc', '[\"*\"]', '2025-06-08 21:40:11', NULL, '2025-06-08 21:38:07', '2025-06-08 21:40:11'),
(39, 'App\\Models\\Pegawai', 7, 'mobile-token', 'c48ec0c84cb7a7128001f8baec2b498271bf445b67d819711f92c3e929016565', '[\"*\"]', '2025-06-08 21:42:23', NULL, '2025-06-08 21:40:33', '2025-06-08 21:42:23'),
(40, 'App\\Models\\Pegawai', 7, 'mobile-token', '6e190e604cf953853a7003c4d2bf477b9efe42b534b6d4fc66e5b6bc7d1ce77c', '[\"*\"]', '2025-06-08 21:42:47', NULL, '2025-06-08 21:42:46', '2025-06-08 21:42:47'),
(41, 'App\\Models\\Pegawai', 7, 'mobile-token', '17fa50c25e99dc765b9d40ea34541d28763ab4a61c38cef78ff4b863362162e8', '[\"*\"]', '2025-06-08 22:17:30', NULL, '2025-06-08 22:17:07', '2025-06-08 22:17:30'),
(42, 'App\\Models\\Pegawai', 7, 'mobile-token', 'cd029f294eacefaae542ff0eef26239562dff627c12a78e752cfda8261c6849e', '[\"*\"]', '2025-06-08 22:28:52', NULL, '2025-06-08 22:23:04', '2025-06-08 22:28:52'),
(43, 'App\\Models\\Pegawai', 7, 'mobile-token', '89263135838c522ac78cc2cab8d6c1b8ba7648c6f188f6281fae318d7a8e08bf', '[\"*\"]', '2025-06-08 22:58:20', NULL, '2025-06-08 22:29:16', '2025-06-08 22:58:20'),
(44, 'App\\Models\\Pegawai', 7, 'mobile-token', 'fe64f4877d9dca4c3431f43926f7070d8391fa84c0dbc8a285c2e0a282d32039', '[\"*\"]', '2025-06-09 00:29:49', NULL, '2025-06-08 22:58:40', '2025-06-09 00:29:49'),
(45, 'App\\Models\\Pegawai', 7, 'mobile-token', 'c45abd16afe246e2e1d4ecd607b46eedd530a60a2b452075b34818cc91a7ae97', '[\"*\"]', '2025-06-09 00:55:06', NULL, '2025-06-09 00:30:07', '2025-06-09 00:55:06'),
(46, 'App\\Models\\Pembeli', 16, 'mobile-token', '7c81a86ffe58f53d80a6fb05a59e7800d512958309636d392a0251541aa29f9f', '[\"*\"]', '2025-06-09 01:09:38', NULL, '2025-06-09 01:02:57', '2025-06-09 01:09:38'),
(47, 'App\\Models\\Pembeli', 16, 'mobile-token', '76647c107f0ff9f8bc2e5d576a5c4e586df264bfd5dae9e1e029500d16fe85f3', '[\"*\"]', '2025-06-09 01:24:01', NULL, '2025-06-09 01:09:49', '2025-06-09 01:24:01'),
(48, 'App\\Models\\Pegawai', 7, 'mobile-token', '067bb1c583cc2500e0c9fd6bb64cf35f2713626305f3150bdc92ba4bca853379', '[\"*\"]', '2025-06-09 01:25:00', NULL, '2025-06-09 01:24:24', '2025-06-09 01:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `requestdonasi`
--

CREATE TABLE `requestdonasi` (
  `id_request` int(11) NOT NULL,
  `id_organisasi` int(11) NOT NULL,
  `tanggal_request` date NOT NULL,
  `status_request` tinyint(1) NOT NULL,
  `deskripsi_request` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requestdonasi`
--

INSERT INTO `requestdonasi` (`id_request`, `id_organisasi`, `tanggal_request`, `status_request`, `deskripsi_request`) VALUES
(1, 1, '2025-04-01', 1, 'Memohon bantuan tas sekolah untuk anak yatim.'),
(2, 2, '2025-04-12', 0, 'Permintaan tempat tidur untuk warga di pengungsian telah dipenuhi.'),
(3, 3, '2025-04-13', 1, 'Donasi pakaian bekas layak pakai untuk komunitas lansia telah diterima.'),
(4, 4, '2025-04-15', 0, 'Mengajukan permintaan mainan edukatif untuk PAUD gratis.'),
(5, 5, '2025-04-17', 1, 'Donasi alat berkebun seperti cangkup dan sekop untuk petani'),
(6, 6, '2025-04-23', 0, 'Permintaan alat dapur untuk dapur umum masih menunggu donatur.'),
(7, 7, '2025-04-20', 1, 'Bantuan popok bayi.'),
(8, 8, '2025-04-08', 1, 'Mengajukan donasi rak buku untuk perpustakaan desa.'),
(9, 9, '2025-04-07', 0, 'Permintaan pakaian anak-anak untuk korban kebakaran sudah terpenuhi.'),
(10, 10, '2025-04-05', 1, 'Permintaan laptop bekas untuk pelatihan kerja remaja belum mendapat respon.'),
(11, 11, '2025-04-02', 1, 'Permintaan donasi buku-buku edukasi anak'),
(12, 2, '2025-04-07', 1, 'Donasi perlengkapan bayi seperti botol susu untuk anak yatim piatu'),
(13, 12, '2025-04-05', 1, 'Permintaan donasi alat tulis untuk sekolah yang membutuhkan'),
(14, 13, '2025-04-17', 1, 'Mainan anak-anak untuk panti asuhan'),
(15, 16, '2025-05-12', 0, '2222222'),
(16, 16, '2025-05-23', 0, 'testing2');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('FfAfvZUa0asBnwK2M5Des6YUOYYo6IQX6uusiriW', 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQ0oyWFBZR3ByaU9vRTJwcmhCcDJodkp3Z09QYzM5R2thdXNkWGpraSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lUGVuaXRpcCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTQ6ImxvZ2luX3BlZ2F3YWlfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoicm9sZSI7czo3OiJwZW5pdGlwIjtzOjU0OiJsb2dpbl9wZW5pdGlwXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MjoiMTUiO30=', 1750419239);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `id_alamat` int(11) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `harga_total_barang` float NOT NULL,
  `status_transaksi` varchar(255) DEFAULT NULL,
  `tanggal_pengambilan` date DEFAULT NULL,
  `tanggal_lunas` date DEFAULT NULL,
  `opsi_pengiriman` tinyint(1) NOT NULL DEFAULT 1,
  `jadwal_pengiriman` date DEFAULT NULL,
  `potongan_harga` float NOT NULL,
  `harga_ongkir` float DEFAULT NULL,
  `poin_pembeli` int(11) NOT NULL,
  `poin_spent` int(11) DEFAULT NULL,
  `bukti_pembayaran` text DEFAULT NULL,
  `nomor_transaksi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_pembeli`, `id_alamat`, `id_pegawai`, `tanggal_transaksi`, `harga_total_barang`, `status_transaksi`, `tanggal_pengambilan`, `tanggal_lunas`, `opsi_pengiriman`, `jadwal_pengiriman`, `potongan_harga`, `harga_ongkir`, `poin_pembeli`, `poin_spent`, `bukti_pembayaran`, `nomor_transaksi`, `created_at`, `updated_at`) VALUES
(1, 3, 11, NULL, '2025-01-15', 250000, 'Disiapkan', NULL, '2025-01-16', 0, NULL, 0, 0, 25, NULL, NULL, '2025.06.01', NULL, '2025-06-03 03:18:27'),
(2, 7, 8, 9, '2025-01-22', 175000, 'Selesai', NULL, '2025-01-23', 1, '2025-06-09', 15000, 20000, 17, NULL, NULL, '2025.06.02', NULL, '2025-06-08 19:43:45'),
(3, 2, 10, 9, '2025-02-02', 420000, 'Disiapkan', NULL, '2025-02-02', 1, NULL, 20000, 30000, 42, NULL, NULL, '2025.06.03', NULL, NULL),
(4, 9, 3, NULL, '2025-02-11', 99000, 'Selesai', NULL, '2025-02-11', 0, NULL, 0, 0, 9, NULL, NULL, '2025.06.04', NULL, '2025-06-03 03:17:34'),
(5, 5, 6, 10, '2025-02-20', 310000, 'Disiapkan ', NULL, '2025-02-22', 1, NULL, 10000, 15000, 31, NULL, NULL, '2025.06.05', NULL, NULL),
(6, 1, 1, 9, '2025-03-05', 600000, 'Sedang Dikirim', NULL, '2025-03-05', 1, '2025-06-03', 30000, 40000, 60, NULL, NULL, '2025.06.06', NULL, '2025-06-02 10:47:19'),
(7, 4, 5, NULL, '2025-03-18', 110000, 'Disiapkan', NULL, '2025-03-18', 0, NULL, 0, 0, 11, NULL, NULL, '2025.06.07', NULL, '2025-06-02 10:33:21'),
(8, 6, 9, 10, '2025-03-22', 250000, 'Sedang Dikirim', NULL, '2025-03-23', 1, '2025-06-03', 10000, 20000, 25, NULL, '', '2025.06.08', NULL, '2025-06-02 18:06:40'),
(9, 8, 7, 9, '2025-04-01', 720000, 'Disiapkan', NULL, '2025-04-01', 1, NULL, 50000, 30000, 72, NULL, NULL, '2025.06.09', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alamat`
--
ALTER TABLE `alamat`
  ADD PRIMARY KEY (`id_alamat`),
  ADD KEY `fk_alamat_pembeli` (`id_pembeli`);

--
-- Indexes for table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`id_badge`),
  ADD KEY `fk_badge_penitip` (`id_penitip`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `fk_barang_transaksi` (`id_transaksi`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `fk_cart_pembeli` (`id_pembeli`),
  ADD KEY `fk_cart_barang` (`id_barang`);

--
-- Indexes for table `diskusi`
--
ALTER TABLE `diskusi`
  ADD PRIMARY KEY (`id_diskusi`),
  ADD KEY `fk_diskusi_pembeli` (`id_pembeli`),
  ADD KEY `fk_diskusi_barang` (`id_barang`),
  ADD KEY `fk_diskusi_pegawai` (`id_pegawai`);

--
-- Indexes for table `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id_barang`,`id_request`),
  ADD KEY `fk_donasi_request` (`id_request`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `komisi`
--
ALTER TABLE `komisi`
  ADD PRIMARY KEY (`id_transaksi`,`id_penitip`,`id_pegawai`) USING BTREE,
  ADD KEY `fk_komisi_penitip` (`id_penitip`),
  ADD KEY `fk_komisi_pegawai` (`id_pegawai`);

--
-- Indexes for table `merchandise`
--
ALTER TABLE `merchandise`
  ADD PRIMARY KEY (`id_merchandise`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisasi`
--
ALTER TABLE `organisasi`
  ADD PRIMARY KEY (`id_organisasi`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD KEY `password_reset_tokens_email_index` (`email`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD KEY `fk_pegawai_jabatan` (`id_jabatan`);

--
-- Indexes for table `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indexes for table `penitip`
--
ALTER TABLE `penitip`
  ADD PRIMARY KEY (`id_penitip`);

--
-- Indexes for table `penitipan`
--
ALTER TABLE `penitipan`
  ADD PRIMARY KEY (`id_penitipan`),
  ADD KEY `fk_penitipan_penitip` (`id_penitip`),
  ADD KEY `fk_penitipan_pegawai` (`id_pegawai`),
  ADD KEY `fk_penitipan_hunter` (`peg_id_pegawai`);

--
-- Indexes for table `penitipanbarang`
--
ALTER TABLE `penitipanbarang`
  ADD PRIMARY KEY (`id_penitipan`,`id_barang`),
  ADD KEY `fk_penitipanbarang_barang` (`id_barang`);

--
-- Indexes for table `penukaranpoin`
--
ALTER TABLE `penukaranpoin`
  ADD PRIMARY KEY (`id_penukaran`),
  ADD KEY `fk_penukaranpoin_merchandise` (`id_merchandise`),
  ADD KEY `fk_penukaranpoin_pembeli` (`id_pembeli`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `requestdonasi`
--
ALTER TABLE `requestdonasi`
  ADD PRIMARY KEY (`id_request`),
  ADD KEY `id_requestdonasi_organisasi` (`id_organisasi`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_transaksi_pembeli` (`id_pembeli`),
  ADD KEY `fk_transkasi_alamat` (`id_alamat`),
  ADD KEY `fk_transaksi_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alamat`
--
ALTER TABLE `alamat`
  MODIFY `id_alamat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `badge`
--
ALTER TABLE `badge`
  MODIFY `id_badge` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `diskusi`
--
ALTER TABLE `diskusi`
  MODIFY `id_diskusi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `merchandise`
--
ALTER TABLE `merchandise`
  MODIFY `id_merchandise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organisasi`
--
ALTER TABLE `organisasi`
  MODIFY `id_organisasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pembeli`
--
ALTER TABLE `pembeli`
  MODIFY `id_pembeli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `penitip`
--
ALTER TABLE `penitip`
  MODIFY `id_penitip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `penitipan`
--
ALTER TABLE `penitipan`
  MODIFY `id_penitipan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `penukaranpoin`
--
ALTER TABLE `penukaranpoin`
  MODIFY `id_penukaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `requestdonasi`
--
ALTER TABLE `requestdonasi`
  MODIFY `id_request` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alamat`
--
ALTER TABLE `alamat`
  ADD CONSTRAINT `fk_alamat_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `badge`
--
ALTER TABLE `badge`
  ADD CONSTRAINT `fk_badge_penitip` FOREIGN KEY (`id_penitip`) REFERENCES `penitip` (`id_penitip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diskusi`
--
ALTER TABLE `diskusi`
  ADD CONSTRAINT `fk_diskusi_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_diskusi_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_diskusi_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `fk_donasi_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donasi_request` FOREIGN KEY (`id_request`) REFERENCES `requestdonasi` (`id_request`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `komisi`
--
ALTER TABLE `komisi`
  ADD CONSTRAINT `fk_komisi_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_komisi_penitip` FOREIGN KEY (`id_penitip`) REFERENCES `penitip` (`id_penitip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_komisi_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `fk_pegawai_jabatan` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penitipan`
--
ALTER TABLE `penitipan`
  ADD CONSTRAINT `fk_penitipan_hunter` FOREIGN KEY (`peg_id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penitipan_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penitipan_penitip` FOREIGN KEY (`id_penitip`) REFERENCES `penitip` (`id_penitip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penitipanbarang`
--
ALTER TABLE `penitipanbarang`
  ADD CONSTRAINT `fk_penitipanbarang_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penitipanbarang_penitipan` FOREIGN KEY (`id_penitipan`) REFERENCES `penitipan` (`id_penitipan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penukaranpoin`
--
ALTER TABLE `penukaranpoin`
  ADD CONSTRAINT `fk_penukaranpoin_merchandise` FOREIGN KEY (`id_merchandise`) REFERENCES `merchandise` (`id_merchandise`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penukaranpoin_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requestdonasi`
--
ALTER TABLE `requestdonasi`
  ADD CONSTRAINT `id_requestdonasi_organisasi` FOREIGN KEY (`id_organisasi`) REFERENCES `organisasi` (`id_organisasi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transkasi_alamat` FOREIGN KEY (`id_alamat`) REFERENCES `alamat` (`id_alamat`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
