<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
// require "seriallabelboning.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// check if idboning is set in $_GET array
$idusers = $_SESSION['idusers'];
if (!isset($_GET['id'])) {
  die("Jalankan Dari Modul Produksi");
}

$idboning = $_GET['id'];
$idboningWithPrefix = str_pad($idboning, 4, "0", STR_PAD_LEFT);

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
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <a href="databoning.php"><button type="button" class="btn btn-sm btn-success"><i class="fas fa-undo-alt"></i> DATA BONING</button></a>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <form method="POST" action="cetaklabelboning.php" onsubmit="submitForm(event)">
                <div class="form-group">
                  <label>Product <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select class="form-control" name="idbarang" id="idbarang" required autofocus>
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
                  <input class="form-check-input" checked type="checkbox" name="tenderstreach" id="tenderstreach" <?php echo isset($_SESSION['tenderstreach']) && $_SESSION['tenderstreach'] ? 'checked' : ''; ?>>
                  <label class="form-check-label">Aktifkan Tenderstreatch</label>
                </div>
                <!-- ... -->
                <input type="hidden" name="idusers" id="idusers" value="<?= $idusers ?>">
                <input type="hidden" name="product" id="product">
                <input type="hidden" name="idboningWithPrefix" id="idboningWithPrefix" value="<?= $idboningWithPrefix; ?>">
                <input type="hidden" name="idboning" id="idboning" value="<?= $idboning; ?>">
                <!-- <input type="hidden" name="kdbarcode" id="kdbarcode" value="<?= "1" . $idboningWithPrefix . $kodeauto; ?>"> -->
                <div class="form-group">
                  <label class="mt-2">Weight & Pcs <span class="text-danger">*</span></label>
                  <div class="input-group col-lg-4">
                    <!-- <div class="col-lg-4"> -->
                    <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" required>
                    <!-- </div> -->
                  </div>
                </div>
                <button type="submit" class="btn bg-gradient-primary btn-block" name="submit">Print</button>
              </form>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead class="text-center">
                  <tr>
                    <th>#</th>
                    <th>Barcode</th>
                    <th>Grade</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Pcs</th>
                    <th>Author</th>
                    <th>Create</th>
                    <th>Hapus</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT l.*, b.nmbarang, u.fullname, g.nmgrade FROM labelboning l
  JOIN barang b ON l.idbarang = b.idbarang 
  JOIN boning bo ON l.idboning = bo.idboning
  JOIN grade g ON l.idgrade = g.idgrade
  JOIN users u ON l.iduser = u.idusers
  WHERE l.idboning = $idboning ORDER BY l.idlabelboning DESC");

                  while ($tampil = mysqli_fetch_array($ambildata)) {
                    $fullname = $tampil['fullname'];

                    // Query untuk memeriksa keberadaan kdbarcode di tabel tallydetail
                    $kdbarcode = $tampil['kdbarcode'];
                    $checkBarcodeQuery = "SELECT COUNT(*) as total FROM tallydetail WHERE barcode = '$kdbarcode'";
                    $result = mysqli_query($conn, $checkBarcodeQuery);
                    $row = mysqli_fetch_assoc($result);
                    $barcodeExist = $row['total'] > 0;
                  ?>
                    <tr class="text-center">
                      <td><?= $no; ?></td>
                      <td><?= $tampil['kdbarcode']; ?></td>
                      <td><?= $tampil['nmgrade']; ?></td>
                      <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                      <td><?= $tampil['qty']; ?></td>
                      <td><?= $tampil['pcs']; ?></td>
                      <td><?= $fullname; ?></td>
                      <td><?= date("H:i:s", strtotime($tampil['dibuat'])); ?></td>
                      <td>
                        <?php if ($barcodeExist) { ?>
                          <i class="fas fa-poo fa-lg"></i>
                        <?php } else { ?>
                          <a href="hapus_labelboning.php?id=<?php echo $tampil['idlabelboning']; ?>&idboning=<?php echo $idboning; ?>&kdbarcode=<?= $tampil['kdbarcode']; ?>" class="text-danger" onclick="return confirm('Yakin Lu?')">
                            <i class="far fa-times-circle"></i>
                          </a>
                        <?php } ?>
                      </td>
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
        <!-- /.col-md-6 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  <script>
    document.title = "Boning <?= "BN" . $idboningWithPrefix ?>";
    document.addEventListener('DOMContentLoaded', function() {
      // Menggunakan event listener untuk menangkap event keydown pada elemen dengan id "idbarang"
      document.getElementById('idbarang').addEventListener('keydown', function(e) {
        // Jika tombol yang ditekan adalah "Tab" (kode 9)
        if (e.keyCode === 9) {
          // Pindahkan fokus ke elemen dengan id "qty"
          document.getElementById('qty').focus();
          // Mencegah perpindahan fokus bawaan yang dihasilkan oleh tombol "Tab"
          e.preventDefault();
        }
      });
    });
  </script>

  <?php
  // require "../footnote.php";
  require "../footer.php";
  ?>