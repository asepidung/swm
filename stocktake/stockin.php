<?php
session_start();

if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";

$idst = $_GET['id'];

if (empty($idst)) {
   echo "Parameter id tidak valid.";
   exit();
}

// Ambil nomor stock take (nost) dari tabel stocktake
$query_nost = "SELECT nost FROM stocktake WHERE idst = ?";
$stmt_nost = $conn->prepare($query_nost);
$stmt_nost->bind_param("i", $idst);
$stmt_nost->execute();
$result_nost = $stmt_nost->get_result();

if ($result_nost->num_rows > 0) {
   $row_nost = $result_nost->fetch_assoc();
   $nost = $row_nost['nost'];
} else {
   echo "Stock Take tidak ditemukan.";
   exit();
}
$stmt_nost->close();

// Hapus semua data di tabel stock
$deleteSql = "DELETE FROM stock";
$deleteStmt = $conn->prepare($deleteSql);
$deleteStmt->execute();
$deleteStmt->close();

// Ambil data dari stocktakedetail
$sql = "SELECT * FROM stocktakedetail WHERE idst = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idst);
$stmt->execute();
$result = $stmt->get_result();

// Inisialisasi statement untuk insert ke dalam stock
$insertSql = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) VALUES (?, ?, ?, ?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);

// Loop melalui hasil query dan sisipkan data ke dalam tabel stock
while ($row = $result->fetch_assoc()) {
   $insertStmt->bind_param("siidisi", $row['kdbarcode'], $row['idgrade'], $row['idbarang'], $row['qty'], $row['pcs'], $row['pod'], $row['origin']);
   $insertStmt->execute();
}

$stmt->close();
$insertStmt->close();

// Insert log activity
$event = "Stock Take Confirm";
$iduser = $_SESSION['idusers'];
$logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, NOW())";
$stmt_log = $conn->prepare($logQuery);
$stmt_log->bind_param("iss", $iduser, $event, $nost);
$stmt_log->execute();
$stmt_log->close();

$conn->close();

header("Location: index.php");
exit();
