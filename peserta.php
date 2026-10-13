<?php
require 'koneksi.php';

// Proteksi halaman
if(!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// =========================
// PROSES TAMBAH DATA
// =========================
if(isset($_POST['tambah'])) {
    $nama = $_POST['nama_peserta'];
    $email = $_POST['email'];
    $nik = $_POST['nik'];
    $telepon = $_POST['telepon'];
    $id_skema = $_POST['id_skema'];
    $detail = $_POST['detail'];

    $stmt = $koneksi->prepare("
        INSERT INTO peserta (nama_peserta, email, nik, telepon, id_skema, detail)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nama, $email, $nik, $telepon, $id_skema, $detail]);

    header("Location: peserta.php");
    exit;
}

// =========================
// PROSES EDIT DATA
// =========================
if(isset($_POST['edit'])) {
    $id = $_POST['id_peserta'];
    $nama = $_POST['nama_peserta'];
    $email = $_POST['email'];
    $nik = $_POST['nik'];
    $telepon = $_POST['telepon'];
    $id_skema = $_POST['id_skema'];
    $detail = $_POST['detail'];

    $stmt = $koneksi->prepare("
        UPDATE peserta SET
        nama_peserta = ?,
        email = ?,
        nik = ?,
        telepon = ?,
        id_skema = ?,
        detail = ?
        WHERE id_peserta = ?
    ");
    $stmt->execute([$nama, $email, $nik, $telepon, $id_skema, $detail, $id]);

    header("Location: peserta.php");
    exit;
}

// =========================
// PROSES HAPUS
// =========================
if(isset($_GET['hapus'])) {
    $stmt = $koneksi->prepare("DELETE FROM peserta WHERE id_peserta = ?");
    $stmt->execute([$_GET['hapus']]);
    header("Location: peserta.php");
    exit;
}

// =========================
// DATA UNTUK EDIT
// =========================
$dataEdit = null;
if(isset($_GET['edit'])) {
    $stmt = $koneksi->prepare("SELECT * FROM peserta WHERE id_peserta = ?");
    $stmt->execute([$_GET['edit']]);
    $dataEdit = $stmt->fetch();
}

// =========================
// DATA UNTUK DETAIL
// =========================
$dataDetail = null;
if(isset($_GET['detail'])) {
    $stmt = $koneksi->prepare("
        SELECT peserta.*, skema.nama_skema
        FROM peserta
        JOIN skema ON peserta.id_skema = skema.id_skema
        WHERE peserta.id_peserta = ?
    ");
    $stmt->execute([$_GET['detail']]);
    $dataDetail = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="p-4 bg-light">
<div class="container">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Peserta</h2>
        <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <!-- DETAIL PESERTA -->
    <?php if($dataDetail): ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <b>Detail Peserta</b>
            </div>
            <div class="card-body">
                <p><b>Nama:</b> <?= htmlspecialchars($dataDetail['nama_peserta']) ?></p>
                <p><b>Email:</b> <?= htmlspecialchars($dataDetail['email']) ?></p>
                <p><b>NIK:</b> <?= htmlspecialchars($dataDetail['nik']) ?></p>
                <p><b>No. Telepon:</b> <?= htmlspecialchars($dataDetail['telepon']) ?></p>
                <p><b>Skema Sertifikasi:</b> <?= htmlspecialchars($dataDetail['nama_skema']) ?></p>
                <p><b>Detail:</b><br><?= nl2br(htmlspecialchars($dataDetail['detail'])) ?></p>
                <a href="peserta.php" class="btn btn-secondary">Tutup</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- FORM TAMBAH / EDIT -->
    <form method="POST" class="row g-3 mb-4 card p-3 shadow-sm">
        <?php if($dataEdit): ?>
            <input type="hidden" name="id_peserta" value="<?= $dataEdit['id_peserta'] ?>">
        <?php endif; ?>

        <div class="col-md-3">
            <label class="form-label font-weight-bold">Nama Peserta</label>
            <input type="text" name="nama_peserta" class="form-control" placeholder="Nama Peserta" required value="<?= $dataEdit ? htmlspecialchars($dataEdit['nama_peserta']) : '' ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label font-weight-bold">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= $dataEdit ? htmlspecialchars($dataEdit['email']) : '' ?>">
        </div>

        <!-- Input NIK (Maksimal 16 angka) -->
        <div class="col-md-3">
            <label class="form-label font-weight-bold">NIK / No. KTP</label>
            <input type="text" name="nik" class="form-control" placeholder="16 Angka NIK" maxlength="16" pattern="\d{16}" title="NIK harus tepat 16 digit angka" required value="<?= $dataEdit ? htmlspecialchars($dataEdit['nik']) : '' ?>">
        </div>

        <!-- Input Telepon (Maksimal 12 angka) -->
        <div class="col-md-3">
            <label class="form-label font-weight-bold">No. Telepon</label>
            <input type="text" name="telepon" class="form-control" placeholder="Maks 12 angka" maxlength="12" pattern="\d{1,12}" title="Nomor telepon maksimal 12 digit angka" required value="<?= $dataEdit ? htmlspecialchars($dataEdit['telepon']) : '' ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label font-weight-bold">Skema Sertifikasi</label>
            <select name="id_skema" class="form-control" required>
                <option value="">-- Pilih Skema --</option>
                <?php
                $skema = $koneksi->query("SELECT * FROM skema")->fetchAll();
                foreach($skema as $s) {
                    $selected = ($dataEdit && $dataEdit['id_skema'] == $s['id_skema']) ? "selected" : "";
                    echo "<option value='{$s['id_skema']}' $selected>" . htmlspecialchars($s['nama_skema']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-8">
            <label class="form-label font-weight-bold">Detail</label>
            <textarea name="detail" class="form-control" rows="1" placeholder="Masukkan detail peserta..."><?= $dataEdit ? htmlspecialchars($dataEdit['detail']) : '' ?></textarea>
        </div>

        <div class="col-md-12">
            <?php if($dataEdit): ?>
                <button type="submit" name="edit" class="btn btn-warning">Simpan Perubahan</button>
                <a href="peserta.php" class="btn btn-secondary">Batal</a>
            <?php else: ?>
                <button type="submit" name="tambah" class="btn btn-success">Tambah Data</button>
            <?php endif; ?>
        </div>
    </form>

    <!-- PENCARIAN & TABEL DATA -->
    <div class="card p-3 shadow-sm">
        <div class="row mb-3">
            <div class="col-md-5">
                <form method="GET" class="input-group">
                    <input type="text" name="cari" class="form-control" placeholder="Cari nama atau email peserta..." value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    <?php if(isset($_GET['cari']) && $_GET['cari'] != ''): ?>
                        <a href="peserta.php" class="btn btn-outline-secondary">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NIK</th>
                    <th>Telepon</th>
                    <th>Skema Sertifikasi</th>
                    <th width="190">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $keyword = isset($_GET['cari']) ? trim($_GET['cari']) : '';
                if($keyword != '') {
                    $stmt = $koneksi->prepare("
                        SELECT peserta.*, skema.nama_skema
                        FROM peserta
                        JOIN skema ON peserta.id_skema = skema.id_skema
                        WHERE peserta.nama_peserta LIKE ? OR peserta.email LIKE ?
                    ");
                    $stmt->execute(["%$keyword%", "%$keyword%"]);
                } else {
                    $stmt = $koneksi->query("
                        SELECT peserta.*, skema.nama_skema
                        FROM peserta
                        JOIN skema ON peserta.id_skema = skema.id_skema
                    ");
                }

                $no = 1;
                $rows = $stmt->fetchAll();
                if(count($rows) > 0) {
                    foreach($rows as $row) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['nik']) ?></td>
                        <td><?= htmlspecialchars($row['telepon']) ?></td>
                        <td><?= htmlspecialchars($row['nama_skema']) ?></td>
                        <td>
                            <a href="peserta.php?detail=<?= $row['id_peserta'] ?>" class="btn btn-sm btn-primary">Detail</a>
                            <a href="peserta.php?edit=<?= $row['id_peserta'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="peserta.php?hapus=<?= $row['id_peserta'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted'>Tidak ada data ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>