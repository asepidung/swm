<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
  // Ambil data dari formulir pengeditan
  $iddo = $_POST['iddo'];
  $deliverydate = $_POST['deliverydate'];
  $po = $_POST['po'];
  $driver = $_POST['driver'];
  $plat = $_POST['plat'];
  $note = $_POST['note'];

  // Query untuk melakukan pembaruan data DO
  $query_update = "UPDATE do SET deliverydate = '$deliverydate', po = '$po', driver = '$driver', plat = '$plat', note = '$note' WHERE iddo = '$iddo'";

  // Eksekusi query
  $result_update = mysqli_query($conn, $query_update);

  if ($result_update) {
    // Jika pembaruan berhasil, redirect ke halaman lain atau tampilkan pesan sukses
    header("location: do.php");
    // atau
    echo "Data DO berhasil diperbarui!";
  } else {
    // Jika pembaruan gagal, tampilkan pesan error atau alihkan kembali ke formulir pengeditan
    echo "Error: " . mysqli_error($conn);
    // atau
    header("location: do.php?iddo=$iddo&error=1");
  }
} else {
  // Jika tidak ada data yang dikirim dari formulir, redirect ke halaman lain atau tampilkan pesan error
  header("location: do.php");
  // atau
  echo "Tidak ada data yang dikirim untuk diperbarui!";
}
