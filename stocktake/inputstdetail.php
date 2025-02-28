<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("Location: ../verifications/login.php");
   exit;
}

require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kdbarcode'], $_POST['idst'])) {
   $kdbarcode = trim($_POST['kdbarcode']);
   $idst = intval($_POST['idst']);

   if (empty($kdbarcode)) {
      header("Location: starttaking.php?id=$idst&stat=invalid");
      exit;
   }

   // Gunakan Prepared Statements untuk keamanan
   $query = $conn->prepare("SELECT idbarang, qty, pcs, pod, idgrade, origin FROM stock WHERE kdbarcode = ?");
   $query->bind_param("s", $kdbarcode);
   $query->execute();
   $result = $query->get_result();

   if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // Cek apakah barcode sudah ada di stocktakedetail
      $checkDuplicateQuery = $conn->prepare("SELECT idstdetail FROM stocktakedetail WHERE kdbarcode = ? AND idst = ?");
      $checkDuplicateQuery->bind_param("si", $kdbarcode, $idst);
      $checkDuplicateQuery->execute();
      $duplicateResult = $checkDuplicateQuery->get_result();

      if ($duplicateResult->num_rows > 0) {
         // Barcode sudah ada di stocktakedetail
         header("Location: starttaking.php?id=$idst&stat=duplicate");
         exit;
      } else {
         // Data dari tabel stock
         $idgrade = $row['idgrade'];
         $idbarang = $row['idbarang'];
         $qty = $row['qty'];
         $pcs = $row['pcs'];
         $pod = $row['pod'];
         $origin = $row['origin'];

         // Handle nilai NULL
         $idgradeValue = ($idgrade !== null) ? $idgrade : null;

         // Insert data ke stocktakedetail
         $insertQuery = $conn->prepare("INSERT INTO stocktakedetail (idst, kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
         $insertQuery->bind_param("issiiisi", $idst, $kdbarcode, $idgradeValue, $idbarang, $qty, $pcs, $pod, $origin);

         if ($insertQuery->execute()) {
            header("Location: starttaking.php?id=$idst&stat=success");
            exit;
         } else {
            error_log("Gagal insert: " . $conn->error);
            header("Location: starttaking.php?id=$idst&stat=error");
            exit;
         }
      }
   } else {
      // Data tidak ditemukan di tabel stock
      $_SESSION['barcode'] = $kdbarcode;
      header("Location: starttaking.php?id=$idst&stat=unknown");
      exit;
   }
} else {
   header("Location: starttaking.php?id=$idst&stat=invalid");
   exit;
}
