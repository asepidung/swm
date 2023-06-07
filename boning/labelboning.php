<?php
require "../konak/conn.php";
session_start();
include 'sessionboning.php';
require "seriallabelboning.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// check if idboning is set in $_GET array
if (!isset($_GET['id'])) {
  die("Jalankan Dari Modul Produksi");
}

$idboning = $_GET['id'];
$idboningWithPrefix = str_pad($idboning, 4, "0", STR_PAD_LEFT);

?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 mt-3">
        <div class="card">
          <div class="card-body">
            <form method="GET" action="cetaklabelboning.php">
              <div class="form-group">
                <label>Product <span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-control" name=" product" id="product" required>
                    <option value=""></option>
                    <?php
                    $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                      $idbarang = $row['idbarang'];
                      $nmbarang = $row['nmbarang'];
                      echo "<option value=\"$idbarang\">$nmbarang</option>";
                    }
                    ?>
                  </select>
                  <!-- <div class="input-group-append">
                    <a href="../barang/newbarang.php" class="btn btn-success"><i class="fas fa-plus"></i></a>
                  </div> -->
                </div>
              </div>
              <div class="form-group">
                <label>Packed Date<span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="date" class="form-control" name="packdate" id="packdate" required>
                </div>
              </div>
              <div class="form-group">
                <label>Expired Date</span></label>
                <div class="input-group">
                  <input type="date" class="form-control" name="exp" id="exp">
                </div>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" checked name="tenderstreach" id="tenderstreach">
                <label class="form-check-label">Aktifkan Tenderstreatch</label>
              </div>
              <input type="hidden" name="idbarang" value="<?php echo isset($_GET['product']) ? $_GET['product'] : ''; ?>">
              <input type="hidden" name="idboningWithPrefix" id="idboningWithPrefix" value="<?= $idboningWithPrefix; ?>">
              <input type="hidden" name="idboning" id="idboning" value="<?= $idboning; ?>">
              <input type="hidden" name="kdbarcode" id="kdbarcode" value="<?= "1" . $idboningWithPrefix . $kodeauto; ?>">
              <div class="form-group">
                <label class="mt-2">Weight & Pcs <span class="text-danger">*</span></label>
                <div class="input-group">
                  <div class="col-lg-4">
                    <input type="text" class="form-control mb-1" name="qty" id="qty" autofocus required>
                  </div>
                </div>
              </div>
              <div class="form-group text-right">
                <button type="submit" class="btn bg-gradient-primary" name="submit" onclick="printLabel()">Print</button>
              </div>
            </form>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md-6 -->
      <div class="col-lg-8 mt-3">
        <div class="card">
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-sm">
              <thead class="text-center">
                <tr>
                  <th>#</th>
                  <th>Barcode</th>
                  <th>Product</th>
                  <th>Qty</th>
                  <th>Pcs</th>
                  <th>Hapus</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $ambildata = mysqli_query($conn, "SELECT l.*, b.nmbarang FROM labelboning l JOIN barang b ON l.idbarang = b.idbarang ORDER BY l.idlabelboning DESC");
                while ($tampil = mysqli_fetch_array($ambildata)) {
                ?>
                  <tr class="text-center">
                    <td><?= $no; ?></td>
                    <td><?= $tampil['kdbarcode']; ?></td>
                    <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                    <td><?= $tampil['qty']; ?></td>
                    <td><?= $tampil['pcs']; ?></td>
                    <td>
                      <a href="hapus_labelboning.php?id=<?php echo $tampil['idlabelboning']; ?>&idboning=<?php echo $idboning; ?>" class="text-danger" onclick="return confirm('Yakin Lu?')">
                        <i class="far fa-times-circle"></i>
                      </a>
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
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../footer.php"; ?>