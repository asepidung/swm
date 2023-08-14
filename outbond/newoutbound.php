<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "nooutbound.php";
?>
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col mt-3">
          <form method="POST" action="inputoutbound.php">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-2">
                    <div class="form-group">
                      <label for="nooutbound">Serial Number <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="text" class="form-control" value="<?= $nooutbound ?>" name="nooutbound" id="nooutbound" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="idbarang">Jenis Proses <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <select class="form-control" name="proses" id="proses" required>
                          <option value="">--Pilih Proses--</option>
                          <option value="Proses Repack">Proses Repack</option>
                          <option value="Mutasi Keluar Dari G. Jonggol">Mutasi Keluar Dari G Jonggol</option>
                          <option value="Mutasi Keluar Dari G. Perum">Mutasi Keluar Dari G Perum</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="deliverydate">Tanggal Outbound <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="date" class="form-control" name="tgloutbound" id="tgloutbound" required autofocus>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="note">Catatan </label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="note">
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
                    <div class="col-1">
                      <div class="form-group">
                        <label for="idgrade">Code</label>
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
                        <label for="idbarang">Product</label>
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
                        <label for="box">Box</label>
                        <div class="input-group">
                          <input type="text" name="box[]" class="form-control text-center" required onkeydown="moveFocusToNextInput(event, this, 'box[]')">
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
                    <div class="col-3">
                      <div class="form-group">
                        <label for="notes">Notes</label>
                        <div class="input-group">
                          <input type="text" name="notes[]" class="form-control">
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
                  <div class="col-4"></div>
                  <div class="col-1">
                    <input type="text" name="xbox" id="xbox" class="form-control text-center" readonly>
                  </div>
                  <div class="col-2">
                    <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly>
                  </div>
                  <div class="col-1">
                    <button type="button" class="btn bg-gradient-warning" onclick="calculateTotals()">Calculate</button>
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
      </div>
    </div>
  </section>
</div>
<script src="../dist/js/movefocus.js"></script>
<script src="../dist/js/calculateTotals.js"></script>
<script>

</script>

<script>
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
</script>
<?php
// require "../footnotes.php";
include "../footer.php";
?>