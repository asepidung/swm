<?php
require "../verifications/auth.php";
require "../konak/conn.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $idreturjual = $_POST['idreturjual'];
  $returnnumber = $_POST['returnnumber'];
  $returdate = $_POST['returdate'];
  $idcustomer = $_POST['idcustomer'];
  $iddo = $_POST['iddo'];
  $note = $_POST['note'];
  $xbox = $_POST['xbox'];
  $xweight = $_POST['xweight'];

  // Update returjual record
  $query_update = "UPDATE returjual SET returnnumber = ?, returdate = ?, idcustomer = ?, iddo = ?, note = ?, xbox = ?, xweight = ? WHERE idreturjual = ?";
  $stmt_update = $conn->prepare($query_update);
  $stmt_update->bind_param("ssiisidi", $returnnumber, $returdate, $idcustomer, $iddo, $note, $xbox, $xweight, $idreturjual);
  $stmt_update->execute();
  $stmt_update->close();

  // Delete existing returjualdetail records for the given idreturjual
  $query_delete_detail = "DELETE FROM returjualdetail WHERE idreturjual = ?";
  $stmt_delete_detail = $conn->prepare($query_delete_detail);
  $stmt_delete_detail->bind_param("i", $idreturjual);
  $stmt_delete_detail->execute();
  $stmt_delete_detail->close();

  // Insert new returjualdetail records
  foreach ($_POST['idgrade'] as $key => $idgrade) {
    $idbarang = $_POST['idbarang'][$key];
    $box = $_POST['box'][$key];
    $weight = $_POST['weight'][$key];
    $notes = $_POST['notes'][$key];

    $query_insert_detail = "INSERT INTO returjualdetail (idreturjual, idgrade, idbarang, box, weight, notes) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert_detail = $conn->prepare($query_insert_detail);
    $stmt_insert_detail->bind_param("iiiids", $idreturjual, $idgrade, $idbarang, $box, $weight, $notes);
    $stmt_insert_detail->execute();
    $stmt_insert_detail->close();
  }

  // Redirect to index.php after successful update
  header("location: index.php");
  exit(); // Make sure to exit to prevent further execution
}

// ... (rest of the code)
