<?php
require "../verifications/auth.php";
require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die("Akses tidak diizinkan.");

$idusers = (int)($_POST['idusers'] ?? ($_SESSION['idusers'] ?? 0));
$noother = trim($_POST['noother'] ?? '');
$tgl     = $_POST['tgl'] ?? date('Y-m-d');
$note    = trim($_POST['note'] ?? '');

$idrawmate = $_POST['idrawmate'] ?? [];
$qty       = $_POST['qty'] ?? [];
$row_note  = $_POST['row_note'] ?? [];

if ($noother === '' || empty($idrawmate)) die("Data tidak lengkap.");

/* 1) Insert header usage_other (atau pakai existing jika noother sama) */
$stmtH = $conn->prepare("INSERT INTO usage_other (noother, tgl, note, idusers) VALUES (?, ?, ?, ?)
                         ON DUPLICATE KEY UPDATE tgl=VALUES(tgl), note=VALUES(note), idusers=VALUES(idusers)");
$stmtH->bind_param("sssi", $noother, $tgl, $note, $idusers);
$stmtH->execute();

/* Ambil idother (LAST_INSERT_ID atau id existing by noother) */
$idother = $conn->insert_id;
if ($idother == 0) {
    $q = $conn->prepare("SELECT idother FROM usage_other WHERE noother=? LIMIT 1");
    $q->bind_param("s", $noother);
    $q->execute();
    $r = $q->get_result()->fetch_assoc();
    $idother = (int)$r['idother'];
    $q->close();
}
$stmtH->close();

$sumber = 'LAINNYA';
$idsumber = $idother;

/* 2) Bersihkan raw_usage lama untuk dokumen ini (jika re-save) */
$del = $conn->prepare("DELETE FROM raw_usage WHERE sumber=? AND idsumber=?");
$del->bind_param("si", $sumber, $idsumber);
$del->execute();
$del->close();

/* 3) Simpan baris ke raw_usage */
$ins = $conn->prepare("INSERT INTO raw_usage (sumber, idsumber, idrawmate, qty, note, iduser, createtime)
                       VALUES (?, ?, ?, ?, ?, ?, NOW())");
for ($i = 0; $i < count($idrawmate); $i++) {
    $idr = (int)$idrawmate[$i];
    $qv  = (float)($qty[$i] ?? 0);
    if ($idr <= 0 || $qv <= 0) continue;
    $rn  = trim($row_note[$i] ?? '');
    $ins->bind_param("siidis", $sumber, $idsumber, $idr, $qv, $rn, $idusers);
    $ins->execute();
}
$ins->close();

/* 4) Redirect ke laporan */
header("Location: laporan_rawusage_other.php?id=$idsumber&msg=" . urlencode("Pengeluaran lainnya berhasil disimpan."));
exit;
