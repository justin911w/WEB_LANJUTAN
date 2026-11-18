<?php
// Aktifkan error reporting untuk mendeteksi error penyebab layar putih
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Mulai sesi jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "student_portal";

$conn = mysqli_connect($host,$user, $pass,$db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>