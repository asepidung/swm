<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk keluar setelah redirect
}

// Koneksi ke database
require "../konak/conn.php";

if (isset($_GET['id']) && isset($_GET['iddetail'])) {
   $id = $_GET['id'];
   $iddetail = $_GET['iddetail'];
   $iduser = $_SESSION['idusers']; // Ambil ID user dari sesi yang aktif

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
         // Catat log aktivitas setelah penghapusan berhasil
         $event = "Hapus Hasil Repack";
         $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, NOW())";
         $logStmt = $conn->prepare($logQuery);
         $logStmt->bind_param('iss', $iduser, $event, $kdbarcode);
         $logStmt->execute();

         // Redirect ke halaman detailhasil.php dengan status "deleted"
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
