<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Tangkap data dari formulir
   $idrepack = $_POST['idrepack'];
   $tglrepack = $_POST['tglrepack'];
   $note = $_POST['note'];

   // Query untuk mengupdate data repack
   $updateRepackQuery = "UPDATE repack SET tglrepack = '$tglrepack', note = '$note' WHERE idrepack = $idrepack";

   // Jalankan query update
   $result = mysqli_query($conn, $updateRepackQuery);

   if ($result) {
      // Redirect ke halaman lain jika update berhasil
      header("location: index.php");
   } else {
      // Handle kesalahan update
      die("Query Error: " . mysqli_error($conn));
   }
}
