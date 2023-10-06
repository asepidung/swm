<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
$iddo = $_GET['iddo'];

// Menghapus data dari tabel doreceiptdetail berdasarkan iddoreceipt dari tabel doreceipt
$sqlDeleteDoreceiptDetail = "DELETE doreceiptdetail FROM doreceiptdetail
                              INNER JOIN doreceipt ON doreceiptdetail.iddoreceipt = doreceipt.iddoreceipt
                              WHERE doreceipt.iddo = $iddo";
if ($conn->query($sqlDeleteDoreceiptDetail) === TRUE) {
   // Jika penghapusan doreceiptdetail berhasil, lanjutkan menghapus data dari tabel doreceipt
   $sqlDeleteDoreceipt = "DELETE FROM doreceipt WHERE iddo = $iddo";
   if ($conn->query($sqlDeleteDoreceipt) === TRUE) {
      // Jika penghapusan doreceipt berhasil, lanjutkan menghapus data dari tabel dodetail
      $sqlDeleteDodetail = "DELETE FROM dodetail WHERE iddo = $iddo";
      if ($conn->query($sqlDeleteDodetail) === TRUE) {
         // Setelah penghapusan berhasil, lanjutkan menghapus data dari tabel do
         $sqlDeleteDO = "DELETE FROM do WHERE iddo = $iddo";
         if ($conn->query($sqlDeleteDO) === TRUE) {
            echo "<script>alert('Delivery Order berhasil dihapus.'); window.location='do.php';</script>";
         } else {
            echo "Terjadi kesalahan saat menghapus data dari tabel do: " . $conn->error;
         }
      } else {
         echo "Terjadi kesalahan saat menghapus data dari tabel dodetail: " . $conn->error;
      }
   } else {
      echo "Terjadi kesalahan saat menghapus data dari tabel doreceipt: " . $conn->error;
   }
} else {
   echo "Terjadi kesalahan saat menghapus data dari tabel doreceiptdetail: " . $conn->error;
}

// Menutup koneksi database
$conn->close();
