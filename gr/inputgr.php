<?php
// inputgr.php (perbaikan: tidak lagi mengandalkan $_POST['submit'])
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../verifications/auth.php";
require "../konak/conn.php";
include "grnumber.php";     // harus menghasilkan $gr
include "idtransaksi.php";  // harus menghasilkan $idtransaksi

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit();
}

// ambil input dengan sanitasi dasar
$deliveryat   = isset($_POST['deliveryat']) ? trim($_POST['deliveryat']) : null;
$idsupplier   = isset($_POST['idsupplier']) ? intval($_POST['idsupplier']) : 0;
$note         = isset($_POST['note']) && trim($_POST['note']) !== '' ? trim($_POST['note']) : '-';
$idpo         = isset($_POST['idpo']) ? intval($_POST['idpo']) : 0;
$idusers      = isset($_SESSION['idusers']) ? intval($_SESSION['idusers']) : 0;
$suppcode     = isset($_POST['suppcode']) ? trim($_POST['suppcode']) : '';
$idrawmate    = isset($_POST['idrawmate']) ? $_POST['idrawmate'] : [];
$received_qty = isset($_POST['received_qty']) ? $_POST['received_qty'] : [];

// cek existence $gr dan $idtransaksi
if (!isset($gr) || $gr === '') {
    echo "Error: GR number not provided (grnumber.php did not set \$gr).";
    exit();
}
if (!isset($idtransaksi) || $idtransaksi === '') {
    echo "Error: idtransaksi not provided (idtransaksi.php did not set \$idtransaksi).";
    exit();
}

// validasi input penting
if (empty($deliveryat) || $idsupplier <= 0 || $idpo <= 0 || $idusers <= 0) {
    echo "Error: Missing required header fields.";
    exit();
}
if (empty($idrawmate) || empty($received_qty) || !is_array($idrawmate) || !is_array($received_qty)) {
    echo "Error: Invalid rawmate or received_qty data.";
    exit();
}
if (count($idrawmate) !== count($received_qty)) {
    echo "Error: Mismatch between idrawmate and received_qty counts.";
    exit();
}

// mulai transaksi
$conn->autocommit(false);

$stmt_gr = $stmt_podetail = $stmt_grdetail = $stmt_stockraw = $stmt_update = $stmt_idrequest = $stmt_update_request = $stmtLogActivity = null;

