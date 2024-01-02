<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
if (isset($_POST['barcode'])) {
   $barcode = $_POST['barcode'];
   $idtally = $_POST['idtally'];
   $idso_query = "SELECT idso FROM tally WHERE idtally = $idtally";
   $idso_result = mysqli_query($conn, $idso_query);

   if ($idso_result) {
      $idso_row = mysqli_fetch_assoc($idso_result);
      $idso = $idso_row['idso'];
   }

   // Langsung query ke tabel stock berdasarkan barcode
   $query = "SELECT idbarang, qty, pcs, pod, origin FROM stock WHERE kdbarcode = '$barcode'";

   // Eksekusi query
   $result = mysqli_query($conn, $query);

   if ($result && $row = mysqli_fetch_assoc($result)) {
      $idbarang = $row['idbarang'];
      $weight = $row['qty']; // Menyesuaikan nama kolom di tabel
      $pcs = $row['pcs'];
      $pod = $row['pod'];
      $origin = $row['origin'];

      // Pengecekan apakah idbarang ada dalam salesorderdetail
      $cekBarangQuery = "SELECT idbarang FROM salesorderdetail WHERE idso = $idso AND idbarang = $idbarang";
      $cekBarangResult = mysqli_query($conn, $cekBarangQuery);

      if (mysqli_num_rows($cekBarangResult) == 0) {
         // Barang tidak ada dalam salesorderdetail, arahkan kembali ke halaman unlisted
         header("location: tallydetail.php?id=$idtally&stat=unlisted");
         exit;
      } else {
         // Selanjutnya, kita akan melakukan pengecekan apakah $barcode sudah ada di tabel tallydetail
         $cekBarcodeQuery = "SELECT idtallydetail FROM tallydetail WHERE idtally = $idtally AND barcode = '$barcode'";
         $cekBarcodeResult = mysqli_query($conn, $cekBarcodeQuery);

         if (mysqli_num_rows($cekBarcodeResult) > 0) {
            // Barcode sudah ada di tabel tallydetail, arahkan kembali ke halaman dengan status "duplicate"
            header("location: tallydetail.php?id=$idtally&stat=duplicate");
            exit;
         } else {
            // Barcode belum ada di tabel tallydetail, lanjutkan dengan query insert
            $insertQuery = "INSERT INTO tallydetail (idtally, barcode, idbarang, weight, pcs, pod, origin) VALUES ('$idtally', '$barcode', '$idbarang', '$weight', '$pcs', '$pod', '$origin')";
            mysqli_query($conn, $insertQuery);

            // Redirect kembali ke halaman tallydetail.php dengan status "success"
            header("location: tallydetail.php?id=$idtally&stat=success");
         }
      }
   } else {
      // Barcode tidak ditemukan di tabel stock
      $_SESSION['barcode'] = $barcode;
      header("location: tallydetail.php?id=$idtally&stat=unknown");
      exit;
   }
}
