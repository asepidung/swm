<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Periksa apakah ada parameter ID yang diberikan
if (isset($_GET['idpomaterial'])) {
   $idpomaterial = $_GET['idpomaterial'];

   // Ambil nopomaterial untuk keperluan log activity sebelum dihapus
   $getPomaterialQuery = "SELECT nopomaterial FROM pomaterial WHERE idpomaterial = $idpomaterial";
   $result = mysqli_query($conn, $getPomaterialQuery);
   $row = mysqli_fetch_assoc($result);
   $nopomaterial = $row['nopomaterial'];

   // Soft delete data dari tabel pomaterial (set is_deleted = 1)
   $softDeleteQuery = "UPDATE pomaterial SET is_deleted = 1 WHERE idpomaterial = $idpomaterial";
   mysqli_query($conn, $softDeleteQuery);

   // Insert log activity into logactivity table
   $idusers = $_SESSION['idusers'];
   $event = "Soft Delete PO Material";
   $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                VALUES ('$idusers', '$nopomaterial', '$event', NOW())";
   mysqli_query($conn, $logQuery);

   // Alihkan ke halaman index.php setelah berhasil menghapus data
   header("location: index.php");
   exit();
} else {
   // Redirect jika ID tidak ada
   echo "ID Tidak Ditemukan";
   exit();
}
