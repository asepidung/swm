<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "kodebatchboning.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
$idusers = $_SESSION['idusers'];
?>

<div class="content-wrapper">
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
                     <h3 class="card-title">New Boning Project</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="prosesnewboning.php">
                     <input type="hidden" name="idusers" id="idusers" value="<?= $idusers ?>">
                     <div class=" card-body">
                        <div class="form-group">
                           <label for="batchboning">BATCH</label>
                           <input type="text" class="form-control" name="batchboning" id="batchboning" value="<?= $kodeauto; ?>" readonly>
                        </div>
                        <div class="form-group">
                           <label>Supplier <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <select class="form-control" name="idsupplier" id="idsupplier" required>
                                 <option value="">Pilih Disini</option>
                                 <?php
                                 $query = "SELECT * FROM supplier ORDER BY nmsupplier ASC";
                                 $result = mysqli_query($conn, $query);
                                 // Generate options based on the retrieved data
                                 while ($row = mysqli_fetch_assoc($result)) {
                                    $idsupplier = $row['idsupplier'];
                                    $nmsupplier = $row['nmsupplier'];
                                    echo "<option value=\"$idsupplier\">$nmsupplier</option>";
                                 }
                                 ?>
                              </select>
                              <div class="input-group-append">
                                 <a href="../supplier/newsupplier.php" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label>Tanggal Boning <span class="text-danger">*</span></label>
                           <div class="input-group date" id="tglboning" data-target-input="nearest">
                              <input type="date" class="form-control" name="tglboning" id="tglboning" required>
                              <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                           </div>
                        </div>
                        <div class="form-group">
                           <label>Jumlah sapi <span class="text-danger">*</span></label>
                           <div class="input-group date" id="qtysapi" data-target-input="nearest">
                              <input type="number" class="form-control" name="qtysapi" id="qtysapi" required>
                              <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                           </div>
                        </div>
                     </div>
                     <div class="form-group mr-3 text-right">
                        <button type="submit" class="btn bg-gradient-primary">Submit</button>
                     </div>
                  </form>
               </div>
               <!-- /.card-body -->
            </div>
            <!-- /.card -->
         </div>
      </div>
   </section>
   <!-- /.container-fluid -->
   <!-- /.content -->
   <!-- </div> -->
   <!-- /.content-wrapper -->
   <script>
      // Mengubah judul halaman web
      document.title = "Boningan Baru";
   </script>

   <?php
   include "../footer.php";
   require "../footnote.php";
   ?>