<?php
require "../konak/conn.php";
require "nomortally.php";
// mengambil data dari form
$idtally = $_POST['idtally'];
$idcustomer = $_POST['idcustomer'];
$deliverydate = $_POST['deliverydate'];
$tallynumber = $kodeauto;
$ponumber = $_POST['ponumber'];
$keterangan = $_POST['keterangan'];
$idusers = $_POST['idusers'];
// membuat query untuk menyimpan data ke database
$sql = "INSERT INTO tally (idtally, idcustomer, deliverydate, tallynumber, ponumber, keterangan, idusers)
            VALUES ('$idtally', '$idcustomer', '$deliverydate', $tallynumber,  $ponumber, '$keterangan', '$idusers')";

// mengeksekusi query
if (mysqli_query($conn, $sql)) {
   echo "<script>alert('Data berhasil disimpan.'); window.location='index.php';</script>";
} else {
   echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// menutup koneksi ke database
mysqli_close($conn);
