<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "nost.php";

if (isset($_POST['submit'])) {
   $tglst = $_POST['tglst'];
   $note = $_POST['note'];

   // Buat query INSERT
   $query_st = "INSERT INTO stocktake (nost, tglst, note) VALUES (?, ?, ?)";
   $stmt_st = $conn->prepare($query_st);
   $stmt_st->bind_param("sss", $kodeauto, $tglst, $note);
   $stmt_st->execute();

   $stmt_st->close();
   $conn->close();

   // header("location: stdetail.php?idst=$last_id");
   header("location: index.php");
}
