<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
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
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-n4">
        <div class="col">
          <marquee behavior="scrolling" direction="">
            <h2>RELABEL</h2>
          </marquee>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4">
          <div class="card">
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
                  <label>Grade <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select class="form-control" name="idgrade" id="idgrade" required>
                      <?php
                      if (isset($_SESSION['idgrade']) && $_SESSION['idgrade'] != '') {
                        $selectedIdgrade = $_SESSION['idgrade'];
                        echo "<option value=\"$selectedIdgrade\" selected>--Pilih Grade--</option>";
                      } else {
                        echo '<option value="" selected>--Pilih Grade--</option>';
                      }
                      $query = "SELECT * FROM grade ORDER BY nmgrade ASC";
                      $result = mysqli_query($conn, $query);
                      while ($row = mysqli_fetch_assoc($result)) {
                        $idgrade = $row['idgrade'];
                        $nmgrade = $row['nmgrade'];
                        $selected = ($idgrade == $selectedIdgrade) ? 'selected' : '';
                        echo "<option value=\"$idgrade\" $selected>$nmgrade</option>";
                      }
                      ?>
                    </select>
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
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="pembulatan" id="pembulatan" <?php echo isset($_SESSION['pembulatan']) && $_SESSION['pembulatan'] ? 'checked' : ''; ?>>
                  <label class="form-check-label">1 Digit Koma</label>
                </div>
                <!-- ... -->
                <input type="hidden" name="idusers" id="idusers" value="<?= $idusers ?>">
                <input type="hidden" name="product" id="product">
                <!-- <input type="hidden" name="kdbarcode" id="kdbarcode" value="<?= "4" . $kodeauto; ?>"> -->
                <div class="form-group">
                  <label class="mt-2">Weight & Pcs <span class="text-danger">*</span></label>
                  <div class="input-group col-lg-4">
                    <!-- <div class="col-lg-4"> -->
                    <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" required autofocus>
                    <!-- </div> -->
                  </div>
                </div>
                <button type="submit" class="btn btn-block bg-gradient-primary" name="submit">Print</button>
              </form>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead class="text-center">
                  <tr>
                    <th>#</th>
                    <th>Barcode</th>
                    <th>Product</th>
                    <th>Grade</th>
                    <th>Qty</th>
                    <th>Pcs</th>
                    <th>Author</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT r.*, b.nmbarang, u.fullname, g.nmgrade FROM relabel r
                                                   INNER JOIN barang b ON r.idbarang = b.idbarang
                                                   INNER JOIN users u ON r.iduser = u.idusers
                                                   LEFT JOIN grade g ON r.idgrade = g.idgrade
                                                   ORDER BY r.dibuat DESC");
                  while ($tampil = mysqli_fetch_array($ambildata)) {
                    $fullname = $tampil['fullname'];
                    $nmbarang = $tampil['nmbarang'];
                  ?>
                    <tr class="text-center">
                      <td><?= $no; ?></td>
                      <td><?= $tampil['kdbarcode']; ?></td>
                      <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                      <td><?= $tampil['nmgrade']; ?></td>
                      <td><?= $tampil['qty']; ?></td>
                      <td><?= $tampil['pcs']; ?></td>
                      <td><?= $fullname; ?></td>
                    </tr>
                  <?php
                    $no++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
</div>
<script>
  document.title = "Relabel";
</script>
<?php
// require "../footnote.php";
require "../footer.php";
?>