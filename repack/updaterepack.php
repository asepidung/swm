<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
// ... (previous code)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $idinbound = $_POST['idinbound'];
  $proses = $_POST['proses'];
  $tglinbound = $_POST['tglinbound'];
  $note = $_POST['note'];
  $xbox = $_POST['xbox'];
  $xweight = $_POST['xweight'];

  // Update inbound record
  $query_update = "UPDATE inbound SET proses = ?, tglinbound = ?, note = ?, xbox = ?, xweight = ? WHERE idinbound = ?";
  $stmt_update = $conn->prepare($query_update);
  $stmt_update->bind_param("sssidi", $proses, $tglinbound, $note, $xbox, $xweight, $idinbound);
  $stmt_update->execute();
  $stmt_update->close();

  // Delete existing inbounddetail records for the given idinbound
  $query_delete_detail = "DELETE FROM inbounddetail WHERE idinbound = ?";
  $stmt_delete_detail = $conn->prepare($query_delete_detail);
  $stmt_delete_detail->bind_param("i", $idinbound);
  $stmt_delete_detail->execute();
  $stmt_delete_detail->close();

  // Insert new inbounddetail records
  foreach ($_POST['idgrade'] as $key => $idgrade) {
    $idbarang = $_POST['idbarang'][$key];
    $box = $_POST['box'][$key];
    $weight = $_POST['weight'][$key];
    $notes = $_POST['notes'][$key];

    $query_insert_detail = "INSERT INTO inbounddetail (idinbound, idgrade, idbarang, box, weight, notes) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert_detail = $conn->prepare($query_insert_detail);
    $stmt_insert_detail->bind_param("iiiids", $idinbound, $idgrade, $idbarang, $box, $weight, $notes);
    $stmt_insert_detail->execute();
    $stmt_insert_detail->close();
  }

  // Redirect to index.php after successful update
  header("location: index.php");
  exit(); // Make sure to exit to prevent further execution
}

// ... (rest of the code)
