<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "nomutasi.php";

if (isset($_POST['submit'])) {
   $tglmutasi = $_POST['tglmutasi'];
   $note = $_POST['note'];
   $iduser = $_SESSION['idusers'];

   // Buat query INSERT
   $query_st = "INSERT INTO mutasi (nomutasi, tglmutasi, note, idusers) VALUES (?, ?, ?, ?)";
   $stmt_st = $conn->prepare($query_st);
   $stmt_st->bind_param("sssi", $kodeauto, $tglmutasi, $note, $iduser);
   $stmt_st->execute();

   // Dapatkan ID terakhir yang di-generate
   $last_id = mysqli_insert_id($conn);

   $stmt_st->close();
   $conn->close();

   // Redirect ke halaman detailmutasi dengan menggunakan ID terakhir
   header("location: mutasidetail.php?id=$last_id&stat=ready");
}
