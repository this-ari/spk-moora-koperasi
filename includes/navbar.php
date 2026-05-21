    <nav>
        <div class="navbar">
            <div class="nav-brand">
                <img src="./assets/img/logo.jpg" alt="logo">
                <a href="index.php">KOSSUMA SALIMAH SEJAHTERA</a>
            </div>
            <div class="nav-menu">
                <a href="index.php">Dashboard</a>
                <a href="anggota.php">Anggota</a>
                <a href="kriteria.php">Kriteria</a>
                <a href="nilai_alternatif.php">Nilai Alternatif</a>
            </div>
            <div class="nav-btn">
                <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Keluar</a>
                <button id="btn-bar" class="">
                    <i class="fas fa-bars"></i>
                </button>
                <button id="btn-times" class="d-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

        </div>

        <div class="nav-mobile d-none" id="pop-menu">
            <ul>
                <li>
                    <a href="index.php">Dashboard</a>
                </li>
                <li>
                    <a href="anggota.php">Anggota</a>
                </li>
                <li>
                    <a href="kriteria.php">Kriteria</a>
                </li>
                <li>
                    <a href="nilai_alternatif.php">Nilai Alternatif</a>
                </li>
                <li>
                    <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Keluar</a>
                </li>
            </ul>
        </div>
    </nav>
