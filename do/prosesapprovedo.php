<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Mendapatkan data dari form
   $iddo = isset($_POST['iddo']) ? intval($_POST['iddo']) : 0;
   $deliverydate = $_POST['deliverydate'];
   $idcustomer = isset($_POST['idcustomer']) ? intval($_POST['idcustomer']) : 0;
   $po = $_POST['po'];
   $donumber = $_POST['donumber'];
   $note = $_POST['note'];
   $xbox = $_POST['xbox'];
   $xweight = $_POST['xweight'];
   $idusers = $_SESSION['idusers'];
   $status = "Approved";
   $idso = $_POST['idso'];

   // Insert data ke tabel doreceipt
   $queryInsertDoreceipt = "INSERT INTO doreceipt (iddo, idso, donumber, deliverydate, idcustomer, po, note, xbox, xweight, idusers, status)
   VALUES ('$iddo', '$idso', '$donumber', '$deliverydate', '$idcustomer', '$po', '$note', '$xbox', '$xweight', '$idusers', '$status')";
   echo "Query Insert Doreceipt: " . $queryInsertDoreceipt . "<br>"; // Debugging
   $resultInsertDoreceipt = mysqli_query($conn, $queryInsertDoreceipt);

   if ($resultInsertDoreceipt) {
      $iddoreceipt = mysqli_insert_id($conn); // Mendapatkan ID dari data yang baru saja di-insert

      // Insert data ke tabel doreceiptdetail
      if (isset($_POST['idbarang']) && isset($_POST['box']) && isset($_POST['weight'])) {
         $idbarang = $_POST['idbarang'];
         $boxes = $_POST['box'];
         $weights = $_POST['weight'];
         $notes = $_POST['notes'];

         for ($i = 0; $i < count($idbarang); $i++) {
            $box = intval($boxes[$i]);
            $weight = floatval($weights[$i]);
            $note = $notes[$i];

            $queryInsertDoreceiptDetail = "INSERT INTO doreceiptdetail (iddoreceipt, idbarang, box, weight, notes)
                                              VALUES ('$iddoreceipt', '$idbarang[$i]', '$box', '$weight', '$note')";
            echo "Query Insert DoreceiptDetail: " . $queryInsertDoreceiptDetail . "<br>"; // Debugging
            $resultInsertDoreceiptDetail = mysqli_query($conn, $queryInsertDoreceiptDetail);
            if (!$resultInsertDoreceiptDetail) {
               die("Error saat memasukkan data doreceiptdetail: " . mysqli_error($conn));
            }
         }
      }

      // Update status pada tabel do menjadi "Approved"
      $queryUpdateDo = "UPDATE do SET status = 'Approved', rweight = '$xweight' WHERE iddo = '$iddo'";
      echo "Query Update Do: " . $queryUpdateDo . "<br>"; // Debugging
      $resultUpdateDo = mysqli_query($conn, $queryUpdateDo);

      if (!$resultUpdateDo) {
         die("Error saat mengupdate status pada tabel do: " . mysqli_error($conn));
      }

      // Update status pada tabel salesorder menjadi "Delivered"
      $queryUpdateSo = "UPDATE salesorder SET progress = 'Delivered' WHERE idso = '$idso'";
      echo "Query Update SalesOrder: " . $queryUpdateSo . "<br>"; // Debugging
      $resultUpdateSo = mysqli_query($conn, $queryUpdateSo);

      if (!$resultUpdateSo) {
         die("Error saat mengupdate progress pada tabel salesorder: " . mysqli_error($conn));
      }

      header("Location: do.php"); // Ganti do.php dengan halaman tujuan setelah data berhasil disimpan
      exit;
   } else {
      die("Error saat memasukkan data doreceipt: " . mysqli_error($conn));
   }
}
