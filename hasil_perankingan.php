<?php
include "includes/db_connect.php";
include "includes/functions.php";


// ==========================================
// PROSES PERHITUNGAN SPK MOORA (BACKEND) - JIKA TOMBOL HITUNG ULANG DITEKAN
// ==========================================
if (isset($_GET['hitung']) && $_GET['hitung'] == '1') {
    // LANGKAH 1: Mengambil Data Kriteria dan Bobot dari Database secara Dinamis
    // Hal ini sesuai dengan aturan AGENT.md agar kriteria tidak di-hardcode
    $query_kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
    $kriteria = [];
    while ($row = mysqli_fetch_assoc($query_kriteria)) {
        $kriteria[$row['kode_kriteria']] = $row;
    }

    // LANGKAH 2: Mengambil Data Alternatif (Nilai Anggota untuk Setiap Kriteria)
    $query_alternatif = mysqli_query($conn, "
        SELECT a.id_anggota, a.nama, al.kode_kriteria, al.nilai_alternatif
        FROM anggota a
        JOIN alternatif al ON a.id_anggota = al.id_anggota
        ORDER BY a.id_anggota ASC, al.kode_kriteria ASC
    ");

    $matriks_x = [];
    while ($row = mysqli_fetch_assoc($query_alternatif)) {
        $id = $row['id_anggota'];
        $matriks_x[$id][$row['kode_kriteria']] = $row['nilai_alternatif'];
    }

    // Memastikan ada data alternatif yang sudah diinput untuk diproses
    if (count($matriks_x) > 0) {
        // LANGKAH 3: Menghitung Nilai Pembagi (Denominator) untuk Normalisasi
        $pembagi = [];
        foreach ($kriteria as $kode => $k) {
            $sum_sq = 0;
            foreach ($matriks_x as $id_anggota => $nilai) {
                $v = isset($nilai[$kode]) ? $nilai[$kode] : 0;
                $sum_sq += pow($v, 2);
            }
            $pembagi[$kode] = sqrt($sum_sq);
        }

        // LANGKAH 4: Melakukan Normalisasi Matriks Keputusan (X) Menjadi Matriks Normalisasi (R)
        $matriks_r = [];
        foreach ($matriks_x as $id_anggota => $nilai) {
            foreach ($kriteria as $kode => $k) {
                $v = isset($nilai[$kode]) ? $nilai[$kode] : 0;
                // Cegah pembagian dengan angka nol jika nilai kriteria kosong semua
                $matriks_r[$id_anggota][$kode] = $pembagi[$kode] != 0 ? $v / $pembagi[$kode] : 0;
            }
        }

        // LANGKAH 5: Optimalisasi Matriks Terbobot & Menghitung Nilai Assessment (Yi)
        $hasil_optimasi = [];
        foreach ($matriks_r as $id_anggota => $nilai_r) {
            $sum_benefit = 0;
            $sum_cost = 0;
            
            foreach ($kriteria as $kode => $k) {
                $bobot = $k['bobot'];
                $jenis = strtolower($k['jenis']); // Mengambil jenis kriteria ('benefit' atau 'cost')
                $nilai_terbobot = $nilai_r[$kode] * $bobot;
                
                // Pengelompokan penjumlahan kriteria berdasarkan jenisnya secara otomatis
                if ($jenis == 'benefit') {
                    $sum_benefit += $nilai_terbobot;
                } else if ($jenis == 'cost') {
                    $sum_cost += $nilai_terbobot;
                }
            }
            
            // Nilai assessment Yi untuk alternatif i
            $yi = $sum_benefit - $sum_cost;
            $hasil_optimasi[$id_anggota] = $yi;
        }

        // LANGKAH 6: Perangkingan
        // Mengurutkan nilai optimasi (Yi) dari yang terbesar ke terkecil
        arsort($hasil_optimasi);

        // LANGKAH 7: Menyimpan Hasil Akhir Perangkingan ke Database
        // Sesuai dengan aturan AGENT.md, wajib menggunakan Prepared Statements demi keamanan SQL injection.
        
        // Hapus data ranking lama terlebih dahulu agar tidak menumpuk
        mysqli_query($conn, "DELETE FROM ranking");

        // Persiapkan statement insert data ranking baru ke tabel 'ranking'
        $stmt = mysqli_prepare($conn, "INSERT INTO ranking (id_alternatif, ranking, nilai_optimasi, tgl_hitung) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $peringkat = 1;
            $tgl_sekarang = date('Y-m-d');
            foreach ($hasil_optimasi as $id_anggota => $yi) {
                // Parameter data: id_anggota (int), peringkat (int), nilai_optimasi (float/double), tanggal (string)
                mysqli_stmt_bind_param($stmt, "iids", $id_anggota, $peringkat, $yi, $tgl_sekarang);
                mysqli_stmt_execute($stmt);
                $peringkat++;
            }
            mysqli_stmt_close($stmt);
        }
        
        echo "<script>
            alert('Perhitungan ulang berhasil dilakukan dan disimpan!');
            window.location.href = 'hasil_perankingan.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Belum ada data nilai alternatif untuk dihitung.');
            window.location.href = 'hasil_perankingan.php';
        </script>";
        exit;
    }
}

// Ambil data hasil perankingan dari database untuk ditampilkan di tabel
$query_ranking = mysqli_query($conn, "
    SELECT r.ranking, a.nama, r.nilai_optimasi
    FROM ranking r
    JOIN anggota a ON r.id_alternatif = a.id_anggota
    ORDER BY r.ranking ASC
");
$has_data = mysqli_num_rows($query_ranking) > 0;

include "includes/header.php";
include "includes/navbar.php";
?>

<!-- Tampilan Utama Halaman Hasil Perankingan -->
<main class="container">
    <div class="page-header">
        <h1>Hasil Perangkingan MOORA</h1>
        <hr>
        <br>
        <a href="cetak_hasil.php" target="_blank" class="btn-cAdd"><i class="fas fa-print"></i> Cetak Hasil Perankingan</a>
    </div>
    
    <?php if (!$has_data): ?>
        <div style="padding: 15px; background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; border-radius: 4px; margin-bottom: 20px;">
            Belum ada data nilai alternatif. Silakan input nilai alternatif terlebih dahulu.
        </div>
    <?php else: ?>

        <!-- Hanya Menampilkan Tabel Hasil Akhir Ranking Saja (Langkah Perantara Dihilangkan agar bersih sesuai aturan AGENT.md) -->
        <section class="main-sec">
            <div class="sec-tableData">
                <div class="table-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable display stripe hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Nama Anggota</th>
                                        <th>Nilai Optimasi (Yi)</th>
                                        <th>Status Kelayakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    while ($row = mysqli_fetch_assoc($query_ranking)): 
                                        $yi = $row['nilai_optimasi'];
                                        // Anggota layak mendapatkan kredit jika nilai optimasi Yi > 0.15
                                        $status = $yi > 0.15 ? "Layak" : "Dipertimbangkan"; 
                                    ?>
                                        <tr>
                                            <td><?= $row['ranking'] ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td><?= round($yi, 4) ?></td>
                                            <td>
                                                <?php if($yi > 0.15): ?>
                                                    <span style="background-color: var(--primary-color, #28a745); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; font-weight: 500;">Layak</span>
                                                <?php else: ?>
                                                    <span style="background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; font-weight: 500;">Dipertimbangkan</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php endif; ?>
</main>

<?php
include "includes/footer.php";
?>