try {
    // 1) insert ke grraw
    $query_gr = "INSERT INTO grraw (grnumber, receivedate, idsupplier, note, idusers, idpo, suppcode) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_gr = $conn->prepare($query_gr);
    if ($stmt_gr === false) {
        throw new Exception("Prepare gr failed: " . $conn->error);
    }
    // types: s (gr), s (date), i (idsupplier), s (note), i (idusers), i (idpo), s (suppcode)
    $stmt_gr->bind_param("ssisiis", $gr, $deliveryat, $idsupplier, $note, $idusers, $idpo, $suppcode);
    if (!$stmt_gr->execute()) {
        throw new Exception("Execute gr failed: " . $stmt_gr->error);
    }
    $idgr = $conn->insert_id; // idgr yang baru

    // 2) ambil order_qty dari podetail (map per idrawmate)
    $query_podetail = "SELECT idrawmate, qty AS order_qty FROM podetail WHERE idpo = ?";
    $stmt_podetail = $conn->prepare($query_podetail);
    if ($stmt_podetail === false) {
        throw new Exception("Prepare podetail failed: " . $conn->error);
    }
    $stmt_podetail->bind_param("i", $idpo);
    if (!$stmt_podetail->execute()) {
        throw new Exception("Execute podetail failed: " . $stmt_podetail->error);
    }
    $result_podetail = $stmt_podetail->get_result();
    $order_quantities = [];
    while ($r = $result_podetail->fetch_assoc()) {
        $order_quantities[intval($r['idrawmate'])] = is_numeric($r['order_qty']) ? (float)$r['order_qty'] : 0.0;
    }

    // 3) siapkan statement insert grrawdetail
    $query_grdetail = "INSERT INTO grrawdetail (idgr, idrawmate, qty, orderqty, idtransaksi) VALUES (?, ?, ?, ?, ?)";
    $stmt_grdetail = $conn->prepare($query_grdetail);
    if ($stmt_grdetail === false) {
        throw new Exception("Prepare grdetail failed: " . $conn->error);
    }

    // 4) siapkan statement insert stockraw
    $query_stockraw = "INSERT INTO stockraw (idrawmate, qty, idtransaksi) VALUES (?, ?, ?)";
    $stmt_stockraw = $conn->prepare($query_stockraw);
    if ($stmt_stockraw === false) {
        throw new Exception("Prepare stockraw failed: " . $conn->error);
    }

    // loop per item
    foreach ($idrawmate as $index => $rawid_raw) {
        $idraw = intval($rawid_raw);
        // pastikan index valid di received_qty
        if (!isset($received_qty[$index])) {
            throw new Exception("Missing received_qty for index {$index}");
        }
        // bersihkan qty
        $qty_received = $received_qty[$index];
        $qty_received = str_replace(',', '.', trim($qty_received));
        if (!is_numeric($qty_received)) {
            throw new Exception("Invalid qty for raw id {$idraw} at index {$index}: {$qty_received}");
        }
        $qty_received = (float)$qty_received;

        $order_qty = isset($order_quantities[$idraw]) ? (int)$order_quantities[$idraw] : 0;

        // insert ke grrawdetail
        // bind types: i (idgr), i (idraw), d (qty_received), i (order_qty), s (idtransaksi)
        if (!$stmt_grdetail->bind_param("iidis", $idgr, $idraw, $qty_received, $order_qty, $idtransaksi)) {
            throw new Exception("Bind grdetail failed: " . $stmt_grdetail->error);
        }
        if (!$stmt_grdetail->execute()) {
            throw new Exception("Execute grdetail failed: " . $stmt_grdetail->error);
        }

        // insert ke stockraw
        // bind types: i (idraw), d (qty_received), s (idtransaksi)
        if (!$stmt_stockraw->bind_param("ids", $idraw, $qty_received, $idtransaksi)) {
            throw new Exception("Bind stockraw failed: " . $stmt_stockraw->error);
        }
        if (!$stmt_stockraw->execute()) {
            throw new Exception("Execute stockraw failed: " . $stmt_stockraw->error);
        }
    }

    // 5) update po stat = 1
    $query_update = "UPDATE po SET stat = 1 WHERE idpo = ?";
    $stmt_update = $conn->prepare($query_update);
    if ($stmt_update === false) {
        throw new Exception("Prepare update po failed: " . $conn->error);
    }
    $stmt_update->bind_param("i", $idpo);
    if (!$stmt_update->execute()) {
        throw new Exception("Execute update po failed: " . $stmt_update->error);
    }

    // 6) ambil idrequest dari po dan update request.stat = 'Completed'
    $query_idrequest = "SELECT idrequest FROM po WHERE idpo = ?";
    $stmt_idrequest = $conn->prepare($query_idrequest);
    if ($stmt_idrequest === false) {
        throw new Exception("Prepare idrequest failed: " . $conn->error);
    }
    $stmt_idrequest->bind_param("i", $idpo);
    if (!$stmt_idrequest->execute()) {
        throw new Exception("Execute idrequest failed: " . $stmt_idrequest->error);
    }
    $res_idreq = $stmt_idrequest->get_result();
    if ($res_idreq->num_rows > 0) {
        $r = $res_idreq->fetch_assoc();
        $idrequest = intval($r['idrequest']);

        $query_update_request = "UPDATE request SET stat = 'Completed' WHERE idrequest = ?";
        $stmt_update_request = $conn->prepare($query_update_request);
        if ($stmt_update_request === false) {
            throw new Exception("Prepare update request failed: " . $conn->error);
        }
        $stmt_update_request->bind_param("i", $idrequest);
        if (!$stmt_update_request->execute()) {
            throw new Exception("Execute update request failed: " . $stmt_update_request->error);
        }
    } else {
        throw new Exception("idrequest not found for idpo={$idpo}");
    }

    // 7) insert log activity
    $event = "Buat GR RAW";
    $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb) VALUES (?, ?, ?)";
    $stmtLogActivity = $conn->prepare($queryLogActivity);
    if ($stmtLogActivity === false) {
        throw new Exception("Prepare logActivity failed: " . $conn->error);
    }
    $stmtLogActivity->bind_param("iss", $idusers, $event, $gr);
    if (!$stmtLogActivity->execute()) {
        throw new Exception("Execute logActivity failed: " . $stmtLogActivity->error);
    }

    // commit
    $conn->commit();

    // redirect kalau sukses
    header("Location: index.php");
    exit();
} catch (Exception $e) {
    // rollback + tampilkan pesan error
    $conn->rollback();
    echo "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
} finally {
    // kembalikan autocommit, tutup statement
    $conn->autocommit(true);

    $stmts = [$stmt_gr, $stmt_podetail, $stmt_grdetail, $stmt_stockraw, $stmt_update, $stmt_idrequest, $stmt_update_request, $stmtLogActivity];
    foreach ($stmts as $s) {
        if ($s && $s instanceof mysqli_stmt) {
            $s->close();
        }
    }
    $conn->close();
}
