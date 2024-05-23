<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['items']) && isset($_POST['iddo']) && isset($_POST['idso'])) {
   $items = $_POST['items'];
   $iddo = intval($_POST['iddo']);
   $idso = intval($_POST['idso']);

   // Prepare statement for inserting into stock table
   $stmtInsert = $conn->prepare("INSERT INTO stock (kdbarcode, idbarang, idgrade, qty, pcs, pod, origin) VALUES (?, ?, ?, ?, ?, ?, ?)");
   if (!$stmtInsert) {
      die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
   }

   // Prepare statement for deleting from tallydetail table
   $stmtDelete = $conn->prepare("DELETE tallydetail FROM tallydetail INNER JOIN tally ON tallydetail.idtally = tally.idtally WHERE tallydetail.barcode = ? AND tally.idso = ?");
   if (!$stmtDelete) {
      die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
   }

   foreach ($items as $item) {
      $item = json_decode($item, true);
      $kdbarcode = $item['barcode'];
      $idbarang = intval($item['idbarang']);
      $idgrade = intval($item['idgrade']);
      $qty = floatval($item['weight']);
      $pcs = isset($item['pcs']) ? intval($item['pcs']) : null;
      $pod = $item['pod'];
      $origin = intval($item['origin']);

      // Bind parameters for insert
      $stmtInsert->bind_param("siidisi", $kdbarcode, $idbarang, $idgrade, $qty, $pcs, $pod, $origin);
      if (!$stmtInsert->execute()) {
         echo "Error: " . $stmtInsert->error;
         exit();
      }

      // Bind parameters for delete
      $stmtDelete->bind_param("si", $kdbarcode, $idso);
      if (!$stmtDelete->execute()) {
         echo "Error: " . $stmtDelete->error;
         exit();
      }
   }

   // Redirect ke halaman lain setelah selesai
   header("location: approvedo.php?iddo=$iddo");
   exit();
} else {
   echo "No items selected or missing iddo.";
   exit();
}

$conn->close();
