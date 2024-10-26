<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Mendapatkan data dari form
$idcarcase = $_POST['idcarcase'];
$berat = $_POST['berat'];
$eartag = $_POST['eartag'];
$carcase1 = $_POST['carcase1'];
$carcase2 = $_POST['carcase2'];
$hides = $_POST['hides'] ?? 0; // Nilai default 0 jika tidak diisi
$tail = $_POST['tail'] ?? 0; // Nilai default 0 jika tidak diisi

// Menyiapkan query untuk menyimpan data ke tabel carcasedetail
$query = "INSERT INTO carcasedetail (idcarcase, berat, eartag, carcase1, carcase2, hides, tail) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("idsdddd", $idcarcase, $berat, $eartag, $carcase1, $carcase2, $hides, $tail);

// Mengecek apakah query berhasil
if ($stmt->execute()) {
   // Cek tombol yang diklik oleh pengguna (Next atau Simpan)
   if (isset($_POST['next'])) {
      // Jika tombol Next diklik, arahkan kembali ke halaman carcasedetail.php
      header("location: carcasedetail.php?idcarcase=$idcarcase");
   } else {
      // Jika tombol Simpan diklik, arahkan ke halaman datacarcase.php
      header("location: datacarcase.php");
   }
} else {
   // Jika terjadi kesalahan, tampilkan pesan
   echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
