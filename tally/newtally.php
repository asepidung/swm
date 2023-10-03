<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
$idusers = $_SESSION['idusers'];
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
                     <h3 class="card-title">New Tally Sheet</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="prosesnewtally.php">
                     <input type="hidden" name="idusers" id="idusers" value="<?= $idusers ?>">
                     <div class=" card-body">
                        <div class="form-group">
                           <label>SO Number <span class="text-danger">*</span></label>
                           <div class="input-group date" id="sonumber" data-target-input="nearest">
                              <input type="text" class="form-control" name="sonumber" id="sonumber" required value="-" readonly>
                           </div>
                        </div>
                        <div class="form-group">
                           <label>Customer <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <select class="form-control" name="idcustomer" id="idcustomer" required>
                                 <option value="">Pilih Disini</option>
                                 <?php
                                 $query = "SELECT * FROM customers ORDER BY nama_customer ASC";
                                 $result = mysqli_query($conn, $query);
                                 // Generate options based on the retrieved data
                                 while ($row = mysqli_fetch_assoc($result)) {
                                    $idcustomer = $row['idcustomer'];
                                    $nama_customer = $row['nama_customer'];
                                    echo "<option value=\"$idcustomer\">$nama_customer</option>";
                                 }
                                 ?>
                              </select>
                              <div class="input-group-append">
                                 <a href="../customer/newcustomer.php" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label>Tanggal Kirim <span class="text-danger">*</span></label>
                           <div class="input-group date" id="deliverydate" data-target-input="nearest">
                              <input type="date" class="form-control" name="deliverydate" id="deliverydate" required>
                           </div>
                        </div>
                        <div class="form-group">
                           <label>Nomor PO</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="ponumber" id="ponumber">
                           </div>
                        </div>
                        <div class="form-group">
                           <label>Keterangan</span></label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="keterangan" id="keterangan">
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

   <script>
      // Mengubah judul halaman web
      document.title = "New Tally Sheet";
   </script>

   <?php
   include "../footer.php";
   require "../footnote.php";
   ?>