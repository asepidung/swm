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
                  // Query SQL untuk mendapatkan data nmbarang dan stock per grade
                  $query = "
                    SELECT b.nmbarang, b.idgrade,
                           (b.stockawal +
                            COALESCE(SUM(gd.weight), 0) +
                            COALESCE(SUM(ad.weight), 0) +
                            COALESCE(SUM(id.weight), 0) +
                            COALESCE(SUM(rjd.weight), 0) -
                            COALESCE(SUM(dd.weight), 0) -
                            COALESCE(SUM(odd.weight), 0)) AS stock
                      FROM barang b
                           LEFT JOIN grdetail gd ON b.idbarang = gd.idbarang
                           LEFT JOIN adjustmentdetail ad ON b.idbarang = ad.idbarang
                           LEFT JOIN inbounddetail id ON b.idbarang = id.idbarang
                           LEFT JOIN returjualdetail rjd ON b.idbarang = rjd.idbarang
                           LEFT JOIN dodetail dd ON b.idbarang = dd.idbarang
                           LEFT JOIN outbounddetail odd ON b.idbarang = odd.idbarang
                     WHERE b.idgrade IN (1, 2, 3, 4, 5, 6)
                     GROUP BY b.nmbarang, b.idgrade
                  ";

                  $result = $conn->query($query);

                  // Inisialisasi array untuk menyimpan data stock per grade
                  $stockPerGrade = array();

                  // Mengisi array dengan data stock
                  while ($row = $result->fetch_assoc()) {
                    $gradeId = $row['idgrade'];
                    $nmbarang = $row['nmbarang'];
                    $stock = $row['stock'];

                    if (!isset($stockPerGrade[$nmbarang])) {
                      $stockPerGrade[$nmbarang] = array();
                    }

                    $stockPerGrade[$nmbarang][$gradeId] = $stock;
                  }

                  // Menampilkan data dalam bentuk tabel
                  foreach ($stockPerGrade as $nmbarang => $stockData) {
                    echo "<tr>";
                    for ($i = 1; $i <= 6; $i++) {
                      $gradeColumn = "P" . str_pad($i, 2, "0", STR_PAD_LEFT);
                      echo "<td>" . $stockData[$i] . "</td>";
                    }
                    echo "</tr>";
                  }
                  ?>
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