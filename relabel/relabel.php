<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "serialrelabel.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// check if idboning is set in $_GET array
$idusers = $_SESSION['idusers'];
// Mengambil daftar barang
$query = "SELECT * FROM barang ORDER BY nmbarang ASC";
$result = mysqli_query($conn, $query);
$barangOptions = "";
while ($row = mysqli_fetch_assoc($result)) {
  $idbarang = $row['idbarang'];
  $nmbarang = $row['nmbarang'];
  $barangOptions .= "<option value=\"$idbarang\">$nmbarang</option>";
}
?>
<div class="content-wrapper">
  <!-- /.content-header -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6">
          <div class="card mt-3">
            <div class="card-body">
              <form method="POST" action="cetakrelabel.php" onsubmit="submitForm(event)">
                <div class="form-group">
                  <label>Product <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select class="form-control" name="idbarang" id="idbarang" required>
                      <?php
                      if (isset($_SESSION['idbarang']) && $_SESSION['idbarang'] != '') {
                        $selectedIdbarang = $_SESSION['idbarang'];
                        echo "<option value=\"$selectedIdbarang\" selected>--Pilih Item--</option>";
                      } else {
                        echo '<option value="" selected>--Pilih Item--</option>';
                      }
                      $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                      $result = mysqli_query($conn, $query);
                      while ($row = mysqli_fetch_assoc($result)) {
                        $idbarang = $row['idbarang'];
                        $nmbarang = $row['nmbarang'];
                        $selected = ($idbarang == $selectedIdbarang) ? 'selected' : '';
                        echo "<option value=\"$idbarang\" $selected>$nmbarang</option>";
                      }
                      ?>
                    </select>
                    <div class="input-group-append">
                      <a href="../barang/newbarang.php" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Packed Date<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <?php
                    // Set the default value of $_SESSION['packdate'] to today's date
                    if (!isset($_SESSION['packdate']) || $_SESSION['packdate'] == '') {
                      $_SESSION['packdate'] = date('Y-m-d'); // Set the format according to your needs
                    }
                    ?>
                    <input type="date" class="form-control" name="packdate" id="packdate" required value="<?= $_SESSION['packdate']; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label>Expired Date</label>
                  <div class="input-group">
                    <input type="date" class="form-control" name="exp" id="exp" value="<?= isset($_SESSION['exp']) ? $_SESSION['exp'] : ''; ?>">
                  </div>
                </div>
                <!-- ... -->
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="tenderstreach" id="tenderstreach" <?php echo isset($_SESSION['tenderstreach']) && $_SESSION['tenderstreach'] ? 'checked' : ''; ?>>
                  <label class="form-check-label">Aktifkan Tenderstreatch</label>
                </div>
                <!-- ... -->
                <input type="hidden" name="idusers" id="idusers" value="<?= $idusers ?>">
                <input type="hidden" name="product" id="product">
                <input type="hidden" name="kdbarcode" id="kdbarcode" value="<?= "4" . $kodeauto; ?>">
                <div class="form-group">
                  <label class="mt-2">Weight & Pcs <span class="text-danger">*</span></label>
                  <div class="input-group col-lg-4">
                    <!-- <div class="col-lg-4"> -->
                    <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" required autofocus>
                    <!-- </div> -->
                  </div>
                </div>
                <button type="submit" class="btn bg-gradient-primary" name="submit">Print</button>
              </form>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  <script>
    document.title = "Relabel";
  </script>
  <?php
  require "../footnote.php";
  require "../footer.php";
  ?>