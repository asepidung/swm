<?php
require "../verifications/auth.php";
require "../konak/conn.php";

session_start();

function backWithError($errors, $old, $idpo)
{
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old']    = $old;
    header("Location: create.php?idpo=" . (int)$idpo);
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    backWithError(['Invalid CSRF token.'], $_POST, (int)($_POST['idpo'] ?? 0));
}

$idpo          = (int)($_POST['idpo'] ?? 0);
$receipt_date  = trim($_POST['receipt_date'] ?? '');
$doc_no        = trim($_POST['doc_no'] ?? '');
$sv_ok         = (isset($_POST['sv_ok']) && $_POST['sv_ok'] == '1') ? 1 : 0;     // checkbox on/off
$skkh_ok       = (isset($_POST['skkh_ok']) && $_POST['skkh_ok'] == '1') ? 1 : 0; // checkbox on/off
$note          = trim($_POST['note'] ?? '');
$iduser        = $_SESSION['idusers'] ?? null;

// Validasi header
if ($idpo <= 0)                          $errors[] = "PO tidak valid.";
if ($receipt_date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $receipt_date)) {
    $errors[] = "Receipt Date tidak valid (YYYY-MM-DD).";
}
if ($doc_no !== '' && strlen($doc_no) > 50)  $errors[] = "Doc No maksimal 50 karakter.";
if (strlen($note) > 255)                      $errors[] = "Note maksimal 255 karakter.";

// Validasi keberadaan PO & belum punya receive aktif
if (empty($errors)) {
    // PO ada dan aktif?
    $cekpo = $conn->prepare("SELECT 1 FROM pocattle WHERE idpo=? AND is_deleted=0 LIMIT 1");
    $cekpo->bind_param("i", $idpo);
    $cekpo->execute();
    if (!$cekpo->get_result()->fetch_row()) {
        $errors[] = "PO tidak ditemukan / sudah dihapus.";
    } else {
        // belum pernah receive aktif?
        $cekrcv = $conn->prepare("SELECT 1 FROM cattle_receive WHERE idpo=? AND is_deleted=0 LIMIT 1");
        $cekrcv->bind_param("i", $idpo);
        $cekrcv->execute();
        if ($cekrcv->get_result()->fetch_row()) {
            $errors[] = "PO ini sudah memiliki penerimaan aktif.";
        }
    }
}

// Detail arrays (tanpa RFID)
$class  = $_POST['class']  ?? [];
$eartag = $_POST['eartag'] ?? [];
$weight = $_POST['weight'] ?? [];
$notes  = $_POST['notes']  ?? [];

// Validasi detail
$rows = [];
$allowedClass = ['STEER', 'BULL', 'HEIFER', 'COW'];
$dupeCheck = [];

$cnt = max(count($class), count($eartag), count($weight), count($notes));
for ($i = 0; $i < $cnt; $i++) {
    $c  = strtoupper(trim($class[$i]  ?? ''));
    $et = trim($eartag[$i] ?? '');
    $wt = trim((string)($weight[$i] ?? ''));
    $nt = trim($notes[$i]  ?? '');

    // lewati baris kosong total
    if ($c === '' && $et === '' && $wt === '' && $nt === '') continue;

    if ($c === '' || !in_array($c, $allowedClass, true))
        $errors[] = "Class baris #" . ($i + 1) . " wajib/invalid.";
    if ($et === '')
        $errors[] = "Eartag baris #" . ($i + 1) . " wajib.";
    if ($wt === '' || !preg_match('/^\d+(\.\d{1,2})?$/', $wt) || (float)$wt <= 0)
        $errors[] = "Weight baris #" . ($i + 1) . " harus angka > 0, max 2 desimal.";
    if (strlen($nt) > 255)
        $errors[] = "Notes baris #" . ($i + 1) . " maksimal 255 karakter.";

    // simpan baris
    $rows[] = [
        'class'  => $c,
        'eartag' => $et,
        'weight' => (float)$wt,
        'notes'  => $nt,
    ];

    // cek duplikat eartag pada input yang sama
    $key = strtolower($et);
    if (isset($dupeCheck[$key])) {
        $errors[] = "Eartag duplikat pada baris #" . ($dupeCheck[$key]) . " dan #" . ($i + 1) . ".";
    } else {
        $dupeCheck[$key] = $i + 1;
    }
}

if (empty($rows)) $errors[] = "Minimal satu baris detail harus diisi.";

// (Opsional) Cek eartag duplikat di database untuk PO ini (jika ada receive lain yang soft-delete?)
// -> Tidak wajib, karena UNIQUE antar receive tidak diminta. Tambah jika diperlukan.

if (!empty($errors)) backWithError($errors, $_POST, $idpo);

// SIMPAN
try {
    $conn->begin_transaction();

    // Insert header
    $stmtH = $conn->prepare("
        INSERT INTO cattle_receive (idpo, receipt_date, doc_no, sv_ok, skkh_ok, note, is_deleted, creatime, createby)
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), ?)
    ");
    $stmtH->bind_param("ississi", $idpo, $receipt_date, $doc_no, $sv_ok, $skkh_ok, $note, $iduser);
    if (!$stmtH->execute()) {
        throw new Exception("Gagal menyimpan header: " . $stmtH->error);
    }
    $idreceive = $stmtH->insert_id;

    // Insert detail (rfid tidak diisi → kolom diabaikan)
    $stmtD = $conn->prepare("
        INSERT INTO cattle_receive_detail (idreceive, eartag, weight, class, notes, creatime, createby)
        VALUES (?, ?, ?, ?, ?, NOW(), ?)
    ");
    foreach ($rows as $r) {
        $stmtD->bind_param('isdssi', $idreceive, $r['eartag'], $r['weight'], $r['class'], $r['notes'], $iduser);
        if (!$stmtD->execute()) {
            throw new Exception("Gagal menyimpan detail: " . $stmtD->error);
        }
    }

    $conn->commit();
    header("Location: view.php?id=" . $idreceive);
    exit;
} catch (Throwable $e) {
    $conn->rollback();
    backWithError(['Transaksi gagal: ' . $e->getMessage()], $_POST, $idpo);
}
