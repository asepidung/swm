<?php
require "../konak/conn.php";

// mengambil data dari form
$nama_customer = $_POST['nama_customer'];
$alamat = $_POST['alamat'];
$idsegment = $_POST['idsegment'];
$top = $_POST['top'];
$pajak = "NO";
$telepon = $_POST['telepon'];
$email = "-";
$tukarfaktur = $_POST['tukarfaktur'];
$catatan = $_POST['catatan'];
$idgroup = $_POST['idgroup'];

// membuat prepared statement
$stmt = $conn->prepare("INSERT INTO customers (nama_customer, alamat1, idsegment, top, pajak, tukarfaktur, telepon, email, catatan, idgroup)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// bind parameter
$stmt->bind_param("ssiisssssi", $nama_customer, $alamat, $idsegment, $top, $pajak, $tukarfaktur, $telepon, $email, $catatan, $idgroup);

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
