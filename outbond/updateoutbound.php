<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
// ... (previous code)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $idoutbound = $_POST['idoutbound'];
  $proses = $_POST['proses'];
  $tgloutbound = $_POST['tgloutbound'];
  $note = $_POST['note'];
  $xbox = $_POST['xbox'];
  $xweight = $_POST['xweight'];

  // Update outbound record
  $query_update = "UPDATE outbound SET proses = ?, tgloutbound = ?, note = ?, xbox = ?, xweight = ? WHERE idoutbound = ?";
  $stmt_update = $conn->prepare($query_update);
  $stmt_update->bind_param("sssidi", $proses, $tgloutbound, $note, $xbox, $xweight, $idoutbound);
  $stmt_update->execute();
  $stmt_update->close();

  // Delete existing outbounddetail records for the given idoutbound
  $query_delete_detail = "DELETE FROM outbounddetail WHERE idoutbound = ?";
  $stmt_delete_detail = $conn->prepare($query_delete_detail);
  $stmt_delete_detail->bind_param("i", $idoutbound);
  $stmt_delete_detail->execute();
  $stmt_delete_detail->close();

  // Insert new outbounddetail records
  foreach ($_POST['idgrade'] as $key => $idgrade) {
    $idbarang = $_POST['idbarang'][$key];
    $box = $_POST['box'][$key];
    $weight = $_POST['weight'][$key];
    $notes = $_POST['notes'][$key];

    $query_insert_detail = "INSERT INTO outbounddetail (idoutbound, idgrade, idbarang, box, weight, notes) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert_detail = $conn->prepare($query_insert_detail);
    $stmt_insert_detail->bind_param("iiiids", $idoutbound, $idgrade, $idbarang, $box, $weight, $notes);
    $stmt_insert_detail->execute();
    $stmt_insert_detail->close();
  }

  // Redirect to index.php after successful update
  header("location: index.php");
  exit(); // Make sure to exit to prevent further execution
}

// ... (rest of the code)
