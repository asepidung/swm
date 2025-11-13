<?php
require "../verifications/auth.php";
require "../konak/conn.php";

session_start();

function backWithError($errors, $old, $idreceive)
{
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old']    = $old;
    header("Location: edit.php?id=" . (int)$idreceive);
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    backWithError(['Invalid CSRF token.'], $_POST, (int)($_POST['idreceive'] ?? 0));
}

$idreceive    = (int)($_POST['idreceive'] ?? 0);
$idpo         = (int)($_POST['idpo'] ?? 0); // tidak diubah, hanya validasi
$receipt_date = trim($_POST['receipt_date'] ?? '');
$doc_no       = trim($_POST['doc_no'] ?? '');
$sv_ok        = (isset($_POST['sv_ok']) && $_POST['sv_ok'] == '1') ? 1 : 0;
$skkh_ok      = (isset($_POST['skkh_ok']) && $_POST['skkh_ok'] == '1') ? 1 : 0;
$note         = trim($_POST['note'] ?? '');
$iduser       = $_SESSION['idusers'] ?? null;

if ($idreceive <= 0) $errors[] = "Receive id tidak valid.";
if ($idpo <= 0)      $errors[] = "PO tidak valid.";
if ($receipt_date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $receipt_date))
    $errors[] = "Receipt Date tidak valid (YYYY-MM-DD).";
if ($doc_no !== '' && strlen($doc_no) > 50)  $errors[] = "Doc No maksimal 50 karakter.";
if (strlen($note) > 255)                      $errors[] = "Note maksimal 255 karakter.";

// Pastikan record ada & cocok dengan idpo
if (empty($errors)) {
    $cek = $conn->prepare("SELECT idpo FROM cattle_receive WHERE idreceive=? AND is_deleted=0 LIMIT 1");
    $cek->bind_param("i", $idreceive);
    $cek->execute();
    $row = $cek->get_result()->fetch_assoc();
    if (!$row) {
        $errors[] = "Receive tidak ditemukan.";
    } elseif ((int)$row['idpo'] !== $idpo) {
        $errors[] = "Data tidak konsisten (idpo).";
    }
}

// Detail arrays
$class  = $_POST['class']  ?? [];
$eartag = $_POST['eartag'] ?? [];
$weight = $_POST['weight'] ?? [];
$notes  = $_POST['notes']  ?? [];

// Validasi detail
$rows = [];
$allowedClass = ['STEER', 'BULL', 'HEIFER', 'COW'];
$dupe = [];

$cnt = max(count($class), count($eartag), count($weight), count($notes));
for ($i = 0; $i < $cnt; $i++) {
    $c  = strtoupper(trim($class[$i]  ?? ''));
    $et = trim($eartag[$i] ?? '');
    $wt = trim((string)($weight[$i] ?? ''));
    $nt = trim($notes[$i]  ?? '');

    if ($c === '' && $et === '' && $wt === '' && $nt === '') continue;

    if ($c === '' || !in_array($c, $allowedClass, true))
        $errors[] = "Class baris #" . ($i + 1) . " wajib/invalid.";
    if ($et === '') $errors[] = "Eartag baris #" . ($i + 1) . " wajib.";
    if ($wt === '' || !preg_match('/^\d+(\.\d{1,2})?$/', $wt) || (float)$wt <= 0)
        $errors[] = "Weight baris #" . ($i + 1) . " harus angka > 0, max 2 desimal.";
    if (strlen($nt) > 255)
        $errors[] = "Notes baris #" . ($i + 1) . " maksimal 255 karakter.";

    $rows[] = ['class' => $c, 'eartag' => $et, 'weight' => (float)$wt, 'notes' => $nt];

    $k = strtolower($et);
    if (isset($dupe[$k])) $errors[] = "Eartag duplikat di baris #" . $dupe[$k] . " dan #" . ($i + 1) . ".";
    else $dupe[$k] = $i + 1;
}

if (empty($rows)) $errors[] = "Minimal satu baris detail harus diisi.";

if (!empty($errors)) backWithError($errors, $_POST, $idreceive);

// SIMPAN
try {
    $conn->begin_transaction();

    // Update header
    $up = $conn->prepare("
    UPDATE cattle_receive
       SET receipt_date=?,
           doc_no=?,
           sv_ok=?,
           skkh_ok=?,
           note=?,
           updatetime=NOW(),
           updateby=?
     WHERE idreceive=? AND is_deleted=0
  ");
    $up->bind_param('ssiisii', $receipt_date, $doc_no, $sv_ok, $skkh_ok, $note, $iduser, $idreceive);
    if (!$up->execute()) throw new Exception("Gagal update header: " . $up->error);

    // Hapus semua detail lama
    $del = $conn->prepare("DELETE FROM cattle_receive_detail WHERE idreceive=?");
    $del->bind_param("i", $idreceive);
    if (!$del->execute()) throw new Exception("Gagal hapus detail: " . $del->error);

    // Insert ulang detail
    $ins = $conn->prepare("
    INSERT INTO cattle_receive_detail (idreceive, eartag, weight, class, notes, creatime, createby)
    VALUES (?,?,?,?,?,NOW(),?)
  ");
    foreach ($rows as $r) {
        $ins->bind_param('isdssi', $idreceive, $r['eartag'], $r['weight'], $r['class'], $r['notes'], $iduser);
        if (!$ins->execute()) throw new Exception("Gagal insert detail: " . $ins->error);
    }

    $conn->commit();
    header("Location: view.php?id=" . $idreceive . "&msg=updated");
    exit;
} catch (Throwable $e) {
    $conn->rollback();
    backWithError(['Transaksi gagal: ' . $e->getMessage()], $_POST, $idreceive);
}
