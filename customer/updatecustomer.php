<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Mendapatkan data dari form
$idcustomer = $_POST['idcustomer'];
$nama_customer = $_POST['nama_customer'];
$alamat = $_POST['alamat'];
$idsegment = $_POST['idsegment'];
$top = $_POST['top'];
$pajak = "NO";
$tukarfaktur = $_POST['tukarfaktur'];
$telepon = $_POST['telepon'];
$email = "-";
$catatan = $_POST['catatan'];
$idgroup = $_POST['idgroup'];

// Update data customer
$query = "UPDATE customers SET nama_customer = ?, alamat1 = ?, idsegment = ?, top = ?, pajak = ?, tukarfaktur = ?, telepon = ?, email = ?, catatan = ?, idgroup = ? WHERE idcustomer = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssissssssii", $nama_customer, $alamat, $idsegment, $top, $pajak, $tukarfaktur, $telepon, $email, $catatan, $idgroup, $idcustomer);

if ($stmt->execute()) {
   echo "<script>alert('Data berhasil diperbarui.'); window.location='customer.php';</script>";
} else {
   echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
