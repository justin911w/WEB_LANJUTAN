<?php
require_once "functions.php";
requireAdmin();

$result = ambilSemuaMahasiswa($conn);
include_once "components/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Daftar Mahasiswa</h3>
        <p class="text-muted small mb-0">Kelola dan pantau seluruh data mahasiswa terdaftar</p>
    </div>
    <a href="tambah_mahasiswa.php" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Mahasiswa
    </a>
</div>

<div class="card card-custom p-3">
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Program Studi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($row['nim']) ?></span></td>
                            <td class="fw-semibold"><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['nama_prodi']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted display-4"></i>
            <p class="text-muted mt-2">Belum ada data mahasiswa yang ditambahkan.</p>
        </div>
    <?php endif; ?>
</div>

<?php include_once "components/footer.php"; ?>