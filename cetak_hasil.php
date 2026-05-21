<?php
include "includes/db_connect.php";
include "includes/functions.php";

// Ambil data hasil perankingan dari database
$query_ranking = mysqli_query($conn, "
    SELECT r.ranking, a.nama, r.nilai_optimasi
    FROM ranking r
    JOIN anggota a ON r.id_alternatif = a.id_anggota
    ORDER BY r.ranking ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Hasil Perangkingan - SPK Koperasi Salimah Sejahtera</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
            position: relative;
        }
        .logo {
            position: absolute;
            left: 10px;
            top: 0;
            width: 90px;
            height: auto;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 20px;
        }
        .header p {
            margin: 2px 0;
            font-size: 14px;
        }
        h3.title {
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .print-info {
            margin-bottom: 15px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            font-size: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #e9e9e9;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        .text-left {
            text-align: left;
        }
        .ttd {
            float: right;
            text-align: center;
            width: 250px;
            margin-top: 20px;
        }
        .ttd p {
            margin: 0;
        }
        .ttd .nama {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }
        /* Sembunyikan elemen no-print saat proses pencetakan */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <!-- Logo koperasi -->
        <img src="assets/img/logo.jpg" alt="Logo Koperasi" class="logo">
        
        <h2>KOPERASI KONSUMEN SERBA USAHA</h2>
        <h3>SALIMAH SEJAHTERA</h3>
        <p>Alamat: Perumahan Pondok Bambu Kuning Blok J2</p>
        <p>RT.02 RW.14, Bojonggede, Kabupaten Bogor, Jawa Barat</p>
    </div>

    <h3 class="title">LAPORAN HASIL PERANGKINGAN KELAYAKAN KREDIT (MOORA)</h3>
    <div class="print-info">
        Tanggal Cetak: <?= date('d-m-Y') ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Ranking</th>
                <th class="text-left" style="width: 45%;">Nama Anggota</th>
                <th style="width: 25%;">Nilai Optimasi (Yi)</th>
                <th style="width: 20%;">Status Kelayakan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (mysqli_num_rows($query_ranking) > 0) {
                while ($row = mysqli_fetch_assoc($query_ranking)) {
                    $yi = $row['nilai_optimasi'];
                    // Berdasarkan nilai optimasi yang ada di AGENT.md atau hasil_perankingan.php (0.15)
                    $status = $yi > 0.15 ? "Layak" : "Dipertimbangkan"; 
            ?>
            <tr>
                <td><?= $row['ranking'] ?></td>
                <td class="text-left"><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= round($yi, 4) ?></td>
                <td><?= $status ?></td>
            </tr>
            <?php 
                }
            } else {
                echo '<tr><td colspan="4">Belum ada data hasil perankingan yang dihitung.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="ttd">
        <p>Mengetahui,</p>
        <p>Ketua Koperasi</p>
        <div class="nama">Ketua Koperasi</div>
    </div>

    <div style="clear:both; padding-top: 50px; text-align: center;" class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor:pointer; background: #28a745; color: #fff; border: none; border-radius: 4px;">Cetak Laporan</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor:pointer; background: #dc3545; color: #fff; border: none; border-radius: 4px; margin-left: 10px;">Tutup Laporan</button>
    </div>

</body>
</html>
