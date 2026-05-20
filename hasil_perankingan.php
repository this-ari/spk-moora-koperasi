<?php
include "includes/db_connect.php";
include "includes/functions.php";


include "includes/header.php";
include "includes/navbar.php";

// Fetch Criteria
$query_kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
$kriteria = [];
while ($row = mysqli_fetch_assoc($query_kriteria)) {
    $kriteria[$row['kode_kriteria']] = $row;
}

// Fetch Alternatives
$query_alternatif = mysqli_query($conn, "
    SELECT a.id_anggota, a.nama, al.kode_kriteria, al.nilai_alternatif
    FROM anggota a
    JOIN alternatif al ON a.id_anggota = al.id_anggota
    ORDER BY a.id_anggota ASC, al.kode_kriteria ASC
");

$matriks_x = [];
$nama_anggota = [];
while ($row = mysqli_fetch_assoc($query_alternatif)) {
    $id = $row['id_anggota'];
    $nama_anggota[$id] = $row['nama'];
    $matriks_x[$id][$row['kode_kriteria']] = $row['nilai_alternatif'];
}

// Ensure there are alternatives to display
$has_data = count($matriks_x) > 0;
?>

<!-- Main Content -->
<main class="container">
    <div class="page-header">
        <h1>Hasil Perangkingan MOORA</h1>
        <hr>
    </div>
    
    <?php if (!$has_data): ?>
        <div style="padding: 15px; background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b; border-radius: 4px; margin-bottom: 20px;">
            Belum ada data nilai alternatif. Silakan input nilai alternatif terlebih dahulu.
        </div>
    <?php else: ?>

        <!-- Step 1: Matriks Keputusan (X) -->
        <section class="main-sec">
            <div class="sec-tableData">
                <div class="table-card">
                    <div class="card-header">
                        <h3>1. Matriks Keputusan (X)</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable display stripe hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Anggota</th>
                                        <?php foreach ($kriteria as $k): ?>
                                            <th><?= $k['kode_kriteria'] ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($matriks_x as $id_anggota => $nilai): 
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($nama_anggota[$id_anggota]) ?></td>
                                            <?php foreach ($kriteria as $kode => $k): ?>
                                                <td><?= isset($nilai[$kode]) ? $nilai[$kode] : 0 ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Step 2: Matriks Normalisasi (R) -->
        <?php
        // Calculate Denominator for each criteria
        $pembagi = [];
        foreach ($kriteria as $kode => $k) {
            $sum_sq = 0;
            foreach ($matriks_x as $id_anggota => $nilai) {
                $v = isset($nilai[$kode]) ? $nilai[$kode] : 0;
                $sum_sq += pow($v, 2);
            }
            $pembagi[$kode] = sqrt($sum_sq);
        }

        // Calculate Normalization Matrix
        $matriks_r = [];
        foreach ($matriks_x as $id_anggota => $nilai) {
            foreach ($kriteria as $kode => $k) {
                $v = isset($nilai[$kode]) ? $nilai[$kode] : 0;
                $matriks_r[$id_anggota][$kode] = $pembagi[$kode] != 0 ? $v / $pembagi[$kode] : 0;
            }
        }
        ?>
        <section class="main-sec" style="margin-top: 30px;">
            <div class="sec-tableData">
                <div class="table-card">
                    <div class="card-header">
                        <h3>2. Matriks Normalisasi (R)</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable display stripe hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Anggota</th>
                                        <?php foreach ($kriteria as $k): ?>
                                            <th><?= $k['kode_kriteria'] ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($matriks_r as $id_anggota => $nilai_r): 
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($nama_anggota[$id_anggota]) ?></td>
                                            <?php foreach ($kriteria as $kode => $k): ?>
                                                <td><?= round($nilai_r[$kode], 4) ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Step 3 & 4: Optimalisasi (Y) & Ranking -->
        <?php
        // Calculate Assessment Value (Yi)
        $hasil_optimasi = [];
        foreach ($matriks_r as $id_anggota => $nilai_r) {
            $sum_benefit = 0;
            $sum_cost = 0;
            
            foreach ($kriteria as $kode => $k) {
                $bobot = $k['bobot'];
                $jenis = strtolower($k['jenis']);
                $nilai_terbobot = $nilai_r[$kode] * $bobot;
                
                if ($jenis == 'benefit') {
                    $sum_benefit += $nilai_terbobot;
                } else if ($jenis == 'cost') {
                    $sum_cost += $nilai_terbobot;
                }
            }
            
            $yi = $sum_benefit - $sum_cost;
            $hasil_optimasi[$id_anggota] = $yi;
        }

        // Sort descending to get ranking
        arsort($hasil_optimasi);
        ?>

        <section class="main-sec" style="margin-top: 30px;">
            <div class="sec-tableData">
                <div class="table-card">
                    <div class="card-header">
                        <h3>3. Hasil Optimasi (Y) dan Perangkingan</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable display stripe hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Peringkat</th>
                                        <th>Nama Anggota</th>
                                        <th>Nilai Optimasi (Yi)</th>
                                        <th>Status Kelayakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $ranking = 1;
                                    foreach ($hasil_optimasi as $id_anggota => $yi): 
                                        // Status kelayakan
                                        $status = $yi > 0 ? "Layak" : "Dipertimbangkan"; 
                                    ?>
                                        <tr>
                                            <td><?= $ranking++ ?></td>
                                            <td><?= htmlspecialchars($nama_anggota[$id_anggota]) ?></td>
                                            <td><?= round($yi, 4) ?></td>
                                            <td>
                                                <?php if($yi > 0): ?>
                                                    <span style="background-color: var(--primary-color, #28a745); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.9em;">Layak</span>
                                                <?php else: ?>
                                                    <span style="background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.9em;">Dipertimbangkan</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
