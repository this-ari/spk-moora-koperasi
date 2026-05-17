<?php
session_start();
include "includes/db_connect.php";

// Redirect jika sudah login
if (isset($_SESSION['id_admin'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        
        // Cek password
        if ($password === $admin['password']) {
            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['nama'] = $admin['nama'];
            $_SESSION['username'] = $admin['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Koperasi Salimah Sejahtera</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-left">
            <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" style="max-height: 350px;">
                <path d="M410.5,321.5Q356,393,260.5,417.5Q165,442,109.5,346Q54,250,111.5,157.5Q169,65,271,72.5Q373,80,419,165Q465,250,410.5,321.5Z" fill="#b2d4b2"></path>
                <g transform="translate(130, 140)">
                    <rect x="10" y="30" width="200" height="130" rx="15" fill="#2d5a2d"/>
                    <rect x="30" y="10" width="160" height="130" rx="15" fill="#ffffff" stroke="#2d5a2d" stroke-width="4"/>
                    <line x1="55" y1="45" x2="165" y2="45" stroke="#2d5a2d" stroke-width="6" stroke-linecap="round"/>
                    <line x1="55" y1="75" x2="135" y2="75" stroke="#2d5a2d" stroke-width="6" stroke-linecap="round"/>
                    <line x1="55" y1="105" x2="115" y2="105" stroke="#b2d4b2" stroke-width="6" stroke-linecap="round"/>
                    
                    <circle cx="155" cy="105" r="18" fill="#d4f4d4" stroke="#2d5a2d" stroke-width="3"/>
                    <path d="M148 105 L153 110 L163 98" stroke="#2d5a2d" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
            </svg>
            <h1>SPK Kelayakan Kredit</h1>
            <p>Anggota Koperasi Salimah Sejahtera</p>
        </div>
        <div class="login-right">
            <div class="login-form-wrapper">
                <h2>Login Admin</h2>
                <?php if ($error): ?>
                    <div class="alert"><?= $error ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required placeholder="Masukkan username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password">
                    </div>
                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>