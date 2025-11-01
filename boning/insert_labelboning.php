<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "seriallabelboning.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idusers  = $_SESSION['idusers'];
    $idbarang = $_POST['idbarang'];
    $idgrade  = $_POST['idgrade'];
    $packdate = $_POST['packdate'];
    $exp      = $_POST['exp'];
    $idboning = $_POST['idboning'];
    $tenderstreachActive = isset($_POST['tenderstreach']);
    $qtyPcsInput = $_POST['qty'];

    // --- Ambil & validasi PH ---
    $ph = filter_input(INPUT_POST, 'ph', FILTER_VALIDATE_FLOAT);
    if ($ph === false || $ph < 5.4 || $ph > 5.7) {
        die('Nilai PH harus antara 5.4 dan 5.7');
    }
    // rapikan ke 1 desimal
    $ph = number_format($ph, 1, '.', '');

    // Pecah qty/pcs
    $qty = null;
    $pcs = null;
    if (strpos($qtyPcsInput, "/") !== false) {
        list($qty, $pcs) = explode("/", $qtyPcsInput);
    } else {
        $qty = $qtyPcsInput;
    }
    $qty = number_format((float)$qty, 2, '.', '');

    // Default pcs jika kosong
    if ($pcs === null || $pcs === '') {
        $pcs = "NULL"; // biarkan NULL di DB
    } else {
        $pcs = (int)$pcs;
    }

    $kdbarcode = "1" . $idboning . $kodeauto;

    // INSERT ke labelboning (tambahkan kolom ph)
    $queryInsertLabel = "
        INSERT INTO labelboning
            (idboning, idbarang, qty, pcs, packdate, kdbarcode, iduser, idgrade, ph)
        VALUES
            ('$idboning', '$idbarang', $qty, $pcs, '$packdate', '$kdbarcode', '$idusers', '$idgrade', $ph)
    ";
    mysqli_query($conn, $queryInsertLabel) or die('Gagal simpan labelboning: ' . mysqli_error($conn));

    // Ambil idlabelboning yang baru
    $idlabelboning = mysqli_insert_id($conn);

    // INSERT ke stock (boleh abaikan PH jika tabel stock tidak punya kolom ph)
    $queryInsertStock = "
        INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin, ph)
        VALUES ('$kdbarcode', '$idgrade', '$idbarang', $qty, $pcs, '$packdate', 1, $ph)
    ";
    mysqli_query($conn, $queryInsertStock) or die('Gagal simpan stock: ' . mysqli_error($conn));

    // Simpan ke session
    $_SESSION['idbarang'] = $_POST['idbarang'] ?? '';
    $_SESSION['idgrade']  = $_POST['idgrade'] ?? '';
    $_SESSION['packdate'] = $_POST['packdate'] ?? '';
    $_SESSION['exp']      = $_POST['exp'] ?? '';
    $_SESSION['ph']       = $ph;

    // Redirect ke cetak
    header("Location: print_labelboning.php?idlabelboning=$idlabelboning&idboning=$idboning");
    exit;
}
