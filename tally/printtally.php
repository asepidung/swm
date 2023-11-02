<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
$idtally = $_GET['id'];
require "hitungantally.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>SWM | Apps</title>
   <link rel="icon" href="../dist/img/favicon.png" type="image/x-icon">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
   <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
   <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
   <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
   <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
   <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   <link rel="stylesheet" href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
   <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
   <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
   <link rel="stylesheet" href="../plugins/bs-stepper/css/bs-stepper.min.css">
   <link rel="stylesheet" href="../plugins/dropzone/min/dropzone.min.css">
   <link rel="stylesheet" href="../dist/css/adminlte.min.css">
   <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
   <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
   <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
   <style>
      body {
         font-size: 20px;
         /* Atur ukuran font sesuai keinginan Anda */
      }

      table.table-bordered th,
      table.table-bordered td {
         border: 2px solid black;
         /* Atur ketebalan garis sesuai kebutuhan Anda */
      }
   </style>
</head>
<div class="wrapper">

   <div class=" container">
      <div class="col text-center">
         <h4 class="mb-n1">TALY SHEET</h4>
         <span><strong> Taly Number : <?= $row_tally['notally']; ?></strong></span>
      </div>
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
      <table width=100% border="1">
         <thead class="thead-dark">
            <tr class="text-center">
               <th>Product</th>
               <th>01</th>
               <th>02</th>
               <th>03</th>
               <th>04</th>
               <th>05</th>
               <th>06</th>
               <th>07</th>
               <th>08</th>
               <th>09</th>
               <th>10</th>
               <th>TOTAL</th>
            </tr>
         </thead>
         <tbody class="text-center">
            <?php
            foreach ($productData as $productName => $data) {
               $weightArray = $data['weights'];
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

                  if ($rowIndex == $rowsNeeded - 1) {
                     echo '<td class="text-right"> <strong>' . number_format($data['total'], 2) . '</strong></td>';
                  } else {
                     echo '<td></td>';
                  }

                  echo '</tr>';
               }
            }
            ?>
         </tbody>
         <tfoot>
            <tr class="text-right">
               <th colspan="9" class="">Box</th>
               <th class="text-center "><?= $totalBox ?></th>
               <th class="">Kg</th>
               <th class=""><?= $totalQty ?></th>
            </tr>
         </tfoot>
      </table>
      <br>
      <div class="row">
         <div class="col-6 text-center">
            WAREHOUSE <br><br><br><br> ( ..................................... )
         </div>
         <div class="col-6 text-center">
            CUSTOMER <br><br><br><br> ( ..................................... )
         </div>
      </div>
      <script>
         // Trigger the print dialog when the page loads
         window.onload = function() {
            window.print();
         };

         // Close the window after printing (optional)
         window.onafterprint = function() {
            window.location.href = 'index.php';
         };
         document.title = "Taly No : <?= $row_tally['notally']; ?>";
      </script>
      <?php
      include "../footer.php";
      ?>