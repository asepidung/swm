<?php
require "../konak/conn.php";

$iddo = $_POST['iddo'];
$deliverydate = $_POST['deliverydate'];
$idcustomer = $_POST['idcustomer'];
$po = $_POST['po'];
$driver = $_POST['driver'];
$plat = $_POST['plat'];
$note = $_POST['note'];

// Query untuk mengupdate data di tabel do
$query_do = "UPDATE do SET deliverydate = '$deliverydate', idcustomer = '$idcustomer', po = '$po', driver = '$driver', plat = '$plat', note = '$note' WHERE iddo = '$iddo'";

if (mysqli_query($conn, $query_do)) {
  // Jika data di tabel do berhasil diupdate, lanjutkan dengan mengupdate data di tabel dodetail

  // Menghapus data dodetail yang terkait dengan iddo yang diberikan
  $delete_query = "DELETE FROM dodetail WHERE iddo = '$iddo'";
  mysqli_query($conn, $delete_query);

  // Mengambil data yang di-submit melalui form
  $idgrade = $_POST['idgrade'];
  $idbarang = $_POST['idbarang'];
  $box = $_POST['box'];
  $weight = $_POST['weight'];
  $notes = $_POST['notes'];

  // Looping untuk menyimpan data dodetail yang baru di tabel dodetail
  for ($i = 0; $i < count($idgrade); $i++) {
    $query_dodetail = "INSERT INTO dodetail (iddo, idgrade, idbarang, box, weight, notes) VALUES ('$iddo', '$idgrade[$i]', '$idbarang[$i]', '$box[$i]', '$weight[$i]', '$notes[$i]')";
    mysqli_query($conn, $query_dodetail);
  }

  echo "Data berhasil diupdate.";
} else {
  echo "Terjadi kesalahan: " . mysqli_error($conn);
}

mysqli_close($conn);
