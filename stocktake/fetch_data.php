<?php
require "../konak/conn.php";

// Ambil parameter DataTables
$limit = isset($_POST["length"]) ? intval($_POST["length"]) : 10;
$start = isset($_POST["start"]) ? intval($_POST["start"]) : 0;
$search = isset($_POST["search"]["value"]) ? $_POST["search"]["value"] : "";

// Pastikan ID tersedia dan valid
$idst = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idst <= 0) {
    echo json_encode([
        "draw" => intval($_POST["draw"]),
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
    exit;
}

// Query untuk menghitung total data tanpa filter
$count_query = "SELECT COUNT(*) FROM stocktakedetail WHERE idst = ?";
$stmt_count = $conn->prepare($count_query);
$stmt_count->bind_param("i", $idst);
$stmt_count->execute();
$total_records = $stmt_count->get_result()->fetch_row()[0];

// Query utama dengan filter pencarian
$query = "SELECT s.idstdetail, s.kdbarcode, b.nmbarang, g.nmgrade, s.qty, s.pcs, s.pod, s.origin 
          FROM stocktakedetail s
          INNER JOIN barang b ON s.idbarang = b.idbarang
          LEFT JOIN grade g ON s.idgrade = g.idgrade
          WHERE s.idst = ?";

$params = [$idst];
$types = "i"; // Tipe parameter untuk idst

// Tambahkan filter pencarian jika ada
if (!empty($search)) {
    $query .= " AND (s.kdbarcode LIKE ? OR b.nmbarang LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "ss"; // Tambahkan tipe parameter string
}

// Tambahkan order dan limit
$query .= " ORDER BY s.idstdetail DESC LIMIT ?, ?";
$params[] = $start;
$params[] = $limit;
$types .= "ii"; // Tambahkan tipe parameter integer untuk limit

// Eksekusi query
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Proses hasil query
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        $row['idstdetail'],
        htmlspecialchars($row['kdbarcode']),
        htmlspecialchars($row['nmbarang']),
        htmlspecialchars($row['nmgrade'] ?? "N/A"), // Jika grade kosong, tampilkan "N/A"
        number_format($row['qty'], 2), // Format angka qty
        intval($row['pcs']), // Pastikan pcs dalam bentuk integer
        htmlspecialchars(date("d-M-y", strtotime($row['pod']))),
        ["Unidentified", "BONING", "TRADING", "REPACK", "RELABEL", "IMPORT"][$row['origin']] ?? "Unknown",
        '<a href="deletestdetail.php?iddetail=' . $row['idstdetail'] . '&id=' . $idst . '" class="text-danger" onclick="return confirm(\'Yakinkan Dirimu?\')">
            <i class="far fa-times-circle"></i>
        </a>'
    ];
}

// Query untuk menghitung jumlah data yang difilter
$count_filtered_query = "SELECT COUNT(*) FROM stocktakedetail WHERE idst = ?";
if (!empty($search)) {
    $count_filtered_query .= " AND (kdbarcode LIKE ? OR idbarang IN (SELECT idbarang FROM barang WHERE nmbarang LIKE ?))";
}
$stmt_count_filtered = $conn->prepare($count_filtered_query);
if (!empty($search)) {
    $stmt_count_filtered->bind_param("iss", $idst, $search_term, $search_term);
} else {
    $stmt_count_filtered->bind_param("i", $idst);
}
$stmt_count_filtered->execute();
$records_filtered = $stmt_count_filtered->get_result()->fetch_row()[0];

// Kirim data dalam format JSON ke DataTables
echo json_encode([
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => $total_records,
    "recordsFiltered" => $records_filtered,
    "data" => $data
]);
