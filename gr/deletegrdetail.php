<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
// Koneksi ke database
require "../konak/conn.php";

if (isset($_GET['idgr']) && isset($_GET['idgrdetail'])) {
   $idgr = $_GET['idgr'];
   $idgrdetail = $_GET['idgrdetail'];

   // Ambil kdbarcode dari tabel detailhasil
   $getBarcodeQuery = "SELECT kdbarcode FROM grdetail WHERE idgrdetail = '$idgrdetail'";
   $getBarcodeResult = mysqli_query($conn, $getBarcodeQuery);

   if ($getBarcodeResult && $rowBarcode = mysqli_fetch_assoc($getBarcodeResult)) {
      $kdbarcode = $rowBarcode['kdbarcode'];

      // Lakukan penghapusan data dari tabel detailhasil
      $hapusDataDetail = mysqli_query($conn, "DELETE FROM grdetail WHERE idgrdetail = '$idgrdetail'");

      // Lakukan penghapusan data dari tabel stock
      $hapusDataStock = mysqli_query($conn, "DELETE FROM stock WHERE kdbarcode = '$kdbarcode'");

      // Periksa apakah penghapusan data berhasil dilakukan di kedua tabel
      if ($hapusDataDetail && $hapusDataStock) {
         header("Location: grdetail.php?idgr=$idgr");
      } else {
         // Jika gagal, tampilkan pesan error
         echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='grdetail.php?idgr=$idgr';</script>";
      }
   } else {
      // Jika tidak berhasil mendapatkan kdbarcode, tampilkan pesan error
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='grdetail.php?idgr=$idgr';</script>";
   }
}
