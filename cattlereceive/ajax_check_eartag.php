<?php
require "../verifications/auth.php";
require "../konak/conn.php";
header('Content-Type: application/json; charset=utf-8');

function norm_tag($x)
{
    $x = strtoupper(trim((string)$x));
    return $x;
}

$tag = isset($_GET['eartag']) ? norm_tag($_GET['eartag']) : '';
if ($tag === '') {
    echo json_encode(['ok' => false, 'active' => false, 'msg' => 'eartag empty']);
    exit;
}

// Cek apakah eartag sudah ada di pool aktif (header belum dihapus)
$sql = "SELECT 1
        FROM cattle_receive_detail d
        JOIN cattle_receive r ON r.idreceive = d.idreceive
        WHERE r.is_deleted = 0
          AND d.eartag = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $tag);
$stmt->execute();
$active = (bool) $stmt->get_result()->fetch_row();

echo json_encode(['ok' => true, 'active' => $active, 'eartag' => $tag]);
