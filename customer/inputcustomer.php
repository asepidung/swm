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
$catatan = $_POST['catatan'];

// membuat query untuk menyimpan data ke database
$sql = "INSERT INTO customers (nama_customer, alamat, idsegment, top, pajak, telepon, email, catatan)
            VALUES ('$nama_customer', '$alamat', '$idsegment', '$top', '$pajak', '$telepon', '$email', '$catatan')";
// mengeksekusi query
if (mysqli_query($conn, $sql)) {
   echo "<script>alert('Data berhasil disimpan.'); window.location='customer.php';</script>";
} else {
   echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// menutup koneksi ke database
mysqli_close($conn);
