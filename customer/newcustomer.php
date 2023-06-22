<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
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
            <div class="col">
               <!-- general form elements -->
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Data Customer Baru</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="inputCustomer.php">
                     <div class=" card-body">
                        <div class="form-group">
                           <label for="nama_customer">Nama Customer <span class="text-danger">*</span></label>
                           <input type="text" class="form-control" name="nama_customer" id="nama_customer" autofocus required>
                           <div class="form-group">
                              <label for="alamat">Alamat</label>
                              <input type="text" class="form-control" name="alamat" id="alamat">
                           </div>
                           <div class="form-group">
                              <label for="idsegment">Segment <span class="text-danger">*</span></label>
                              <div class="input-group">
                                 <select class="form-control" name="idsegment" id="idsegment" required>
                                    <option value="">Pilih Segment</option>
                                    <?php
                                    $query = "SELECT * FROM segment";
                                    $result = mysqli_query($conn, $query);
                                    // Generate options based on the retrieved data
                                    while ($row = mysqli_fetch_assoc($result)) {
                                       $idsegment = $row['idsegment'];
                                       $nmsegment = $row['nmsegment'];
                                       echo "<option value=\"$idsegment\">$nmsegment</option>";
                                    }
                                    ?>
                                 </select>
                                 <div class="input-group-append">
                                    <a href="../segment/segment.php" class="btn btn-warning"><i class="fas fa-plus"></i></a>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="top">T.O.P</label>
                              <input type="number" class="form-control" name="top" id="top">
                           </div>
                           <div class="form-group">
                              <label for="pajak">Customer Dikenakan Pajak</label>
                              <select class="form-control" name="pajak" id="pajak">
                                 <option>--Pilih Satu</option>
                                 <option value="1">Yes</option>
                                 <option value="0">No</option>
                              </select>
                           </div>
                           <div class="form-group">
                              <label for="telepon">Telepon</label>
                              <input type="tel" class="form-control" name="telepon" id="telepon">
                           </div>
                           <div class="form-group">
                              <label for="email">Email</label>
                              <input type="email" class="form-control" name="email" id="email">
                           </div>
                           <div class="form-group">
                              <label for="catatan">Catatan</label>
                              <textarea name="catatan" id="catatan" rows="2" class="form-control"></textarea>
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
<?php include "../footnote.php" ?>
<?php include "../footer.php" ?>