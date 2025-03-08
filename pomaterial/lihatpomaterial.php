<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../inv/terbilang.php";

$idpomaterial = $_GET['idpomaterial'];
$idusers = $_SESSION['idusers'];

// Tampilkan data dari tabel pomaterial
$query_pomaterial = "SELECT pomaterial.*, supplier.nmsupplier 
                  FROM pomaterial 
                  INNER JOIN supplier ON pomaterial.idsupplier = supplier.idsupplier 
                  WHERE pomaterial.idpomaterial = '$idpomaterial'";
$result_pomaterial = mysqli_query($conn, $query_pomaterial);
$row_pomaterial = mysqli_fetch_assoc($result_pomaterial);
$Terms = $row_pomaterial['Terms'];
// Tampilkan data dari tabel pomaterialdetail
$query_pomaterialdetail = "SELECT pomaterialdetail.*, rawmate.nmrawmate 
                        FROM pomaterialdetail 
                        INNER JOIN rawmate ON pomaterialdetail.idrawmate = rawmate.idrawmate 
                        WHERE idpomaterial = '$idpomaterial'";
$result_pomaterialdetail = mysqli_query($conn, $query_pomaterialdetail);


?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="widtd=device-widtd, initial-scale=1">
   <title><?= $row_pomaterial['nopomaterial'] . " " . $row_pomaterial['nmsupplier']; ?></title>
   <link rel="icon" href="../dist/img/favicon.png" type="image/x-icon">
   <link rel="stylesheet" href="../https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
   <link rel="stylesheet" href="../https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
   <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
   <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
   <link rel="stylesheet" href="../dist/css/adminlte.min.css">
   <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
   <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
   <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
   <style>
      .floating-buttons {
         position: fixed;
         bottom: 20px;
         left: 20px;
         z-index: 9999;
      }

      .floating-buttons .btn {
         margin-bottom: 5px;
         display: block;
      }
   </style>


</head>

<body class="hold-transition sidebar-mini layout-fixed">
   <div class="row">
      <div class="col-xs floating-buttons">
         <a href="index.php" class="btn btn-warning"><i class="fas fa-undo-alt"></i> Kembali</a>
         <a href="printpomaterial.php?idpomaterial=<?= $idpomaterial ?>" class="btn btn-primary"><i class="fas fa-print"></i> Print</a>
      </div>
   </div>
   <div class="wrapper">
      <div class="container">
         <div class="row mb-2">
            <img src="../dist/img/headerpo.png" alt=" Logo-pomaterial" class="img-fluid">
         </div>
         <!-- <span class="mt-3 mb-2">
            <h5><?= $row_pomaterial['nopomaterial']; ?></h5>
         </span> -->
         <table class="table table-borderless table-sm">
            <tr>
               <td width="15%">PO Number</td>
               <td width="1%">:</td>
               <td width="30%"><?= $row_pomaterial['nopomaterial']; ?></td>
               <td></td>
               <td width="15%">Delivery Date</td>
               <td width="1%">:</td>
               <td width="30%"><?= date('d-M-Y', strtotime($row_pomaterial['deliveryat'])); ?></td>
            </tr>
            <tr>
               <td width="15%">PO Date</td>
               <td width="1%">:</td>
               <td width="30%"><?= date('d-M-Y', strtotime($row_pomaterial['deliveryat'])); ?></td>
               <td></td>
               <td width="15%">Supplier</td>
               <td width="1%">:</td>
               <td width="30%"><?= $row_pomaterial['nmsupplier']; ?></td>
            </tr>
            <tr>
               <td width="15%">Delivery Address</td>
               <td width="1%">:</td>
               <td width="30%">RPH Jonggol Kp. Menan Rt 04.01 Ds. Sukamaju Kec. Jonggol Kab. Bogor</td>
               <td></td>
               <td width="15%" valign="top">Terms</td>
               <td width="1%" valign="top">:</td>
               <?php if ($Terms === "COD" || $Terms === "CBD") { ?>
                  <td><?= $Terms; ?> </td>
               <?php } else { ?>
                  <td><?= $Terms . " " . "Hari Dari Kedatangan"; ?> </td>
               <?php } ?>
            </tr>

         </table>
         <table class="table table-sm table-bordered border-0">
            <thead class="thead-dark">
               <tr class="text-center">
                  <th>#</th>
                  <th>Prod Descriptions</th>
                  <th>Weight</th>
                  <th>Price</th>
                  <th>Total</th>
                  <th>Notes</th>
               </tr>
            </thead>
            <tbody>
               <?php $no = 1;
               $xweight = 0;
               $xamount = 0;
               while ($row_pomaterialdetail = mysqli_fetch_assoc($result_pomaterialdetail)) {
                  $xweight += $row_pomaterialdetail['qty'];
                  $xamount += $row_pomaterialdetail['amount'];
               ?>
                  <tr class="text-right">
                     <td class="text-center"><?= $no; ?></td>
                     <td class="text-left"><?= $row_pomaterialdetail['nmrawmate']; ?></td>
                     <td><?= number_format($row_pomaterialdetail['qty'], 2); ?></td>
                     <td><?= number_format($row_pomaterialdetail['price'], 2); ?></td>
                     <td><?= number_format($row_pomaterialdetail['amount'], 2); ?></td>
                     <td><?= $row_pomaterialdetail['notes']; ?></td>
                  </tr>
               <?php $no++;
               } ?>
            </tbody>
            <tfoot class="text-right">


               <tr>
                  <th colspan="2" class="border-0">Qty Total</th>
                  <th class="border-0"><?= number_format($xweight, 2); ?></th>
                  <th class="border-0">Total Amount</th>
                  <th class="border-0"><?= number_format($xamount, 2); ?></th>
               </tr>
            </tfoot>
         </table>
         <hr>
         <div class="row">
            <div class="col-6">
               <strong>
                  Says :
               </strong>
               <?= terbilang($xamount) . " " . "Rupiah" ?>
            </div>
         </div>
         <div class="row mt-3">
            <div class="col-6 float-right text-justify">
               <strong>Catatan :</strong>
               <?= $row_pomaterial['note']; ?>
            </div>
         </div>
         <div class="row">
            <div class="col text-right mt-4">
               P U R C H A S I N G
               <br><br><br><br><br>
               ( ............................ )
            </div>
         </div>
      </div>
   </div>
   <script src="../plugins/jquery/jquery.min.js"></script>
   <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="../plugins/select2/js/select2.full.min.js"></script>
   <script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
   <script src="../plugins/moment/moment.min.js"></script>
   <script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
   <script src="../plugins/daterangepicker/daterangepicker.js"></script>
   <script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
   <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
   <script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
   <script src="../plugins/dropzone/min/dropzone.min.js"></script>
   <script src="../dist/js/adminlte.min.js"></script>
   <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
   <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
   <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
   <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
   <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
   <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
   <script src="../plugins/jszip/jszip.min.js"></script>
   <script src="../plugins/pdfmake/pdfmake.min.js"></script>
   <script src="../plugins/pdfmake/vfs_fonts.js"></script>
   <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
   <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>


</body>

</html>