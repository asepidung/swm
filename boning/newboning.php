<?php
require "../konak/conn.php";
require "kodebatchboning.php";
include "../assets/html/header.php";
include "../assets/html/navbar.php";
include "../assets/html/mainsidebar.php";
?>

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
            <div class="card card-primary mt-3">
               <div class="card-header">
                  <h3 class="card-title">New Boning Project</h3>
               </div>
               <!-- /.card-header -->
               <!-- form start -->
               <form method="POST" action="prosesnewboning.php">
                  <div class=" card-body">
                     <div class="form-group">
                        <label for="batchboning">BATCH</label>
                        <input type="text" class="form-control" name="batchboning" id="batchboning" value="<?= $kodeauto; ?>" readonly>
                     </div>
                     <div class="form-group">
                        <label>Supplier</label>
                        <select class="form-control" name="idsupplier" id="idsupplier">
                           <option value=""> Pilih Disini</option>
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
                     </div>
                     <div class="form-group">
                        <label>Tanggal Potong</label>
                        <div class="input-group date" id="tglkill" data-target-input="nearest">
                           <input type="date" class="form-control" name="tglkill" id="tglkill">
                           <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                        </div>
                     </div>
                     <div class="form-group">
                        <label>Tanggal Boning</label>
                        <div class="input-group date" id="tglboning" data-target-input="nearest">
                           <input type="date" class="form-control" name="tglboning" id="tglboning">
                           <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                        </div>
                     </div>
                     <div class="form-group">
                        <label>Jumlah sapi</label>
                        <div class="input-group date" id="qtysapi" data-target-input="nearest">
                           <input type="number" class="form-control" name="qtysapi" id="qtysapi">
                           <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                        </div>
                     </div>
                     <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" rows="3" name="catatan" id="catatan" placeholder="Tulis disini bila ada yang penting ..."></textarea>
                     </div>
                  </div>
                  <div class="form-group ml-3">
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