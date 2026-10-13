<?php
require 'koneksi.php';

if(isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Disarankan di-hash, tapi kita sesuaikan dengan sistem login sederhana Anda
    $username = $_POST['username']; // Opsional jika dibutuhkan untuk login

    // Cek apakah email sudah terdaftar
    $cek = $koneksi->prepare("SELECT * FROM admin WHERE email = ?");
    $cek->execute([$email]);
    
    if($cek->rowCount() > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $stmt = $koneksi->prepare("INSERT INTO admin (nama, email, username, password) VALUES (?, ?, ?, ?)");
        // Sesuaikan kolom dengan tabel database Anda jika tidak ada kolom username/nama
        $stmt->execute([$nama, $email, $username, $password]);
        
        $success = "Registrasi berhasil! Silakan <a href='index.php'>Login</a>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 400px;">
        <div class="card p-4 shadow-sm">
            <div class="text-center mb-3">
                <h3 class="fw-bold">Register Akun</h3>
                <p class="text-muted">Pendaftaran Akun Baru</p>
            </div>
            
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Username untuk login" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email aktif" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" name="register" class="btn btn-success w-100 mb-3">Daftar</button>
                <div class="text-center">
                    <small>Sudah punya akun? <a href="index.php">Login di sini</a></small>
                </div>
            </form>
        </div>
    </div>
</body>
</html>