<?php
session_start();
require "../konak/conn.php";

$idgr = $_POST['idgr'];
$barcode = $_POST['barcode'];

// Cek apakah barcode sudah ada di tabel grdetail dengan idgr terkait
$cekDuplikat = mysqli_query($conn, "SELECT * FROM grdetail WHERE kdbarcode = '$barcode' AND idgr = $idgr");

if (mysqli_num_rows($cekDuplikat) > 0) {
   // Jika duplikat ditemukan, redirect kembali ke halaman grscan dengan pesan error
   $_SESSION['error'] = "Data dengan barcode tersebut sudah ada di GR Detail.";
   header("Location: grscan.php?idgr=$idgr");
   exit();
}

// Jika tidak ada duplikat, lanjutkan proses pengambilan data dari tabel tallydetail
$queryTally = mysqli_query($conn, "SELECT * FROM tallydetail WHERE barcode = '$barcode' LIMIT 1");
$dataTally = mysqli_fetch_assoc($queryTally);

if ($dataTally) {
   $idgrade = $dataTally['idgrade'];
   $idbarang = $dataTally['idbarang'];
   $qty = $dataTally['weight'];
   $pcs = $dataTally['pcs'];
   $pod = $dataTally['pod'];
   $origin = $dataTally['origin'];

   // Insert data ke tabel grdetail
   $insertGrDetail = mysqli_query($conn, "INSERT INTO grdetail (idgr, idgrade, idbarang, kdbarcode, pcs, qty, pod) 
                                           VALUES ($idgr, $idgrade, $idbarang, '$barcode', $pcs, $qty, '$pod')");

   // Insert data ke tabel stock
   $insertStock = mysqli_query($conn, "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) 
                                        VALUES ('$barcode', $idgrade, $idbarang, $qty, $pcs, '$pod', $origin)");

   if ($insertGrDetail && $insertStock) {
      $_SESSION['success'] = "Data berhasil diinput ke GR Detail dan Stock.";
   } else {
      $_SESSION['error'] = "Gagal menyimpan data. Silakan coba lagi.";
   }
} else {
   $_SESSION['error'] = "Barcode tidak ditemukan di Tally Detail.";
}

header("Location: grscan.php?idgr=$idgr");
exit();
