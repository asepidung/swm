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

   // Hapus data dari tabel poproductdetail
   $deleteDetailQuery = "DELETE FROM poproductdetail WHERE idpoproduct = $idpoproduct";
   mysqli_query($conn, $deleteDetailQuery);

   // Hapus data dari tabel poproduct
   $deleteQuery = "DELETE FROM poproduct WHERE idpoproduct = $idpoproduct";
   mysqli_query($conn, $deleteQuery);

   // Alihkan ke halaman index.php setelah berhasil menghapus data
   header("location: index.php");
   exit();
} else {
   // Redirect jika ID tidak ada
   echo "ID Tidak Ditemukan";
   exit();
}
