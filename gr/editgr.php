<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Ambil data dari database berdasarkan idgr yang dikirim melalui parameter URL
if (isset($_GET['idgr'])) {
   $idgr = $_GET['idgr'];
   $query_edit = "SELECT gr.*, supplier.nmsupplier FROM gr
                  JOIN supplier ON gr.idsupplier = supplier.idsupplier
                  WHERE idgr = ?";
   $stmt_edit = $conn->prepare($query_edit);
   $stmt_edit->bind_param("i", $idgr);
   $stmt_edit->execute();
   $result_edit = $stmt_edit->get_result();
   $data_edit = $result_edit->fetch_assoc();
   $stmt_edit->close();
}
?>
<div class="content-wrapper">

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="updategr.php">
                  <input type="hidden" name="idgr" value="<?= $data_edit['idgr']; ?>">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label for="grnumber">GR Number</label>
                                 <input type="text" class="form-control" name="grnumber" value="<?= $data_edit['grnumber']; ?>" readonly>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="receivedate">Receiving Date</label>
                                 <input type="date" class="form-control" name="receivedate" value="<?= $data_edit['receivedate']; ?>" required>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="idsupplier">Supplier</label>
                                 <select class="form-control" name="idsupplier" required>
                                    <option value="">Pilih supplier</option>
                                    <?php
                                    $query_supplier = "SELECT * FROM supplier ORDER BY nmsupplier ASC";
                                    $result_supplier = mysqli_query($conn, $query_supplier);
                                    while ($row_supplier = mysqli_fetch_assoc($result_supplier)) {
                                       $selected = ($row_supplier['idsupplier'] == $data_edit['idsupplier']) ? "selected" : "";
                                       echo "<option value=\"{$row_supplier['idsupplier']}\" $selected>{$row_supplier['nmsupplier']}</option>";
                                    }
                                    ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="idnumber">ID Number</label>
                                 <input type="text" class="form-control" name="idnumber" value="<?= $data_edit['idnumber']; ?>">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <input type="text" class="form-control" name="note" value="<?= $data_edit['note']; ?>" placeholder="Receiving Note">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div class="row mb-2">
                           <div class="col-1">Code</div>
                           <div class="col-4">Prod. Description</div>
                           <div class="col-1">Box</div>
                           <div class="col-2">Weight</div>
                           <div class="col">Note</div>
                        </div>
                        <?php
                        $query_grdetail = "SELECT grdetail.*, grade.nmgrade, barang.nmbarang
                                          FROM grdetail
                                          INNER JOIN grade ON grdetail.idgrade = grade.idgrade
                                          INNER JOIN barang ON grdetail.idbarang = barang.idbarang
                                          WHERE idgr = '$idgr'";
                        $result_grdetail = mysqli_query($conn, $query_grdetail);
                        while ($row_grdetail = mysqli_fetch_assoc($result_grdetail)) { ?>
                           <div class="row mb-n2">
                              <div class="col-1">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" class="form-control" value="<?= $row_grdetail['nmgrade'] ?>">
                                       <input type="hidden" name="idgrade[]" value="<?= $row_grdetail['idgrade'] ?>">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-4">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" class="form-control" value="<?= $row_grdetail['nmbarang'] ?>">
                                       <input type="hidden" name="idbarang[]" value="<?= $row_grdetail['idbarang'] ?>">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-1">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="box[]" class="form-control text-center" value="<?= $row_grdetail['box'] ?>">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="weight[]" class="text-right form-control" value="<?= $row_grdetail['weight'] ?>">
                                    </div>
                                 </div>
                              </div>
                              <div class="col">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="notes[]" class="form-control" value="<?= $row_grdetail['notes'] ?>">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        <?php } ?>
                        <div class="row">
                           <div class="col-5"></div>
                           <div class="col-1">
                              <input type="text" name="xbox" id="xbox" class="form-control text-center" readonly>
                           </div>
                           <div class="col-2">
                              <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly>
                           </div>
                           <div class="col-1">
                              <button type="button" class="btn bg-gradient-warning" onclick="calculateTotals()">Calculate</button>
                           </div>
                           <div class="col-1">
                              <button type="submit" class="btn bg-gradient-primary ml-2" name="submit" onclick="return confirm('Pastikan Data Yang Di Update Sudah Benar')" disabled id="submit-btn">Update</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
<script>
   function calculateTotals() {
      var rows = document.getElementsByClassName("row mb-n2");
      var xboxTotal = 0;
      var xweightTotal = 0;

      for (var i = 0; i < rows.length; i++) {
         var boxInput = rows[i].querySelector(".form-group .input-group input[name='box[]']");
         var weightInput = rows[i].querySelector(".form-group .input-group input[name='weight[]']");

         xboxTotal += parseInt(boxInput.value) || 0;
         xweightTotal += parseFloat(weightInput.value) || 0;
      }

      document.getElementsByName("xbox")[0].value = xboxTotal;
      document.getElementsByName("xweight")[0].value = xweightTotal.toFixed(2);

      var submitBtn = document.getElementById("submit-btn");
      submitBtn.disabled = false;
   }


   // Mengubah judul halaman web
   document.title = "Goods Receipt List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>