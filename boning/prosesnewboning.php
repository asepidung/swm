<?php
require "../konak/conn.php";

// mengambil data dari form
$batchboning = $_POST['batchboning'];
$tglboning = $_POST['tglboning'];
$idsupplier = $_POST['idsupplier'];
$qtysapi = $_POST['qtysapi'];
$idusers = $_POST['idusers'];
// membuat query untuk menyimpan data ke database
$sql = "INSERT INTO boning (batchboning, idsupplier, tglboning, qtysapi, iduser)
            VALUES ('$batchboning', '$idsupplier', '$tglboning', $qtysapi, '$idusers')";

// mengeksekusi query
if (mysqli_query($conn, $sql)) {
  echo "<script>alert('Data berhasil disimpan.'); window.location='databoning.php';</script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// menutup koneksi ke database
mysqli_close($conn);
