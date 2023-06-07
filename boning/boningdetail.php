<?php
require "../konak/conn.php";
include "../assets/html/header.php";
include "../assets/html/navbar.php";
include "../assets/html/mainsidebar.php";
?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">DATA BONING</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Products</th>
                  <th>Qty</th>
                  <th>Actual</th>
                  <th>Net Price</th>
                  <th>Gross Price</th>
                  <th>Ttl Price</th>
                  <th>Buying Price</th>
                  <th>Ttl Buying Price</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>TENDERLOIN</td>
                  <td>1000</td>
                  <td>5%</td>
                  <td>100000</td>
                  <td>120000</td>
                  <td>130000</td>
                  <td>90000</td>
                  <td>95000</td>
                </tr>

                <!-- </tbody> -->
              <tfoot>
                <tr>
                  <th colspan="2" class="text-right">Load Weight</th>
                  <th>?</th>
                  <th></th>
                  <th colspan="2" class="text-right">Total</th>
                  <th>?</th>
                  <th colspan="2"></th>
                </tr>
                <tr>
                  <th colspan="2" class="text-right">Average</th>
                  <th>?</th>
                  <th></th>
                  <th colspan="2" class="text-right">Purchase Cost</th>
                  <th>?</th>
                  <th colspan="2"></th>
                </tr>
                <tr>
                  <th colspan="2" class="text-right"> Price/Head</th>
                  <th>?</th>
                  <th></th>
                  <th colspan="2" class="text-right">Gross Profit</th>
                  <th>?</th>
                  <th colspan="2"></th>
                </tr>
                <tr>
                  <th colspan="6" class="text-right"> Gross Profit/kg</th>
                  <th colspan="3">?</th>
                </tr>
                <tr>
                  <th colspan="6" class="text-right"> Over Head/kg</th>
                  <th colspan="3">?</th>
                </tr>
                <tr>
                  <th colspan="6" class="text-right"> Net Profit/kg</th>
                  <th colspan="3">?</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- </div> -->
<!-- /.content-wrapper -->
<script>
  // Mengubah judul halaman web
  document.title = "Detail Hasil Boning";
</script>
<?php include "../assets/html/footer.php" ?>