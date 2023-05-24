<?php
require "../konak/conn.php";

// mengambil data dari form
$batchboning = $_POST['batchboning'];
$tglkill = $_POST['tglkill'];
$tglboning = $_POST['tglboning'];
$idsupplier = $_POST['idsupplier'];
$qtysapi = $_POST['qtysapi'];
$catatan = $_POST['catatan'];

// membuat query untuk menyimpan data ke database
$sql = "INSERT INTO boning (batchboning, idsupplier, tglkill, tglboning, qtysapi, catatan)
            VALUES ('$batchboning', '$idsupplier', '$tglkill', '$tglboning', $qtysapi, '$catatan')";

// mengeksekusi query
if (mysqli_query($conn, $sql)) {
  echo "<script>alert('Data berhasil disimpan.'); window.location='databoning.php';</script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// menutup koneksi ke database
mysqli_close($conn);
