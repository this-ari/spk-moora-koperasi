<?php
include "includes/db_connect.php";
include "includes/functions.php";


include "includes/header.php";
include "includes/navbar.php";
?>

<?php

function getNextKode($conn)
{
    $query = "SELECT kode_kriteria FROM kriteria ORDER BY kode_kriteria DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $last_kode = mysqli_fetch_array($result)[0] ?? '';

    if ($last_kode) {
        preg_match('/C(\d+)/', $last_kode, $matches);
        $next = (int)$matches[1] + 1;
        return 'C' . $next;
    }
    return 'C1';  // Mulai dari C1
}

$next_kode = getNextKode($conn);

if (isset($_POST['reqAdd'])) {
    $nama_kriteria = mysqli_real_escape_string($conn, $_POST['nama_kriteria']);
    $kode_kriteria = mysqli_real_escape_string($conn, $_POST['kode_kriteria']);
    $bobot = mysqli_real_escape_string($conn, $_POST['bobot']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);

    $addKriteria = mysqli_query($conn, "INSERT INTO kriteria (nama_kriteria, kode_kriteria, bobot, jenis) VALUES ('$nama_kriteria', '$kode_kriteria', '$bobot', '$jenis')");
    if ($addKriteria) {
        // Insert sub kriteria
        if (isset($_POST['nama_sub'])) {
            $nama_subs = $_POST['nama_sub'];

            for ($i = 0; $i < count($nama_subs); $i++) {
                $nama_sub = mysqli_real_escape_string($conn, $nama_subs[$i]);
                $nilai_sub = 5 - $i; // Otomatis: 5, 4, 3, 2, 1

                // Hanya insert jika nama_sub tidak kosong
                if (!empty($nama_sub)) {
                    $addSubKriteria = mysqli_query($conn, "INSERT INTO sub_kriteria (kode_kriteria, nama_sub, nilai) VALUES ('$kode_kriteria', '$nama_sub', '$nilai_sub')");
                }
            }
        }

        echo "<script>
                alert('Data kriteria berhasil ditambahkan!');
                window.location.href = 'kriteria.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan data kriteria. Silakan coba lagi.');
                window.location.href = 'kriteria.php';
              </script>";
    }
}

if (isset($_POST['reqUpdate'])) {
}

if (isset($_POST['reqUpdate'])) {
    $nama_kriteria = mysqli_real_escape_string($conn, $_POST['nama_kriteria']);
    $kode_kriteria = mysqli_real_escape_string($conn, $_POST['kode_kriteria']);
    $bobot = mysqli_real_escape_string($conn, $_POST['bobot']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);

    $updateKriteria = mysqli_query($conn, "UPDATE kriteria SET nama_kriteria='$nama_kriteria', bobot='$bobot', jenis='$jenis' WHERE kode_kriteria='$kode_kriteria'");
    if ($updateKriteria) {
        // Hapus semua sub kriteria lama
        mysqli_query($conn, "DELETE FROM sub_kriteria WHERE kode_kriteria='$kode_kriteria'");

        // Insert sub kriteria baru
        if (isset($_POST['nama_sub'])) {
            $nama_subs = $_POST['nama_sub'];

            for ($i = 0; $i < count($nama_subs); $i++) {
                $nama_sub = mysqli_real_escape_string($conn, $nama_subs[$i]);
                $nilai_sub = 5 - $i; // Otomatis: 5, 4, 3, 2, 1

                // Hanya insert jika nama_sub tidak kosong
                if (!empty($nama_sub)) {
                    $addSubKriteria = mysqli_query($conn, "INSERT INTO sub_kriteria (kode_kriteria, nama_sub, nilai) VALUES ('$kode_kriteria', '$nama_sub', '$nilai_sub')");
                }
            }
        }

        echo "<script>
                alert('Data kriteria berhasil diperbarui!');
                window.location.href = 'kriteria.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data kriteria. Silakan coba lagi.');
                window.location.href = 'kriteria.php';
              </script>";
    }
}

