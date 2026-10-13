<?php
require 'koneksi.php';
// Tendang kembali ke login jika belum ada session
if(!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }

// Menghitung jumlah data untuk indikator ringkasan
$jml_peserta = $koneksi->query("SELECT COUNT(*) FROM peserta")->fetchColumn();
$jml_skema = $koneksi->query("SELECT COUNT(*) FROM skema")->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Selamat Datang, <?= $_SESSION['admin'] ?>!</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Menu Navigasi -->
        <div class="mb-4">
            <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
            <a href="skema.php" class="btn btn-outline-primary">Kelola Skema</a>
            <a href="peserta.php" class="btn btn-outline-primary">Kelola Peserta</a>
        </div>

        <!-- Indikator Ringkasan Data -->
        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3 p-3">
                    <h4>Total Skema Sertifikasi</h4>
                    <h2><?= $jml_skema ?> Skema</h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-info mb-3 p-3">
                    <h4>Total Peserta Terdaftar</h4>
                    <h2><?= $jml_peserta ?> Peserta</h2>
                </div>
            </div>
        </div>
    </div>
</body>
</html>