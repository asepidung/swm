<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: verifications/login.php");
}
$userid = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="widtd=device-widtd, initial-scale=1">
   <title>SWM Welcome</title>
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
</head>

<body class="hold-transition sidebar-mini layout-fixed">
   <div class="wrapper">
      <div class="container">
         <div class="row mb-2">
            <img src="../dist/img/invoiceswm.png" alt="Logo-Invoice" class="btn-block">
         </div>
         <div class="row">
            <table class="table table-sm table-borderless mb-3">
               <thead>
                  <tr>
                     <th>Bill To</th>
                     <td class="text-right">:</td>
                     <td>LION DCA SUPERINDO</td>
                     <td></td>
                     <th>Invoice Number</th>
                     <td class="text-right">:</td>
                     <td>INV-SWM/23/VIII/000252</td>
                  </tr>
                  <tr>
                     <th>Terms</th>
                     <td class="text-right">:</td>
                     <td>30 Days (transfer)</td>
                     <td></td>
                     <th>Invoice Date</th>
                     <td class="text-right">:</td>
                     <td>03-Aug-2023</td>
                  </tr>
                  <tr>
                     <th>Due Date</th>
                     <td class="text-right">:</td>
                     <td>07-Aug-2023</td>
                     <td></td>
                     <th>DO Number</th>
                     <td class="text-right">:</td>
                     <td>INV-DO/23/VIII/000252</td>
                  </tr>
                  <tr>
                     <th>Sales Ref</th>
                     <td class="text-right">:</td>
                     <td>MURYANI</td>
                     <td></td>
                     <th>DO Date</th>
                     <td class="text-right">:</td>
                     <td>03-Aug-2023</td>
                  </tr>
                  <tr>
                     <th>Cust. PO</th>
                     <td class="text-right">:</td>
                     <td colspan="5">PO 123456789123</td>
                  </tr>
               </thead>
            </table>
            <table class="table table-bordered table-sm">
               <thead class="text-center">
                  <tr>
                     <th>#</th>
                     <th>Product Desc</th>
                     <th>Qty (Kg) </th>
                     <th>Price </th>
                     <th>Discount % </th>
                     <th>Discount Rp </th>
                     <th>Total </th>
                  </tr>
               </thead>
               <tbody>
                  <tr class="text-right">
                     <td class="text-center">1</td>
                     <td class="text-left">Cuberoll</td>
                     <td>31.52</td>
                     <td>152.000</td>
                     <td class="text-center">2%</td>
                     <td>15.000</td>
                     <td>1,576,235.13</td>
                  </tr>
                  <tr class="text-right">
                     <td class="text-center">1</td>
                     <td class="text-left">Cuberoll</td>
                     <td>31.52</td>
                     <td>152.000</td>
                     <td class="text-center">2%</td>
                     <td>15.000</td>
                     <td>1,576,235.13</td>
                  </tr>
                  <tr class="text-right">
                     <td class="text-center">1</td>
                     <td class="text-left">Cuberoll</td>
                     <td>31.52</td>
                     <td>152.000</td>
                     <td class="text-center">2%</td>
                     <td>15.000</td>
                     <td>1,576,235.13</td>
                  </tr>
                  <tr class="text-right">
                     <td class="text-center">1</td>
                     <td class="text-left">Cuberoll</td>
                     <td>31.52</td>
                     <td>152.000</td>
                     <td class="text-center">2%</td>
                     <td>15.000</td>
                     <td>1,576,235.13</td>
                  </tr>
                  <tr class="text-right">
                     <td class="text-center">1</td>
                     <td class="text-left">Cuberoll</td>
                     <td>31.52</td>
                     <td>152.000</td>
                     <td class="text-center">2%</td>
                     <td>15.000</td>
                     <td>1,576,235.13</td>
                  </tr>
                  <tr class="text-right">
                     <td class="text-center">1</td>
                     <td class="text-left">Cuberoll</td>
                     <td>31.52</td>
                     <td>152.000</td>
                     <td class="text-center">2%</td>
                     <td>15.000</td>
                     <td>1,576,235.13</td>
                  </tr>
                  <tr class="text-right">
                     <td class="text-center">1</td>
                     <td class="text-left">Cuberoll</td>
                     <td>31.52</td>
                     <td>152.000</td>
                     <td class="text-center">2%</td>
                     <td>15.000</td>
                     <td>1,576,235.13</td>
                  </tr>
               </tbody>
               <tfoot class="text-right">
                  <tr>
                     <th colspan="3">22,171,252.36</th>
                     <th colspan="3">Grand Total :</th>
                     <th>22,171,252.36</th>
                  </tr>
                  <tr>
                     <th colspan="6">Tax 11% :</th>
                     <th>22,171,252.36</th>
                  </tr>
                  <tr>
                     <th colspan="6">Charge :</th>
                     <th>22,171,252.36</th>
                  </tr>
                  <tr>
                     <th colspan="6">DownPayment :</th>
                     <th>22,171,252.36</th>
                  </tr>
                  <tr>
                     <th colspan="6">Balance :</th>
                     <th>22,171,252.36</th>
                  </tr>
               </tfoot>
            </table>
            <div class="row">
               <div class="col">
                  Says : <br>
                  <i> Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sunt excepturi </i>
               </div>
            </div>
         </div>
         <div class="row mt-3">
            <div class="col-8">
               PAYMENT METHODS
            </div>
         </div>
         <div class="row">
            <div class="col">
               BNI (BANK NEGARA INDONESIA)
               <br>
               ACC NAME : PT. SANTI WIJAYA MEAT
               <br>
               ACC NUMBER : 8585889991
            </div>
         </div>
         <!-- /.container-fluid -->

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