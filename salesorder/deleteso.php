<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}
require "../konak/conn.php";

if (isset($_GET['idso'])) {
   $idsalesorder = $_GET['idso'];

   // Ambil sonumber sebelum dihapus
   $query_get_sonumber = "SELECT sonumber FROM salesorder WHERE idso = ?";
   $stmt_get_sonumber = $conn->prepare($query_get_sonumber);
   $stmt_get_sonumber->bind_param("i", $idsalesorder);
   $stmt_get_sonumber->execute();
   $stmt_get_sonumber->bind_result($sonumber);
   $stmt_get_sonumber->fetch();
   $stmt_get_sonumber->close();

   // Hapus data dari tabel plandev terlebih dahulu
   $query_delete_plandev = "DELETE FROM plandev WHERE idso = ?";
   $stmt_delete_plandev = $conn->prepare($query_delete_plandev);
   $stmt_delete_plandev->bind_param("i", $idsalesorder);
   $stmt_delete_plandev->execute();
   $stmt_delete_plandev->close();

   // Hapus data dari tabel salesorderdetail
   $query_delete_salesorderdetail = "DELETE FROM salesorderdetail WHERE idso = ?";
   $stmt_delete_salesorderdetail = $conn->prepare($query_delete_salesorderdetail);
   $stmt_delete_salesorderdetail->bind_param("i", $idsalesorder);
   $stmt_delete_salesorderdetail->execute();
   $stmt_delete_salesorderdetail->close();

   // Hapus data dari tabel salesorder
   $query_delete_salesorder = "DELETE FROM salesorder WHERE idso = ?";
   $stmt_delete_salesorder = $conn->prepare($query_delete_salesorder);
   $stmt_delete_salesorder->bind_param("i", $idsalesorder);
   $stmt_delete_salesorder->execute();
   $stmt_delete_salesorder->close();

   // Insert log activity into logactivity table
   $idusers = $_SESSION['idusers'];
   $event = "Hapus Sales Order";
   $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                VALUES (?, ?, ?, NOW())";
   $stmt_log = $conn->prepare($logQuery);
   $stmt_log->bind_param("iss", $idusers, $sonumber, $event);
   $stmt_log->execute();
   $stmt_log->close();
}

header("location: index.php"); // Redirect to the list page
exit();
