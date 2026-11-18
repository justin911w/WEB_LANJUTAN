<?php
require_once 'functions.php';
check_login();

$mahasiswa = get_all_mahasiswa();
include 'components/header.php';
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Mahasiswa</h2>
        <a href="tambah_mahasiswa.php" class="btn btn-primary">+ Tambah Mahasiswa</a>
    </div>

    <div class="table-responsive bg-white p-3 rounded shadow-sm">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Email</th>
                    <th>Kehadiran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mahasiswa)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data mahasiswa.</td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($mahasiswa as $row): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($row['nim']); ?></td>
                            <td><?= htmlspecialchars($row['nama']); ?></td>
                            <td><?= htmlspecialchars($row['nama_prodi'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['kehadiran']); ?> Pertemuan</td>
                            <td>
                                <a href="edit_mahasiswa.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus_mahasiwa.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'components/footer.php'; ?>