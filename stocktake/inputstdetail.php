<?php
session_start();

if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

require "../konak/conn.php";

if (isset($_POST['kdbarcode'])) {
   $kdbarcode = mysqli_real_escape_string($conn, $_POST['kdbarcode']);
   $idst = $_POST['idst'];

   // Buat query sesuai dengan digit pertama
   $query = '';
   switch ($kdbarcode[0]) {
      case '1':
         $query = "SELECT idbarang, qty, pcs, packdate, idgrade FROM labelboning WHERE kdbarcode = '$kdbarcode'";
         $origin = 1;
         break;
      case '2':
         $query = "SELECT idbarang, qty, pcs, packdate, idgrade FROM trading WHERE kdbarcode = '$kdbarcode'";
         $origin = 2;
         break;
      case '3':
         $query = "SELECT idbarang, qty, pcs, packdate, idgrade FROM detailhasil WHERE kdbarcode = '$kdbarcode'";
         $origin = 3;
         break;
      case '4':
         $query = "SELECT idbarang, qty, pcs, packdate, idgrade FROM relabel WHERE kdbarcode = '$kdbarcode'";
         $origin = 4;
         break;
   }

   if (!empty($query)) {
      $result = mysqli_query($conn, $query);

      if ($result) {
         if (mysqli_num_rows($result) > 0) {
            // Data ditemukan, cek apakah sudah ada di stocktakedetail
            $checkDuplicateQuery = "SELECT * FROM stocktakedetail WHERE kdbarcode = '$kdbarcode' AND idst = $idst";
            $duplicateResult = mysqli_query($conn, $checkDuplicateQuery);

            if ($duplicateResult && mysqli_num_rows($duplicateResult) > 0) {
               // Barcode sudah ada di stocktakedetail, redirect ke halaman duplicate
               header("location: starttaking.php?id=$idst&stat=duplicate");
               exit;
            } else {
               // Barcode belum ada di stocktakedetail, lakukan insert
               $row = mysqli_fetch_assoc($result);
               $idgrade = $row['idgrade'];
               // $kdbarcode = $row['kdbarcode']; // Ini seharusnya tidak diperlukan karena sudah diatur di atas
               $idbarang = $row['idbarang'];
               $qty = $row['qty'];
               $pcs = $row['pcs'];
               $pod = $row['packdate'];

               // Lakukan insert ke stocktakedetail
               $insertQuery = "INSERT INTO stocktakedetail (idst, kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) VALUES ('$idst', '$kdbarcode', '$idgrade', '$idbarang', '$qty', '$pcs', '$pod', '$origin')";
               $insertResult = mysqli_query($conn, $insertQuery);

               if (!$insertResult) {
                  // Handle error saat insert
                  exit;
               }

               header("location: starttaking.php?id=$idst&stat=success");
            }
         } else {
            $_SESSION['barcode'] = $kdbarcode;
            header("location: starttaking.php?id=$idst&stat=unknown");
            exit;
         }
      } else {
         // Handle query execution error
      }
   } else {
      // Handle empty query
   }
}

// ...
