<?php
require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

   // Validasi input
   for ($i = 0; $i < count($berat); $i++) {
      if (empty($berat[$i]) || $berat[$i] > 1000) {
         die("Baris " . ($i + 1) . ": Berat tidak boleh kosong dan maksimal 1000.");
      }
      if (empty($eartag[$i])) {
         die("Baris " . ($i + 1) . ": Eartag tidak boleh kosong.");
      }
      if (empty($carcase1[$i]) || $carcase1[$i] > 250) {
         die("Baris " . ($i + 1) . ": Carcase 1 tidak boleh kosong dan maksimal 250.");
      }
      if (empty($carcase2[$i]) || $carcase2[$i] > 250) {
         die("Baris " . ($i + 1) . ": Carcase 2 tidak boleh kosong dan maksimal 250.");
      }
      if (empty($hides[$i]) || $hides[$i] > 100) {
         die("Baris " . ($i + 1) . ": Hides tidak boleh kosong dan maksimal 100.");
      }
      if (!empty($tail[$i]) && $tail[$i] > 100) {
         die("Baris " . ($i + 1) . ": Tails maksimal 100.");
      }
   }

   mysqli_begin_transaction($conn);
   try {
      // Hapus detail sebelumnya
      $deleteQuery = "DELETE FROM carcasedetail WHERE idcarcase = ?";
      $stmt = mysqli_prepare($conn, $deleteQuery);
      mysqli_stmt_bind_param($stmt, "i", $idcarcase);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      // Update carcase utama
      $updateQuery = "UPDATE carcase SET killdate = ?, idsupplier = ? WHERE idcarcase = ?";
      $stmt = mysqli_prepare($conn, $updateQuery);
      mysqli_stmt_bind_param($stmt, "sii", $killdate, $idsupplier, $idcarcase);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      // Tambahkan detail baru
      $insertDetailQuery = "INSERT INTO carcasedetail (idcarcase, berat, eartag, carcase1, carcase2, hides, tail, breed) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $insertDetailQuery);

      for ($i = 0; $i < count($berat); $i++) {
         mysqli_stmt_bind_param($stmt, "idddddss", $idcarcase, $berat[$i], $eartag[$i], $carcase1[$i], $carcase2[$i], $hides[$i], $tail[$i], $breed[$i]);
         mysqli_stmt_execute($stmt);
      }

      mysqli_stmt_close($stmt);

      // Commit transaksi
      mysqli_commit($conn);

      header("Location: datacarcase.php");
      exit;
   } catch (Exception $e) {
      mysqli_rollback($conn);
      echo "Terjadi kesalahan: " . $e->getMessage();
   }

   mysqli_close($conn);
}
