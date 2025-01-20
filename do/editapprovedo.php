<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";

if (isset($_GET['iddo'])) {
   $iddo = $_GET['iddo'];

   // Soft delete data dari tabel doreceipt
   $query_soft_delete_doreceipt = "UPDATE doreceipt 
                                    SET is_deleted = 1 
                                    WHERE iddo = ?";
   $stmt_soft_delete_doreceipt = $conn->prepare($query_soft_delete_doreceipt);
   $stmt_soft_delete_doreceipt->bind_param("i", $iddo);
   $stmt_soft_delete_doreceipt->execute();
   $stmt_soft_delete_doreceipt->close();

   // Update data di tabel do kolom status menjadi Unapproved
   $query_update_do = "UPDATE do 
                        SET status = 'Unapproved' 
                        WHERE iddo = ?";
   $stmt_update_do = $conn->prepare($query_update_do);
   $stmt_update_do->bind_param("i", $iddo);
   $stmt_update_do->execute();
   $stmt_update_do->close();

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
   $event = "Unapproved DO";
   $docnumb = $donumber;
   $waktu = date('Y-m-d H:i:s'); // Waktu saat ini

   $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb, waktu) 
                         VALUES (?, ?, ?, ?)";
   $stmt_log_activity = $conn->prepare($queryLogActivity);
   $stmt_log_activity->bind_param("isss", $idusers, $event, $docnumb, $waktu);
   $stmt_log_activity->execute();
   $stmt_log_activity->close();

   // Redirect ke halaman daftar DO
   header("location: do.php");
   exit();
}
