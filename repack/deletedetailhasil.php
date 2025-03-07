<?php
require "../verifications/auth.php";

// Koneksi ke database
require "../konak/conn.php";

if (isset($_GET['id']) && isset($_GET['iddetail'])) {
   $id = $_GET['id'];
   $iddetail = $_GET['iddetail'];
   $iduser = $_SESSION['idusers']; // Ambil ID user dari sesi yang aktif

   // Ambil kdbarcode dari tabel detailhasil
   $getBarcodeQuery = "SELECT kdbarcode FROM detailhasil WHERE iddetailhasil = ?";
   $getBarcodeStmt = $conn->prepare($getBarcodeQuery);
   $getBarcodeStmt->bind_param('i', $iddetail);
   $getBarcodeStmt->execute();
   $getBarcodeResult = $getBarcodeStmt->get_result();

   if ($getBarcodeResult && $rowBarcode = $getBarcodeResult->fetch_assoc()) {
      $kdbarcode = $rowBarcode['kdbarcode'];

      // Lakukan soft delete pada tabel detailhasil
      $softDeleteDetailQuery = "UPDATE detailhasil SET is_deleted = 1 WHERE iddetailhasil = ?";
      $softDeleteDetailStmt = $conn->prepare($softDeleteDetailQuery);
      $softDeleteDetailStmt->bind_param('i', $iddetail);
      $softDeleteDetailSuccess = $softDeleteDetailStmt->execute();

      // Lakukan hard delete pada tabel stock
      $hardDeleteStockQuery = "DELETE FROM stock WHERE kdbarcode = ?";
      $hardDeleteStockStmt = $conn->prepare($hardDeleteStockQuery);
      $hardDeleteStockStmt->bind_param('s', $kdbarcode);
      $hardDeleteStockSuccess = $hardDeleteStockStmt->execute();

      // Periksa apakah penghapusan berhasil di kedua tabel
      if ($softDeleteDetailSuccess && $hardDeleteStockSuccess) {
         // Catat log aktivitas setelah penghapusan berhasil
         $event = "Hapus Hasil Repack";
         $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, NOW())";
         $logStmt = $conn->prepare($logQuery);
         $logStmt->bind_param('iss', $iduser, $event, $kdbarcode);
         $logStmt->execute();

         // Redirect ke halaman detailhasil.php dengan status "deleted"
         header("Location: detailhasil.php?id=$id&stat=deleted");
         exit;
      } else {
         // Jika gagal, tampilkan pesan error
         echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='tallydetail.php?id=$id';</script>";
      }
   } else {
      // Jika tidak berhasil mendapatkan kdbarcode, tampilkan pesan error
      echo "<script>alert('Maaf, data tidak ditemukan atau terjadi kesalahan.'); window.location='tallydetail.php?id=$id';</script>";
   }
} else {
   // Jika parameter tidak valid, arahkan kembali
   header("Location: tallydetail.php?id=$id");
   exit();
}
