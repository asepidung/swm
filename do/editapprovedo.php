<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['iddo'])) {
   $iddo = $_GET['iddo'];

   // Delete data dari tabel doreceiptdetail berdasarkan $iddo
   $query_delete_doreceiptdetail = "DELETE FROM doreceiptdetail WHERE iddoreceipt IN (SELECT iddoreceipt FROM doreceipt WHERE iddo = ?)";
   $stmt_delete_doreceiptdetail = $conn->prepare($query_delete_doreceiptdetail);
   $stmt_delete_doreceiptdetail->bind_param("i", $iddo);
   $stmt_delete_doreceiptdetail->execute();
   $stmt_delete_doreceiptdetail->close();

   // Delete data dari tabel doreceipt berdasarkan $iddo
   $query_delete_doreceipt = "DELETE FROM doreceipt WHERE iddo = ?";
   $stmt_delete_doreceipt = $conn->prepare($query_delete_doreceipt);
   $stmt_delete_doreceipt->bind_param("i", $iddo);
   $stmt_delete_doreceipt->execute();
   $stmt_delete_doreceipt->close();

   // Update data di tabel do kolom status menjadi Unapproved
   $query_update_do = "UPDATE do SET status = 'Unapproved' WHERE iddo = ?";
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
                        VALUES ('$idusers', '$event', '$docnumb', '$waktu')";
   $resultLogActivity = mysqli_query($conn, $queryLogActivity);

   if (!$resultLogActivity) {
      die("Error saat memasukkan data log activity: " . mysqli_error($conn));
   }
}

header("location: do.php"); // Redirect to the list page
