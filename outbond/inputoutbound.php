<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
  $nooutbound = $_POST['nooutbound'];
  $tgloutbound = $_POST['tgloutbound'];
  $xweight = $_POST['xweight'];
  $xbox = $_POST['xbox'];
  $note = $_POST['note'];
  $proses = $_POST['proses'];
  $idusers = $_SESSION['idusers'];

  $query_outbound = "INSERT INTO outbound (nooutbound, tgloutbound, xweight, xbox, note, proses, idusers) VALUES (?,?,?,?,?,?,?)";
  $stmt_outbound = $conn->prepare($query_outbound);
  $stmt_outbound->bind_param("ssdissi", $nooutbound, $tgloutbound, $xweight, $xbox, $note, $proses, $idusers);
  $stmt_outbound->execute();

  $last_id = $stmt_outbound->insert_id;

  $idgrade = $_POST['idgrade'];
  $idbarang = $_POST['idbarang'];
  $box = $_POST['box'];
  $weight = $_POST['weight'];
  $notes = $_POST['notes'];

  $query_outbounddetail = "INSERT INTO outbounddetail (idoutbound, idgrade, idbarang, weight, box, notes) VALUES (?,?,?,?,?,?)";
  $stmt_outbounddetail = $conn->prepare($query_outbounddetail);

  for ($i = 0; $i < count($idgrade); $i++) {
    $stmt_outbounddetail->bind_param("iiidis", $last_id, $idgrade[$i], $idbarang[$i], $weight[$i], $box[$i], $notes[$i]);
    $stmt_outbounddetail->execute();
  }

  $stmt_outbounddetail->close();
  $stmt_outbound->close();
  $conn->close();

  header("location: index.php");
}
