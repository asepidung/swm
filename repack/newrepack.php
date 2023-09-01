<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "norepack.php";
?>
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col mt-3">
          <form method="POST" action="inputrepack.php">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-2">
                    <div class="form-group">
                      <label for="norepack">Serial Number <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="text" class="form-control" value="<?= $norepack ?>" name="norepack" id="norepack" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label for="deliverydate">Tanggal Repack <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="date" class="form-control" name="tglrepack" id="tglrepack" required autofocus>
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
                  <div class="row mb-n2">
                    <div class="col-1">
                      <div class="form-group">
                        <label for="idgrade">Code</label>
                        <div class="input-group">
                          <select class="form-control" name="idgradebahan[]" id="idgradebahan">
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
                    <div class="col-2">
                      <div class="form-group">
                        <label for="idbarang">Product</label>
                        <div class="input-group">
                          <select class="form-control" name="idbarangbahan[]" id="idbarangbahan" required>
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
                        <label for="weight">Weight</label>
                        <div class="input-group">
                          <input type="text" name="weightbahan[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weightbahan[]')">
                        </div>
                      </div>
                    </div>
                    <div class="col-1">
                      <div class="form-group">
                        <label for="idgrade">Code</label>
                        <div class="input-group">
                          <select class="form-control" name="idgradehasil[]" id="idgradehasil">
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
                    <div class="col-2">
                      <div class="form-group">
                        <label for="idbarang">Product</label>
                        <div class="input-group">
                          <select class="form-control" name="idbaranghasil[]" id="idbaranghasil" required>
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
                        <label for="weight">Weight</label>
                        <div class="input-group">
                          <input type="text" name="weighthasil[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weighthasil[]')">
                        </div>
                      </div>
                    </div>
                    <div class="col-1">
                      <div class="form-group">
                        <label for="weight">Susut</label>
                        <div class="input-group">
                          <input type="text" name="susut[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'susut[]')">
                        </div>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="weight">Notes</label>
                        <div class="input-group">
                          <input type="text" name="weight[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-3">
                    <button type="button" class="btn btn-link text-success" onclick="addItem()"><i class="fas fa-plus-circle"></i></button>
                  </div>
                  <div class="col-1">
                    <input type="text" class="form-control" name="xweightbahan" readonly>
                  </div>
                  <div class="col-3"></div>
                  <div class="col-1">
                    <input type="text" class="form-control" name="xweighthasil" readonly>
                  </div>
                  <div class="col-1">
                    <input type="text" class="form-control" name="susut" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div id="items-container">
                  <div class="row">
                    <div class="col">
                      <button type="button" class="btn btn-block bg-gradient-warning" onclick="calculateTotals()">Calculate</button>
                    </div>
                    <div class="col">
                      <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')" disabled id="submit-btn">Submit</button>
                    </div>
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
<!-- <script src="../dist/js/movefocus.js"></script> -->
<!-- <script src="../dist/js/calculateTotals.js"></script> -->
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
                          <select class="form-control" name="idgradebahan[]" id="idgradebahan">
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
                    <div class="col-2">
                      <div class="form-group">
                        <div class="input-group">
                          <select class="form-control" name="idbarangbahan[]" id="idbarangbahan" required>
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
                          <input type="text" name="weightbahan[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weight[]')">
                        </div>
                      </div>
                    </div>
                    <div class="col-1">
                      <div class="form-group">
                        <div class="input-group">
                          <select class="form-control" name="idgradehasil[]" id="idgradehasil">
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
                    <div class="col-2">
                      <div class="form-group">
                        <div class="input-group">
                          <select class="form-control" name="idbaranghasil[]" id="idbaranghasil" required>
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
                          <input type="text" name="weighthasil[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'weighthasil[]')">
                        </div>
                      </div>
                    </div>
                    <div class="col-1">
                      <div class="form-group">
                        <div class="input-group">
                          <input type="text" name="susut[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'susut[]')">
                        </div>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <div class="input-group">
                          <input type="text" name="notes[]" class="form-control text-right" required onkeydown="moveFocusToNextInput(event, this, 'notes[]')">
                        </div>
                      </div>
                    </div>                    <div class="col-1">
<button type="button" class="btn btn-link text-danger btn-right btn-remove-item" onclick="removeItem(this)">
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