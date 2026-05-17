# PROJECT CONTEXT & AI RULES

## 1. Project Overview
* **Nama Project:** Sistem Pendukung Keputusan Kelayakan Pemberian Kredit Anggota
* **Studi Kasus:** Koperasi Konsumen Serba Usaha Salimah Sejahtera
* **Metode SPK:** MOORA (Multi-Objective Optimization on the basis of Ratio Analysis)
* **Tujuan:** Menentukan peringkat dan kelayakan anggota yang mengajukan kredit berdasarkan bobot dan kriteria yang ditentukan.

## 2. Tech Stack & Constraints (STRICT RULES)
Anda HARUS mematuhi batasan teknologi di bawah ini. JANGAN gunakan library atau framework di luar daftar ini:
* **Bahasa Pemrograman:** PHP Native (Tanpa Framework seperti Laravel/CodeIgniter).
* **Styling & UI:** Vanilla CSS dan Vanilla JavaScript, serta library datatables.net untuk tabel data anggota, kriteria, data alternatif, dan hasil perangkingan. JANGAN gunakan Bootstrap, Tailwind atau preprocessor seperti SASS.
* **Database:** MySQL (Menggunakan ekstensi PHP `mysqli`).
* **Arsitektur:** Prosedural.

> **PERINGATAN UNTUK AI:** Jangan pernah menyarankan instalasi package via Composer atau npm kecuali diminta secara eksplisit. Tulis kode PHP yang bersih, aman dari SQL Injection (gunakan Prepared Statements), dan mudah dipahami programmer pemula.

## 3. Alur Algoritma MOORA di Website Ini
Setiap kali membuat atau memodifikasi fungsi perhitungan SPK, pastikan langkah-langkah MOORA berikut terpenuhi:
1. **Matriks Keputusan ($X$):** Input alternatif (anggota) dan nilai kriterianya.
2. **Normalisasi Matriks:** Membagi setiap nilai dengan akar jumlah kuadrat nilai per kriteria.
   $$r_{ij} = \frac{x_{ij}}{\sqrt{\sum_{i=1}^{m} x_{ij}^2}}$$
3. **Optimalisasi Matriks (Bobot):** Mengalikan matriks yang dinormalisasi dengan bobot kriteria ($w_j$).
4. **Menghitung Nilai Assessment ($Y_i$):** Mengurangi total kriteria *benefit* dengan total kriteria *cost*.
   $$Y_i = \sum_{j=1}^{g} w_j r_{ij} - \sum_{j=g+1}^{n} w_j r_{ij}$$
5. **Ranking:** Mengurutkan hasil $Y_i$ dari yang terbesar ke terkecil untuk menentukan kelayakan kredit.

## 4. Kriteria & Bobot SPK
* **C1: Masa Keanggotaan** (Benefit) - Bobot: [Misal: 0.30]
* **C2: Jumlah Pendapatan** (Benefit) - Bobot: [Misal: 0.15]
* **C3: Jumlah Pengeluaran** (Cost) - Bobot: [Misal: 0.15]
* **C4: Jumlah Simpanan (Wajib & Sukarela)** (Benefit) - Bobot: [Misal: 0.40]

## 5. Database Schema Reference

### Tabel: `admin`
* `id_admin` (int, PK, AI)
* `username` (varchar)
* `password` (varchar)
* `nama` (varchar)

### Tabel: `alternatif`
* `id_alternatif` (int, PK, AI)
* `id_anggota` (int)
* `kode_kriteria` (varchar)
* `nilai_alternatif` (int)

### Tabel: `anggota`
* `id_anggota` (int, PK, AI)
* `nama` (varchar)
* `no_telp` (varchar)

### Tabel: `kriteria`
* `kode_kriteria` (varchar, PK)
* `nama_kriteria` (varchar)
* `bobot` (float)
* `jenis` (varchar)

### Tabel: `sub_kriteria`
* `id_sub` (int, PK, AI)
* `kode_kriteria` (varchar)
* `nama_sub` (varchar)
* `nilai` (int)

### Tabel: `ranking`
* `id_ranking` (int, PK, AI)
* `id_alternatif` (int)
* `nilai_optimasi` (float)
* `ranking` (int)
* `tgl_hitung` (date)

## 6. Coding Style Preference
* Gunakan bahasa Indonesia untuk komentar kode (code comments).
* Tulis penamaan variabel menggunakan `snake_case` (contoh: `$nilai_moora`, `$kriteria_benefit`).
* Setiap file koneksi database harus menggunakan file `db_connect.php` yang sudah ada.