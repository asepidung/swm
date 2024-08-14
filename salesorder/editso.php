<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Periksa apakah ID salesorder yang akan diedit telah diberikan
if (isset($_GET['idso'])) {
   $idso = $_GET['idso'];
   $query = "SELECT * FROM salesorder WHERE idso = $idso";
   $result = mysqli_query($conn, $query);
   $data = mysqli_fetch_assoc($result);

   // Periksa apakah data ditemukan
   if (!$data) {
      echo "Data tidak ditemukan.";
      exit;
   }
} else {
   echo "ID salesorder tidak diberikan.";
   exit;
}
?>
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="updateso.php">
                  <input type="hidden" name="idso" value="<?php echo $data['idso']; ?>">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-4">
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
                                          $selected = ($idcustomer == $data['idcustomer']) ? "selected" : "";
                                          echo "<option value=\"$idcustomer\" $selected>$nama_customer</option>";
                                       }
                                       ?>
                                    </select>
                                    <div class="input-group-append">
                                       <a href="../customer/newcustomer.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="deliverydate">Tgl Kirim <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="deliverydate" id="deliverydate" required autofocus value="<?php echo $data['deliverydate']; ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="po">Cust PO</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="po" id="po" value="<?php echo $data['po']; ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <select class="form-control" name="alamat" id="alamat" required>
                                       <option value="">Pilih Alamat</option>
                                       <?php
                                       // Retrieve and display addresses as needed
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="Catatan Untuk Penyiapan Pengiriman" value="<?php echo $data['note']; ?>">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div id="items-container">
                           <div class="row mb-2">
                              <div class="col-3">Product</div>
                              <div class="col-2">Weight</div>
                              <div class="col-2">Price</div>
                              <div class="col-4">Notes</div>
                              <div class="col-1"></div>
                           </div>
                           <!-- Baris item pertama -->
                           <?php
                           $query = "SELECT * FROM salesorderdetail WHERE idso = $idso";
                           $result = mysqli_query($conn, $query);
                           while ($row = mysqli_fetch_assoc($result)) {
                           ?>
                              <div class="row item-row mb-n2">
                                 <div class="col-3">
                                    <div class="form-group">
                                       <!-- <label for="idbarang">Product</label> -->
                                       <div class="input-group">
                                          <select class="form-control" name="idbarang[]" id="idbarang" required>
                                             <option value="">--Pilih--</option>
                                             <?php
                                             $barangQuery = "SELECT * FROM barang ORDER BY nmbarang ASC";
                                             $barangResult = mysqli_query($conn, $barangQuery);
                                             while ($barang = mysqli_fetch_assoc($barangResult)) {
                                                $idbarang = $barang['idbarang'];
                                                $nmbarang = $barang['nmbarang'];
                                                $selected = ($idbarang == $row['idbarang']) ? "selected" : "";
                                                echo '<option value="' . $idbarang . '" ' . $selected . '>' . $nmbarang . '</option>';
                                             }
                                             ?>
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <!-- <label for="weight">Weight</label> -->
                                       <div class="input-group">
                                          <input type="text" name="weight[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')" value="<?php echo $row['weight']; ?>">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <!-- <label for="price">Price</label> -->
                                       <div class="input-group">
                                          <input type="text" name="price[]" class="form-control text-right" onkeydown="moveFocusToNextInput(event, this, 'price[]')" value="<?php echo $row['price']; ?>">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-4">
                                    <div class="form-group">
                                       <!-- <label for="notes">Notes</label> -->
                                       <div class="input-group">
                                          <input type="text" name="notes[]" class="form-control" value="<?php echo $row['notes']; ?>">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <button type="button" class="btn btn-link text-danger btn-remove-item" onclick="removeItem(this)">
                                       <i class="fas fa-minus-circle"></i>
                                    </button>
                                 </div>
                              </div>
                           <?php
                           }
                           ?>
                        </div>
                        <div class="row">
                           <div class="col-1">
                              <button type="button" class="btn btn-link text-success" onclick="addItem()"><i class="fas fa-plus-circle"></i></button>
                           </div>
                           <div class="col-6"></div>
                           <div class="col-2">
                              <button type="button" class="btn btn-block bg-gradient-warning"><i class="fas fa-share-alt"></i> Submit & Share</button>
                           </div>
                           <div class="col-2">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')">Submit</button>
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
<script src="../dist/js/fill_alamat_note.js"></script>
<script src="../dist/js/movefocus.js"></script>
<script>
   function addItem() {
      var itemsContainer = document.getElementById('items-container');

      // Baris item baru
      var newItemRow = document.createElement('div');
      newItemRow.className = 'row item-row mt-n2';

      // Konten baris item baru
      newItemRow.innerHTML = `
         <div class="col-3">
            <div class="form-group">
               <div class="input-group">
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
         </div>
         <div class="col-2">
            <div class="form-group">
               <div class="input-group">
                  <input type="text" name="weight[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')">
               </div>
            </div>
         </div>
         <div class="col-2">
            <div class="form-group">
               <div class="input-group">
                  <input type="text" name="price[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'price[]')">
               </div>
            </div>
         </div>
         <div class="col-4">
            <div class="form-group">
               <div class="input-group">
                  <input type="text" name="notes[]" class="form-control">
               </div>
            </div>
         </div>
         <div class="col-1">
            <button type="button" class="btn btn-link text-danger btn-remove-item" onclick="removeItem(this)">
               <i class="fas fa-minus-circle"></i>
            </button>
         </div>
      `;
      // Tambahkan baris item baru ke dalam container
      itemsContainer.appendChild(newItemRow);
   }

   function removeItem(button) {
      var itemRow = button.closest('.item-row');

      // Hapus baris item
      if (itemRow) {
         itemRow.remove();
      }
   }

   // Mengubah judul halaman web
   document.title = "Edit Sales Order";
</script>
<?php
include "../footer.php";
?>