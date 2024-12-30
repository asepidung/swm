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
               <form method="POST" action="prosespoproduct.php">
                  <input type="hidden" value="<?= $kodeauto ?>" name="nopoproduct" id="nopoproduct">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="tglpoproduct">PO Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <?php
                                    // Set the default value of packdate to today's date
                                    $defaultPackdate = date('Y-m-d'); // Set the format according to your needs
                                    ?>
                                    <input type="date" class="form-control" name="tglpoproduct" id="tglpoproduct" required value="<?= $defaultPackdate ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="deliveryat">Delivery Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="deliveryat" id="deliveryat" required autofocus>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="idsupplier">Supplier</label>
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
                                       <a href="../supplier/newsupplier.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="terms">Terms</label>
                                 <div class="input-group">
                                    <select id="termsDropdown" class="form-control" name="terms">
                                       <option value="select">Pilih Terms</option>
                                       <option value="custom">Custom</option>
                                       <option value="COD">C.O.D</option>
                                       <option value="CBD">C.B.D</option>
                                    </select>
                                    <input type="number" id="customTermInput" name="custom_terms" class="form-control" placeholder="Jumlah Hari" style="display: none;">
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
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div id="items-container">
                           <!-- Baris item pertama -->
                           <div class="row mb-n2">
                              <div class="col-3">
                                 <div class="form-group">
                                    <label for="idbarang">Product</label>
                                    <div class="input-group">
                                       <select class="form-control" name="idbarang[]" required>
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
                              </div>
                              <div class="col-1">
                                 <div class="form-group">
                                    <label for="weight">Qty</label>
                                    <div class="input-group">
                                       <input type="text" name="weight[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="weight">Price</label>
                                    <div class="input-group">
                                       <input type="text" name="price[]" placeholder="Tanpa Titik/Koma" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'price[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="weight">Amount</label>
                                    <div class="input-group">
                                       <input type="text" name="amount[]" class="form-control text-right" readonly onkeydown="moveFocusToNextInput(event, this, 'amount[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-3">
                                 <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <div class="input-group">
                                       <input type="text" name="notes[]" class="form-control" onkeydown="moveFocusToNextInput(event, this, 'notes[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col"></div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-1">
                              <button type="button" class="btn btn-link text-success" onclick="addItem()"><i class="fas fa-plus-circle"></i></button>
                           </div>
                           <div class="col-2"></div>
                           <div class="col-1">
                              <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly>
                           </div>
                           <div class="col-2"></div>
                           <div class="col-2">
                              <input type="text" name="xamount" id="xamount" class="form-control text-right" readonly>
                           </div>
                           <div class="col-1">
                              <button type="button" class="btn bg-gradient-warning" onclick="calculateTotals()" id="calculate-btn">Calculate</button>
                           </div>
                           <div class="col ml-1">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')" disabled id="submit-btn">Submit</button>
                           </div>
                           <div class="col-1"></div>
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
      <div class="row mb-n2">
         <div class="col-3">
            <div class="form-group">
               <div class="input-group">
                  <select class="form-control" name="idbarang[]" required>
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
         </div>
         <div class="col-1">
            <div class="form-group">
               <div class="input-group">
                  <input type="text" name="weight[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')">
               </div>
            </div>
         </div>
         <div class="col-2">
            <div class="form-group">
               <div class="input-group">
                  <input type="text" name="price[]" placeholder="Tanpa Titik/Koma" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'price[]')">
               </div>
            </div>
         </div>
         <div class="col-2">
            <div class="form-group">
               <div class="input-group">
                  <input type="text" name="amount[]" class="form-control text-right" readonly onkeydown="moveFocusToNextInput(event, this, 'amount[]')">
               </div>
            </div>
         </div>
         <div class="col-3">
            <div class="form-group">
               <div class="input-group">
                  <input type="text" name="notes[]" class="form-control" onkeydown="moveFocusToNextInput(event, this, 'notes[]')">
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
   document.title = "PO PRODUCT";
</script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>