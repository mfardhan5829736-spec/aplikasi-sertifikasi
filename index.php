<?php
require 'koneksi.php';

if(isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$user]);
    $data = $stmt->fetch();

    if($data && $pass == $data['password']) {
        $_SESSION['admin'] = $data['nama'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Pustaka Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 400px;">
        <div class="card p-4 shadow-sm text-center">
            
            <!-- Logo Utama dari Folder assets -->
            <div class="mb-3">
                <img src="assets/logo.jpg" alt="Logo Login" class="img-fluid rounded" style="max-height: 90px; object-fit: contain;" onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text=Logo+Tidak+Ditemukan';">
            </div>

            <h3 class="mb-3">Login Admin</h3>
            
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            
            <form method="POST" class="text-start">
                <!-- Input Username dengan Ikon -->
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person-fill text-primary"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                </div>

                <!-- Input Password dengan Ikon -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock-fill text-primary"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 mb-3">Login</button>
                
                <div class="text-center">
                    <small>Belum punya akun? <a href="register.php">Register di sini</a></small>
                </div>
            </form>
        </div>
    </div>
</body>
</html>