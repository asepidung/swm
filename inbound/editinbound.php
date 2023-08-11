<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
if (isset($_GET['idinbound'])) {
  $idinbound = $_GET['idinbound'];
  $query_edit = "SELECT inbound.*, users.idusers FROM inbound
                  JOIN users ON inbound.idusers = users.idusers
                  WHERE idinbound = ?";
  $stmt_edit = $conn->prepare($query_edit);
  $stmt_edit->bind_param("i", $idinbound);
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
          <form method="POST" action="updateinbound.php">
            <input type="hidden" name="idinbound" value="<?= $data_edit['idinbound'] ?>">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-2">
                    <div class="form-group">
                      <label for="noinbound">Serial Number <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="text" class="form-control" value="<?= $data_edit['noinbound'] ?>" name="noinbound" id="noinbound" readonly>
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
                      <label for="tglinbound">Tanggal Inbound <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="date" class="form-control" name="tglinbound" id="tglinbound" required value="<?= $data_edit['tglinbound'] ?>">
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
                  $query_inbounddetail = "SELECT inbounddetail.*, grade.nmgrade, barang.nmbarang
                                    FROM inbounddetail
                                    INNER JOIN grade ON inbounddetail.idgrade = grade.idgrade
                                    INNER JOIN barang ON inbounddetail.idbarang = barang.idbarang
                                    WHERE idinbound = '$idinbound'";
                  $result_inbounddetail = mysqli_query($conn, $query_inbounddetail);
                  while ($row_inbounddetail = mysqli_fetch_assoc($result_inbounddetail)) { ?>
                    <div class="row mb-n2">
                      <div class="col-1">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="hidden" name="idgrade[]" id="idgrade" value="<?= $row_inbounddetail['idgrade']; ?>">
                            <input type="text" class="form-control tex-center" value="<?= $row_inbounddetail['nmgrade']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="hidden" name="idbarang[]" id="idbarang" value="<?= $row_inbounddetail['idbarang']; ?>">
                            <input type="text" class="form-control" value="<?= $row_inbounddetail['nmbarang']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="box[]" class="form-control text-center" value="<?= $row_inbounddetail['box']; ?>" required onkeydown="moveFocusToNextInput(event, this, 'box[]')">
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="weight[]" class="form-control text-right" value="<?= $row_inbounddetail['weight']; ?>" required onkeydown=" moveFocusToNextInput(event, this, 'weight[]' )">
                          </div>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" name="notes[]" class="form-control" value="<?= $row_inbounddetail['notes']; ?>">
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