<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "kdlabel.php"; // Mengambil $kodeauto dari kdlabel.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangkap data dari form
    $idusers = $_SESSION['idusers'];
    $idbarang = mysqli_real_escape_string($conn, $_POST['idbarang']);
    $idgrade = mysqli_real_escape_string($conn, $_POST['idgrade']);
    $pod = mysqli_real_escape_string($conn, $_POST['pod']); // Nama sebelumnya: pod
    $qtyPcsInput = mysqli_real_escape_string($conn, $_POST['qty']); // Input dari user (bisa "12.41" atau "12.41/5")

    // Validasi input tidak boleh kosong
    if (empty($idbarang) || empty($idgrade) || empty($pod) || empty($qtyPcsInput)) {
        echo "Data tidak boleh kosong!";
        exit;
    }

    // Memecah nilai qty dan pcs
    $qty = null;
    $pcs = null;
    if (strpos($qtyPcsInput, "/") !== false) {
        list($qty, $pcs) = explode("/", $qtyPcsInput);
    } else {
        $qty = $qtyPcsInput;
    }
    $qty = number_format($qty, 2, '.', ''); // Pastikan qty dalam format desimal (misal: 12.41)

    // Pastikan pcs memiliki nilai default jika kosong
    if (empty($pcs)) {
        $pcs = "NULL"; // Jika kolom di database bisa menerima NULL
    } else {
        $pcs = (int) $pcs; // Konversi ke integer agar tidak menyebabkan error MySQL
    }

    // **Pastikan $origin tersedia di stockin.php**
    if (!isset($origin)) {
        $origin = 7; // Default jika tidak didefinisikan
    }

    // Simpan data ke tabel stockin
    $queryInsertStockin = "INSERT INTO stockin (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin)
                           VALUES ('$kodeauto', '$idgrade', '$idbarang', $qty, $pcs, '$pod', $origin)";
    if (!mysqli_query($conn, $queryInsertStockin)) {
        die("Gagal memasukkan data ke stockin: " . mysqli_error($conn));
    }

    // Ambil ID terakhir yang diinsert ke stockin
    $idstockin = mysqli_insert_id($conn);

    // Simpan data ke tabel stock
    $queryInsertStock = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin)
                         VALUES ('$kodeauto', '$idgrade', '$idbarang', $qty, $pcs, '$pod', $origin)";
    if (!mysqli_query($conn, $queryInsertStock)) {
        die("Gagal memasukkan data ke stock: " . mysqli_error($conn));
    }

    // Simpan data sesi agar tetap tersimpan jika halaman di-refresh
    $_SESSION['idbarang'] = $_POST['idbarang'] ?? '';
    $_SESSION['idgrade'] = $_POST['idgrade'] ?? '';
    $_SESSION['pod'] = $_POST['pod'] ?? '';

    // Redirect ke halaman cetak label dengan membawa idstockin
    header("Location: print_labelstockin.php?idstockin=$idstockin");
    exit;
}
