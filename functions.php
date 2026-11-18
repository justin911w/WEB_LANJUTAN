<?php
require_once 'config.php';

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Error: " . mysqli_error($conn));
    }
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function check_login() {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: login.php");
        exit;
    }
}

function get_all_mahasiswa() {
    $sql = "SELECT m.*, p.nama_prodi 
            FROM mahasiswa m 
            LEFT JOIN program_studi p ON m.id_prodi = p.id_prodi 
            ORDER BY m.id DESC";
    return query($sql);
}

function get_mahasiswa_by_id($id) {
    global $conn;
    $id = (int)$id;
    $sql = "SELECT * FROM mahasiswa WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function get_all_prodi() {
    return query("SELECT * FROM program_studi ORDER BY nama_prodi ASC");
}

/**
 * Fungsi Autogenerate NIM berdasarkan Prodi dan Tahun Angkatan
 * Format: [2 Digit Prefix Prodi][4 Digit Tahun Angkatan][3 Digit Nomor Urut]
 */
function generate_nim($id_prodi, $tahun_angkatan) {
    global $conn;
    
    $id_prodi = (int)$id_prodi;
    $tahun_angkatan = trim($tahun_angkatan);

    // Ambil kode_prefix dari tabel program_studi
    $prodi_query = mysqli_query($conn, "SELECT kode_prefix FROM program_studi WHERE id_prodi = $id_prodi");
    $prodi = mysqli_fetch_assoc($prodi_query);
    $prefix = $prodi['kode_prefix'] ?? '00';

    // Pola awalan NIM (contoh: 422025)
    $pattern = $prefix . $tahun_angkatan;

    // Cari NIM terakhir di database dengan pola awalan yang sama
    $query = "SELECT nim FROM mahasiswa WHERE nim LIKE '$pattern%' ORDER BY nim DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_nim = $row['nim'];
        $last_urut = (int)substr($last_nim, -3); // Ambil 3 digit terakhir
        $next_urut = $last_urut + 1;
    } else {
        $next_urut = 1;
    }

    // Format nomor urut menjadi 3 digit angka (contoh: 001)
    $urut_formatted = sprintf("%03d", $next_urut);

    return $pattern . $urut_formatted;
}

function tambah_mahasiswa($data) {
    global $conn;
    $id_prodi       = (int)$data['id_prodi'];
    $tahun_angkatan = htmlspecialchars(trim($data['tahun_angkatan']));
    
    // Generate NIM otomatis
    $nim = generate_nim($id_prodi, $tahun_angkatan);

    $nama        = htmlspecialchars(trim($data['nama']));
    $email       = htmlspecialchars(trim($data['email']));
    $password    = trim($data['password']);
    $nilai_tugas = (float)($data['nilai_tugas'] ?? 0);
    $nilai_uts   = (float)($data['nilai_uts'] ?? 0);
    $nilai_uas   = (float)($data['nilai_uas'] ?? 0);
    $kehadiran   = (int)($data['kehadiran'] ?? 0);

    $password_final = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : '123456';

    $query = "INSERT INTO mahasiswa (nim, nama, email, password, id_prodi, nilai_tugas, nilai_uts, nilai_uas, kehadiran) 
              VALUES ('$nim', '$nama', '$email', '$password_final', $id_prodi, $nilai_tugas, $nilai_uts, $nilai_uas, $kehadiran)";
    
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function edit_mahasiswa($data) {
    global $conn;
    $id           = (int)$data['id'];
    $new_id_prodi = (int)$data['id_prodi'];
    $new_tahun    = htmlspecialchars(trim($data['tahun_angkatan']));

    // Ambil data lama untuk komparasi prodi & tahun
    $mhs_lama     = get_mahasiswa_by_id($id);
    $old_nim      = $mhs_lama['nim'];
    $old_id_prodi = $mhs_lama['id_prodi'];
    $old_tahun    = (strlen($old_nim) >= 6) ? substr($old_nim, 2, 4) : '';

    // Jika prodi ATAU tahun angkatan berubah, generate NIM baru
    if ($old_id_prodi != $new_id_prodi || $old_tahun != $new_tahun) {
        $nim = generate_nim($new_id_prodi, $new_tahun);
    } else {
        $nim = $old_nim;
    }

    $nama        = htmlspecialchars(trim($data['nama']));
    $email       = htmlspecialchars(trim($data['email']));
    $nilai_tugas = (float)$data['nilai_tugas'];
    $nilai_uts   = (float)$data['nilai_uts'];
    $nilai_uas   = (float)$data['nilai_uas'];
    $kehadiran   = (int)$data['kehadiran'];

    if (!empty($data['password'])) {
        $password = password_hash(trim($data['password']), PASSWORD_DEFAULT);
        $query = "UPDATE mahasiswa SET 
                    nim = '$nim', 
                    nama = '$nama', 
                    email = '$email', 
                    password = '$password',
                    id_prodi = $new_id_prodi, 
                    nilai_tugas = $nilai_tugas, 
                    nilai_uts = $nilai_uts, 
                    nilai_uas = $nilai_uas, 
                    kehadiran = $kehadiran 
                  WHERE id = $id";
    } else {
        $query = "UPDATE mahasiswa SET 
                    nim = '$nim', 
                    nama = '$nama', 
                    email = '$email', 
                    id_prodi = $new_id_prodi, 
                    nilai_tugas = $nilai_tugas, 
                    nilai_uts = $nilai_uts, 
                    nilai_uas = $nilai_uas, 
                    kehadiran = $kehadiran 
                  WHERE id = $id";
    }
    
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function hapus_mahasiswa($id) {
    global $conn;
    $id = (int)$id;
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");
    return mysqli_affected_rows($conn);
}
?>