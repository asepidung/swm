<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// check if idboning is set in $_GET array
$idusers = $_SESSION['idusers'];
if (!isset($_GET['id'])) {
   die("Jalankan Dari Modul Produksi");
}
?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-6">
               <a href="index.php"><button type="button" class="btn btn-sm btn-success"><i class="fas fa-undo-alt"></i> TALLY</button></a>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->
   <div class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <div class="card">
                  <div class="card-body">

                  </div>
               </div>
               <!-- /.card -->
            </div>
         </div>
         <div class="row">
            <!-- /.col-md-6 -->
            <div class="col">
               <div class="card">
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>Barcode</th>
                              <th>Item</th>
                              <th>Weight</th>
                              <th>Pcs</th>
                              <th>Origin</th>
                              <th>POD</th>
                              <th>Hapus</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
               </div>
               <!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </div>
   <?php
   // require "../footnote.php";
   require "../footer.php";
   ?>