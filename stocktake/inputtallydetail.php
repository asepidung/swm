<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
if (isset($_POST['barcode'])) {
   $barcode = $_POST['barcode'];
   $firstDigit = substr($barcode, 0, 1);
   $idtally = $_POST['idtally'];
   $idso_query = "SELECT idso FROM tally WHERE idtally = $idtally";
   $idso_result = mysqli_query($conn, $idso_query);

   if ($idso_result) {
      $idso_row = mysqli_fetch_assoc($idso_result);
      $idso = $idso_row['idso'];
   }

   // Buat query sesuai dengan digit pertama
   $query = '';
   if ($firstDigit == '1') {
      $query = "SELECT idbarang, qty, pcs, packdate FROM labelboning WHERE kdbarcode = '$barcode'";
      $origin = 1;
   } elseif ($firstDigit == '2') {
      $query = "SELECT idbarang, qty, pcs, packdate FROM trading WHERE kdbarcode = '$barcode'";
      $origin = 2;
   } elseif ($firstDigit == '4') {
      $query = "SELECT idbarang, qty, pcs, packdate FROM relabel WHERE kdbarcode = '$barcode'";
      $origin = 4;
   }

   // Eksekusi query
   $result = mysqli_query($conn, $query);

   if ($result && $row = mysqli_fetch_assoc($result)) {
      $idbarang = $row['idbarang'];
      $weight = $row['qty']; // Menyesuaikan nama kolom di tabel
      $pcs = $row['pcs'];
      $pod = $row['packdate'];

      // Selanjutnya, masukkan data ke dalam tabel tallydetail
      $insertQuery = "INSERT INTO tallydetail (idtally, barcode, idbarang, weight, pcs, pod, origin) VALUES ('$idtally', '$barcode', '$idbarang', '$weight', '$pcs', '$pod', '$origin')";
      mysqli_query($conn, $insertQuery);

      // Redirect kembali ke halaman tallydetail.php?id=$idtally
      header("location: tallydetail.php?id=$idtally&stat=success");
   }
}
