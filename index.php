<?php
include "includes/db_connect.php";
include "includes/functions.php";


include "includes/header.php";
include "includes/navbar.php";
?>

<!-- Main Content -->
<main class="container">
    <div class="page-header">
        <h1>Dashboard</h1>
        <hr>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <?php
                $query_anggota = mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota");
                $data_anggota = mysqli_fetch_assoc($query_anggota);
                ?>
                <h3><?php echo $data_anggota['total'] ?? "0"; ?></h3>
                <p>Total Anggota</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clipboard"></i></div>
            <div class="stat-info">
                <?php
                $query_kriteria = mysqli_query($conn, "SELECT COUNT(*) as total FROM kriteria");
                $data_kriteria = mysqli_fetch_assoc($query_kriteria);
                ?>
                <h3><?php echo $data_kriteria['total'] ?? "0"; ?></h3>
                <p>Kriteria Penilaian</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-info">
                <?php
                $query_alt = mysqli_query($conn, "SELECT COUNT(*) as total FROM sub_kriteria");
                $data_alt = mysqli_fetch_assoc($query_alt);
                ?>
                <h3><?php echo $data_alt['total'] ?? "0"; ?></h3>
                <p>Sub Kriteria</p>
            </div>
        </div>

    </div>

    <!-- Table Data -->
    <div class="table-container">
        <div class="table-card">
            <div class="card-header">
                <p>Data Anggota</p>
                <a href="anggota.php" class="btn btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="datatable display" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Telp</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $qData_anggota = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id_anggota DESC LIMIT 10");

                        if (mysqli_num_rows($qData_anggota) > 0) :
                            while ($dAnggota = mysqli_fetch_assoc($qData_anggota)):
                        ?>
                                <tr class="table-data">
                                    <td><?= $dAnggota['id_anggota'] ?></td>
                                    <td><?= $dAnggota['nama'] ?></td>
                                    <td><?= $dAnggota['no_telp'] ?></td>
                                </tr>
                            <?php
                            endwhile;
                        else : ?>
                            <tr>
                                <td colspan="3" align="center"><i>Data Anggota Belum Tersedia</i></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="table-card">
            <div class="card-header">
                <p>Data Kriteria</p>
                <a href="kriteria.php" class="btn btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="datatable display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $qData_kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY kode_kriteria");

                        if (mysqli_num_rows($qData_kriteria) > 0) :
                            while ($dKriteria = mysqli_fetch_assoc($qData_kriteria)):
                        ?>
                                <tr class="table-data">
                                    <td><?= $dKriteria['kode_kriteria'] ?></td>
                                    <td><?= $dKriteria['nama_kriteria'] ?></td>
                                    <td><?= $dKriteria['bobot'] ?></td>
                                    <td><?= $dKriteria['jenis'] ?></td>
                                </tr>
                            <?php
                            endwhile;
                        else : ?>
                            <tr>
                                <td colspan="4" align="center"><i>Data Kritera Belum Tersedia</i></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>


<?php
include "includes/footer.php";
?>