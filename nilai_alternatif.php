<?php
include "includes/db_connect.php";
include "includes/functions.php";


include "includes/header.php";
include "includes/navbar.php";

// Proses Simpan Input Baru
if (isset($_POST['reqAdd'])) {
    if (isset($_POST['nilai'])) {
        $success = true;
        foreach ($_POST['nilai'] as $id_anggota => $kriteria_nilai) {
            $id_anggota_clean = mysqli_real_escape_string($conn, $id_anggota);
            // Hapus data lama untuk mencegah duplikasi jika user melakukan input ulang
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

// Proses Simpan Ubah (Update Single Alternatif)
if (isset($_POST['reqEditSingle'])) {
    $id_anggota = mysqli_real_escape_string($conn, $_POST['id_anggota']);
    if (isset($_POST['nilai_single'])) {
        $success = true;
        // Hapus data lama untuk alternatif terpilih
        mysqli_query($conn, "DELETE FROM alternatif WHERE id_anggota = '$id_anggota'");
        
        foreach ($_POST['nilai_single'] as $kode_kriteria => $nilai_alternatif) {
            if ($nilai_alternatif !== "") {
                $kode_kriteria_clean = mysqli_real_escape_string($conn, $kode_kriteria);
                $nilai_alternatif_clean = mysqli_real_escape_string($conn, $nilai_alternatif);
                
                $query = "INSERT INTO alternatif (id_anggota, kode_kriteria, nilai_alternatif) VALUES ('$id_anggota', '$kode_kriteria_clean', '$nilai_alternatif_clean')";
                $insert = mysqli_query($conn, $query);
                if (!$insert) {
                    $success = false;
                }
            }
        }
        
        if ($success) {
            echo "<script>
                    alert('Data nilai alternatif berhasil diperbarui!');
                    window.location.href = 'nilai_alternatif.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memperbarui data nilai alternatif. Silakan coba lagi.');
                  </script>";
        }
    }
}

// Ambil kriteria kriteria untuk tabel
$query_kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY kode_kriteria ASC");
$kriteria_list = [];
while ($row = mysqli_fetch_assoc($query_kriteria)) {
    $kriteria_list[] = $row;
}

// Ambil data anggota
$query_anggota = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id_anggota ASC");
$anggota_list = [];
$anggota_names = [];
while ($row = mysqli_fetch_assoc($query_anggota)) {
    $anggota_list[] = $row;
    $anggota_names[$row['id_anggota']] = $row['nama'];
}

// Ambil nilai alternatif yang ada
$query_nilai = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_anggota ASC, kode_kriteria ASC");
$nilai_alternatif_map = [];
while ($row = mysqli_fetch_assoc($query_nilai)) {
    $nilai_alternatif_map[$row['id_anggota']][$row['kode_kriteria']] = $row['nilai_alternatif'];
}

// Pisahkan anggota yang belum memiliki nilai alternatif
$anggota_tanpa_nilai = [];
foreach ($anggota_list as $a) {
    if (!isset($nilai_alternatif_map[$a['id_anggota']]) || count($nilai_alternatif_map[$a['id_anggota']]) < count($kriteria_list)) {
        $anggota_tanpa_nilai[] = $a;
    }
}

// Cek apakah mode Edit (Ubah) sedang aktif
$show_edit_form = false;
$edit_anggota_id = null;
$edit_anggota_nama = '';
if (isset($_GET['ubah_alternatif'])) {
    $edit_anggota_id = mysqli_real_escape_string($conn, $_GET['ubah_alternatif']);
    if (isset($anggota_names[$edit_anggota_id])) {
        $show_edit_form = true;
        $edit_anggota_nama = $anggota_names[$edit_anggota_id];
    }
}
?>

<main class="container">
    <div class="page-header">
        <h1>Nilai Alternatif</h1>
        <hr>
    </div>

    <!-- Tampilkan Form Ubah jika parameter ubah_alternatif terpilih -->
    <?php if ($show_edit_form): ?>
    <section class="sec-add show" style="margin-bottom: 30px;">
        <form action="" method="post" id="formEditNilaiAlternatif">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; color: #2d5a2d;">Ubah Nilai Alternatif: <?= htmlspecialchars($edit_anggota_nama) ?></h4>
                <button type="button" class="btn-close" onclick="closeForm()" title="Tutup">×</button>
            </div>
            <hr>
            <input type="hidden" name="id_anggota" value="<?= $edit_anggota_id ?>">
            <div class="table-responsive">
                <table class="display stripe hover" style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <?php foreach ($kriteria_list as $k): ?>
                                <th style="padding: 10px; text-align: left; background-color: #d4f4d4; color: #2b2b2b; border-bottom: 2px solid #b2d4b2;"><?= $k['kode_kriteria'] ?> (<?= htmlspecialchars($k['nama_kriteria']) ?>)</th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php foreach ($kriteria_list as $k): 
                                $current_val = isset($nilai_alternatif_map[$edit_anggota_id][$k['kode_kriteria']]) ? $nilai_alternatif_map[$edit_anggota_id][$k['kode_kriteria']] : '';
                            ?>
                            <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                                <select name="nilai_single[<?= $k['kode_kriteria'] ?>]" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                                    <option value="">-- Pilih --</option>
                                    <?php 
                                    $sub = mysqli_query($conn, "SELECT nama_sub, nilai FROM sub_kriteria WHERE kode_kriteria = '{$k['kode_kriteria']}'");
                                    while ($s = mysqli_fetch_assoc($sub)): 
                                        $selected = ($s['nilai'] == $current_val) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $s['nilai'] ?>" <?= $selected ?>><?= htmlspecialchars($s['nama_sub']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-btn">
                <button type="submit" class="btn-add" name="reqEditSingle">Simpan Perubahan</button>
                <button type="button" class="btn-reset" onclick="closeForm()">Batal</button>
            </div>
        </form>
    </section>
    <?php endif; ?>

    <section class="main-sec" style="display: flex; flex-direction: column; gap: 30px;">
        
        <!-- Tabel Data Nilai Alternatif yang sudah diinput (Tampilan Matriks Keputusan) -->
        <?php if (count($nilai_alternatif_map) > 0): ?>
        <div class="sec-tableData">
            <div class="table-card">
                <div class="card-header" style="display: flex; margin: 15px 0; justify-content: space-between; align-items: center;">
                    <p style="margin: 0; font-weight: 600;">Data Nilai Alternatif</p>
                    <div>
                        <a href="hasil_perankingan.php?hitung=1" class="btn-reset" style="margin-bottom: 0;"><i class="fas fa-sync-alt"></i> Hitung Ulang</a>
                        <a href="hasil_perankingan.php" class="btn-add" style="margin-bottom: 0;"><i class="fas fa-eye"></i> Lihat Hasil</a>
                    </div>
                    
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable display stripe hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Anggota</th>
                                    <?php foreach ($kriteria_list as $k): ?>
                                        <th><?= $k['kode_kriteria'] ?></th>
                                    <?php endforeach; ?>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($nilai_alternatif_map as $id_ang => $nilai): 
                                    if (!isset($anggota_names[$id_ang])) continue;
                                ?>
                                    <tr class="table-data">
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($anggota_names[$id_ang]) ?></td>
                                        <?php foreach ($kriteria_list as $k): ?>
                                            <td><?= isset($nilai[$k['kode_kriteria']]) ? $nilai[$k['kode_kriteria']] : '0' ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <a href="nilai_alternatif.php?ubah_alternatif=<?= $id_ang ?>" class="btn-update">Ubah</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Form Input Nilai Alternatif Baru (hanya muncul jika ada anggota yang belum memiliki nilai) -->
        <?php if (count($anggota_tanpa_nilai) > 0): ?>
        <div class="sec-tableData">
            <div class="table-card">
                <div class="card-header">
                    <p style="margin: 0; font-size: 18px; font-weight: 600;">Input Nilai Alternatif</p>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="formNilaiAlternatif">
                        <div class="table-responsive">
                            <table id="tableInputNilai" class="display stripe hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Anggota</th>
                                        <?php foreach ($kriteria_list as $k): ?>
                                            <th><?= $k['kode_kriteria'] ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <?php foreach ($kriteria_list as $k): ?>
                                            <th><?= htmlspecialchars($k['nama_kriteria']) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1; 
                                    foreach ($anggota_tanpa_nilai as $a):
                                    ?>
                                    <tr>
                                        <td><?=$no++ ?></td>
                                        <td><?= htmlspecialchars($a['nama']) ?></td>
                                        <?php foreach ($kriteria_list as $k): ?>
                                        <td>
                                            <select name="nilai[<?= $a['id_anggota'] ?>][<?= $k['kode_kriteria'] ?>]" required>
                                                <option value="">-- Pilih --</option>
                                                <?php 
                                                $sub = mysqli_query($conn, "SELECT nama_sub, nilai FROM sub_kriteria WHERE kode_kriteria = '{$k['kode_kriteria']}'");
                                                while($s = mysqli_fetch_assoc($sub)){
                                                ?>
                                                <option value="<?= $s['nilai'] ?>"><?= htmlspecialchars($s['nama_sub']) ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-btn">
                            <button type="submit" class="btn-add" name="reqAdd">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const formInput = document.getElementById('formNilaiAlternatif');
    if (formInput) {
        formInput.addEventListener('submit', function(e) {
            let emptyCount = 0;
            // Jika jQuery dan DataTable aktif, ambil input dari data internal datatable agar dapat mengakses semua baris (termasuk yang tidak tampil di halaman saat ini)
            if (typeof $ !== 'undefined' && $.fn.DataTable) {
                const table = $('#tableInputNilai').DataTable();
                table.$('select').each(function() {
                    if ($(this).val() === "") {
                        emptyCount++;
                        $(this).css('border-color', 'red');
                    } else {
                        $(this).css('border-color', '');
                    }
                });
            } else {
                const selects = formInput.querySelectorAll('select');
                selects.forEach(select => {
                    if (select.value === "") {
                        emptyCount++;
                        select.style.borderColor = 'red';
                    } else {
                        select.style.borderColor = '';
                    }
                });
            }

            if (emptyCount > 0) {
                e.preventDefault();
                alert('Data tidak dapat dikirim karena masih ada nilai alternatif yang kosong! Silakan lengkapi semua baris.');
            } else {
                // Pastikan semua input dari halaman DataTable yang tersembunyi juga ikut dikirim saat submit
                if (typeof $ !== 'undefined' && $.fn.DataTable) {
                    const table = $('#tableInputNilai').DataTable();
                    table.$('select').each(function() {
                        if (!$.contains(document, this)) {
                            // Elemen select ini tidak ada di DOM (di halaman tersembunyi),
                            // sehingga kita buat input hidden agar nilainya tetap terkirim
                            $('<input>', {
                                type: 'hidden',
                                name: this.name,
                                value: $(this).val()
                            }).appendTo(formInput);
                        }
                    });
                }
            }
        });
    }
});
</script>

<?php
include "includes/footer.php";
?>