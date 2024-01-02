<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

// Sertakan file koneksi database
require "../konak/conn.php";

// Terima parameter id dari URL
$idst = $_GET['id'];

// Periksa apakah idst telah diberikan
if (empty($idst)) {
   echo "Parameter id tidak valid.";
   exit();
}

// Ambil data dari stocktakedetail
$sql = "SELECT * FROM stocktakedetail WHERE idst = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idst);
$stmt->execute();
$result = $stmt->get_result();

// Loop melalui hasil query dan sisipkan data ke dalam tabel stock
while ($row = $result->fetch_assoc()) {
   $insertSql = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) VALUES (?, ?, ?, ?, ?, ?, ?)";
   $insertStmt = $conn->prepare($insertSql);
   $insertStmt->bind_param("siidisi", $row['kdbarcode'], $row['idgrade'], $row['idbarang'], $row['qty'], $row['pcs'], $row['pod'], $row['origin']);
   $insertStmt->execute();
}

// Tutup statement dan koneksi database
$stmt->close();
$insertStmt->close();
$conn->close();

// Kembali ke halaman index.php
header("Location: index.php");
exit();
