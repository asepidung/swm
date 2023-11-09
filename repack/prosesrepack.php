<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

if (isset($_POST['submit'])) {
   require "../konak/conn.php";

   $norepack = $_POST['norepack'];
   $tglrepack = $_POST['tglrepack'];
   $note = $_POST['note'];
   $idusers = $_SESSION['idusers']; // Anda perlu mengganti ini dengan cara Anda mendapatkan ID pengguna yang sesuai.

   // Query SQL untuk memasukkan data ke dalam tabel "repack"
   $sql = "INSERT INTO repack (norepack, tglrepack, note, idusers) VALUES (?, ?, ?, ?)";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("sssi", $norepack, $tglrepack, $note, $idusers);

   if ($stmt->execute()) {
      // Data berhasil dimasukkan
      echo "Data berhasil dimasukkan ke dalam tabel repack.";
      header("Location: index.php"); // Ganti dengan halaman yang sesuai setelah input data berhasil.
   } else {
      // Gagal memasukkan data
      echo "Gagal memasukkan data ke dalam tabel repack: " . $stmt->error;
   }

   $stmt->close();
   $conn->close();
} else {
   echo "Akses langsung ke halaman ini tidak diizinkan.";
}
