<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "kdrawmateunik.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>

<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-6">

               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Input Item Baru</h3>
                  </div>

                  <form method="POST" action="prosesnewrawmate.php">
                     <div class="card-body">

                        <div class="form-group">
                           <label for="kdrawmate">Kode</label>
                           <input type="text" class="form-control" name="kdrawmate" id="kdrawmate" value="<?= $kodeauto; ?>" readonly>
                        </div>

                        <div class="form-group">
                           <label for="nmrawmate">Nama Material <span class="text-danger">*</span></label>
                           <input type="text" class="form-control" autofocus name="nmrawmate" id="nmrawmate" placeholder="Gunakan Huruf BESAR !!!" required>
                        </div>

                        <div class="form-group">
                           <label for="category">Category <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <select name="idrawcategory" id="category" class="form-control" required>
                                 <option value="">-- Select Category --</option>
                                 <?php
                                 $query  = "SELECT idrawcategory, nmcategory FROM rawcategory ORDER BY nmcategory ASC";
                                 $result = mysqli_query($conn, $query);

                                 if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                       echo "<option value='" . htmlspecialchars($row['idrawcategory'], ENT_QUOTES, 'UTF-8') . "'>"
                                          . htmlspecialchars($row['nmcategory'], ENT_QUOTES, 'UTF-8') . "</option>";
                                    }
                                 }
                                 ?>
                              </select>
                              <div class="input-group-append">
                                 <a href="../rawcategory/newrawcategory.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                              </div>
                           </div>
                        </div>

                        <!-- FIELD UNIT BARU -->
                        <div class="form-group">
                           <label for="unit">Unit / Satuan <span class="text-danger">*</span></label>
                           <select class="form-control" name="unit" id="unit" required>
                              <option value="">-- Pilih Satuan --</option>

                              <?php
                              $units = [
                                 "Box",
                                 "Ikat",
                                 "Kg",
                                 "Pack",
                                 "Pcs",
                              ];
                              foreach ($units as $u) {
                                 echo "<option value='$u'>$u</option>";
                              }
                              ?>
                           </select>
                        </div>
                        <!-- END FIELD UNIT -->

                        <div class="form-group">
                           <label for="tampilkan_stock">Tampilkan di Stock</label>
                           <div class="form-check">
                              <input class="form-check-input" type="radio" name="tampilkan_stock" id="tampilkan_stock_yes" value="1" checked required>
                              <label class="form-check-label" for="tampilkan_stock_yes">Ya</label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="radio" name="tampilkan_stock" id="tampilkan_stock_no" value="0" required>
                              <label class="form-check-label" for="tampilkan_stock_no">Tidak</label>
                           </div>
                        </div>

                     </div>

                     <div class="form-group mr-3 text-right">
                        <button type="submit" class="btn bg-gradient-primary">Submit</button>
                     </div>

                  </form>
               </div>

            </div>
         </div>
      </div>
   </section>
</div>

<?php
include "../footer.php";
include "../footnote.php";
?>