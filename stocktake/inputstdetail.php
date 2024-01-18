<?php
session_start();

if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

require "../konak/conn.php";

if (isset($_POST['kdbarcode'])) {
   $kdbarcode = mysqli_real_escape_string($conn, $_POST['kdbarcode']);
   $idst = $_POST['idst'];

   // Cek apakah barcode sudah ada di tabel stock
   $query = "SELECT idbarang, qty, pcs, pod, idgrade, origin FROM stock WHERE kdbarcode = '$kdbarcode'";
   $result = mysqli_query($conn, $query);

   if ($result) {
      if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);

         // Data ditemukan, cek apakah sudah ada di stocktakedetail
         $checkDuplicateQuery = "SELECT * FROM stocktakedetail WHERE kdbarcode = '$kdbarcode' AND idst = $idst";
         $duplicateResult = mysqli_query($conn, $checkDuplicateQuery);

         if ($duplicateResult && mysqli_num_rows($duplicateResult) > 0) {
            // Barcode sudah ada di stocktakedetail, redirect ke halaman duplicate
            header("location: starttaking.php?id=$idst&stat=duplicate");
            exit;
         } else {
            // Barcode belum ada di stocktakedetail, lakukan insert
            $idgrade = $row['idgrade'];
            $idbarang = $row['idbarang'];
            $qty = $row['qty'];
            $pcs = $row['pcs'];
            $pod = $row['pod'];
            $origin = $row['origin'];

            // Handle NULL idgrade
            $idgradeValue = ($idgrade !== null) ? "'$idgrade'" : "NULL";

            // Lakukan insert ke stocktakedetail
            $insertQuery = "INSERT INTO stocktakedetail (idst, kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) VALUES ('$idst', '$kdbarcode', $idgradeValue, '$idbarang', '$qty', '$pcs', '$pod', '$origin')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if (!$insertResult) {
               // Handle error saat insert
               exit;
            }

            header("location: starttaking.php?id=$idst&stat=success");
         }
      } else {
         // Data tidak ditemukan di tabel stock
         $_SESSION['barcode'] = $kdbarcode;
         header("location: starttaking.php?id=$idst&stat=unknown");
         exit;
      }
   } else {
      // Handle query execution error
   }
}
// ...
