<?php
require "../konak/conn.php";
require "seriallabelboning.php";
require "../assets/html/header.php";
require "../assets/html/navbar.php";
require "../assets/html/mainsidebar.php";

// check if idboning is set in $_GET array
if (!isset($_GET['id'])) {
  die("Jalankan Dari Modul Produksi");
}

$idboning = $_GET['id'];
$idboningWithPrefix = str_pad($idboning, 4, "0", STR_PAD_LEFT);

?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

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
                    <select class="form-control" name="product" id="product" required>
                      <option value="">--Pilih--</option>
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
                    <div class="input-group-append">
                      <a href="../master/newbarang.php" class="btn btn-success"><i class="fas fa-plus"></i></a>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Packed Date<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="date" class="form-control" name="packdate" id="packdate" required>
                    <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
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
                <input type="hidden" name="idboning" id="idboning" value="<?= $idboningWithPrefix; ?>" readonly>
                <input type="hidden" name="kdbarcode" id="kdbarcode" value="<?= "1" . $idboningWithPrefix . $kodeauto; ?>" readonly>
                <div class="form-group">
                  <label class="mt-2">Weight & Pcs <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="col-lg-4">
                      <input type="text" class="form-control mb-1" name="qty" id="qty" autofocus>
                    </div>
                  </div>
                </div>
                <div class="form-group text-right">
                  <button type="submit" class="btn bg-gradient-primary" name="submit">Print</button>
                </div>
                <input type="hidden" name="submit" value="1">
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
                  $ambildata = mysqli_query($conn, "SELECT * FROM labelboning ORDER BY idlabelboning DESC");
                  while ($tampil = mysqli_fetch_array($ambildata)) {
                  ?>
                    <tr class="text-center">
                      <td><?= $no; ?></td>
                      <td><?= $tampil['kdbarcode']; ?></td>
                      <td><?= $tampil['idbarang']; ?></td>
                      <td><?= $tampil['qty']; ?></td>
                      <td><?= $tampil['Pcs']; ?></td>
                      <td class="text-danger"> <i class="far fa-times-circle"></i> </td>
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
  <?php require "../assets/html/footer.php"; ?>