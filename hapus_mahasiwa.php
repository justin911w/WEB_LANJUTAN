<?php
require_once 'functions.php';
check_login();

$id = $_GET['id'] ?? null;

if ($id) {
    hapus_mahasiswa($id);
}

header("Location: dashboard.php");
exit;
?>