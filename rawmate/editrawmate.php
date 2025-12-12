<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "kdrawmateunik.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Ambil idrawmate dengan aman
$idrawmate = isset($_GET['idrawmate']) ? intval($_GET['idrawmate']) : 0;
if ($idrawmate <= 0) {
  echo "<div class='alert alert-danger'>ID tidak valid.</div>";
  include "../footer.php";
  exit();
}

// Prepared statement untuk mengambil data rawmate
$stmt = $conn->prepare("SELECT * FROM rawmate WHERE idrawmate = ?");
$stmt->bind_param("i", $idrawmate);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
  echo "<div class='alert alert-danger'>Data material tidak ditemukan.</div>";
  include "../footer.php";
  exit();
}

// Daftar unit yang disederhanakan (urut alfabet)
$units = [
  "Box",
  "Ikat",
  "Kg",
  "Pack",
  "Pcs"
];
?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-dark mt-3">
            <div class="card-header">
              <h3 class="card-title">Edit MATERIAL</h3>
            </div>

            <form method="POST" action="proseseditrawmate.php">
              <div class="card-body">
                <div class="form-group">
                  <label for="kdrawmate">Kode</label>
                  <input type="hidden" name="idrawmate" value="<?= htmlspecialchars($row['idrawmate'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                  <input type="text" class="form-control" name="kdrawmate" id="kdrawmate" value="<?= htmlspecialchars($row['kdrawmate'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" readonly>
                </div>

                <div class="form-group">
                  <label for="nmrawmate">Nama Product <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nmrawmate" id="nmrawmate" value="<?= htmlspecialchars($row['nmrawmate'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" required>
                </div>

                <div class="form-group">
                  <label for="category">Category <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select name="idrawcategory" id="category" class="form-control" required>
                      <option value="">-- Select Category --</option>
                      <?php
                      $qcat = "SELECT idrawcategory, nmcategory FROM rawcategory ORDER BY nmcategory ASC";
                      $rescat = mysqli_query($conn, $qcat);
                      while ($category = mysqli_fetch_assoc($rescat)) {
                        $selected = ($category['idrawcategory'] == $row['idrawcategory']) ? 'selected' : '';
                        echo "<option value='" . (int)$category['idrawcategory'] . "' $selected>" . htmlspecialchars($category['nmcategory'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</option>";
                      }
                      ?>
                    </select>
                    <div class="input-group-append">
                      <a href="addrawcategory.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                    </div>
                  </div>
                </div>

                <!-- Field Unit -->
                <div class="form-group">
                  <label for="unit">Unit / Satuan <span class="text-danger">*</span></label>
                  <select name="unit" id="unit" class="form-control" required>
                    <option value="">-- Pilih Satuan --</option>
                    <?php
                    $currentUnit = isset($row['unit']) ? $row['unit'] : '';
                    foreach ($units as $u) {
                      $sel = ($u === $currentUnit) ? 'selected' : '';
                      echo "<option value=\"" . htmlspecialchars($u, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "\" $sel>" . htmlspecialchars($u, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <!-- End Field Unit -->

                <div class="form-group">
                  <label for="tampilkan_stock">Tampilkan di Stock</label>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="stock"
                      id="tampilkan_stock_yes"
                      value="1"
                      required
                      <?= ($row['stock'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="tampilkan_stock_yes">Ya</label>
                  </div>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="stock"
                      id="tampilkan_stock_no"
                      value="0"
                      required
                      <?= ($row['stock'] == 0) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="tampilkan_stock_no">Tidak</label>
                  </div>
                </div>

              </div>

              <div class="form-group mr-3 text-right">
                <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-level-up-alt"></i> Update</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  document.title = "EDIT RAW MATERIAL";
</script>

<?php
include "../footer.php";
include "../footnote.php";
?>