<?php
require "../konak/conn.php";

// Ambil data dari form
$idcarcase = $_POST['idcarcase'];
$killdate = $_POST['killdate'];
$idsupplier = $_POST['idsupplier'];
$breed = $_POST['breed'];
$berat = $_POST['berat'];
$eartag = $_POST['eartag'];
$carcase1 = $_POST['carcase1'];
$carcase2 = $_POST['carcase2'];
$hides = $_POST['hides'];
$tail = $_POST['tail'];

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
   // Hapus semua detail yang memiliki idcarcase terkait
   $deleteQuery = "DELETE FROM carcasedetail WHERE idcarcase = ?";
   $stmt = mysqli_prepare($conn, $deleteQuery);
   mysqli_stmt_bind_param($stmt, "i", $idcarcase);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);

   // Update data carcase
   $updateQuery = "UPDATE carcase SET killdate = ?, idsupplier = ? WHERE idcarcase = ?";
   $stmt = mysqli_prepare($conn, $updateQuery);
   mysqli_stmt_bind_param($stmt, "sii", $killdate, $idsupplier, $idcarcase);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_close($stmt);

   // Insert data carcasedetail baru
   $insertDetailQuery = "INSERT INTO carcasedetail (idcarcase, berat, eartag, carcase1, carcase2, hides, tail, breed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
   $stmt = mysqli_prepare($conn, $insertDetailQuery);

   for ($i = 0; $i < count($berat); $i++) {
      mysqli_stmt_bind_param($stmt, "idddddds", $idcarcase, $berat[$i], $eartag[$i], $carcase1[$i], $carcase2[$i], $hides[$i], $tail[$i], $breed[$i]);
      mysqli_stmt_execute($stmt);
   }
   mysqli_stmt_close($stmt);

   // Commit transaksi jika semua berhasil
   mysqli_commit($conn);

   // Redirect ke halaman datacarcase.php jika berhasil
   header("Location: datacarcase.php");
   exit;
} catch (Exception $e) {
   // Rollback jika terjadi error
   mysqli_rollback($conn);
   echo "Terjadi kesalahan: " . $e->getMessage();
}

// Tutup koneksi database
mysqli_close($conn);
