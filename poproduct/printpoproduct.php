<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../inv/terbilang.php";
$idpoproduct = $_GET['idpoproduct'];
$idusers = $_SESSION['idusers'];

// Tampilkan data dari tabel poproduct
$query_poproduct = "SELECT poproduct.*, supplier.nmsupplier 
                  FROM poproduct 
                  INNER JOIN supplier ON poproduct.idsupplier = supplier.idsupplier 
                  WHERE poproduct.idpoproduct = '$idpoproduct'";
$result_poproduct = mysqli_query($conn, $query_poproduct);
$row_poproduct = mysqli_fetch_assoc($result_poproduct);
$Terms = $row_poproduct['Terms'];
// Tampilkan data dari tabel poproductdetail
$query_poproductdetail = "SELECT poproductdetail.*, barang.nmbarang 
                        FROM poproductdetail 
                        INNER JOIN barang ON poproductdetail.idbarang = barang.idbarang 
                        WHERE idpoproduct = '$idpoproduct'";
$result_poproductdetail = mysqli_query($conn, $query_poproductdetail);
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="widtd=device-widtd, initial-scale=1">
   <title><?= $row_poproduct['nopoproduct'] . " " . $row_poproduct['nmsupplier']; ?></title>
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
      .mt-0 {
         margin: 0 0 0 0;
      }

      .floatingButton {
         position: fixed;
         top: 20px;
         right: 20px;
         z-index: 9999;
      }

      .floatingLeft {
         position: fixed;
         top: 20px;
         left: 20px;
         /* Mengubah right menjadi left */
         z-index: 9999;
      }


      /* Media query untuk tampilan cetak */
      @media print {
         .floatingButton {
            display: none;
         }
      }

      /* Media query untuk tampilan cetak */
      @media print {
         .floatingLeft {
            display: none;
         }
      }
   </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
   <button class="btn bg-gradient-success floatingButton" onclick="printPage()" media="print">Print PO</button>
   <a class="btn bg-gradient-danger floatingLeft" href="index.php">Back</a>
   <div class="wrapper">
      <div class="container">
         <div class="row mb-2">
            <img src="../dist/img/headerpo.png" alt=" Logo-poproduct" class="img-fluid">
         </div>
         <!-- <span class="mt-3 mb-2">
            <h5><?= $row_poproduct['nopoproduct']; ?></h5>
         </span> -->
         <table class="table table-borderless table-sm">
            <tr>
               <td width="15%">PO Number</td>
               <td width="1%">:</td>
               <td width="30%"><?= $row_poproduct['nopoproduct']; ?></td>
               <td></td>
               <td width="15%">Delivery Date</td>
               <td width="1%">:</td>
               <td width="30%"><?= date('d-M-Y', strtotime($row_poproduct['deliveryat'])); ?></td>
            </tr>
            <tr>
               <td width="15%">PO Date</td>
               <td width="1%">:</td>
               <td width="30%"><?= date('d-M-Y', strtotime($row_poproduct['deliveryat'])); ?></td>
               <td></td>
               <td width="15%">Supplier</td>
               <td width="1%">:</td>
               <td width="30%"><?= $row_poproduct['nmsupplier']; ?></td>
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
                  <td><?= $Terms . " " . "Hari"; ?> </td>
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
               </tr>
            </thead>
            <tbody>
               <?php $no = 1;
               while ($row_poproductdetail = mysqli_fetch_assoc($result_poproductdetail)) { ?>
                  <tr class="text-right">
                     <td class="text-center"><?= $no; ?></td>
                     <td class="text-left"><?= $row_poproductdetail['nmbarang']; ?></td>
                     <td><?= number_format($row_poproductdetail['qty'], 2); ?></td>
                     <td><?= number_format($row_poproductdetail['price'], 2); ?></td>
                     <td><?= number_format($row_poproductdetail['amount'], 2); ?></td>
                  </tr>
               <?php $no++;
               } ?>
            </tbody>
            <tfoot class="text-right">
               <tr>
                  <th colspan="2" class="border-0">Qty Total</th>
                  <th class="border-0"><?= number_format($row_poproduct['xweight'], 2); ?></th>
                  <th class="border-0">Total Amount</th>
                  <th colspan="3" class="border-0"><?= number_format($row_poproduct['xamount'], 2); ?></th>
               </tr>
            </tfoot>
         </table>
         <div class="row">
            <div class="col-6">
               <strong>
                  Says :
               </strong>
               <?= terbilang($row_poproduct['xamount']) . " " . "Rupiah" ?>
            </div>
         </div>
         <div class="row mt-3">
            <div class="col-6 float-right text-justify">
               <strong>Catatan :</strong>
               <?= $row_poproduct['note']; ?>
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
   <script>
      function printPage() {
         window.print();
      }
   </script>
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