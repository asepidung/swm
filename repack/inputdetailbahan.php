<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
if (isset($_POST['barcode'])) {
   $barcode = $_POST['barcode'];
   $idrepack = $_POST['idrepack'];

   // Langsung query ke tabel stock berdasarkan barcode
   $query = "SELECT idbarang, idgrade, qty, pcs, pod, origin FROM stock WHERE kdbarcode = '$barcode'";

   // Eksekusi query
   $result = mysqli_query($conn, $query);

   if ($result && $row = mysqli_fetch_assoc($result)) {
      $idbarang = $row['idbarang'];
      $idgrade = $row['idgrade'];
      $qty = $row['qty']; // Menyesuaikan nama kolom di tabel
      $pcs = $row['pcs'];
      $pod = $row['pod'];
      $origin = $row['origin'];

      // Selanjutnya, kita akan melakukan pengecekan apakah $barcode sudah ada di tabel detailbahan
      $cekBarcodeQuery = "SELECT iddetailbahan FROM detailbahan WHERE idrepack = $idrepack AND barcode = '$barcode'";
      $cekBarcodeResult = mysqli_query($conn, $cekBarcodeQuery);

      if (mysqli_num_rows($cekBarcodeResult) > 0) {
         // Barcode sudah ada di tabel detailbahan, arahkan kembali ke halaman dengan status "duplicate"
         header("location: detailbahan.php?id=$idrepack&stat=duplicate");
         exit;
      } else {
         // Barcode belum ada di tabel detailbahan, lanjutkan dengan query insert
         $insertQuery = "INSERT INTO detailbahan (idrepack, barcode, idbarang, idgrade, qty, pcs, pod, origin) VALUES ('$idrepack', '$barcode', '$idbarang',  '$idgrade', '$qty', '$pcs', '$pod', '$origin')";
         mysqli_query($conn, $insertQuery);

         // Hapus data dari tabel stock
         $deleteQuery = "DELETE FROM stock WHERE kdbarcode = '$barcode'";
         mysqli_query($conn, $deleteQuery);

         // Redirect kembali ke halaman detailbahan.php dengan status "success"
         header("location: detailbahan.php?id=$idrepack&stat=success");
      }
   } else {
      // Barcode tidak ditemukan di tabel stock
      $_SESSION['barcode'] = $barcode;
      header("location: detailbahan.php?id=$idrepack&stat=unknown");
      exit;
   }
}
