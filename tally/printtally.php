<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
$idtally = $_GET['id'];
$query_tally = "SELECT tally.*, customers.nama_customer
                  FROM tally 
                  INNER JOIN customers ON tally.idcustomer = customers.idcustomer 
                  WHERE tally.idtally = '$idtally'";
$result_tally = mysqli_query($conn, $query_tally);
$row_tally = mysqli_fetch_assoc($result_tally);

$query_tallydetail = "SELECT tallydetail.*, barang.nmbarang 
                        FROM tallydetail 
                        INNER JOIN barang ON tallydetail.idbarang = barang.idbarang 
                        WHERE idtally = '$idtally'";
$result_tallydetail = mysqli_query($conn, $query_tallydetail);
?>

<div class=" container">
   <div class="col text-center">
      <h4 class="mb-n1">TALY SHEET</h4>
      <span><strong> Taly Number : <?= $row_tally['notally']; ?></strong></span>
   </div>
   <hr>
   <div class="row mt-2">
      <div class="col-6">
         <table class="table table-responsive table-borderless table-sm">
            <tr>
               <td>Customer</td>
               <td>:</td>
               <th><?= $row_tally['nama_customer']; ?></th>
            </tr>
            <tr>
               <td>PO Numb</td>
               <td>:</td>
               <th><?= $row_tally['po']; ?></th>
            </tr>
         </table>
      </div>
      <div class="col-6">
         <table class="table table-responsive table-borderless table-sm">
            <tr>
               <td>Delivery Date</td>
               <td>:</td>
               <th><?= date('d-M-Y', strtotime($row_tally['deliverydate'])); ?></th>
            </tr>
            <tr>
               <td>SO Numb</td>
               <td>:</td>
               <th><?= $row_tally['sonumber']; ?></th>
            </tr>
         </table>
      </div>
   </div>
   <table class="table table-sm table-striped table-bordered">
      <thead class="thead-dark">
         <tr class="text-center">
            <th>Product</th>
            <?php
            for ($i = 1; $i <= 10; $i++) {
               $header = ($i < 10) ? '0' . $i : $i;
               echo '<th>' . $header . '</th>';
            }
            echo '<th>TOTAL</th>';
            ?>
         </tr>
      </thead>
      <tbody class="text-center">
         <?php
         $productData = [];

         while ($row_tallydetail = mysqli_fetch_assoc($result_tallydetail)) {
            $currentProductName = $row_tallydetail['nmbarang'];
            $weight = $row_tallydetail['weight'];

            if (!isset($productData[$currentProductName])) {
               $productData[$currentProductName] = [
                  'weights' => [],
                  'total' => 0,
               ];
            }

            $productData[$currentProductName]['weights'][] = $weight;
            $productData[$currentProductName]['total'] += $weight;
         }

         foreach ($productData as $productName => $data) {
            $weightArray = $data['weights'];
            $weightTotal = $data['total'];
            $count = count($weightArray);
            $rowsNeeded = ceil($count / 10);

            for ($rowIndex = 0; $rowIndex < $rowsNeeded; $rowIndex++) {
               echo '<tr>';
               if ($rowIndex === 0) {
                  echo '<td class="text-left">' . $productName . '</td>';
               } else {
                  echo '<td></td>';
               }

               for ($i = 0; $i < 10; $i++) {
                  $weightIndex = ($rowIndex * 10) + $i;
                  if (isset($weightArray[$weightIndex])) {
                     echo '<td>' . $weightArray[$weightIndex] . '</td>';
                  } else {
                     echo '<td></td>';
                  }
               }

               if ($rowIndex === $rowsNeeded - 1) {
                  echo '<td>' . $weightTotal . '</td>';
               } else {
                  echo '<td></td>';
               }

               echo '</tr>';
            }
         }

         ?>
      </tbody>
   </table>
   <div class="row mt-4">
      <div class="col-6 text-center">
         WAREHOUSE <br><br><br><br> ( ..................................... )
      </div>
      <div class="col-6 text-center">
         CUSTOMER <br><br><br><br> ( ..................................... )
      </div>
   </div>
   <?php
   include "../footer.php";
   ?>