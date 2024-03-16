<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['id'])) {
   $idtally = $_GET['id'];
   $idso = $_GET['idso'];

   // Ambil data dari tabel tallydetail
   $query_select_tallydetail = "SELECT * FROM tallydetail WHERE idtally = ?";
   $stmt_select_tallydetail = $conn->prepare($query_select_tallydetail);
   $stmt_select_tallydetail->bind_param("i", $idtally);
   $stmt_select_tallydetail->execute();
   $result_tallydetail = $stmt_select_tallydetail->get_result();

   // Loop untuk memasukkan data dari tallydetail ke stock
   while ($row = $result_tallydetail->fetch_assoc()) {
      $barcode = $row['barcode'];
      $idbarang = $row['idbarang'];
      $idgrade = $row['idgrade'];
      $weight = $row['weight'];
      $pcs = $row['pcs'];
      $pod = $row['pod'];
      $origin = $row['origin'];

      // Masukkan data ke tabel stock
      $query_insert_stock = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt_insert_stock = $conn->prepare($query_insert_stock);
      $stmt_insert_stock->bind_param("siidisi", $barcode, $idgrade, $idbarang, $weight, $pcs, $pod, $origin);
      $stmt_insert_stock->execute();
   }

   // Tutup statement untuk query select tallydetail
   $stmt_select_tallydetail->close();

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
}

header("location: index.php?stat=deleted"); // Redirect to the list page
