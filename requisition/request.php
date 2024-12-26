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
               <form method="POST" action="prosesrequest.php">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                              <div class="col-12 col-sm-4">
                                 <div class="form-group">
                                 <label for="duedate">Due Date (Barang Datang Paling Lambat) <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="duedate" id="duedate" required autofocus>
                                 </div>
                                 </div>
                              </div>

                              <div class="col-12 col-sm-4">
                                 <div class="form-group">
                                    <label for="idsupplier">Buy To <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <select class="form-control" name="idsupplier" id="idsupplier" required>
                                          <option value="">Pilih Supplier</option>
                                          <?php
                                          $query = "SELECT * FROM supplier ORDER BY nmsupplier ASC";
                                          $result = mysqli_query($conn, $query);
                                          while ($row = mysqli_fetch_assoc($result)) {
                                             $idsupplier = $row['idsupplier'];
                                             $nmsupplier = $row['nmsupplier'];
                                             echo "<option value=\"$idsupplier\">$nmsupplier</option>";
                                          }
                                          ?>
                                       </select>
                                       <div class="input-group-append">
                                          <a href="../supplier/newsupplier.php" class="btn btn-dark">
                                             <i class="fas fa-plus"></i>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>


                              <div class="col-12 col-sm-4">
                                 <div class="form-group">
                                 <label for="other">If Vendor Other</label>
                                 <div class="input-group">
                                    <input type="text" id="other" name="other" class="form-control" placeholder="If Vendor IS Other">
                                 </div>
                                 </div>
                              </div>
                        </div>

                        <div class="row">
                              <div class="col-12">
                                 <div class="form-group">
                                    <label for="note">Note</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="Summary Describe">
                                 </div>
                                 </div>
                              </div>
                        </div>
                     </div>
                </div>

                 <div class="card">
                     <div class="card-body">
                        <div id="items-container">
                           <!-- Baris item pertama -->
                           <div class="row">
                              <div class="col-12 col-md-3">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <select class="form-control" name="idrawmate[]" required>
                                          <option value="">--Product--</option>
                                          <?php
                                          $query = "SELECT * FROM rawmate ORDER BY nmrawmate ASC";
                                          $result = mysqli_query($conn, $query);
                                          while ($row = mysqli_fetch_assoc($result)) {
                                             $idrawmate = $row['idrawmate'];
                                             $nmrawmate = $row['nmrawmate'];
                                             echo '<option value="' . $idrawmate . '">' . $nmrawmate . '</option>';
                                          }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-1">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="weight[]" placeholder="Qty" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-2">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="price[]" placeholder="Price" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'price[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-2">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="amount[]" placeholder="amount" class="form-control text-right" readonly>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-3">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" placeholder="note" name="notes[]" class="form-control">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                       <!-- Total dan Button -->
                        <div class="row align-items-center">
                           <!-- Add Item Button -->
                           <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
                              <button type="button" class="btn btn-link text-success" onclick="addItem()">
                                 <i class="fas fa-plus-circle"></i> Add Item
                              </button>
                           </div>

                           <!-- Total Qty -->
                           <div class="col-6 col-md-2 mb-3 mb-md-0">
                              <div class="d-md-none text-muted mb-1 text-center">Total Qty</div>
                              <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly placeholder="Total Qty">
                           </div>

                           <!-- Spacer (for alignment in large screens) -->
                           <div class="col-12 col-md-2 d-none d-md-block"></div>

                           <!-- Total Amount -->
                           <div class="col-6 col-md-2 mb-3 mb-md-0">
                              <div class="d-md-none text-muted mb-1 text-center">Total Amount</div>
                              <input type="text" name="xamount" id="xamount" class="form-control text-right" readonly placeholder="Total Amount">
                           </div>

                           <!-- Calculate Button -->
                           <div class="col-6 col-md">
                              <button type="button" class="btn btn-warning btn-block" onclick="calculateTotals()">
                                 Calculate
                              </button>
                           </div>

                           <!-- Submit Button -->
                           <div class="col-6 col-md">
                              <button type="submit" class="btn btn-primary btn-block" name="submit" 
                                 onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')" disabled id="submit-btn">
                                 Submit
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <!-- /.card -->
         </div>
      </div>
   </section>
</div>
<script src="../dist/js/movefocus.js"></script>
<script src="../dist/js/calculatepo.js"></script>
<script>
   const termsDropdown = document.getElementById('termsDropdown');
   const customTermInput = document.getElementById('customTermInput');

   termsDropdown.addEventListener('change', function() {
      if (termsDropdown.value === 'custom') {
         customTermInput.style.display = 'block';
      } else {
         customTermInput.style.display = 'none';
      }
   });

   function addItem() {
      var itemsContainer = document.getElementById('items-container');

      // Baris item baru
      var newItemRow = document.createElement('div');
      newItemRow.className = 'item-row';

      // Konten baris item baru
      newItemRow.innerHTML = `
      <div class="row">
                              <div class="col-12 col-md-3">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <select class="form-control" name="idrawmate[]" required>
                                          <option value="">--Product--</option>
                                          <?php
                                          $query = "SELECT * FROM rawmate ORDER BY nmrawmate ASC";
                                          $result = mysqli_query($conn, $query);
                                          while ($row = mysqli_fetch_assoc($result)) {
                                             $idrawmate = $row['idrawmate'];
                                             $nmrawmate = $row['nmrawmate'];
                                             echo '<option value="' . $idrawmate . '">' . $nmrawmate . '</option>';
                                          }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-1">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="weight[]" placeholder="Qty" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-2">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="price[]" placeholder="Price" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'price[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-2">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" name="amount[]" placeholder="amount" class="form-control text-right" readonly>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-6 col-md-3">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" placeholder="note" name="notes[]" class="form-control">
                                    </div>
                                 </div>
                              </div>
                              <div class="col">
            <button type="button" class="btn btn-link text-danger btn-remove-item" onclick="removeItem(this)">
               <i class="fas fa-minus-circle"></i>
            </button>
         </div>
                           </div>
`;
      // Tambahkan baris item baru ke dalam container
      itemsContainer.appendChild(newItemRow);
   }

   function removeItem(button) {
      var itemRow = button.closest('.item-row');

      // Hapus baris item
      itemRow.remove();
   }

   // Mengubah judul halaman web
   document.title = "New Request";
</script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>