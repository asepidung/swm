<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$iddo = $_GET['iddo'];

$query = "SELECT * FROM do WHERE iddo = '$iddo'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

?>
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col mt-3">
          <form method="POST" action="prosesupdatedo.php">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <input type="hidden" name="iddo" value="<?= $iddo ?>">
                  <div class="col-2">
                    <div class="form-group">
                      <label for="deliverydate">Tgl Kirim <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="date" class="form-control" name="deliverydate" id="deliverydate" value="<?= $row['deliverydate']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="idcustomer">Customer <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <select class="form-control" name="idcustomer" id="idcustomer">
                          <option value="">Pilih Customer</option>
                          <?php
                          $query = "SELECT * FROM customers ORDER BY nama_customer ASC";
                          $result = mysqli_query($conn, $query);
                          while ($customerRow = mysqli_fetch_assoc($result)) {
                            $idcustomer = $customerRow['idcustomer'];
                            $nama_customer = $customerRow['nama_customer'];
                            $selected = ($idcustomer == $row['idcustomer']) ? "selected" : "";
                            echo "<option value=\"$idcustomer\" $selected>$nama_customer</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="po">Cust PO</label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="po" id="po" value="<?= $row['po']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="driver">Driver</label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="driver" id="driver" value="<?= $row['driver']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="plat">Plat Number</label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="plat" id="plat" value="<?= $row['plat']; ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <div class="input-group">
                        <input type="text" class="form-control" name="note" id="note" placeholder="keterangan" value="<?= $row['note']; ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div id="items-container">
                  <!-- bagian dodetail -->
                  <div class="row">
                    <div class="col-1">
                      <div class="form-group">
                        <label>Code</label>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Product</label>
                      </div>
                    </div>
                    <div class="col-1">
                      <div class="form-group">
                        <label>Box</label>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label>Weight</label>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label>Notes</label>
                      </div>
                    </div>
                  </div>
                  <?php
                  $query_dodetail = "SELECT * FROM dodetail WHERE iddo = '$iddo'";
                  $result_dodetail = mysqli_query($conn, $query_dodetail);
                  while ($row_dodetail = mysqli_fetch_assoc($result_dodetail)) {
                  ?>
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
                                  $selected = ($row['idgrade'] == $row_dodetail['idgrade']) ? "selected" : "";
                                  echo "<option value=\"" . $row["idgrade"] . "\" $selected>" . $row["nmgrade"] . "</option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <!-- <label for="idbarang">Product</label> -->
                          <div class="input-group">
                            <select class="form-control" name="idbarang[]" id="idbarang">
                              <option value="">--Pilih--</option>
                              <?php
                              $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                              $result = mysqli_query($conn, $query);
                              while ($row = mysqli_fetch_assoc($result)) {
                                $idbarang = $row['idbarang'];
                                $nmbarang = $row['nmbarang'];
                                $selected = ($idbarang == $row_dodetail['idbarang']) ? "selected" : "";
                                echo '<option value="' . $idbarang . '" ' . $selected . '>' . $nmbarang . '</option>';
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="box[]" class="form-control text-center box" value="<?= $row_dodetail['box']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="weight[]" class="form-control text-right weight" value="<?= $row_dodetail['weight']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="notes[]" class="form-control" value="<?= $row_dodetail['notes']; ?>">
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
                    <div class="col">
                      <button type="submit" name="approve" class="btn btn-block btn-outline-success" onclick="return confirm('Setelah di Approve anda tidak bisa lagi mengubah atau menghapus surat jalan terkait, tetapi anda masih bisa mencetak ulang')">Approve</button>
                    </div>
                  </div>
                </div>
              </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Kode JavaScript -->
<script>
  function calculateTotals() {
    var boxes = document.getElementsByClassName("box");
    var weights = document.getElementsByClassName("weight");
    var xbox = 0;
    var xweight = 0;

    for (var i = 0; i < boxes.length; i++) {
      xbox += parseInt(boxes[i].value) || 0;
      xweight += parseFloat(weights[i].value) || 0;
    }

    document.getElementById("xbox").value = xbox;
    document.getElementById("xweight").value = xweight.toFixed(2);

    document.getElementById("submit-btn").disabled = false;
  }
</script>

<?php
include "../footer.php";
?>