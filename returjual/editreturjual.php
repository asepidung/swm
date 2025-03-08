<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "returnnumber.php";
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="updatereturjual.php">
                  <!-- Note: Change the action attribute to the appropriate update script (e.g., updatereturjual.php) -->
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="returnnumber">Return Number <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $returnnumber ?>" name="returnnumber" id="returnnumber" readonly>
                                 </div>
                              </div>
                           </div>
                           <!-- Add fields for other form elements (returdate, idcustomer, donumber, note) here -->
                        </div>
                        <div class="row">
                           <!-- Add fields for other form elements (note) here -->
                        </div>
                     </div>
                  </div>

                  <div class="card">
                     <div class="card-body">
                        <div id="items-container">
                           <!-- Existing item rows will be populated here -->

                           <!-- Add PHP code to retrieve existing item data and populate the fields -->

                        </div>

                        <div class="row">
                           <!-- Add button to add new item row (if needed) -->

                           <div class="col-1">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')" id="submit-btn">Update</button>
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

<script src="../dist/js/get_dos.js"></script>
<script src="../dist/js/movefocus.js"></script>
<script src="../dist/js/calculateTotals.js"></script>
<script>
   document.getElementById("idcustomer").addEventListener("change", function() {
      var idcustomer = this.value;

      // Dapatkan elemen select box "Nomor DO"
      var donumberSelect = document.getElementById("donumber");
      donumberSelect.innerHTML = '<option value="">Pilih Nomor DO</option>';

      if (idcustomer) {
         // Menggunakan AJAX untuk mengambil data donumber berdasarkan idcustomer
         var xhr = new XMLHttpRequest();
         xhr.open("GET", "get_donumber.php?idcustomer=" + idcustomer, true);

         xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
               var data = JSON.parse(xhr.responseText);
               data.forEach(function(item) {
                  var option = document.createElement("option");
                  option.value = item.iddo;
                  option.text = item.donumber;
                  donumberSelect.appendChild(option);
               });
            }
         };

         xhr.send();
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
<div class="col-1">
<div class="form-group">
<div class="input-group">
<select class="form-control" name="idgrade[]" id="idgrade">
<?php
// Query untuk mengambil data dari tabel grade
$sql = "SELECT * FROM grade";
$result = $conn->query($sql);
// Membuat pilihan dalam select box berdasarkan data yang diambil
if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
      echo "<option value=\"" . $row["idgrade"] . "\">" . $row["nmgrade"] . "</option>";
   }
}
?>
</select>
</div>
</div>
</div>
<div class="col-4">
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
<div class="col-1">
<div class="form-group">
<div class="input-group">
<input type="text" name="box[]" class="form-control text-center" required onkeydown="moveFocusToNextInput(event, this, 'box[]')">
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
<div class="col">
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
   document.title = "Edit Retur Jual";
</script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>