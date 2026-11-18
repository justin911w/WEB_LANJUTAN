<?php
require_once 'functions.php';
check_login();

$prodi_list = get_all_prodi();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (tambah_mahasiswa($_POST) > 0) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Gagal menambahkan data mahasiswa.";
    }
}

include 'components/header.php';
?>

<div class="container my-5" style="max-width: 600px;">
    <div class="bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">Tambah Data Mahasiswa</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="tambah_mahasiswa.php" method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password Sesi Mahasiswa</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika default (123456)">
            </div>
            <div class="mb-3">
                <label for="id_prodi" class="form-label">Program Studi</label>
                <select class="form-select" id="id_prodi" name="id_prodi" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <?php foreach ($prodi_list as $p): ?>
                        <option value="<?= $p['id_prodi']; ?>">
                            <?= htmlspecialchars($p['nama_prodi']); ?> (Prefix: <?= htmlspecialchars($p['kode_prefix']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="tahun_angkatan" class="form-label">Tahun Angkatan</label>
                <input type="number" class="form-control" id="tahun_angkatan" name="tahun_angkatan" value="<?= date('Y'); ?>" min="2000" max="2099" required>
                <small class="text-muted">*NIM akan dibuat otomatis: Prefix Prodi + Tahun Angkatan + Nomor Urut.</small>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nilai_tugas" class="form-label">Nilai Tugas</label>
                    <input type="number" step="0.01" class="form-control" id="nilai_tugas" name="nilai_tugas" value="0.00">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="nilai_uts" class="form-label">Nilai UTS</label>
                    <input type="number" step="0.01" class="form-control" id="nilai_uts" name="nilai_uts" value="0.00">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="nilai_uas" class="form-label">Nilai UAS</label>
                    <input type="number" step="0.01" class="form-control" id="nilai_uas" name="nilai_uas" value="0.00">
                </div>
            </div>
            <div class="mb-3">
                <label for="kehadiran" class="form-label">Kehadiran (Jumlah Pertemuan)</label>
                <input type="number" class="form-control" id="kehadiran" name="kehadiran" value="0">
            </div>
            <div class="d-flex justify-content-between">
                <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php include 'components/footer.php'; ?>