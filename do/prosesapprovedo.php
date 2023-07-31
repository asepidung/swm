<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['approve'])) {
   // Ambil nilai iddo dari form
   $iddo = $_POST['iddo'];

   // Update field "status" menjadi "approved" di tabel "do"
   $query = "UPDATE do SET status = 'Approved' WHERE iddo = '$iddo'";
   $result = mysqli_query($conn, $query);

   // Periksa apakah update berhasil
   if ($result) {
      header("location: do.php");
   } else {
   }

   // Tutup koneksi ke database
   mysqli_close($conn);
}
