<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
if (isset($_GET['idreturjual'])) {
  $idreturjual = $_GET['idreturjual'];
  $query_edit = "SELECT returjual.*, users.idusers, do.iddo FROM returjual
                  JOIN do    ON returjual.iddo = do.iddo
                  JOIN users ON returjual.idusers = users.idusers
                  WHERE idreturjual = ?";
  $stmt_edit = $conn->prepare($query_edit);
  $stmt_edit->bind_param("i", $idreturjual);
  $stmt_edit->execute();
  $result_edit = $stmt_edit->get_result();
  $data_edit = $result_edit->fetch_assoc();
  $stmt_edit->close();
}
?>
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col mt-3">
          <form method="POST" action="updatereturjual.php">
            <input type="hidden" name="idreturjual" value="<?= $data_edit['idreturjual'] ?>">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-2">
                    <div class="form-group">
                      <label for="returnnumber">Return Number <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="text" class="form-control" value="<?= $data_edit['returnnumber'] ?>" name="returnnumber" id="returnnumber" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="returdate">Tgl Retur <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="date" class="form-control" name="returdate" id="returdate" required value="<?= $data_edit['returdate'] ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-3">
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
                            $selected = ($idcustomer == $data_edit['idcustomer']) ? "selected" : "";
                            echo "<option value=\"$idcustomer\" $selected>$nama_customer</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="iddo">Nomor DO </label>
                      <div class="input-group">
                        <select name="iddo" id="iddo" class="form-control">
                          <?php
                          $query_do = "SELECT iddo, donumber FROM do ORDER BY donumber ASC";
                          $result_do = mysqli_query($conn, $query_do);
                          while ($doRow = mysqli_fetch_assoc($result_do)) {
                            $iddo = $doRow['iddo'];
                            $donumber = $doRow['donumber'];
                            $selected_do = ($iddo == $data_edit['iddo']) ? "selected" : "";
                            echo "<option value=\"$iddo\" $selected_do>$donumber</option>";
                          }
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
                        <input type="text" class="form-control" name="note" id="note" value="<?= $data_edit['note'] ?>">
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
                      Notes
                    </div>
                  </div>
                  <?php
                  $query_returjualdetail = "SELECT returjualdetail.*, grade.nmgrade, barang.nmbarang
                                    FROM returjualdetail
                                    INNER JOIN grade ON returjualdetail.idgrade = grade.idgrade
                                    INNER JOIN barang ON returjualdetail.idbarang = barang.idbarang
                                    WHERE idreturjual = '$idreturjual'";
                  $result_returjualdetail = mysqli_query($conn, $query_returjualdetail);
                  while ($row_returjualdetail = mysqli_fetch_assoc($result_returjualdetail)) { ?>
                    <div class="row mb-n2">
                      <div class="col-1">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="hidden" name="idgrade[]" id="idgrade" value="<?= $row_returjualdetail['idgrade']; ?>">
                            <input type="text" class="form-control text-center" value="<?= $row_returjualdetail['nmgrade']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="hidden" name="idbarang[]" id="idbarang" value="<?= $row_returjualdetail['idbarang']; ?>">
                            <input type="text" class="form-control" value="<?= $row_returjualdetail['nmbarang']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="box[]" class="form-control text-center" value="<?= $row_returjualdetail['box']; ?>" required onkeydown="moveFocusToNextInput(event, this, 'box[]')">
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="weight[]" class="form-control text-right" value="<?= $row_returjualdetail['weight']; ?>" required onkeydown=" moveFocusToNextInput(event, this, 'weight[]' )">
                          </div>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="notes[]" class="form-control" value="<?= $row_returjualdetail['notes']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col">
                      </div>
                    </div>
                  <?php } ?>
                </div>
                <div class="row">
                  <div class="col-5"></div>
                  <div class="col-1">
                    <input type="text" name="xbox" id="xbox" class="text-center form-control" readonly>
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
<script src="../dist/js/get_dos.js"></script>
<script src="../dist/js/movefocus.js"></script>
<script src="../dist/js/calculateTotals.js"></script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>