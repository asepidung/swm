<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <div class="card">
            <div class="card-body">
              <div class="col">
                <table id="example1" class="table table-bordered table-striped table-sm">
                  <thead class="text-center">
                    <tr>
                      <th rowspan="2">Kode</th>
                      <th rowspan="2">Nama Product</th>
                      <th colspan="3">G. Jonggol</th>
                      <th colspan="3">G. Perum</th>
                      <th rowspan="2">Total</th>
                    </tr>
                    <tr>
                      <th>CHILL</th>
                      <th>FROZEN</th>
                      <th>GRADE</th>
                      <th>CHILL</th>
                      <th>FROZEN</th>
                      <th>GRADE</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ambildata = mysqli_query($conn, "SELECT * FROM barang");
                    while ($tampil = mysqli_fetch_array($ambildata)) {
                      $idbarang = $tampil['idbarang'];
                      include "flowstock.php";
                    ?>
                      <tr class="text-right">
                        <td class="text-center"><?= $tampil['kdbarang']; ?></td>
                        <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                        <td><?= ($J01 == 0) ? '' : (($J01 < 0) ? '<span class="text-danger">' . number_format($J01, 2) . '</span>' : number_format($J01, 2)); ?></td>
                        <td><?= ($J02 == 0) ? '' : (($J02 < 0) ? '<span class="text-danger">' . number_format($J02, 2) . '</span>' : number_format($J02, 2)); ?></td>
                        <td><?= ($J03 == 0) ? '' : (($J03 < 0) ? '<span class="text-danger">' . number_format($J03, 2) . '</span>' : number_format($J03, 2)); ?></td>
                        <td><?= ($P01 == 0) ? '' : (($P01 < 0) ? '<span class="text-danger">' . number_format($P01, 2) . '</span>' : number_format($P01, 2)); ?></td>
                        <td><?= ($P02 == 0) ? '' : (($P02 < 0) ? '<span class="text-danger">' . number_format($P02, 2) . '</span>' : number_format($P02, 2)); ?></td>
                        <td><?= ($P03 == 0) ? '' : (($P03 < 0) ? '<span class="text-danger">' . number_format($P03, 2) . '</span>' : number_format($P03, 2)); ?></td>
                        <th><?= ($totalstockperitem < 0) ? '<span class="text-danger">' . number_format($totalstockperitem, 2) . '</span>' : (($totalstockperitem == 0) ? '' : number_format($totalstockperitem, 2)); ?></th>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
  document.title = "DATA BARANG";
</script>
<?php
include "../footer.php";
// include "../footnote.php";
?>