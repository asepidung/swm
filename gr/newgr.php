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
            <div class="col mt-3">
               <form method="POST" action="inputgr.php">
                  <input type="hidden" value="<?= $gr ?>" name="grnumber" id="grnumber">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label for="receivedate">Receiving Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="receivedate" id="receivedate" required>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="idsupplier">Supplier <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <select class="form-control" name="idsupplier" id="idsupplier" required>
                                       <option value="">Pilih supplier</option>
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
                                       <a href="../supplier/newsupplier.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="po">ID Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="idnumber" id="idnumber">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="Receiving Note">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col ml-1">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')">Submit</button>
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
<script>
   // Mengubah judul halaman web
   document.title = "<?= $gr ?>";
</script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>