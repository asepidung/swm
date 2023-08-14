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
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <h3>DATA STOCK</h3>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered table-striped table-sm">
                <thead class="text-center">
                  <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Poduct</th>
                    <th colspan="3">G. JONGGOL</th>
                    <th colspan="3">G. PERUM</th>
                    <th rowspan="2">TOTAL</th>
                  </tr>
                  <tr>
                    <th>J01</th>
                    <th>J02</th>
                    <th>J03</th>
                    <th>P01</th>
                    <th>P02</th>
                    <th>P03</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $idgrade1 = "total_hitung_idbarang_berdasarkan_idgrade";
                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT * FROM barang");
                  while ($tampil = mysqli_fetch_array($ambildata)) { ?>
                    <tr class="text-right">
                      <td class="text-center"><?= $no; ?></td>
                      <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                      <td><?= $idgrade1; ?></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  <?php $no++;
                  } ?>
                </tbody>
                <tfoot>
                  <tr>

                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  // Mengubah judul halaman web
  document.title = "Stock";
</script>
<?php
$conn->close();
// require "../footnote.php";
include "../footer.php" ?>