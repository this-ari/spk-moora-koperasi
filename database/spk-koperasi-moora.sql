-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Bulan Mei 2026 pada 13.47
-- Versi server: 10.4.6-MariaDB
-- Versi PHP: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk-koperasi-moora`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama`) VALUES
(1, 'admin', 'admin', 'Administrator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `kode_kriteria` varchar(2) NOT NULL,
  `nilai_alternatif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `id_anggota`, `kode_kriteria`, `nilai_alternatif`) VALUES
(1, 1, 'C1', 4),
(2, 1, 'C2', 2),
(3, 1, 'C3', 3),
(4, 1, 'C4', 1),
(5, 2, 'C1', 5),
(6, 2, 'C2', 2),
(7, 2, 'C3', 5),
(8, 2, 'C4', 5),
(9, 3, 'C1', 2),
(10, 3, 'C2', 4),
(11, 3, 'C3', 4),
(12, 3, 'C4', 2),
(13, 4, 'C1', 4),
(14, 4, 'C2', 2),
(15, 4, 'C3', 5),
(16, 4, 'C4', 2),
(17, 5, 'C1', 4),
(18, 5, 'C2', 2),
(19, 5, 'C3', 5),
(20, 5, 'C4', 2),
(21, 6, 'C1', 5),
(22, 6, 'C2', 4),
(23, 6, 'C3', 4),
(24, 6, 'C4', 3),
(25, 7, 'C1', 2),
(26, 7, 'C2', 4),
(27, 7, 'C3', 3),
(28, 7, 'C4', 5),
(29, 8, 'C1', 2),
(30, 8, 'C2', 1),
(31, 8, 'C3', 3),
(32, 8, 'C4', 1),
(33, 9, 'C1', 4),
(34, 9, 'C2', 5),
(35, 9, 'C3', 4),
(36, 9, 'C4', 4),
(37, 10, 'C1', 3),
(38, 10, 'C2', 5),
(39, 10, 'C3', 3),
(40, 10, 'C4', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_telp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama`, `no_telp`) VALUES
(1, 'Sri Wahyuni', '081234567891'),
(2, 'Siti Aminah', '081398765432'),
(3, 'Endang Susilowati', '085611223344'),
(4, 'Retno Lestari', '087855667788'),
(5, 'Ika Nurjanah', '081900112233'),
(6, 'Dewi Kartika', '082144556677'),
(7, 'Nining Sunarni', '085288990011'),
(8, 'Titik Puspa', '081122334455'),
(9, 'Yati Sumiati', '089677889900'),
(10, 'Hariyati', '081355556666');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `kode_kriteria` varchar(2) NOT NULL,
  `nama_kriteria` varchar(50) NOT NULL,
  `bobot` float NOT NULL,
  `jenis` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`kode_kriteria`, `nama_kriteria`, `bobot`, `jenis`) VALUES
('C1', 'Masa Keanggotaan', 0.3, 'Benefit'),
('C2', 'Pendapatan Perbulan', 0.15, 'Benefit'),
('C3', 'Pengeluaran Perbulan', 0.15, 'Cost'),
('C4', 'Jumlah Simpanan (Wajib & Sukarela)', 0.4, 'Benefit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ranking`
--

CREATE TABLE `ranking` (
  `id_ranking` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `ranking` int(11) NOT NULL,
  `nilai_optimasi` float NOT NULL,
  `tgl_hitung` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub` int(11) NOT NULL,
  `kode_kriteria` varchar(2) NOT NULL,
  `nama_sub` varchar(50) NOT NULL,
  `nilai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub`, `kode_kriteria`, `nama_sub`, `nilai`) VALUES
(26, 'C2', 'Lebih dari 7jt', 5),
(27, 'C2', '5jt - 7jt', 4),
(28, 'C2', '4jt - 4,999jt', 3),
(29, 'C2', '3jt - 3,999jt', 2),
(30, 'C2', 'Kurang dari 3jt', 1),
(46, 'C4', 'Lebih dari 8jt', 5),
(47, 'C4', '5jt - 8jt', 4),
(48, 'C4', '3jt - 4,999jt', 3),
(49, 'C4', '1,5jt - 2,999jt', 2),
(50, 'C4', 'kurang dari 1,5jt', 1),
(51, 'C1', 'Lebih dari 3 Tahun', 5),
(52, 'C1', '2 - 3 Tahun', 4),
(53, 'C1', '1 - 2 Tahun', 3),
(54, 'C1', '6 Bulan - 1 Tahun', 2),
(55, 'C1', 'Kurang dari 6 Bulan', 1),
(56, 'C3', 'Kurang dari 3jt', 5),
(57, 'C3', '3jt - 3,999jt', 4),
(58, 'C3', '4jt - 4,999jt', 3),
(59, 'C3', '5jt - 6jt', 2),
(60, 'C3', 'Lebih dari 6jt', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`kode_kriteria`);

--
-- Indeks untuk tabel `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id_ranking`);

--
-- Indeks untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `ranking`
--
ALTER TABLE `ranking`
  MODIFY `id_ranking` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
