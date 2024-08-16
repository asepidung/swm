<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
// Koneksi ke database
require "../konak/conn.php";

if (isset($_GET['id']) && isset($_GET['iddetail'])) {
   $id = $_GET['id'];
   $iddetail = $_GET['iddetail'];

   // Ambil kdbarcode dari tabel detailhasil
   $getBarcodeQuery = "SELECT kdbarcode FROM detailhasil WHERE iddetailhasil = '$iddetail'";
   $getBarcodeResult = mysqli_query($conn, $getBarcodeQuery);

   if ($getBarcodeResult && $rowBarcode = mysqli_fetch_assoc($getBarcodeResult)) {
      $kdbarcode = $rowBarcode['kdbarcode'];

      // Lakukan penghapusan data dari tabel detailhasil
      $hapusDataDetail = mysqli_query($conn, "DELETE FROM detailhasil WHERE iddetailhasil = '$iddetail'");

      // Lakukan penghapusan data dari tabel stock
      $hapusDataStock = mysqli_query($conn, "DELETE FROM stock WHERE kdbarcode = '$kdbarcode'");

      // Periksa apakah penghapusan data berhasil dilakukan di kedua tabel
      if ($hapusDataDetail && $hapusDataStock) {
         header("Location: detailhasil.php?id=$id&stat=deleted");
      } else {
         // Jika gagal, tampilkan pesan error
         echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='tallydetail.php?id=$id';</script>";
      }
   } else {
      // Jika tidak berhasil mendapatkan kdbarcode, tampilkan pesan error
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='tallydetail.php?id=$id';</script>";
   }
}
