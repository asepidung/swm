<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// mengambil data dari form
$idcustomer = $_POST['idcustomer'];
$nama_customer = $_POST['nama_customer'];
$alamat1 = $_POST['alamat1'];
$alamat2 = $_POST['alamat2'];
$alamat3 = $_POST['alamat3'];
$idsegment = $_POST['idsegment'];
$top = $_POST['top'];
$pajak = $_POST['pajak'];
$tukarfaktur = $_POST['tukarfaktur'];
$telepon = $_POST['telepon'];
$email = $_POST['email'];
$catatan = $_POST['catatan'];

// update data customer
$query = "UPDATE customers SET nama_customer = ?, alamat1 = ?, alamat2 = ?, alamat3 = ?, idsegment = ?, top = ?, pajak = ?, tukarfaktur = ?, telepon = ?, email = ?, catatan = ? WHERE idcustomer = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssissssssi", $nama_customer, $alamat1, $alamat2, $alamat3, $idsegment, $top, $pajak, $tukarfaktur, $telepon, $email, $catatan, $idcustomer);

if ($stmt->execute()) {
   echo "<script>alert('Data berhasil diperbarui.'); window.location='customer.php';</script>";
} else {
   echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
