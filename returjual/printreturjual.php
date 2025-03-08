<?php
require "../verifications/auth.php";
require "../konak/conn.php";
$idreturjual = $_GET['idreturjual'];
$query = "SELECT returjual.*, customers.nama_customer
FROM returjual 
INNER JOIN customers ON returjual.idcustomer = customers.idcustomer
WHERE idreturjual = $idreturjual";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
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
</head>
<div class="wrapper">

   <div class="container mt-4">
      <div class="col text-center">
         <h4 class="mb-n1">SALES RETURN</h4>
         <span><strong><?= $row['returnnumber']; ?></strong></span>
      </div>
      <hr>
      <div class="row mt-2">
         <div class="col-5">
            <table class="table table-responsive table-borderless table-sm">
               <tr>
                  <td>Customer</td>
                  <td>:</td>
                  <th><?= $row['nama_customer']; ?></th>
               </tr>
               <tr>
                  <td>DO Number</td>
                  <td>:</td>
                  <th><?= $row['donumber']; ?></th>
               </tr>
               <tr>
                  <td>Return Date</td>
                  <td>:</td>
                  <th><?= $row['returdate']; ?></th>
               </tr>
            </table>
         </div>
         <div class="col-2"></div>
      </div>
      <table class="table table-sm table-striped table-bordered">
         <thead class="thead-dark">
            <tr class="text-center">
               <th>#</th>
               <th>Product Desc</th>
               <th>Box</th>
               <th>Qty</th>
               <th>Notes</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $no = 1;
            $query_returjualdetail = "SELECT returjualdetail.*, barang.nmbarang
             FROM returjualdetail
             INNER JOIN barang ON returjualdetail.idbarang = barang.idbarang
             WHERE idreturjual = '$idreturjual'";
            $result_returjualdetail = mysqli_query($conn, $query_returjualdetail);
            while ($row_returjualdetail = mysqli_fetch_assoc($result_returjualdetail)) { ?>
               <tr>
                  <td class="text-center"><?= $no; ?></td>
                  <td><?= $row_returjualdetail['nmbarang']; ?></td>
                  <td class="text-center"><?= $row_returjualdetail['box']; ?></td>
                  <td class="text-right"><?= number_format($row_returjualdetail['weight'], 2); ?></td>
                  <td><?= $row_returjualdetail['notes']; ?></td>
               </tr>
            <?php $no++;
            } ?>
         </tbody>
         <tfoot>
            <tr>
               <th colspan="2" class="text-right">Total</th>
               <th class="text-right"></th>
               <th class="text-right"></th>
               <th></th>
            </tr>
         </tfoot>
      </table>
      <p class="mb-n1">
         <?php
         if ($row['note'] !== "") { ?>
            <strong>Catatan :</strong>
         <?php } else {
            echo "-";
         } ?>
      </p>
      <p>
         <i><?= $row['note']; ?></i>
      </p>
      <div class="row mt-3">
         <div class="col-9"></div>
         <div class="col-1">
            <a href="index.php">
               <button type="button" class="btn btn-block btn-warning"><i class="fas fa-undo"></i> Back</button>
            </a>
         </div>
         <div class="col-1">
            <a href="editso.php?idreturjual=<?= $idreturjual ?>">
               <button type="button" class="btn btn-block btn-info"><i class="fas fa-edit"></i> Edit</button>
            </a>
         </div>
         <div class="col-1">
            <a href="printso.php?idreturjual=<?= $idreturjual ?>">
               <button type="button" class="btn btn-block btn-primary"><i class="fas fa-print"></i> Print</button>
            </a>
         </div>
      </div>
   </div>
   </body>
   <script>
      document.title = "<?= $row['returnnumber']; ?>"
      window.onload = function() {
         window.print();
      };

      // Close the window after printing (optional)
      window.onafterprint = function() {
         window.location.href = 'index.php';
      };
   </script>
   <?php

   include "../footer.php"
   ?>