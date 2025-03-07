<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['timeout'] = 600; // Timeout dikurangi menjadi 30 detik untuk ujicoba

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: ../verifications/login.php");
    exit();
}

// Cek apakah user sudah tidak aktif selama timeout
if (isset($_SESSION['last_activity'])) {
    $inactive_time = time() - $_SESSION['last_activity'];

    if ($inactive_time > $_SESSION['timeout']) {
        session_unset(); // Hapus semua variabel sesi
        session_destroy(); // Hancurkan sesi
        header("Location: ../verifications/login.php?error=timeout");
        exit();
    }
}

// Perbarui waktu aktivitas terakhir
$_SESSION['last_activity'] = time();
