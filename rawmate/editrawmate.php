<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "kdrawmateunik.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Mendapatkan idrawmate dari URL
$idrawmate = $_GET['idrawmate'];

// Mengambil data rawmate dari database berdasarkan idrawmate
$query = "SELECT * FROM rawmate WHERE idrawmate = '$idrawmate'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

?>

<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="card card-dark mt-3">
            <div class="card-header">
              <h3 class="card-title">Edit MATERIAL</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="proseseditrawmate.php">
              <div class=" card-body">
                <div class="form-group">
                  <label for="kdrawmate">Kode</label>
                  <input type="hidden" name="idrawmate" value="<?= $idrawmate ?>">
                  <input type="text" class="form-control" name="kdrawmate" id="kdrawmate" value="<?= $row['kdrawmate']; ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="nmrawmate">Nama Product <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nmrawmate" id="nmrawmate" value="<?= $row['nmrawmate']; ?>">
                </div>
                <div class="form-group">
                  <label for="category">Category <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select name="idrawcategory" id="category" class="form-control" required>
                      <option value="">-- Select Category --</option>
                      <?php
                      $query = "SELECT idrawcategory, nmcategory FROM rawcategory";
                      $result = mysqli_query($conn, $query);

                      // Looping untuk menampilkan data ke dalam option
                      while ($category = mysqli_fetch_assoc($result)) {
                        $selected = ($category['idrawcategory'] == $row['idrawcategory']) ? 'selected' : '';
                        echo "<option value='" . $category['idrawcategory'] . "' $selected>" . htmlspecialchars($category['nmcategory']) . "</option>";
                      }
                      ?>
                    </select>
                    <div class="input-group-append">
                      <a href="addrawcategory.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                    </div>
                  </div>
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
                        <?= $row['stock'] == 1 ? 'checked' : ''; ?>>
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
                        <?= $row['stock'] == 0 ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="tampilkan_stock_no">Tidak</label>
                    </div>
                  </div>


                </div>
              </div>
              <div class="form-group mr-3 text-right">
                <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-level-up-alt"></i> Update</button>
              </div>
            </form>

          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </section>
</div><!-- /.container-fluid -->
<!-- /.content-wrapper -->
<script>
  document.title = "EDIT RAW MATERIAL";
</script>
<?php
include "../footer.php";
include "../footnote.php";
?>