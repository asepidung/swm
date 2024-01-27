<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $iddo = isset($_POST['iddo']) ? intval($_POST['iddo']) : 0;
  $deliverydate = $_POST['deliverydate'];
  $po = $_POST['po'];
  $driver = $_POST['driver'];
  $plat = $_POST['plat'];
  $note = $_POST['note'];

  // Validasi atau lakukan penanganan kesalahan jika diperlukan

  // Update data pada tabel 'do'
  $query_do = "UPDATE do SET deliverydate = ?, po = ?, driver = ?, plat = ?, note = ? WHERE iddo = ?";
  $stmt_do = mysqli_prepare($conn, $query_do);
  mysqli_stmt_bind_param($stmt_do, "sssssi", $deliverydate, $po, $driver, $plat, $note, $iddo);

  if (mysqli_stmt_execute($stmt_do)) {
    // Tutup statement
    mysqli_stmt_close($stmt_do);
    mysqli_close($conn);

    header("location: do.php");
    exit;
  } else {
    echo "Terjadi kesalahan: " . mysqli_error($conn);
    mysqli_stmt_close($stmt_do);
    mysqli_close($conn);
  }
} else {
  // Jika halaman diakses secara langsung tanpa melalui metode POST, arahkan ke halaman yang sesuai
  header("location: do.php");
  exit;
}
