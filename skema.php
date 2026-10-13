<?php
require 'koneksi.php';
if(!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }

// Variable penampung awal untuk mode Tambah
$id_edit = '';
$kode_val = '';
$nama_val = '';

// 1. Logika Ambil Data saat Tombol 'Edit' Diklik
if(isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $stmt = $koneksi->prepare("SELECT * FROM skema WHERE id_skema = ?");
    $stmt->execute([$id_edit]);
    $data_edit = $stmt->fetch();

    if($data_edit) {
        $kode_val = $data_edit['kode_skema'];
        $nama_val = $data_edit['nama_skema'];
    }
}

// 2. Logika Simpan (Bisa Tambah Baru ATAU Ubah/Update)
if(isset($_POST['simpan'])) {
    $kode = $_POST['kode_skema'];
    $nama = $_POST['nama_skema'];
    $id   = $_POST['id_skema'];

    if($id != '') {
        // Jika ID ada, jalankan perintah UPDATE (Ubah)
        $stmt = $koneksi->prepare("UPDATE skema SET kode_skema = ?, nama_skema = ? WHERE id_skema = ?");
        $stmt->execute([$kode, $nama, $id]);
    } else {
        // Jika ID kosong, jalankan perintah INSERT (Tambah Baru)
        $stmt = $koneksi->prepare("INSERT INTO skema (kode_skema, nama_skema) VALUES (?, ?)");
        $stmt->execute([$kode, $nama]);
    }
    header("Location: skema.php");
}

// 3. Logika Hapus Data
if(isset($_GET['hapus'])) {
    $stmt = $koneksi->prepare("DELETE FROM skema WHERE id_skema = ?");
    $stmt->execute([$_GET['hapus']]);
    header("Location: skema.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Skema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Data Skema Sertifikasi</h2>
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <!-- Form Input (Otomatis berubah jadi mode Edit jika tombol Edit diklik) -->
        <form method="POST" class="row g-3 mb-4 card p-3 shadow-sm">
            <!-- Hidden input untuk menyimpan ID saat mode Edit -->
            <input type="hidden" name="id_skema" value="<?= $id_edit ?>">

            <div class="col-md-4">
                <label class="form-label font-weight-bold">Kode Skema</label>
                <input type="text" name="kode_skema" class="form-control" value="<?= $kode_val ?>" placeholder="Contoh: SKM-JWD-01" required>
            </div>
            <div class="col-md-5">
                <label class="form-label font-weight-bold">Nama Skema</label>
                <input type="text" name="nama_skema" class="form-control" value="<?= $nama_val ?>" placeholder="Contoh: Junior Web Developer" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" name="simpan" class="btn btn-<?= $id_edit ? 'warning' : 'success' ?> w-100">
                    <?= $id_edit ? 'Update Data' : 'Tambah Skema' ?>
                </button>
            </div>
            <?php if($id_edit) { ?>
                <div class="col-12">
                    <a href="skema.php" class="btn btn-sm btn-link text-secondary">Batal Edit</a>
                </div>
            <?php } ?>
        </form>

        <!-- Tabel Tampil Data Skema -->
        <div class="card p-3 shadow-sm">
            <table class="table table-striped table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode Skema</th>
                        <th>Nama Skema</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $koneksi->query("SELECT * FROM skema");
                    $no = 1;
                    while($row = $stmt->fetch()) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['kode_skema']) ?></td>
                            <td><?= htmlspecialchars($row['nama_skema']) ?></td>
                            <td>
                                <!-- Tombol Edit -->
                                <a href="skema.php?edit=<?= $row['id_skema'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                
                                <!-- Tombol Hapus -->
                                <a href="skema.php?hapus=<?= $row['id_skema'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>