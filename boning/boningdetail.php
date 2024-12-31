<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
  exit();
}

require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// Check if idboning is set in $_GET array
if (!isset($_GET['id'])) {
  die("Jalankan Dari Modul Produksi");
}

$idboning = $_GET['id'];
$idboningWithPrefix = str_pad($idboning, 4, "0", STR_PAD_LEFT);

$query = "SELECT l.idlabelboning, b.nmbarang, l.qty, l.pcs
          FROM labelboning l
          INNER JOIN barang b ON l.idbarang = b.idbarang
          WHERE l.idboning = $idboning AND l.is_deleted = 0";

$result = mysqli_query($conn, $query);
if (!$result) {
  die("Query error: " . mysqli_error($conn));
}

// Buat array untuk menyimpan data barang dengan nama yang sama
$items = array();

while ($row = mysqli_fetch_assoc($result)) {
  $currentItem = $row['nmbarang'];
  $currentQty = $row['qty'];
  $currentPcs = $row['pcs'];

  if (array_key_exists($currentItem, $items)) {
    // Jika item sudah ada dalam array, tambahkan qty, pcs dan totalBox
    $items[$currentItem]['qty'] += $currentQty;
    $items[$currentItem]['pcs'] += is_numeric($currentPcs) ? (int)$currentPcs : 0;
    $items[$currentItem]['totalBox'] += 1;
  } else {
    // Jika item belum ada dalam array, tambahkan item baru
    $items[$currentItem] = array(
      'name' => $currentItem,
      'qty' => $currentQty,
      'pcs' => is_numeric($currentPcs) ? (int)$currentPcs : 0,
      'totalBox' => 1 // Set totalBox awal menjadi 1
    );
  }
}

require "boningtotal.php";
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <a href="databoning.php"><button type="button" class="btn btn-sm btn-success"><i class="fas fa-undo-alt"></i> BONING</button></a>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead class="text-center">
                  <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Box Total</th>
                    <th>Pcs Total</th>
                    <th>Qty Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $counter = 1;
                  $totalQty = 0; // total qty barang
                  $totalPcs = 0; // total pcs barang
                  $totalBox = 0; // total box barang

                  foreach ($items as $item) {
                    $itemName = $item['name'];
                    $itemQty = $item['qty'];
                    $itemTotalPcs = $item['pcs'];
                    $itemTotalBox = $item['totalBox'];

                    echo '<tr>
                                                <td class="text-center">' . $counter++ . '</td>
                                                <td>' . $itemName . '</td>
                                                <td class="text-center">' . $itemTotalBox . '</td>
                                                <td class="text-center">' . $itemTotalPcs . '</td>
                                                <td class="text-right">' . number_format($itemQty, 2) . '</td>
                                              </tr>';

                    $totalQty += $itemQty;
                    $totalPcs += $itemTotalPcs;
                    $totalBox += $itemTotalBox;
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="2" class="text-right">GRAND TOTAL</th>
                    <th class="text-center"><?= $totalBox . " Box"; ?></th>
                    <th class="text-center"><?= $totalPcs . " Pcs"; ?></th>
                    <th class="text-right"><?= number_format($totalQty, 2); ?></th>
                  </tr>
                </tfoot>
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
    document.title = "Detail Boning <?= "BN" . $idboningWithPrefix ?>";
  </script>
  <?php
  require "../footnote.php";
  include "../footer.php";
  ?>
</div>
