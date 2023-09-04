<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Periksa apakah ada parameter ID yang diberikan
if (isset($_GET['idadjustment'])) {
   $idadjustment = $_GET['idadjustment'];

   // Query untuk mengambil data penyesuaian berdasarkan ID
   $query = "SELECT * FROM adjustment WHERE idadjustment = $idadjustment";
   $result = mysqli_query($conn, $query);

   // Periksa apakah data penyesuaian ditemukan
   if ($result && mysqli_num_rows($result) > 0) {
      $adjustment_data = mysqli_fetch_assoc($result);
      $noadjustment = $adjustment_data['noadjustment'];
      $tgladjustment = $adjustment_data['tgladjustment'];
      $eventadjustment = $adjustment_data['eventadjustment'];
   } else {
      // Redirect jika data tidak ditemukan
      header("location: daftaradjustment.php");
      exit();
   }
} else {
   // Redirect jika ID tidak ada
   header("location: daftaradjustment.php");
   exit();
}
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="updateadjustment.php">
                  <input type="hidden" name="idadjustment" value="<?php echo $idadjustment; ?>">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-3">
                              <div class="form-group">
                                 <label for="noadjustment">Serial Number <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $noadjustment ?>" name="noadjustment" id="noadjustment" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col-3">
                              <div class="form-group">
                                 <label for="tgladjustment">Tanggal Adjust <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="tgladjustment" id="tgladjustment" value="<?= $tgladjustment ?>" required autofocus>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="eventadjustment">Penjelasan <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="eventadjustment" value="<?= $eventadjustment ?>" required>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div id="items-container">
                           <?php
                           // Query untuk mengambil item penyesuaian dari tabel adjustmentdetail
                           $item_query = "SELECT * FROM adjustmentdetail WHERE idadjustment = $idadjustment";
                           $item_result = mysqli_query($conn, $item_query);
                           if ($item_result && mysqli_num_rows($item_result) > 0) {
                              while ($item_row = mysqli_fetch_assoc($item_result)) {
                                 $idgrade = $item_row['idgrade'];
                                 $idbarang = $item_row['idbarang'];
                                 $weight = $item_row['weight'];
                                 $notes = $item_row['notes'];
                           ?>
                                 <div class="row mb-n2">
                                    <div class="col-1">
                                       <div class="form-group">
                                          <div class="input-group">
                                             <select class="form-control" name="idgrade[]">
                                                <?php
                                                // Query untuk mengambil data dari tabel grade
                                                $grade_query = "SELECT * FROM grade";
                                                $grade_result = $conn->query($grade_query);

                                                if ($grade_result->num_rows > 0) {
                                                   while ($grade_row = $grade_result->fetch_assoc()) {
                                                      $selected = ($idgrade == $grade_row["idgrade"]) ? "selected" : "";
                                                      echo "<option value=\"" . $grade_row["idgrade"] . "\" $selected>" . $grade_row["nmgrade"] . "</option>";
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
                                             <select class="form-control" name="idbarang[]" required>
                                                <?php
                                                // Query untuk mengambil data dari tabel barang
                                                $barang_query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                                                $barang_result = mysqli_query($conn, $barang_query);
                                                while ($barang_row = mysqli_fetch_assoc($barang_result)) {
                                                   $selected = ($idbarang == $barang_row['idbarang']) ? "selected" : "";
                                                   echo '<option value="' . $barang_row['idbarang'] . '" ' . $selected . '>' . $barang_row['nmbarang'] . '</option>';
                                                }
                                                ?>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-2">
                                       <div class="form-group">
                                          <div class="input-group">
                                             <input type="text" name="weight[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')" value="<?php echo $weight; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <div class="input-group">
                                             <input type="text" name="notes[]" class="form-control" value="<?php echo $notes; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col"></div>
                                 </div>
                           <?php
                              }
                           }
                           ?>
                        </div>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-1">
                        <button type="button" class="btn btn-link text-success" onclick="addItem()"><i class="fas fa-plus-circle"></i></button>
                     </div>
                     <div class="col-4"></div>
                     <div class="col-2">
                        <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly>
                     </div>
                     <div class="col-1">
                        <button type="button" class="btn bg-gradient-warning" onclick="calculateTotals()">Calculate</button>
                     </div>
                     <div class="col ml-1">
                        <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')" disabled id="submit-btn">Update</button>
                     </div>
                     <div class="col-1"></div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
</div>
<script src="../dist/js/movefocus.js"></script>
<script>
   function calculateTotals() {
      var weightInputs = document.getElementsByName('weight[]');
      var totalWeight = 0;

      for (var i = 0; i < weightInputs.length; i++) {
         var weightValue = parseFloat(weightInputs[i].value);
         if (!isNaN(weightValue)) {
            totalWeight += weightValue;
         }
      }

      document.getElementById('xweight').value = totalWeight.toFixed(2);
      document.getElementById("submit-btn").disabled = false;
   }
</script>

<script>
   function addItem() {
      var itemsContainer = document.getElementById('items-container');

      // Baris item baru
      var newItemRow = document.createElement('div');
      newItemRow.className = 'item-row';

      // Konten baris item baru
      newItemRow.innerHTML = `
<div class="row">
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

   document.title = "Edit Adjustment";
</script>
<?php
// require "../footnotes.php";
include "../footer.php";
?>