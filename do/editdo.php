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
                  <div class="row mb-2">
                    <div class="col-1">
                      Code
                    </div>
                    <div class="col-4">
                      Product Desc
                    </div>
                    <div class="col-1">
                      Box
                    </div>
                    <div class="col-2">
                      Weight(Kg)
                    </div>
                    <div class="col">
                      Code
                    </div>
                  </div>
                  <?php
                  $query_dodetail = "SELECT dodetail.*, grade.nmgrade, barang.nmbarang
                                    FROM dodetail
                                    INNER JOIN grade ON dodetail.idgrade = grade.idgrade
                                    INNER JOIN barang ON dodetail.idbarang = barang.idbarang
                                    WHERE iddo = '$iddo'";
                  $result_dodetail = mysqli_query($conn, $query_dodetail);
                  while ($row_dodetail = mysqli_fetch_assoc($result_dodetail)) { ?>
                    <div class="row mb-n2">
                      <div class="col-1">
                        <div class="form-group">
                          <div class="input-group">
                            <select class="form-control" name="idgrade[]" id="idgrade">
                              <option value="">Pilih Grade</option>
                              <?php
                              $querygrade = "SELECT * FROM grade ORDER BY nmgrade ASC";
                              $resultgrade = mysqli_query($conn, $querygrade);
                              while ($gradeRow = mysqli_fetch_assoc($resultgrade)) {
                                $idgrade = $gradeRow['idgrade'];
                                $nmgrade = $gradeRow['nmgrade'];
                                $selectedgrade = ($idgrade == $row_dodetail['idgrade']) ? "selected" : "";
                                echo "<option value=\"$idgrade\" $selectedgrade>$nmgrade</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <div class="input-group">
                            <select class="form-control" name="idbarang[]" id="idbarang">
                              <option value="">Pilih Barang</option>
                              <?php
                              $querybarang = "SELECT * FROM barang ORDER BY nmbarang ASC";
                              $resultbarang = mysqli_query($conn, $querybarang);
                              while ($barangRow = mysqli_fetch_assoc($resultbarang)) {
                                $idbarang = $barangRow['idbarang'];
                                $nmbarang = $barangRow['nmbarang'];
                                $selectedbarang = ($idbarang == $row_dodetail['idbarang']) ? "selected" : "";
                                echo "<option value=\"$idbarang\" $selectedbarang>$nmbarang</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="box[]" class="form-control text-center" value="<?= $row_dodetail['box']; ?>" required onkeydown="moveFocusToNextInput(event, this, 'box[]')">
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="weight[]" class="form-control text-right" value="<?= $row_dodetail['weight']; ?>" required onkeydown=" moveFocusToNextInput(event, this, 'weight[]' )">
                          </div>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="notes[]" class="form-control" value="<?= $row_dodetail['notes']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col">
                      </div>
                    </div>
                  <?php } ?>
                </div>
                <div class="row">
                  <div class="col-1">
                    <button type="button" class="btn btn-link text-success" onclick="addItem()"><i class="fas fa-plus-circle"></i></button>
                  </div>
                  <div class="col-4"></div>
                  <div class="col-1">
                    <input type="text" name="xbox" id="xbox" class="form-control" readonly>
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
<script src="../dist/js/calculateTotals.js"></script>
<script src="../dist/js/movefocus.js"></script>
<script>
  document.title = "Edit Do";
</script>

<?php
include "../footer.php";
?>