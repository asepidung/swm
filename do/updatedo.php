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
$query_do = "UPDATE do SET deliverydate = ?, idcustomer = ?, po = ?, driver = ?, plat = ?, note = ? WHERE iddo = ?";
$stmt_do = mysqli_prepare($conn, $query_do);
mysqli_stmt_bind_param($stmt_do, "sissssi", $deliverydate, $idcustomer, $po, $driver, $plat, $note, $iddo);

if (mysqli_stmt_execute($stmt_do)) {
  // Jika data di tabel do berhasil diupdate, lanjutkan dengan mengupdate data di tabel dodetail

  // Menghapus data dodetail yang terkait dengan iddo yang diberikan
  $delete_query = "DELETE FROM dodetail WHERE iddo = ?";
  $stmt_delete = mysqli_prepare($conn, $delete_query);
  mysqli_stmt_bind_param($stmt_delete, "i", $iddo);
  mysqli_stmt_execute($stmt_delete);

  // Mengambil data yang di-submit melalui form
  $idgrade = $_POST['idgrade'];
  $idbarang = $_POST['idbarang'];
  $box = $_POST['box'];
  $weight = $_POST['weight'];
  $notes = $_POST['notes'];

  // Looping untuk menyimpan data dodetail yang baru di tabel dodetail
  $query_dodetail = "INSERT INTO dodetail (iddo, idgrade, idbarang, box, weight, notes) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt_dodetail = mysqli_prepare($conn, $query_dodetail);
  mysqli_stmt_bind_param($stmt_dodetail, "iiiiis", $iddo, $idgrade_val, $idbarang_val, $box_val, $weight_val, $notes_val);

  for ($i = 0; $i < count($idgrade); $i++) {
    $idgrade_val = $idgrade[$i];
    $idbarang_val = $idbarang[$i];
    $box_val = $box[$i];
    $weight_val = $weight[$i];
    $notes_val = $notes[$i];
    mysqli_stmt_execute($stmt_dodetail);
  }

  mysqli_stmt_close($stmt_do);
  mysqli_stmt_close($stmt_delete);
  mysqli_stmt_close($stmt_dodetail);
  mysqli_close($conn);

  header("location: do.php");
} else {
  echo "Terjadi kesalahan: " . mysqli_error($conn);
  mysqli_stmt_close($stmt_do);
  mysqli_close($conn);
}
