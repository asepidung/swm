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
               <form method="POST" action="inputso.php">
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
                                       // Generate options based on the retrieved data
                                       while ($row = mysqli_fetch_assoc($result)) {
                                          $idcustomer = $row['idcustomer'];
                                          $idgroup = $row['idgroup'];
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
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="deliverydate">Tgl Kirim <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="deliverydate" id="deliverydate" required autofocus>
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="po">Cust PO</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="po" id="po">
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <select class="form-control" name="alamat" id="alamat" required>
                                       <option value="">Pilih Alamat</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="Catatan Untuk Penyiapan">
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
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="weight">Weight</label>
                                    <div class="input-group">
                                       <input type="text" name="weight[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="price">Price</label>
                                    <div class="input-group">
                                       <input type="text" name="price[]" class="form-control text-right price-input">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-4">
                                 <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <div class="input-group">
                                       <input type="text" name="notes[]" class="form-control">
                                    </div>
                                 </div>
                              </div>
                              <div class="col">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-1">
                              <button type="button" class="btn btn-link text-success" onclick="addItem()"><i class="fas fa-plus-circle"></i></button>
                           </div>
                           <div class="col-6"></div>
                           <div class="col-2">
                              <button type="button" class="btn btn-block bg-gradient-warning"><i class="fas fa-tags"></i></i> Add Price</button>
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
<script src="../dist/js/movefocus.js"></script>
<script src="../dist/js/fill_alamat_note.js"></script>
<script>
   // Function to add digit grouping (thousands separator) to a number
   function addDigitGrouping(number) {
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
   }

   // Function to format the Price input
   function formatPriceInput() {
      const priceInputs = document.querySelectorAll('input[name="price[]"]');

      priceInputs.forEach(function(input) {
         input.addEventListener('input', function() {
            // Remove any existing commas
            let value = this.value.replace(/,/g, '');

            // Convert the value to a number
            let number = parseFloat(value);

            // Check if it's a valid number
            if (!isNaN(number)) {
               // Add digit grouping to the number
               this.value = addDigitGrouping(number);
            }
         });
      });
   }

   // Call the formatPriceInput function when the page loads
   document.addEventListener('DOMContentLoaded', function() {
      formatPriceInput();

      // Add event listener for adding item
      document.querySelector('.btn-add-item').addEventListener('click', addItem);
   });

   function addItem() {
      var itemsContainer = document.getElementById('items-container');
      var newItemRow = document.createElement('div');
      newItemRow.className = 'item-row';

      newItemRow.innerHTML = `
         <div class="row mt-n2">
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
                     <input type="text" name="price[]" class="form-control text-right price-input">
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
            <div class="col">
               <button type="button" class="btn btn-link text-danger btn-remove-item" onclick="removeItem(this)">
                  <i class="fas fa-minus-circle"></i>
               </button>
            </div>
         </div>
      `;

      itemsContainer.appendChild(newItemRow);

      // Call the formatPriceInput function for the new Price input
      formatPriceInput();
   }

   function removeItem(button) {
      var itemRow = button.closest('.item-row');
      itemRow.remove();
   }

   // Function to add digit grouping (thousands separator) to a number
   function addDigitGrouping(number) {
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
   }

   // Function to format the Price input
   function formatPriceInput() {
      const priceInputs = document.querySelectorAll('input[name="price[]"]');

      priceInputs.forEach(function(input) {
         input.addEventListener('input', function() {
            // Remove any existing commas
            let value = this.value.replace(/,/g, '');

            // Convert the value to a number
            let number = parseFloat(value);

            // Check if it's a valid number
            if (!isNaN(number)) {
               // Add digit grouping to the number
               this.value = addDigitGrouping(number);
            }
         });
      });
   }

   // Call the formatPriceInput function when the page loads
   document.addEventListener('DOMContentLoaded', formatPriceInput);
   document.title = "Sales Order";
</script>
<?php
include "../footer.php";
?>