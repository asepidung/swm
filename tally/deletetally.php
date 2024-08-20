<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['id'])) {
   $idtally = $_GET['id'];
   $idso = $_GET['idso'];

   // Ambil data dari tabel tally
   $query_select_tally = "SELECT notally FROM tally WHERE idtally = ?";
   $stmt_select_tally = $conn->prepare($query_select_tally);
   $stmt_select_tally->bind_param("i", $idtally);
   $stmt_select_tally->execute();
   $result_tally = $stmt_select_tally->get_result();
   $row_tally = $result_tally->fetch_assoc();
   $notally = $row_tally['notally'];

   // Delete data dari tabel tallydetail
   $query_delete_tallydetail = "DELETE FROM tallydetail WHERE idtally = ?";
   $stmt_delete_tallydetail = $conn->prepare($query_delete_tallydetail);
   $stmt_delete_tallydetail->bind_param("i", $idtally);
   $stmt_delete_tallydetail->execute();
   $stmt_delete_tallydetail->close();

   // Delete data dari tabel tally
   $query_delete_tally = "DELETE FROM tally WHERE idtally = ?";
   $stmt_delete_tally = $conn->prepare($query_delete_tally);
   $stmt_delete_tally->bind_param("i", $idtally);
   $stmt_delete_tally->execute();
   $stmt_delete_tally->close();

   // Update data di tabel salesorder
   $query_update_salesorder = "UPDATE salesorder SET progress = 'Waiting' WHERE idso = ?";
   $stmt_update_salesorder = $conn->prepare($query_update_salesorder);
   $stmt_update_salesorder->bind_param("i", $idso);
   $stmt_update_salesorder->execute();
   $stmt_update_salesorder->close();

   // Masukkan log ke tabel logactivity
   $iduser = $_SESSION['idusers'];
   $event = "Delete Data Tally";
   $query_insert_log = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, CURRENT_TIMESTAMP())";
   $stmt_insert_log = $conn->prepare($query_insert_log);
   $stmt_insert_log->bind_param("iss", $iduser, $event, $notally);
   $stmt_insert_log->execute();
   $stmt_insert_log->close();
}

header("location: index.php?stat=deleted"); // Redirect ke halaman index setelah penghapusan
