<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
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

   $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb, waktu) 
                        VALUES ('$idusers', '$event', '$docnumb', '$waktu')";
   $resultLogActivity = mysqli_query($conn, $queryLogActivity);

   if (!$resultLogActivity) {
      throw new Exception("Error saat memasukkan data log activity: " . mysqli_error($conn));
   }

   // Commit transaksi
   mysqli_commit($conn);

   // Redirect ke halaman do.php setelah selesai
   echo "<script>alert('Data berhasil di  Reject.'); window.location='do.php';</script>";
} catch (Exception $e) {
   // Rollback transaksi jika terjadi kesalahan
   mysqli_rollback($conn);
   echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.location='do.php';</script>";
}

// Menutup koneksi ke database
mysqli_close($conn);
