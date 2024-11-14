<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Mendapatkan data dari form
$idcarcase = $_POST['idcarcase'];
$berat = $_POST['berat'];
$breed = $_POST['breed'];
$eartag = $_POST['eartag'];
$carcase1 = $_POST['carcase1'];
$carcase2 = $_POST['carcase2'];
$hides = $_POST['hides'] ?? 0;
$tail = $_POST['tail'] ?? 0;

// Menyiapkan query untuk menyimpan data ke tabel carcasedetail
$query = "INSERT INTO carcasedetail (idcarcase, berat, eartag, carcase1, carcase2, hides, tail, breed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("idsdddds", $idcarcase, $berat, $eartag, $carcase1, $carcase2, $hides, $tail, $breed);

if ($stmt->execute()) {
   // Dapatkan id detail carcase yang baru
   $_SESSION['last_iddetail'] = $stmt->insert_id;
   $_SESSION['breed'] = $breed; // Simpan breed ke session
   header("Location: carcasedetail.php?idcarcase=$idcarcase");
   exit();
} else {
   echo "Error: " . $stmt->error;
}
$stmt->close();
