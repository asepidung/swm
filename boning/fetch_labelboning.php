<?php
require "../konak/conn.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validasi ID boning
$idboning = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idboning == 0) {
    echo json_encode(["error" => "ID Boning tidak valid"]);
    exit;
}

// Validasi parameter DataTables
$limit = isset($_GET['length']) ? intval($_GET['length']) : 10;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

// Query utama
$query = "SELECT l.idlabelboning, l.kdbarcode, g.nmgrade, b.nmbarang, l.qty, l.pcs, u.fullname, l.dibuat 
          FROM labelboning l
          JOIN barang b ON l.idbarang = b.idbarang 
          JOIN boning bo ON l.idboning = bo.idboning
          JOIN grade g ON l.idgrade = g.idgrade
          JOIN users u ON l.iduser = u.idusers
          WHERE l.idboning = ? AND l.is_deleted = 0";

// Jika ada pencarian, tambahkan filter
if (!empty($search)) {
    $query .= " AND (b.nmbarang LIKE ? OR g.nmgrade LIKE ? OR l.kdbarcode LIKE ?)";
}

// Persiapkan statement
$stmt = mysqli_prepare($conn, $query);

// Jika ada pencarian, bind parameter dengan wildcard `%`
if (!empty($search)) {
    $searchTerm = "%$search%";
    mysqli_stmt_bind_param($stmt, "isss", $idboning, $searchTerm, $searchTerm, $searchTerm);
} else {
    mysqli_stmt_bind_param($stmt, "i", $idboning);
}

// Eksekusi query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$totalData = mysqli_num_rows($result);

// Tambahkan paginasi
$query .= " ORDER BY l.idlabelboning DESC LIMIT ?, ?";
$stmt = mysqli_prepare($conn, $query);
if (!empty($search)) {
    mysqli_stmt_bind_param($stmt, "isssii", $idboning, $searchTerm, $searchTerm, $searchTerm, $start, $limit);
} else {
    mysqli_stmt_bind_param($stmt, "iii", $idboning, $start, $limit);
}

// Jalankan ulang query dengan paginasi
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [];
$no = $start + 1;
while ($row = mysqli_fetch_assoc($result)) {
    // Bersihkan output dari karakter tidak valid
    $barcode = htmlspecialchars($row['kdbarcode'], ENT_QUOTES, 'UTF-8');
    $grade = htmlspecialchars($row['nmgrade'], ENT_QUOTES, 'UTF-8');
    $product = htmlspecialchars($row['nmbarang'], ENT_QUOTES, 'UTF-8');
    $qty = number_format($row['qty'], 2);
    $pcs = htmlspecialchars($row['pcs'], ENT_QUOTES, 'UTF-8');
    $author = htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8');
    $created = date("H:i:s", strtotime($row['dibuat']));

    // Hapus karakter aneh dari tombol aksi
    $action = "<a href='hapus_labelboning.php?id={$row['idlabelboning']}&idboning=$idboning' class='text-danger' onclick='return confirm(\"Hapus label ini?\");'>
        <i class='fas fa-minus-square'></i></a>";

    $data[] = [
        $no++,
        $barcode,
        $grade,
        $product,
        $qty,
        $pcs,
        $author,
        $created,
        $action
    ];
}

// Kirim JSON ke DataTables
header('Content-Type: application/json');
echo json_encode([
    "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalData,
    "data" => $data
]);
