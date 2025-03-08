<?php
require "../verifications/auth.php";
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
            <div class="col-6 mt-3">
               <form method="POST" action="inputreturjual.php">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label for="idcustomer">Customer <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <select class="form-control" name="idcustomer" id="idcustomer" required>
                                       <option value="">Pilih Customer</option>
                                       <?php
                                       $query = "SELECT * FROM customers ORDER BY nama_customer ASC";
                                       $result = mysqli_query($conn, $query);
                                       while ($row = mysqli_fetch_assoc($result)) {
                                          $idcustomer = $row['idcustomer'];
                                          $nama_customer = $row['nama_customer'];
                                          echo "<option value=\"$idcustomer\">$nama_customer</option>";
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label for="returdate">Tgl Retur <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="returdate" id="returdate" required autofocus>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="keterangan">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-6">
                              <div class="form-group">
                                 <div class="input-group">
                                    <button type="submit" class="btn btn-info"><i class="fas fa-plus-circle"></i> Process</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
</div>
<script src="../dist/js/movefocus.js"></script>
<script>
   // Mengubah judul halaman web
   document.title = "New Retur Jual";
</script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>