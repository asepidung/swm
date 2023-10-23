<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
// include "../navbar.php";
// include "../mainsidebar.php";
?>
<!-- <div class="content-wrapper"> -->
<div class="content-header">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <a href="index.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Back To List</button></a>
         </div>
      </div>
   </div>
</div>
<!-- Main content -->
<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <div class="card">
               <div class="card-body">
                  <div id="items-container">
                     <div class="row mb-n2">
                        <div class="col-2">
                           <div class="form-group">
                              <label>Barcode</label>
                              <input type="text" class="form-control text-center" name="barcode" id="barcode" autofocus>
                           </div>
                        </div>
                        <div class="col-2">
                           <div class="form-group">
                              <label>Product</label>
                              <select class="form-control" name="idbarang[]" id="idbarang" required>
                                 <option value="">--Pilih--</option>
                                 <?php
                                 $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                                 $result = mysqli_query($conn, $query);
                                 while ($row = mysqli_fetch_assoc($result)) {
                                    $idbarang = $row['idbarang'];
                                    $nmbarang = $row['nmbarang'];
                                    echo '<option value="' . $idbarang . '">' . $nmbarang . '</option>';
                                 }
                                 ?>
                              </select>
                           </div>
                        </div>
                        <div class="col-1">
                           <div class="form-group">
                              <label>Weight</label>
                              <input type="text" class="form-control text-center" name="weight" id="weight">
                           </div>
                        </div>
                        <div class="col-1">
                           <div class="form-group">
                              <label>Pcs</label>
                              <input type="text" class="form-control text-center" name="pcs" id="pcs">
                           </div>
                        </div>
                        <div class="col-2">
                           <div class="form-group">
                              <label>Origin</label>
                              <input type="text" class="form-control text-center" name="origin" id="origin">
                           </div>
                        </div>
                        <div class="col-2">
                           <div class="form-group">
                              <label>P.O.D</label>
                              <input type="date" class="form-control text-center" name="pod" id="pod">
                           </div>
                        </div>
                        <div class="col-1">
                           <div class="form-group">
                              <label>Status</label>
                              <input type="text" class="form-control text-center" readonly>
                           </div>
                        </div>
                        <div class="col-1">
                           <div class="form-group">
                              <label for="add"></label>
                              <button class="btn text-warning" name="add"><i class="fas fa-plus-circle"></i></button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-8">
                  <div class="card">
                     <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>#</th>
                                 <th>Barcode</th>
                                 <th>Item</th>
                                 <th>Weight</th>
                                 <th>Pcs</th>
                                 <th>POD</th>
                                 <th>Origin</th>
                                 <th>Hapus</th>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="card">
                     <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>Prod</th>
                                 <th>PO</th>
                                 <th>Qty</th>
                                 <th>Box</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td></td>
                                 <td class="text-center"></td>
                                 <td class="text-right"></td>
                                 <td class="text-center"></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
</div>

<script>
   // Mengubah judul halaman web
   document.title = "Tally Sheet";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>