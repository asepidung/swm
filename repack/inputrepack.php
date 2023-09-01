<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
  $noinbound = $_POST['noinbound'];
  $tglinbound = $_POST['tglinbound'];
  $xweight = $_POST['xweight'];
  $xbox = $_POST['xbox'];
  $note = $_POST['note'];
  $proses = $_POST['proses'];
  $idusers = $_SESSION['idusers'];

  $query_inbound = "INSERT INTO inbound (noinbound, tglinbound, xweight, xbox, note, proses, idusers) VALUES (?,?,?,?,?,?,?)";
  $stmt_inbound = $conn->prepare($query_inbound);
  $stmt_inbound->bind_param("ssdissi", $noinbound, $tglinbound, $xweight, $xbox, $note, $proses, $idusers);
  $stmt_inbound->execute();

  $last_id = $stmt_inbound->insert_id;

  $idgrade = $_POST['idgrade'];
  $idbarang = $_POST['idbarang'];
  $box = $_POST['box'];
  $weight = $_POST['weight'];
  $notes = $_POST['notes'];

  $query_inbounddetail = "INSERT INTO inbounddetail (idinbound, idgrade, idbarang, weight, box, notes) VALUES (?,?,?,?,?,?)";
  $stmt_inbounddetail = $conn->prepare($query_inbounddetail);

  for ($i = 0; $i < count($idgrade); $i++) {
    $stmt_inbounddetail->bind_param("iiidis", $last_id, $idgrade[$i], $idbarang[$i], $weight[$i], $box[$i], $notes[$i]);
    $stmt_inbounddetail->execute();
  }

  $stmt_inbounddetail->close();
  $stmt_inbound->close();
  $conn->close();

  header("location: index.php");
}
