<?php
require "../konak/conn.php";

// Mendapatkan ID carcase
$idcarcase = $_GET['idcarcase'];

// Mendapatkan jumlah data (count) dari kolom eartag untuk Head &Sigma;
$headQuery = "SELECT COUNT(eartag) AS head_count FROM carcasedetail WHERE idcarcase = ?";
$headStmt = $conn->prepare($headQuery);
$headStmt->bind_param("i", $idcarcase);
$headStmt->execute();
$headResult = $headStmt->get_result();
$headData = $headResult->fetch_assoc();
$headCount = $headData['head_count'];

// Menghitung total hides
$hidesQuery = "SELECT SUM(hides) AS hides_total FROM carcasedetail WHERE idcarcase = ?";
$hidesStmt = $conn->prepare($hidesQuery);
$hidesStmt->bind_param("i", $idcarcase);
$hidesStmt->execute();
$hidesResult = $hidesStmt->get_result();
$hidesData = $hidesResult->fetch_assoc();
$hidesTotal = $hidesData['hides_total'];

// Menghitung total tails
$tailsQuery = "SELECT SUM(tail) AS tails_total FROM carcasedetail WHERE idcarcase = ?";
$tailsStmt = $conn->prepare($tailsQuery);
$tailsStmt->bind_param("i", $idcarcase);
$tailsStmt->execute();
$tailsResult = $tailsStmt->get_result();
$tailsData = $tailsResult->fetch_assoc();
$tailsTotal = $tailsData['tails_total'];

// Menghitung total carcase dan menghitung carcase %
// Carcase % = (total berat carcase / total berat sapi) * 100
$carcaseQuery = "SELECT SUM(carcase1 + carcase2) AS carcase_total FROM carcasedetail WHERE idcarcase = ?";
$carcaseStmt = $conn->prepare($carcaseQuery);
$carcaseStmt->bind_param("i", $idcarcase);
$carcaseStmt->execute();
$carcaseResult = $carcaseStmt->get_result();
$carcaseData = $carcaseResult->fetch_assoc();
$carcaseTotal = $carcaseData['carcase_total'];

// Asumsi bahwa total berat sapi diambil dari sumber lain, misalnya tabel `carcase`, dengan kolom `weight`
$weightQuery = "SELECT weight FROM carcase WHERE idcarcase = ?";
$weightStmt = $conn->prepare($weightQuery);
$weightStmt->bind_param("i", $idcarcase);
$weightStmt->execute();
$weightResult = $weightStmt->get_result();
$weightData = $weightResult->fetch_assoc();
$weightTotal = $weightData['weight'];

// Perhitungan Offal &Sigma;
$offalTotal = $carcaseTotal + $tailsTotal;

// Perhitungan Carcase %
$carcasePercentage = ($weightTotal > 0) ? ($carcaseTotal / $weightTotal) * 100 : 0;

// Menampilkan hasil perhitungan
echo json_encode([
   "headCount" => $headCount,
   "carcaseTotal" => number_format($carcaseTotal, 2),
   "offalTotal" => number_format($offalTotal, 2),
   "hidesTotal" => number_format($hidesTotal, 2),
   "tailsTotal" => number_format($tailsTotal, 2),
   "carcasePercentage" => number_format($carcasePercentage, 2) . "%"
]);

// Menutup koneksi dan statement
$headStmt->close();
$hidesStmt->close();
$tailsStmt->close();
$carcaseStmt->close();
$weightStmt->close();
$conn->close();
