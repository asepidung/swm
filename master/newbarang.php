<?php
require "../konak/conn.php";
require "kdbarangunik.php";
include "../assets/html/header.php";
include "../assets/html/navbar.php";
include "../assets/html/mainsidebar.php";
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
   <!-- Content Header (Page header) -->
   <!-- <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">NEW BONING PROJECT</h1>
            </div>
         </div>
      </div>
   </div> -->
   <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <!-- left column -->
            <div class="col-md-6">
               <!-- general form elements -->
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Input Item Baru</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="prosesnewbarang.php">
                     <div class=" card-body">
                        <div class="form-group">
                           <label for="kdbarang">Kode</label>
                           <input type="text" class="form-control" name="kdbarang" id="kdbarang" value="<?= $kodeauto; ?>" readonly>
                        </div>
                        <div class="form-group">
                           <label for="nmbarang">Nama Product <span class="text-danger">*</span></label>
                           <input type="text" class="form-control" name="nmbarang" id="nmbarang" required>
                        </div>
                     </div>
                     <div class="form-group mr-3 text-right">
                        <button type="submit" class="btn bg-gradient-primary">Submit</button>
                     </div>
               </div>
               <!-- /.card-body -->

               </form>
            </div>
            <!-- /.card -->
         </div>
      </div>
   </section>
   </div><!-- /.container-fluid -->
   <!-- /.content -->
   <!-- </div> -->
   <!-- /.content-wrapper -->

   <?php include "../assets/html/footer.php" ?>