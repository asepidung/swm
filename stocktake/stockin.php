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
$conn->close();

header("Location: index.php");
exit();
