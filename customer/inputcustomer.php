<?php
require "../konak/conn.php";

// mengambil data dari form
$nama_customer = $_POST['nama_customer'];
$alamat1 = $_POST['alamat1'];
$alamat2 = $_POST['alamat2'];
$alamat3 = $_POST['alamat3'];
$idsegment = $_POST['idsegment'];
$top = $_POST['top'];
$pajak = $_POST['pajak'];
$telepon = $_POST['telepon'];
$email = $_POST['email'];
$tukarfaktur = $_POST['tukarfaktur'];
$catatan = $_POST['catatan'];

// membuat prepared statement
$stmt = $conn->prepare("INSERT INTO customers (nama_customer, alamat1, alamat2, alamat3, idsegment, top, pajak, tukarfaktur, telepon, email, catatan)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// bind parameter
$stmt->bind_param("ssssiisssss", $nama_customer, $alamat1, $alamat2, $alamat3, $idsegment, $top, $pajak, $tukarfaktur, $telepon, $email, $catatan);

// mengeksekusi prepared statement
if ($stmt->execute()) {
   echo "<script>alert('Data berhasil disimpan.'); window.location='customer.php';</script>";
} else {
   echo "Error: " . $stmt->error;
}

// menutup prepared statement
$stmt->close();

// menutup koneksi ke database
$conn->close();
