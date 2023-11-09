<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
if (isset($_POST['barcode'])) {
   $barcode = $_POST['barcode'];
   $firstDigit = substr($barcode, 0, 1);
   $idrepack = $_POST['idrepack'];

   $query = '';
   if ($firstDigit == '1') {
      $query = "SELECT idbarang, qty, pcs, packdate FROM labelboning WHERE kdbarcode = '$barcode'";
      $origin = 1;
   } elseif ($firstDigit == '2') {
      $query = "SELECT idbarang, qty, pcs, packdate FROM trading WHERE kdbarcode = '$barcode'";
      $origin = 2;
   } elseif ($firstDigit == '3') {
      $query = "SELECT idbarang, qty, pcs, packdate FROM detailhasil WHERE kdbarcode = '$barcode'";
      $origin = 2;
   } elseif ($firstDigit == '4') {
      $query = "SELECT idbarang, qty, pcs, packdate FROM relabel WHERE kdbarcode = '$barcode'";
      $origin = 4;
   }

   if (!empty($query)) {
      $result = mysqli_query($conn, $query);
      if ($result && mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);
         $idbarang = $row['idbarang'];
         $qty = $row['qty']; // Menyesuaikan nama kolom di tabel
         $pcs = $row['pcs'];
         $pod = $row['packdate'];

         // Selanjutnya, kita akan melakukan pengecekan apakah $barcode sudah ada di tabel detailbahan
         $cekBarcodeQuery = "SELECT iddetailbahan FROM detailbahan WHERE idrepack = $idrepack AND barcode = '$barcode'";
         $cekBarcodeResult = mysqli_query($conn, $cekBarcodeQuery);

         if (mysqli_num_rows($cekBarcodeResult) > 0) {
            // Barcode sudah ada di tabel detailbahan, arahkan kembali ke halaman dengan status "duplicate"
            header("location: detailbahan.php?id=$idrepack&stat=duplicate");
            exit;
         } else {
            // Barcode belum ada di tabel detailbahan, lanjutkan dengan query insert
            $insertQuery = "INSERT INTO detailbahan (idrepack, barcode, idbarang, qty, pcs, pod, origin) VALUES ('$idrepack', '$barcode', '$idbarang', '$qty', '$pcs', '$pod', '$origin')";
            mysqli_query($conn, $insertQuery);

            // Redirect kembali ke halaman detailbahan.php dengan status "success"
            header("location: detailbahan.php?id=$idrepack&stat=success");
         }
      } else {
         $_SESSION['barcode'] = $barcode;
         header("location: detailbahan.php?id=$idrepack&stat=unknown");
         exit;
      }
   }
}
