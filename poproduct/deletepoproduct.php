<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi kode lebih lanjut jika belum login
}

require "../konak/conn.php";

// Periksa apakah ada parameter ID yang diberikan
if (isset($_GET['idpoproduct'])) {
   $idpoproduct = $_GET['idpoproduct'];

   // Ambil nopoproduct sebelum menghapus data
   $query = "SELECT nopoproduct FROM poproduct WHERE idpoproduct = $idpoproduct";
   $result = mysqli_query($conn, $query);
   $row = mysqli_fetch_assoc($result);
   $nopoproduct = $row['nopoproduct'];

   // Hapus data dari tabel poproductdetail
   $deleteDetailQuery = "DELETE FROM poproductdetail WHERE idpoproduct = $idpoproduct";
   mysqli_query($conn, $deleteDetailQuery);

   // Hapus data dari tabel poproduct
   $deleteQuery = "DELETE FROM poproduct WHERE idpoproduct = $idpoproduct";
   mysqli_query($conn, $deleteQuery);

   // Insert log activity into logactivity table
   $idusers = $_SESSION['idusers'];
   $event = "Delete PO Product";
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
