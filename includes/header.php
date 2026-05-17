<?php
session_start();
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Koperasi Salimah Sejahtera</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" type="text/css" href="assets/icon/css/all.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="assets/css/datatables.css" />
</head>

<body>