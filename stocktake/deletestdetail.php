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

   // Ambil kdbarcode dari tabel stocktakedetail berdasarkan idstdetail
   $query_kdbarcode = "SELECT kdbarcode FROM stocktakedetail WHERE idstdetail = ?";
   $stmt_kdbarcode = $conn->prepare($query_kdbarcode);
   $stmt_kdbarcode->bind_param("i", $iddetail);
   $stmt_kdbarcode->execute();
   $result_kdbarcode = $stmt_kdbarcode->get_result();
   $row_kdbarcode = $result_kdbarcode->fetch_assoc();
   $kdbarcode = $row_kdbarcode['kdbarcode'];

   $stmt_kdbarcode->close();

   // Hapus data dari tabel stocktakedetail
   $hapusdata = mysqli_query($conn, "DELETE FROM stocktakedetail WHERE idstdetail = '$iddetail'");

   // Periksa apakah penghapusan data berhasil dilakukan
   if ($hapusdata) {
      // Insert ke tabel logactivity
      $event = "Delete Detail ST";
      $iduser = $_SESSION['idusers'];
      $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, NOW())";
      $stmt_log = $conn->prepare($logQuery);
      $stmt_log->bind_param("iss", $iduser, $event, $kdbarcode);
      $stmt_log->execute();
      $stmt_log->close();

      // Redirect ke halaman starttaking.php setelah berhasil menghapus data
      header("Location: starttaking.php?id=$id&stat=deleted");
   } else {
      // Jika gagal, tampilkan pesan error
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='starttaking.php?id=$id';</script>";
   }
}
