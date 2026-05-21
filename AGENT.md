# PROJECT CONTEXT & AI RULES

## 1. Project Overview

- **Nama Project:** Sistem Pendukung Keputusan Kelayakan Pemberian Kredit Anggota
- **Studi Kasus:** Koperasi Konsumen Serba Usaha Salimah Sejahtera
- **Metode SPK:** MOORA (Multi-Objective Optimization on the basis of Ratio Analysis)
- **Tujuan:** Menentukan peringkat dan kelayakan anggota yang mengajukan kredit berdasarkan bobot dan kriteria yang ditentukan.

## 2. Tech Stack & Constraints (STRICT RULES)

Anda HARUS mematuhi batasan teknologi di bawah ini. JANGAN gunakan library atau framework di luar daftar ini:

- **Bahasa Pemrograman:** PHP Native (Tanpa Framework seperti Laravel/CodeIgniter).
- **Styling & UI:** Vanilla CSS dan Vanilla JavaScript, serta library datatables.net untuk tabel data anggota, kriteria, data alternatif, dan hasil perangkingan. JANGAN gunakan Bootstrap, Tailwind atau preprocessor seperti SASS.
- **Database:** MySQL (Menggunakan ekstensi PHP `mysqli`).
- **Arsitektur:** Prosedural.

> **PERINGATAN UNTUK AI:** Jangan pernah menyarankan instalasi package via Composer atau npm kecuali diminta secara eksplisit. Tulis kode PHP yang bersih, aman dari SQL Injection (gunakan Prepared Statements), dan mudah dipahami programmer pemula.

## 3. Alur Algoritma MOORA & Aturan Output Website

Setiap kali membuat atau memodifikasi fungsi perhitungan SPK, AI harus memastikan rumus MOORA tetap dihitung sepenuhnya secara internal di backend, namun wajib mematuhi aturan tampilan dan penyimpanan berikut:

### A. Langkah Perhitungan Internal (Backend)

1. **Matriks Keputusan ($X$):** Input alternatif (anggota) dan nilai kriterianya.
2. **Normalisasi Matriks:** Membagi setiap nilai dengan akar jumlah kuadrat nilai per kriteria.
   $$r_{ij} = \frac{x_{ij}}{\sqrt{\sum_{i=1}^{m} x_{ij}^2}}$$
3. **Optimalisasi Matriks (Bobot):** Mengalikan matriks yang dinormalisasi dengan bobot kriteria ($w_j$).
4. **Menghitung Nilai Assessment ($Y_i$):** Mengurangi total kriteria _benefit_ dengan total kriteria _cost_.
   $$Y_i = \sum_{j=1}^{g} w_j r_{ij} - \sum_{j=g+1}^{n} w_j r_{ij}$$
5. **Ranking:** Mengurutkan hasil $Y_i$ dari yang terbesar ke terkecil untuk menentukan kelayakan kredit.

### B. Aturan Tampilan & Penyimpanan (STRICT RULES)

- **Aturan Tampilan Halaman `hasil_perangkingan.php`:** AI hanya boleh menampilkan tabel hasil akhir ranking saja (Nama Anggota/Alternatif, Nilai Optimasi/Assessment, dan Posisi Peringkat) menggunakan library `datatables.net`. **JANGAN** menampilkan tabel langkah perantara (seperti Matriks Keputusan, Matriks Normalisasi, atau Matriks Optimalisasi) di halaman ini agar tampilan tetap bersih.
- **Aturan Penyimpanan Database:** Setiap kali proses perhitungan perangkingan MOORA dijalankan, hasil akhir dari urutan peringkat tersebut harus langsung dimasukkan (di-insert) ke dalam tabel `ranking` di database. Proses ini wajib menggunakan _Prepared Statements_ (`mysqli`) untuk keamanan.

## 4. Aturan Kriteria & Bobot SPK (Dinamis dari Database)

- **DILARANG HARDCODE:** AI sama sekali tidak boleh menuliskan kode kriteria, nama kriteria, bobot, maupun jenis kriteria secara statis (_hardcode_) di dalam script PHP perhitungan.
- **Pengambilan Data Dinamis:** Semua data mengenai kode kriteria, bobot ($w_j$), dan jenis (`benefit` atau `cost`) wajib diambil langsung dari tabel `kriteria` menggunakan query MySQL (`mysqli`) setiap kali perhitungan MOORA dieksekusi.
- **Logika Benefit/Cost Otomatis:** Pemisahan kriteria untuk penjumlahan nilai _benefit_ dan pengurangan nilai _cost_ pada Langkah 4 (Menghitung Nilai Assessment $Y_i$) harus ditentukan secara otomatis berdasarkan kolom `jenis` yang ada pada baris data tabel `kriteria`.

## 5. Database Schema Reference

### Tabel: `admin`

- `id_admin` (int, PK, AI)
- `username` (varchar)
- `password` (varchar)
- `nama` (varchar)

### Tabel: `alternatif`

- `id_alternatif` (int, PK, AI)
- `id_anggota` (int)
- `kode_kriteria` (varchar)
- `nilai_alternatif` (int)

### Tabel: `anggota`

- `id_anggota` (int, PK, AI)
- `nama` (varchar)
- `no_telp` (varchar)

### Tabel: `kriteria`

- `kode_kriteria` (varchar, PK)
- `nama_kriteria` (varchar)
- `bobot` (float)
- `jenis` (varchar)

### Tabel: `sub_kriteria`

- `id_sub` (int, PK, AI)
- `kode_kriteria` (varchar)
- `nama_sub` (varchar)
- `nilai` (int)

### Tabel: `ranking`

- `id_ranking` (int, PK, AI)
- `id_alternatif` (int)
- `nilai_optimasi` (float)
- `ranking` (int)
- `tgl_hitung` (date)

## 6. Coding Style Preference

- Gunakan bahasa Indonesia untuk komentar kode (code comments).
- Buat agar code lebih clean dan mudah dipahami, karena akan dimasukkan kedalam listing program pada skripsi saya
- Tulis penamaan variabel menggunakan `snake_case` (contoh: `$nilai_moora`, `$kriteria_benefit`).
- Setiap file koneksi database harus menggunakan file `db_connect.php` yang sudah ada.
