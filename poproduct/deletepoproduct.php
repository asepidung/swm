<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Periksa apakah ada parameter ID yang diberikan
if (isset($_GET['idpoproduct'])) {
   $idpoproduct = $_GET['idpoproduct'];

   // Ambil nopoproduct sebelum menghapus data
   $query = "SELECT nopoproduct FROM poproduct WHERE idpoproduct = $idpoproduct";
   $result = mysqli_query($conn, $query);
   $row = mysqli_fetch_assoc($result);
   $nopoproduct = $row['nopoproduct'];

   // Soft delete data dari tabel poproduct (set is_deleted = 1)
   $softDeleteQuery = "UPDATE poproduct SET is_deleted = 1 WHERE idpoproduct = $idpoproduct";
   mysqli_query($conn, $softDeleteQuery);

   // Insert log activity into logactivity table
   $idusers = $_SESSION['idusers'];
   $event = "Soft Delete PO Product";
   $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                VALUES ('$idusers', '$nopoproduct', '$event', NOW())";
   mysqli_query($conn, $logQuery);

   // Alihkan ke halaman index.php setelah berhasil menghapus data
   header("location: index.php");
   exit();
} else {
   // Redirect jika ID tidak ada
   echo "ID Tidak Ditemukan";
   exit();
}
