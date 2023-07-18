<?php
require "../konak/conn.php";

// mengambil data dari form
$nama_customer = $_POST['nama_customer'];
$alamat = $_POST['alamat'];
$idsegment = $_POST['idsegment'];
$top = $_POST['top'];
$pajak = $_POST['pajak'];
$telepon = $_POST['telepon'];
$email = $_POST['email'];
$tukarfaktur = $_POST['tukarfaktur'];
$catatan = $_POST['catatan'];

// membuat prepared statement
$stmt = $conn->prepare("INSERT INTO customers (nama_customer, alamat, idsegment, top, pajak, tukarfaktur, telepon, email, catatan)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

// bind parameter
$stmt->bind_param("ssiisssss", $nama_customer, $alamat, $idsegment, $top, $pajak, $tukarfaktur, $telepon, $email, $catatan);

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
