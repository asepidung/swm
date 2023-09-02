<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
if (isset($_GET['idoutbound'])) {
  $idoutbound = $_GET['idoutbound'];
  $query_edit = "SELECT outbound.*, users.idusers FROM outbound
                  JOIN users ON outbound.idusers = users.idusers
                  WHERE idoutbound = ?";
  $stmt_edit = $conn->prepare($query_edit);
  $stmt_edit->bind_param("i", $idoutbound);
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
          <form method="POST" action="updateoutbound.php">
            <input type="hidden" name="idoutbound" value="<?= $data_edit['idoutbound'] ?>">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-2">
                    <div class="form-group">
                      <label for="nooutbound">Serial Number <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="text" class="form-control" value="<?= $data_edit['nooutbound'] ?>" name="nooutbound" id="nooutbound" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="proses">Jenis Proses <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <select class="form-control" name="proses" id="proses" required>
                          <option value="Hasil Repack" <?= ($data_edit['proses'] === 'Hasil Repack') ? '' : 'selected' ?>>Hasil Repack</option>
                          <option value="Mutasi Masuk G. Jonggol" <?= ($data_edit['proses'] === 'Mutasi Masuk G. Jonggol') ? 'selected' : '' ?>>Mutasi Masuk G Jonggol</option>
                          <option value="Mutasi Masuk G. Perum" <?= ($data_edit['proses'] === 'Mutasi Masuk G. Perum') ? 'selected' : '' ?>>Mutasi Masuk G Perum</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="tgloutbound">Tanggal outbound <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="date" class="form-control" name="tgloutbound" id="tgloutbound" required value="<?= $data_edit['tgloutbound'] ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label for="note">Catatan </label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="note" value="<?= $data_edit['note'] ?>">
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
                      Notes
                    </div>
                  </div>
                  <?php
                  $query_outbounddetail = "SELECT outbounddetail.*, grade.nmgrade, barang.nmbarang
                                    FROM outbounddetail
                                    INNER JOIN grade ON outbounddetail.idgrade = grade.idgrade
                                    INNER JOIN barang ON outbounddetail.idbarang = barang.idbarang
                                    WHERE idoutbound = '$idoutbound'";
                  $result_outbounddetail = mysqli_query($conn, $query_outbounddetail);
                  while ($row_outbounddetail = mysqli_fetch_assoc($result_outbounddetail)) { ?>
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
                                $selectedgrade = ($idgrade == $row_outbounddetail['idgrade']) ? "selected" : "";
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
                                $selectedbarang = ($idbarang == $row_outbounddetail['idbarang']) ? "selected" : "";
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
                            <input type="text" name="box[]" class="form-control text-center" value="<?= $row_outbounddetail['box']; ?>" required onkeydown="moveFocusToNextInput(event, this, 'box[]')">
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="weight[]" class="form-control text-right" value="<?= $row_outbounddetail['weight']; ?>" required onkeydown=" moveFocusToNextInput(event, this, 'weight[]' )">
                          </div>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="notes[]" class="form-control" value="<?= $row_outbounddetail['notes']; ?>">
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
                    <input type="text" name="xbox" id="xbox" class="form-control tex-center" readonly>
                  </div>
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

<?php
// require "../footnotes.php";
include "../footer.php";
?>