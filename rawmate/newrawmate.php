<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "kdrawmateunik.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>

<div class="content-wrapper">
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
                  <form method="POST" action="prosesnewrawmate.php">
                     <div class=" card-body">
                        <div class="form-group">
                           <label for="kdrawmate">Kode</label>
                           <input type="text" class="form-control" name="kdrawmate" id="kdrawmate" value="<?= $kodeauto; ?>" readonly>
                        </div>
                        <div class="form-group">
                           <label for="nmrawmate">Nama Material <span class="text-danger">*</span></label>
                           <input type="text" class="form-control" name="nmrawmate" id="nmrawmate" placeholder="Gunakan Huruf BESAR !!!" required>
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

<?php
include "../footer.php";
include "../footnote.php";
?>