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
            <div class="col mt-3">
               <form method="POST" action="inputplandev.php">
                  <div class="card">
                     <div class="card-body">
                        <div class="form-group">
                           <label for="plandelivery">Tgl Kirim <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <input type="date" class="form-control" name="plandelivery" id="plandelivery" required autofocus>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="idcustomer">Customer <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <select class="form-control" name="idcustomer" id="idcustomer" required>
                                 <option value="">Pilih Customer</option>
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
                                 <a href="../customer/newcustomer.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="weight">Weight <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <input type="number" class="form-control" name="weight" id="weight" required>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="driver_name">Driver</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="driver_name" id="driver_name">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="armada">Armada</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="armada" id="armada">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="loadtime">Load Time</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="loadtime" id="loadtime">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="note">Catatan</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="note" id="note" placeholder="keterangan">
                           </div>
                        </div>
                        <div class="form-group text-center">
                           <button type="submit" class="btn btn-block btn-primary">Set Up</button>
                        </div>
                     </div>
                  </div>
               </form>

            </div>
         </div>
      </div>
   </section>
</div>
<script>
   document.title = "Kiriman Baru";
</script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>