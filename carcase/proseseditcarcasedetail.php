<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Mendapatkan data dari form
$iddetail = $_POST['iddetail'];
$berat = $_POST['berat'];
$eartag = $_POST['eartag'];
$carcase1 = $_POST['carcase1'];
$carcase2 = $_POST['carcase2'];
$hides = $_POST['hides'] ?? 0;
$tail = $_POST['tail'] ?? 0;

// Menyiapkan query untuk mengupdate data di tabel carcasedetail
$query = "UPDATE carcasedetail SET berat = ?, eartag = ?, carcase1 = ?, carcase2 = ?, hides = ?, tail = ? WHERE iddetail = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("dsddddd", $berat, $eartag, $carcase1, $carcase2, $hides, $tail, $iddetail);

// Mengecek apakah query berhasil
if ($stmt->execute()) {
   // Redirect ke halaman carcasedetail.php dengan idcarcase yang sesuai
   $idcarcase = $_POST['idcarcase'];  // Ambil idcarcase dari form
   header("location: carcasedetail.php?idcarcase=$idcarcase");
   exit;
} else {
   echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
