<?php
session_start();
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";
// check if idboning is set in $_GET array
if (!isset($_GET['id'])) {
  die("Jalankan Dari Modul Produksi");
}

$idboning = $_GET['id'];
$idboningWithPrefix = str_pad($idboning, 4, "0", STR_PAD_LEFT);
$query = "SELECT l.idlabelboning, b.nmbarang, l.qty
          FROM labelboning l
          INNER JOIN barang b ON l.idbarang = b.idbarang
          WHERE l.idboning = $idboning";
$result = mysqli_query($conn, $query);
if (!$result) {
  die("Query error: " . mysqli_error($conn));
}
require "boningtotal.php";
?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col mt-3">
        <div class="card">
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-sm">
              <thead class="text-center">
                <tr>
                  <th>#</th>
                  <th>Product</th>
                  <th>Box Total</th>
                  <th>Qty Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 1;
                $previousItem = ""; // untuk melacak item sebelumnya
                $totalQty = 0; // total qty barang

                while ($row = mysqli_fetch_assoc($result)) {
                  $currentItem = $row['nmbarang'];
                  $currentQty = $row['qty'];

                  if ($previousItem != $currentItem) {
                    // Menampilkan nama barang hanya sekali dengan total qty
                    if ($previousItem != "") { // mengecualikan item pertama
                ?>
                      <tr>
                        <td class="text-center"><?= $counter++; ?></td>
                        <td><?= $previousItem; ?></td>
                        <td class="text-center"><?php echo $counter - 2; ?></td>
                        <td class="text-right"><?= $totalQty; ?></td>
                      </tr>
                <?php
                    }
                    // Mengatur ulang total qty untuk item baru
                    $totalQty = $currentQty;
                  } else {
                    // Menambahkan qty item saat ini ke total qty
                    $totalQty += $currentQty;
                  }

                  $previousItem = $currentItem;
                }
                // Menampilkan data untuk item terakhir
                ?>
                <tr>
                  <td class="text-center"><?= $counter++; ?></td>
                  <td><?= $previousItem; ?></td>
                  <td class="text-center"><?php echo $counter - 2; ?></td>
                  <td class="text-right"><?= $totalQty; ?></td>
                </tr>
              </tbody>
              <?php

              ?>
              <tfoot>
                <th colspan="2" class="text-right">GRAND TOTAL</th>
                <th class="text-center"><?= $total_box . " Box"; ?></th>
                <th class="text-right"><?= $total_weight; ?></th>
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
include "../footer.php" ?>