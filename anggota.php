<?php
include "includes/db_connect.php";
include "includes/functions.php";


include "includes/header.php";
include "includes/navbar.php";
?>

<?php
if (isset($_POST['reqAdd'])) {
    $nama = $_POST['nama'];
    $no_telp = $_POST['no_telp'];

    $addAnggota = mysqli_query($conn, "INSERT INTO anggota (nama, no_telp) VALUES ('$nama', '$no_telp')");
    if ($addAnggota) {
        echo "<script>
                alert('Data anggota berhasil ditambahkan!');
                window.location.href = 'anggota.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan data anggota. Silakan coba lagi.');
                window.location.href = 'anggota.php';
              </script>";
    }
}

if (isset($_POST['reqUpdate'])) {
    $idAnggota = $_POST['id_anggota'];
    $updateAnggota = mysqli_query($conn, "UPDATE anggota SET nama='$_POST[nama]', no_telp='$_POST[no_telp]' WHERE id_anggota='$idAnggota'");
    if ($updateAnggota) {
        echo "<script>
                alert('Data anggota berhasil diperbarui!');
                window.location.href = 'anggota.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data anggota. Silakan coba lagi.');
                window.location.href = 'anggota.php';
              </script>";
    }
}

if (isset($_GET['hapusData'])) {
    $idAnggota = $_GET['hapusData'];
    $deleteAnggota = mysqli_query($conn, "DELETE FROM anggota WHERE id_anggota='$idAnggota'");
    if ($deleteAnggota) {
        echo "<script>
                alert('Data anggota berhasil dihapus!');
                window.location.href = 'anggota.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data anggota. Silakan coba lagi.');
                window.location.href = 'anggota.php';
              </script>";
    }
} 
?>


<!-- Main Content -->
<main class="container">
    <div class="page-header">
        <h1>Data Anggota</h1>
        <hr>
    </div>

    <button onclick="addData()" class="btn-cAdd"><i class="fa fa-plus"></i> Tambah Data Anggota</button>

    <section class="main-sec">
        <div class="sec-tableData">
            <div class="table-card">
                <div class="card-header">
                    <p>Data Anggota</p>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable display" style="width:100%" id="datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Telp</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $qData_anggota = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id_anggota LIMIT 10");

                            if (mysqli_num_rows($qData_anggota) > 0) :
                                while ($dAnggota = mysqli_fetch_assoc($qData_anggota)):
                            ?>
                                    <tr class="table-data">
                                        <td><?= $dAnggota['id_anggota'] ?></td>
                                        <td><?= $dAnggota['nama'] ?></td>
                                        <td><?= $dAnggota['no_telp'] ?></td>
                                        <td>
                                            <a href="anggota.php?updateAnggota=<?= $dAnggota['id_anggota'] ?>" class="btn-update">Ubah</a>
                                            <a href="anggota.php?hapusData=<?= $dAnggota['id_anggota'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else : ?>
                                <tr>
                                    <td colspan="4" align="center"><i>Data Anggota Belum Tersedia</i></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <section class="sec-add <?php echo isset($_GET['updateAnggota']) ? 'show' : ''; ?>">
            <?php
            if (isset($_GET['updateAnggota'])):
                $id_anggota = $_GET['updateAnggota'];
                $qFormAnggota = mysqli_query($conn, "SELECT * FROM anggota WHERE id_anggota='$id_anggota'");
                if (mysqli_num_rows($qFormAnggota) > 0):
                    $dFormAnggota = mysqli_fetch_assoc($qFormAnggota);
            ?>
                    <form action="" method="post">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h4>Update Data Anggota</h4>
                            <button type="button" class="btn-close" onclick="closeForm()" title="Tutup">×</button>
                        </div>
                        <hr>
                        <input type="hidden" name="id_anggota" value="<?= $dFormAnggota['id_anggota'] ?>">
                        <label for="nama">Nama Anggota:</label>
                        <input type="text" id="nama" name="nama" value="<?= $dFormAnggota['nama'] ?>" autofocus required>
                        <label for="no_telp">No. Telepon:</label>
                        <input type="text" id="no_telp" name="no_telp" value="<?= $dFormAnggota['no_telp'] ?>" required>
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
                        <h4>Tambah Data Anggota</h4>
                        <button type="button" class="btn-close" onclick="closeForm()" title="Tutup">×</button>
                    </div>
                    <hr>
                    <label for="nama">Nama Anggota:</label>
                    <input type="text" id="nama" name="nama" required>
                    <label for="no_telp">No. Telepon:</label>
                    <input type="text" id="no_telp" name="no_telp" required>
                    <div class="form-btn">
                        <button type="submit" class="btn-add" name="reqAdd">Simpan</button>
                        <button type="reset" class="btn-reset">Bersihkan</button>
                    </div>
                </form>
            <?php
            endif;
            ?>
        </section>
    </section>


</main>



<?php
include "includes/footer.php";
?>