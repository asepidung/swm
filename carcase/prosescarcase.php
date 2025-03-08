<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Mendapatkan data dari form
$killdate = $_POST['killdate'];
$idsupplier = $_POST['idsupplier'];
$note = $_POST['note'];
$idusers = $_SESSION['idusers']; // Mengambil idusers dari session

// Mengecek apakah semua field wajib diisi
if (empty($killdate) || empty($idsupplier)) {
   echo "<script>alert('Mohon lengkapi semua field yang wajib diisi.'); window.history.back();</script>";
   exit;
}

// Menyimpan data ke tabel carcase dengan idusers
$query = "INSERT INTO carcase (killdate, idsupplier, note, idusers) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sisi", $killdate, $idsupplier, $note, $idusers);

if ($stmt->execute()) {
   $idcarcase = $stmt->insert_id;
   header("Location: carcasedetail.php?idcarcase=" . $idcarcase);
   exit;
} else {
   echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
