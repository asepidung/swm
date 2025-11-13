<?php
require "../verifications/auth.php";
require "../konak/conn.php";

session_start();

function backWithError($errors, $old)
{
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old']    = $old;
    header("Location: newpocattle.php");
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: newpocattle.php");
    exit;
}

// CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    backWithError(['Invalid CSRF token.'], $_POST);
}

/**
 * ===== NOMOR PO dari ponumber.php =====
 * Pastikan file ponumber.php MENGISI variabel $nopocattle dan TIDAK echo.
 * Jika ponumber.php masih echo, kita buang output dengan buffer agar halaman tetap bersih.
 */
ob_start();
include "ponumber.php";   // harus set $nopocattle
ob_end_clean();

if (empty($nopocattle)) {
    backWithError(['Nomor PO gagal dibuat. Pastikan ponumber.php mengeset $nopocattle.'], $_POST);
}

// ===== INPUT HEADER =====
$podate       = trim($_POST['podate'] ?? '');
$arrival_date = trim($_POST['arrival_date'] ?? '');
$idsupplier   = $_POST['idsupplier'] ?? '';
$note         = trim($_POST['note'] ?? '');
$iduser       = $_SESSION['idusers'] ?? null;

// ===== INPUT DETAIL (ARRAY) =====
$class  = $_POST['class']  ?? [];
$qty    = $_POST['qty']    ?? [];
$price  = $_POST['price']  ?? [];
$notes  = $_POST['notes']  ?? [];

// ===== VALIDASI HEADER =====
if ($podate === '')        $errors[] = "Tanggal PO wajib.";
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $podate)) $errors[] = "Format PO Date tidak valid (YYYY-MM-DD).";
if ($arrival_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $arrival_date)) $errors[] = "Format Arrival Date tidak valid.";
if ($idsupplier === '' || !ctype_digit((string)$idsupplier)) $errors[] = "Supplier wajib.";

// ===== VALIDASI DETAIL =====
$rows = [];
for ($i = 0; $i < count($class); $i++) {
    $c  = trim($class[$i] ?? '');
    $qv = (string)($qty[$i] ?? '');
    $pv = trim((string)($price[$i] ?? ''));
    $nt = trim($notes[$i] ?? '');

    // lewati baris yang benar-benar kosong
    if ($c === '' && $qv === '' && $pv === '' && $nt === '') continue;

    if ($c === '') {
        $errors[] = "Cattle Class baris #" . ($i + 1) . " wajib.";
    }
    if ($qv === '' || !ctype_digit($qv) || (int)$qv <= 0) {
        $errors[] = "Qty baris #" . ($i + 1) . " harus angka bulat > 0.";
    }
    if ($pv !== '' && !preg_match('/^\d+(\.\d{1,2})?$/', $pv)) {
        $errors[] = "Price baris #" . ($i + 1) . " tidak valid. Gunakan titik desimal, max 2 angka desimal.";
    }

    $rows[] = [
        'class' => $c,
        'qty'   => (int)$qv,
        'price' => $pv,  // string; akan di-NULLIF saat insert
        'notes' => $nt,
    ];
}
if (empty($rows)) $errors[] = "Minimal satu baris detail harus diisi.";

// Cek unik NOPO (jaga-jaga kalau ponumber dipakai bersamaan user lain)
if (empty($errors)) {
    $stmt = $conn->prepare("SELECT 1 FROM pocattle WHERE nopo = ? LIMIT 1");
    $stmt->bind_param('s', $nopocattle);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "No. PO duplikat. Silakan submit ulang.";
    }
}

if (!empty($errors)) {
    backWithError($errors, $_POST);
}

// ===== SIMPAN (TRANSAKSI) =====
try {
    $conn->begin_transaction();

    $arrivalDB = ($arrival_date === '' ? null : $arrival_date);

    // Header (TANPA kolom status)
    $sqlH = "INSERT INTO pocattle (nopo, podate, arrival_date, idsupplier, note, creatime, createby)
             VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    $stmtH = $conn->prepare($sqlH);
    $stmtH->bind_param('sssisi', $nopocattle, $podate, $arrivalDB, $idsupplier, $note, $iduser);
    if (!$stmtH->execute()) {
        throw new Exception("Gagal menyimpan header: " . $stmtH->error);
    }
    $idpo = $stmtH->insert_id;

    // Detail
    $sqlD = "INSERT INTO pocattledetail (idpo, class, qty, price, notes, creatime, createby)
             VALUES (?, ?, ?, NULLIF(?,''), ?, NOW(), ?)";
    $stmtD = $conn->prepare($sqlD);

    foreach ($rows as $r) {
        $stmtD->bind_param('isissi', $idpo, $r['class'], $r['qty'], $r['price'], $r['notes'], $iduser);
        if (!$stmtD->execute()) {
            throw new Exception("Gagal menyimpan detail: " . $stmtD->error);
        }
    }

    $conn->commit();
    header("Location: index.php?msg=created");
    exit;
} catch (Exception $ex) {
    $conn->rollback();
    backWithError(['Transaksi gagal: ' . $ex->getMessage()], $_POST);
}