if (isset($_GET['hapusData'])) {
    $kode_kriteria = $_GET['hapusData'];

    // Hapus sub kriteria terlebih dahulu
    mysqli_query($conn, "DELETE FROM sub_kriteria WHERE kode_kriteria='$kode_kriteria'");

    // Kemudian hapus kriteria
    $deleteKriteria = mysqli_query($conn, "DELETE FROM kriteria WHERE kode_kriteria='$kode_kriteria'");
    if ($deleteKriteria) {
        echo "<script>
                alert('Data kriteria berhasil dihapus!');
                window.location.href = 'kriteria.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data kriteria. Silakan coba lagi.');
                window.location.href = 'kriteria.php';
              </script>";
    }
}
?>


<!-- Main Content -->
<main class="container">
    <div class="page-header">
        <h1>Data Kriteria</h1>
        <hr>
    </div>

    <button onclick="addData()" class="btn-cAdd">Tambah Data Kriteria</button>

    <section class="sec-add <?php echo isset($_GET['updateKriteria']) ? 'show' : ''; ?>">
        <?php
        if (isset($_GET['updateKriteria'])):
            $kode_kriteria = $_GET['updateKriteria'];
            $qFormKriteria = mysqli_query($conn, "SELECT * FROM kriteria WHERE kode_kriteria='$kode_kriteria'");
            if (mysqli_num_rows($qFormKriteria) > 0):
                $dFormKriteria = mysqli_fetch_assoc($qFormKriteria);
        ?>
                <form action="" method="post">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h3>Update Data Kriteria</h3>
                        <button type="button" class="btn-close" onclick="closeForm()" title="Tutup">×</button>
                    </div>
                    <hr>
                    <div class="form-grid">
                        <div class="form-left">
                            <label for="kode_kriteria">Kode Kriteria:</label>
                            <input type="text" id="kode_kriteria" name="kode_kriteria" value="<?= $dFormKriteria['kode_kriteria'] ?>" required readonly>
                            <label for="nama_kriteria">Nama Kriteria:</label>
                            <input type="text" id="nama_kriteria" name="nama_kriteria" value="<?= $dFormKriteria['nama_kriteria'] ?>" required>
                            <label for="bobot">Bobot:</label>
                            <input type="number" id="bobot" name="bobot" value="<?= $dFormKriteria['bobot'] ?>" step="0.01" required>
                            <label for="jenis">Jenis:</label>
                            <select name="jenis" id="jenis">
                                <option value="Benefit" <?= $dFormKriteria['jenis'] == 'Benefit' ? 'selected' : '' ?>>Benefit</option>
                                <option value="Cost" <?= $dFormKriteria['jenis'] == 'Cost' ? 'selected' : '' ?>>Cost</option>
                            </select>

                            <div class="warning">
                                <p><i><b>Catatan: </b> Jika Jenis Kriteria adalah "Benefit", maka semakin tinggi nilai sub kriteria, semakin baik. Jika Jenis Kriteria adalah "Cost", maka semakin rendah nilai sub kriteria, semakin baik.</i></p>
                            </div>
                        </div>

                        <div class="form-right">
                            <?php
                            // Ambil data sub kriteria yang sudah ada
                            $qSubKriteria = mysqli_query($conn, "SELECT * FROM sub_kriteria WHERE kode_kriteria='" . $dFormKriteria['kode_kriteria'] . "' ORDER BY id_sub");
                            $subKriteriaData = [];
                            while ($dSub = mysqli_fetch_assoc($qSubKriteria)) {
                                $subKriteriaData[] = $dSub;
                            }
                            // Pastikan ada 5 field, isi dengan data yang ada atau kosong
                            for ($i = 0; $i < 5; $i++) {
                                $nama_sub = isset($subKriteriaData[$i]) ? $subKriteriaData[$i]['nama_sub'] : '';
                                $nilai = 5 - $i; // Otomatis: 5, 4, 3, 2, 1 berdasarkan posisi
                                $id_sub = isset($subKriteriaData[$i]) ? $subKriteriaData[$i]['id_sub'] : '';
                            ?>
                                <div class="sub-kriteria-row">
                                    <div class="nama-sub">
                                        <label for="nama_sub_<?= $i + 1 ?>">Sub Kriteria <?= $i + 1 ?> (<?= 5 - $i ?> Poin):</label>
                                        <input type="text" id="nama_sub_<?= $i + 1 ?>" name="nama_sub[]" value="<?= $nama_sub ?>" maxlength="50">
                                    </div>
                                </div>
                                <input type="hidden" name="id_sub[]" value="<?= $id_sub ?>">
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-btn">
                        <button type="submit" class="btn-add" name="reqUpdate">Simpan Perubahan</button>
                        <button type="reset" class="btn-reset">Bersihkan</button>
                    </div>
                </form>
            <?php
            endif;
        else:
            ?>
            <form action="" method="post">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Tambah Data Kriteria</h3>
                    <button type="button" class="btn-close" onclick="closeForm()" title="Tutup">×</button>
                </div>
                <hr>
                <div class="form-grid">
                    <div class="form-left">
                        <label for="kode_kriteria">Kode Kriteria:</label>
                        <input type="text" id="kode_kriteria" name="kode_kriteria" value="<?= $next_kode ?>" readonly required>
                        <label for="nama_kriteria">Nama Kriteria:</label>
                        <input type="text" id="nama_kriteria" name="nama_kriteria" required>
                        <label for="bobot">Bobot:</label>
                        <input type="number" id="bobot" name="bobot" step="0.01" required>
                        <label for="jenis">Jenis:</label>
                        <select name="jenis" id="jenis">
                            <option value="Benefit">Benefit</option>
                            <option value="Cost">Cost</option>
                        </select>
                        <div class="warning">
                            <p><i><b>Catatan: </b> Jika Jenis Kriteria adalah "Benefit", maka semakin tinggi nilai sub kriteria, semakin baik. Jika Jenis Kriteria adalah "Cost", maka semakin rendah nilai sub kriteria, semakin baik.</i></p>
                        </div>
                    </div>

                    <div class="form-right">
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <div class="sub-kriteria-row">
                                <div class="nama-sub">
                                    <label for="nama_sub_<?= $i + 1 ?>">Sub Kriteria <?= $i + 1 ?> (<?= 5 - $i ?> Poin):</label>
                                    <input type="text" id="nama_sub_<?= $i + 1 ?>" name="nama_sub[]" maxlength="50">
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-btn">
                    <button type="submit" class="btn-add" name="reqAdd">Simpan</button>
                    <button type="reset" class="btn-reset">Bersihkan</button>
                </div>
            </form>
        <?php
        endif;
        ?>
    </section>
    <br>

    <br>
    <div class="sec-tableData">
        <div class="table-card">
            <div class="card-header">
                <p>Data Kriteria</p>
                <hr>
            </div>
            <div class="card-body">
                <table class="datatable display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Jenis</th>
                            <th>Sub Kriteria</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $qData_kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY kode_kriteria");

                    if (mysqli_num_rows($qData_kriteria) > 0) :
                        while ($dKriteria = mysqli_fetch_assoc($qData_kriteria)):
                    ?>
                            <tr class="table-data">
                                <td><b><?= $dKriteria['kode_kriteria'] ?></b></td>
                                <td><?= $dKriteria['nama_kriteria'] ?></td>
                                <td><?= $dKriteria['bobot'] ?></td>
                                <td><?= $dKriteria['jenis'] ?></td>
                                <td>
                                    <?php
                                    $qSubKriteria = mysqli_query($conn, "SELECT * FROM sub_kriteria WHERE kode_kriteria = '{$dKriteria['kode_kriteria']}'");
                                    if (mysqli_num_rows($qSubKriteria) > 0) :
                                        while ($dSubKriteria = mysqli_fetch_assoc($qSubKriteria)):
                                    ?>
                                            <p><?= $dSubKriteria['nama_sub'] ?></p>
                                        <?php
                                        endwhile;
                                    else : ?>
                                        <p><i>Sub Kriteria Belum Tersedia</i></p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="kriteria.php?updateKriteria=<?= $dKriteria['kode_kriteria'] ?>" class="btn-update">Ubah</a>
                                    <a href="kriteria.php?hapusData=<?= $dKriteria['kode_kriteria'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else : ?>
                        <tr>
                            <td colspan="5" align="center"><i>Data Kriteria Belum Tersedia</i></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</main>



<?php
include "includes/footer.php";
?>