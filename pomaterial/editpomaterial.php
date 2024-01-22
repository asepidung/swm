<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi kode lebih lanjut jika belum login
}

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Periksa apakah ada parameter ID yang diberikan
if (isset($_GET['idpomaterial'])) {
   $idpomaterial = $_GET['idpomaterial'];

   // Query untuk mengambil data pomaterial berdasarkan ID
   $query = "SELECT * FROM pomaterial WHERE idpomaterial = $idpomaterial";
   $result = mysqli_query($conn, $query);

   // Periksa apakah data pomaterial ditemukan
   if ($result && mysqli_num_rows($result) > 0) {
      $pomaterial_data = mysqli_fetch_assoc($result);
      $nopomaterial = $pomaterial_data['nopomaterial'];
      $tglpomaterial = $pomaterial_data['tglpomaterial'];
   } else {
      // Redirect jika data tidak ditemukan
      echo "Data Tidak Ditemukan";
      exit();
   }
} else {
   // Redirect jika ID tidak ada
   echo "ID Tidak Ditemukan";
   exit();
}
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="updatepomaterial.php">
                  <input type="hidden" name="idpomaterial" value="<?php echo $idpomaterial; ?>">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="tglpomaterial">PO Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="tglpomaterial" id="tglpomaterial" required value="<?= $tglpomaterial ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="deliveryat">Delivery Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="deliveryat" id="deliveryat" required value="<?= $pomaterial_data['deliveryat']; ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="idsupplier">Supplier</label>
                                 <div class="input-group">
                                    <select class="form-control" name="idsupplier" id="idsupplier">
                                       <option value="">Pilih supplier</option>
                                       <?php
                                       $query = "SELECT * FROM supplier ORDER BY nmsupplier ASC";
                                       $result = mysqli_query($conn, $query);
                                       while ($supplierRow = mysqli_fetch_assoc($result)) {
                                          $idsupplier = $supplierRow['idsupplier'];
                                          $nmsupplier = $supplierRow['nmsupplier'];
                                          $selected = ($idsupplier == $pomaterial_data['idsupplier']) ? "selected" : "";
                                          echo "<option value=\"$idsupplier\" $selected>$nmsupplier</option>";
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
                                    <input type="text" class="form-control" value="<?= $pomaterial_data['Terms'] ?>" name="terms">
                                    <input type="number" id="customTermInput" name="custom_terms" class="form-control" placeholder="Jumlah Hari" style="display: none;">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="keterangan" value="<?= $pomaterial_data['note'] ?>">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div id="items-container">
                           <div class="row mb-n2">
                              <div class="col-3">
                                 <div class="form-group">
                                    <label for="idrawmate">Product</label>
                                 </div>
                              </div>
                              <div class="col-1">
                                 <div class="form-group">
                                    <label for="weight">Qty</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="proce">Price</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="amount">Amount</label>
                                 </div>
                              </div>
                              <div class="col">
                                 <div class="form-group">
                                    <label for="notes">Notes</label>
                                 </div>
                              </div>
                              <div class="col-1"></div>
                           </div>
                           <?php
                           // Query untuk mengambil item penyesuaian dari tabel pomaterialdetail
                           $item_query = "SELECT * FROM pomaterialdetail WHERE idpomaterial = $idpomaterial";
                           $item_result = mysqli_query($conn, $item_query);
                           if ($item_result && mysqli_num_rows($item_result) > 0) {
                              while ($item_row = mysqli_fetch_assoc($item_result)) {
                                 $idrawmate = $item_row['idrawmate'];
                                 $weight = $item_row['qty'];
                                 $price = $item_row['price'];
                                 $amount = $item_row['amount'];
                                 $notes = $item_row['notes'];
                           ?>
                                 <div class="row mb-n2">
                                    <div class="col-3">
                                       <div class="form-group">
                                          <div class="input-group">
                                             <select class="form-control" name="idrawmate[]" required>
                                                <?php
                                                // Query untuk mengambil data dari tabel rawmate
                                                $rawmate_query = "SELECT * FROM rawmate ORDER BY nmrawmate ASC";
                                                $rawmate_result = mysqli_query($conn, $rawmate_query);
                                                while ($rawmate_row = mysqli_fetch_assoc($rawmate_result)) {
                                                   $selected = ($idrawmate == $rawmate_row['idrawmate']) ? "selected" : "";
                                                   echo '<option value="' . $rawmate_row['idrawmate'] . '" ' . $selected . '>' . $rawmate_row['nmrawmate'] . '</option>';
                                                }
                                                ?>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-1">
                                       <div class="form-group">
                                          <div class="input-group">
                                             <input type="text" name="weight[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')" value="<?= $weight; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-2">
                                       <div class="form-group">
                                          <div class="input-group">
                                             <input type="text" name="price[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'price[]')" value="<?= $price; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-2">
                                       <div class="form-group">
                                          <div class="input-group">
                                             <input type="text" name="amount[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'amount[]')">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col">
                                       <div class="form-group">
                                          <div class="input-group">
                                             <input type="text" name="notes[]" class="form-control" value="<?php echo $notes; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-1"></div>
                                 </div>
                           <?php
                              }
                           }
                           ?>
                        </div>
                        <div class="row">
                           <div class="col-3">
                              <button type="button" class="btn btn-link text-success" onclick="addItem()"><i class="fas fa-plus-circle"></i></button>
                           </div>
                           <div class="col-1">
                              <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly>
                           </div>
                           <div class="col-2"></div>
                           <div class="col-2">
                              <input type="text" name="xamount" id="xamount" class="form-control text-right" readonly>
                           </div>
                           <div class="col-1">
                              <button type="button" class="btn bg-gradient-warning" onclick="calculateTotals()">Calculate</button>
                           </div>
                           <div class="col ml-1">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')" disabled id="submit-btn">Update</button>
                           </div>
                           <div class="col-1"></div>
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
                  <select class="form-control" name="idrawmate[]" required>
                     <option value="">--Pilih--</option>
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