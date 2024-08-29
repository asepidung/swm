<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi kode lebih lanjut jika belum login
}

require "../konak/conn.php";

// Periksa apakah ada parameter ID yang diberikan
if (isset($_GET['idpomaterial'])) {
   $idpomaterial = $_GET['idpomaterial'];

   // Ambil nopomaterial untuk keperluan log activity sebelum dihapus
   $getPomaterialQuery = "SELECT nopomaterial FROM pomaterial WHERE idpomaterial = $idpomaterial";
   $result = mysqli_query($conn, $getPomaterialQuery);
   $row = mysqli_fetch_assoc($result);
   $nopomaterial = $row['nopomaterial'];

   // Hapus data dari tabel pomaterialdetail
   $deleteDetailQuery = "DELETE FROM pomaterialdetail WHERE idpomaterial = $idpomaterial";
   mysqli_query($conn, $deleteDetailQuery);

   // Hapus data dari tabel pomaterial
   $deleteQuery = "DELETE FROM pomaterial WHERE idpomaterial = $idpomaterial";
   mysqli_query($conn, $deleteQuery);

   // Insert log activity into logactivity table
   $idusers = $_SESSION['idusers'];
   $event = "Delete PO Material";
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
