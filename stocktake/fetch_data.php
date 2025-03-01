<?php
require "../konak/conn.php";

$limit = $_POST["length"];
$start = $_POST["start"];
$search = $_POST["search"]["value"];

$query = "SELECT s.*, b.nmbarang, g.nmgrade FROM stocktakedetail s
          INNER JOIN barang b ON s.idbarang = b.idbarang
          LEFT JOIN grade g ON s.idgrade = g.idgrade
          WHERE s.idst = ?
          AND (s.kdbarcode LIKE ? OR b.nmbarang LIKE ?)
          ORDER BY s.idstdetail DESC
          LIMIT ?, ?";

$stmt = $conn->prepare($query);
$idst = intval($_GET['id']);
$search_term = "%$search%";
$stmt->bind_param("issii", $idst, $search_term, $search_term, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        $row['idstdetail'],
        htmlspecialchars($row['kdbarcode']),
        htmlspecialchars($row['nmbarang']),
        htmlspecialchars($row['nmgrade']),
        $row['qty'],
        $row['pcs'],
        $row['pod'] . " Days",
        ["Unidentified", "BONING", "TRADING", "REPACK", "RELABEL", "IMPORT"][$row['origin']] ?? "Unknown",
        '<a href="deletestdetail.php?iddetail=' . $row['idstdetail'] . '&id=' . $idst . '" class="text-danger" onclick="return confirm(\'Yakinkan Dirimu?\')">
            <i class="far fa-times-circle"></i>
        </a>'
    ];
}

$count_query = "SELECT COUNT(*) FROM stocktakedetail WHERE idst = ?";
$stmt_count = $conn->prepare($count_query);
$stmt_count->bind_param("i", $idst);
$stmt_count->execute();
$total_records = $stmt_count->get_result()->fetch_row()[0];

echo json_encode([
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_records,
    "data" => $data
]);
