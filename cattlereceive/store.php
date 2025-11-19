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
$sv_ok         = (isset($_POST['sv_ok']) && $_POST['sv_ok'] == '1') ? 1 : 0;
$skkh_ok       = (isset($_POST['skkh_ok']) && $_POST['skkh_ok'] == '1') ? 1 : 0;
$note          = trim($_POST['note'] ?? '');
$iduser        = isset($_SESSION['idusers']) ? (int)$_SESSION['idusers'] : null;

// Validasi header
if ($idpo <= 0)                          $errors[] = "PO tidak valid.";
if ($receipt_date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $receipt_date)) {
    $errors[] = "Receipt Date tidak valid (YYYY-MM-DD).";
}
if ($doc_no !== '' && mb_strlen($doc_no) > 50)  $errors[] = "Doc No maksimal 50 karakter.";
if (mb_strlen($note) > 255)                      $errors[] = "Note maksimal 255 karakter.";

// Validasi keberadaan PO & belum punya receive aktif
if (empty($errors)) {
    $cekpo = $conn->prepare("SELECT 1 FROM pocattle WHERE idpo=? AND is_deleted=0 LIMIT 1");
    if (!$cekpo) backWithError(["DB error."], $_POST, $idpo);
    $cekpo->bind_param("i", $idpo);
    $cekpo->execute();
    if (!$cekpo->get_result()->fetch_row()) {
        $errors[] = "PO tidak ditemukan / sudah dihapus.";
    } else {
        $cekrcv = $conn->prepare("SELECT 1 FROM cattle_receive WHERE idpo=? AND is_deleted=0 LIMIT 1");
        if (!$cekrcv) backWithError(["DB error."], $_POST, $idpo);
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
    $wt_raw = trim((string)($weight[$i] ?? ''));
    $nt = trim($notes[$i]  ?? '');

    // lewati baris kosong total
    if ($c === '' && $et === '' && $wt_raw === '' && $nt === '') continue;

    // normalisasi eartag
    $et_norm = strtoupper($et);

    // normalisasi desimal: koma -> titik
    $wt_norm = str_replace(',', '.', $wt_raw);

    // Validasi field
    if ($c === '' || !in_array($c, $allowedClass, true)) {
        $errors[] = "Class baris #" . ($i + 1) . " wajib/invalid.";
    }
    if ($et_norm === '') {
        $errors[] = "Eartag baris #" . ($i + 1) . " wajib.";
    }

    // Weight: terima 0; cek numeric & max 2 desimal
    // pola: angka bulat atau desimal dengan titik, max 2 desimal
    if ($wt_norm === '' || !preg_match('/^\d+(\.\d{1,2})?$/', $wt_norm) || (float)$wt_norm < 0) {
        $errors[] = "Weight baris #" . ($i + 1) . " harus angka >= 0, max 2 desimal.";
    }

    if (mb_strlen($nt) > 255) {
        $errors[] = "Notes baris #" . ($i + 1) . " maksimal 255 karakter.";
    }

    // simpan baris (round 2 desimal)
    $rows[] = [
        'class'  => $c,
        'eartag' => $et_norm,
        'weight' => round((float)$wt_norm, 2),
        'notes'  => $nt,
    ];

    // cek duplikat eartag pada input yang sama (case-insensitive)
    $key = strtolower($et_norm);
    if (isset($dupeCheck[$key])) {
        $errors[] = "Eartag duplikat pada baris #" . ($dupeCheck[$key]) . " dan #" . ($i + 1) . ".";
    } else {
        $dupeCheck[$key] = $i + 1;
    }
}

if (empty($rows)) $errors[] = "Minimal satu baris detail harus diisi.";

if (!empty($errors)) backWithError($errors, $_POST, $idpo);

// SIMPAN
try {
    $conn->begin_transaction();

    // Insert header
    $stmtH = $conn->prepare("
        INSERT INTO cattle_receive (idpo, receipt_date, doc_no, sv_ok, skkh_ok, note, is_deleted, creatime, createby)
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), ?)
    ");
    if (!$stmtH) {
        throw new Exception("Prepare header failed: " . $conn->error);
    }

    // types: idpo(i), receipt_date(s), doc_no(s), sv_ok(i), skkh_ok(i), note(s), iduser(i)
    $stmtH->bind_param("issiisi", $idpo, $receipt_date, $doc_no, $sv_ok, $skkh_ok, $note, $iduser);
    if (!$stmtH->execute()) {
        throw new Exception("Gagal menyimpan header: " . $stmtH->error);
    }
    $idreceive = $stmtH->insert_id;

    // Insert detail
    $stmtD = $conn->prepare("
        INSERT INTO cattle_receive_detail (idreceive, eartag, weight, class, notes, creatime, createby)
        VALUES (?, ?, ?, ?, ?, NOW(), ?)
    ");
    if (!$stmtD) {
        throw new Exception("Prepare detail failed: " . $conn->error);
    }

    // bind types: idreceive(i), eartag(s), weight(d), class(s), notes(s), iduser(i)
    foreach ($rows as $r) {
        $idrec = $idreceive;
        $eartagVal = $r['eartag'];
        $weightVal = $r['weight']; // float
        $classVal = $r['class'];
        $notesVal = $r['notes'];
        $stmtD->bind_param('isdssi', $idrec, $eartagVal, $weightVal, $classVal, $notesVal, $iduser);
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
