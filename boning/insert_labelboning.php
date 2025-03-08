<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "seriallabelboning.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idusers = $_SESSION['idusers'];
    $idbarang = $_POST['idbarang'];
    $idgrade = $_POST['idgrade'];
    $packdate = $_POST['packdate'];
    $exp = $_POST['exp'];
    $idboning = $_POST['idboning'];
    $tenderstreachActive = isset($_POST['tenderstreach']) ? true : false;
    $qtyPcsInput = $_POST['qty'];

    // Memecah nilai qty dan pcs
    $qty = null;
    $pcs = null;
    if (strpos($qtyPcsInput, "/") !== false) {
        list($qty, $pcs) = explode("/", $qtyPcsInput);
    } else {
        $qty = $qtyPcsInput;
    }
    $qty = number_format($qty, 2, '.', '');

    // Pastikan pcs memiliki nilai default jika kosong
    if (empty($pcs)) {
        $pcs = "NULL"; // Jika kolom di database bisa menerima NULL
    } else {
        $pcs = (int) $pcs; // Konversi ke integer agar MySQL tidak error
    }

    $kdbarcode = "1" . $idboning . $kodeauto;

    // Debugging opsional (bisa dihapus setelah tidak dibutuhkan)
    // var_dump($pcs);
    // exit;

    // Simpan data ke tabel labelboning
    $queryInsertLabel = "INSERT INTO labelboning (idboning, idbarang, qty, pcs, packdate, kdbarcode, iduser, idgrade)
                         VALUES ('$idboning', '$idbarang', $qty, $pcs, '$packdate', '$kdbarcode', '$idusers', '$idgrade')";
    mysqli_query($conn, $queryInsertLabel);

    // Ambil idlabelboning yang baru saja di-insert
    $idlabelboning = mysqli_insert_id($conn);

    // Simpan data ke tabel stock
    $queryInsertStock = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin)
                         VALUES ('$kdbarcode', '$idgrade', '$idbarang', $qty, $pcs, '$packdate', 1)";
    mysqli_query($conn, $queryInsertStock);

    $_SESSION['idbarang'] = $_POST['idbarang'] ?? '';
    $_SESSION['idgrade'] = $_POST['idgrade'] ?? '';
    $_SESSION['packdate'] = $_POST['packdate'] ?? '';
    $_SESSION['exp'] = $_POST['exp'] ?? '';

    // Redirect ke halaman cetak dengan idlabelboning dan idboning
    header("Location: print_labelboning.php?idlabelboning=$idlabelboning&idboning=$idboning");
    exit;
}
