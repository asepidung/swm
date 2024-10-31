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
$hides = $_POST['hides'] ?? 0;
$tail = $_POST['tail'] ?? 0;

// Menyiapkan query untuk menyimpan data ke tabel carcasedetail
$query = "INSERT INTO carcasedetail (idcarcase, berat, eartag, carcase1, carcase2, hides, tail) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("idsdddd", $idcarcase, $berat, $eartag, $carcase1, $carcase2, $hides, $tail);

if ($stmt->execute()) {
   // Dapatkan iddetail terakhir yang dimasukkan
   $last_iddetail = $stmt->insert_id;
   $_SESSION['last_iddetail'] = $last_iddetail;

   // Redirect berdasarkan tombol yang ditekan
   if (isset($_POST['next'])) {
      header("location: carcasedetail.php?idcarcase=$idcarcase");
   } else {
      // Hapus session last_iddetail jika tombol 'save' yang ditekan
      unset($_SESSION['last_iddetail']);
      header("location: datacarcase.php");
   }
   exit;
} else {
   echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
