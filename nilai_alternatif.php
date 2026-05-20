<?php
include "includes/db_connect.php";
include "includes/functions.php";


include "includes/header.php";
include "includes/navbar.php";

if (isset($_POST['reqAdd'])) {
    if (isset($_POST['nilai'])) {
        $success = true;
        foreach ($_POST['nilai'] as $id_anggota => $kriteria_nilai) {
            // Hapus data lama untuk mencegah duplikasi jika user melakukan update
            $id_anggota_clean = mysqli_real_escape_string($conn, $id_anggota);
            mysqli_query($conn, "DELETE FROM alternatif WHERE id_anggota = '$id_anggota_clean'");
            
            foreach ($kriteria_nilai as $kode_kriteria => $nilai_alternatif) {
                if ($nilai_alternatif !== "") {
                    $kode_kriteria_clean = mysqli_real_escape_string($conn, $kode_kriteria);
                    $nilai_alternatif_clean = mysqli_real_escape_string($conn, $nilai_alternatif);
                    
                    $query = "INSERT INTO alternatif (id_anggota, kode_kriteria, nilai_alternatif) VALUES ('$id_anggota_clean', '$kode_kriteria_clean', '$nilai_alternatif_clean')";
                    $insert = mysqli_query($conn, $query);
                    if (!$insert) {
                        $success = false;
                    }
                }
            }
        }
        
        if ($success) {
            echo "<script>
                    alert('Data nilai alternatif berhasil disimpan!');
                    window.location.href = 'nilai_alternatif.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menyimpan data nilai alternatif. Silakan coba lagi.');
                  </script>";
        }
    }
}
?>

<main class="container">
    <div class="page-header">
        <h1>Nilai Alternatif</h1>
        <hr>
        <br>
        <a href="hasil_perankingan.php" class="btn-cAdd">Lihat Hasil</a>
    </div>

    <section class="main-sec">
        <div class="sec-tableData">
            <div class="table-card">
                <div class="card-header">
                    <h3>Input Nilai Alternatif</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="formNilaiAlternatif">
                        <table class="display stripe hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Anggota</th>
                                    <?php 
                                    $kriteria = mysqli_query($conn, "SELECT kode_kriteria FROM kriteria");
                                    while($k = mysqli_fetch_assoc($kriteria)){
                                        ?>
                                        <th><?php echo $k['kode_kriteria']; ?></th>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php
                                    $kriteria = mysqli_query($conn, "SELECT nama_kriteria FROM kriteria");
                                    while($k = mysqli_fetch_assoc($kriteria)){
                                        ?>
                                        <th><?php echo $k['nama_kriteria']; ?></th>
                                        <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1; 
                                $anggota = mysqli_query($conn, "SELECT id_anggota, nama FROM anggota");
                                while($a = mysqli_fetch_assoc($anggota)){
                                ?>
                                <tr>
                                    <td><?=$no++ ?></td>
                                    <td>
                                        <?=$a['nama'] ?>
                                    </td>
                                    <?php 
                                    $kriteria = mysqli_query($conn, "SELECT kode_kriteria FROM kriteria");
                                    while($k = mysqli_fetch_assoc($kriteria)){
                                    ?>
                                    <td>
                                        <select name="nilai[<?= $a['id_anggota'] ?>][<?= $k['kode_kriteria'] ?>]" id="" required>
                                            <option value="">-- Pilih --</option>
                                        <?php 
                                        $sub = mysqli_query($conn, 
                                            "SELECT nama_sub, nilai FROM sub_kriteria 
                                            WHERE kode_kriteria = '$k[kode_kriteria]'");
                                        while($s = mysqli_fetch_assoc($sub)){
                                        ?>
                                        <option value="<?= $s['nilai'] ?>"><?= $s['nama_sub'] ?></option>
                                        <?php } ?>
                                    </select>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="form-btn">
                                            <button type="submit" class="btn-add" name="reqAdd">Simpan</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </section>
    

</main>

<?php
include "includes/footer.php";
?>