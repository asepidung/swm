<?php
require "../konak/conn.php";

// mengambil data dari form
$kdbarang = $_POST['kdbarang'];
$nmbarang = $_POST['nmbarang'];

// membuat query untuk menyimpan data ke database
$sql = "INSERT INTO barang (kdbarang, nmbarang)
            VALUES ('$kdbarang', '$nmbarang')";

// mengeksekusi query
if (mysqli_query($conn, $sql)) {
   echo "<script>alert('Data berhasil disimpan.'); window.location='barang.php';</script>";
} else {
   echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// menutup koneksi ke database
mysqli_close($conn);
