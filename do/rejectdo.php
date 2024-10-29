<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";

$iddo = intval($_GET['iddo']);
$idso = intval($_GET['idso']);

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
   // Update tabel do untuk mengubah status menjadi 'Rejected'
   $updateDoQuery = "UPDATE do SET status = 'Rejected' WHERE iddo = ?";
   $stmtDo = $conn->prepare($updateDoQuery);
   $stmtDo->bind_param("i", $iddo);
   $stmtDo->execute();
   $stmtDo->close();

   // Update tabel tally untuk mengubah stat menjadi 'Rejected' berdasarkan $idso terkait
   $updateTallyQuery = "UPDATE tally SET stat = 'Rejected' WHERE idso = ?";
   $stmtTally = $conn->prepare($updateTallyQuery);
   $stmtTally->bind_param("i", $idso);
   $stmtTally->execute();
   $stmtTally->close();

   // Update tabel salesorder untuk mengubah progress menjadi 'Rejected' berdasarkan $idso terkait
   $updateSoQuery = "UPDATE salesorder SET progress = 'Rejected' WHERE idso = ?";
   $stmtSo = $conn->prepare($updateSoQuery);
   $stmtSo->bind_param("i", $idso);
   $stmtSo->execute();
   $stmtSo->close();

   // Mendapatkan idtally terkait dari tabel tally berdasarkan $idso
   $querySelectIdTally = "SELECT idtally FROM tally WHERE idso = ?";
   $stmtSelectIdTally = $conn->prepare($querySelectIdTally);
   $stmtSelectIdTally->bind_param("i", $idso);
   $stmtSelectIdTally->execute();
   $stmtSelectIdTally->bind_result($idtally);
   $stmtSelectIdTally->fetch();
   $stmtSelectIdTally->close();

   // Ambil data dari tallydetail berdasarkan idtally
   $querySelectTallyDetail = "SELECT barcode, idgrade, idbarang, weight, pcs, pod, origin FROM tallydetail WHERE idtally = ?";
   $stmtSelectTallyDetail = $conn->prepare($querySelectTallyDetail);
   $stmtSelectTallyDetail->bind_param("i", $idtally);
   $stmtSelectTallyDetail->execute();
   $stmtSelectTallyDetail->store_result();
   $stmtSelectTallyDetail->bind_result($barcode, $idgrade, $idbarang, $weight, $pcs, $pod, $origin);

   // Masukkan data ke tabel stock
   $insertStockQuery = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) VALUES (?, ?, ?, ?, ?, ?, ?)";
   $stmtInsertStock = $conn->prepare($insertStockQuery);

   while ($stmtSelectTallyDetail->fetch()) {
      // Bind parameter dan execute untuk setiap record
      $stmtInsertStock->bind_param("siidssi", $barcode, $idgrade, $idbarang, $weight, $pcs, $pod, $origin);
      $stmtInsertStock->execute();
   }

   $stmtInsertStock->close();
   $stmtSelectTallyDetail->close();

   // Mendapatkan donumber dari tabel do berdasarkan $iddo
   $query_select_donumber = "SELECT donumber FROM do WHERE iddo = ?";
   $stmt_select_donumber = $conn->prepare($query_select_donumber);
   $stmt_select_donumber->bind_param("i", $iddo);
   $stmt_select_donumber->execute();
   $stmt_select_donumber->bind_result($donumber);
   $stmt_select_donumber->fetch();
   $stmt_select_donumber->close();

   // Insert ke tabel logactivity
   $idusers = $_SESSION['idusers'];
   $event = "Reject DO";
   $docnumb = $donumber;
   $waktu = date('Y-m-d H:i:s'); // Waktu saat ini

   $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, ?)";
   $stmtLogActivity = $conn->prepare($queryLogActivity);
   $stmtLogActivity->bind_param("isss", $idusers, $event, $docnumb, $waktu);
   $resultLogActivity = $stmtLogActivity->execute();

   if (!$resultLogActivity) {
      throw new Exception("Error saat memasukkan data log activity: " . mysqli_error($conn));
   }

   // Commit transaksi
   mysqli_commit($conn);

   // Redirect ke halaman do.php setelah selesai
   echo "<script>alert('Data berhasil di Reject dan barang dimasukkan ke stock.'); window.location='do.php';</script>";
} catch (Exception $e) {
   // Rollback transaksi jika terjadi kesalahan
   mysqli_rollback($conn);
   error_log($e->getMessage()); // Menyimpan error ke log
   echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.location='do.php';</script>";
} finally {
   // Menutup koneksi ke database
   mysqli_close($conn);
}
