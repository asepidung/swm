<?php
require "../konak/conn.php";
include "../assets/html/header.php";
include "../assets/html/navbar.php";
include "../assets/html/mainsidebar.php";
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-2">
          <!-- <h1 class="m-0">DATA BONING</h1> -->
          <button type="button" class="btn btn-info">Buat Data Boning Baru</button>
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
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>BATCH</th>
                    <th>Tgl Killing</th>
                    <th>Tgl Boning</th>
                    <th>Supplier</th>
                    <th>Jml Sapi</th>
                    <th>Ttl Weight</th>
                    <th>Catatan</th>
                    <th>AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  </tr>
                  <?php
                  $query_total_sapi = "SELECT SUM(qtysapi) AS total_sapi FROM boning";
                  $result_total_sapi = mysqli_query($conn, $query_total_sapi);
                  $row_total_sapi = mysqli_fetch_assoc($result_total_sapi);
                  $total_sapi = $row_total_sapi['total_sapi'];

                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT b.*, p.nmsupplier FROM boning b JOIN supplier p ON b.idsupplier = p.idsupplier ORDER BY b.batchboning DESC");
                  while ($tampil = mysqli_fetch_array($ambildata)) {
                    $tglkill = date("d-M-Y", strtotime($tampil['tglkill']));
                    $tglboning = date("d-M-Y", strtotime($tampil['tglboning']));
                  ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td><?= $tampil['batchboning']; ?></td>
                      <td><?= $tglkill; ?></td>
                      <td><?= $tglboning; ?></td>
                      <td><?= $tampil['nmsupplier']; ?></td>
                      <td><?= $tampil['qtysapi']; ?></td>
                      <td>1000 Kg</td>
                      <td><?= $tampil['catatan']; ?></td>
                      <td><a href="#">LIHAT</a> | <a href="#">EDIT</a> | <a href="#">BARCODE</a></td>
                    </tr>
                  <?php
                    $no++;
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th colspan="4" class="text-right">TOTAL</th>
                    <th><?= $total_sapi; ?></th>
                    <th colspan="3">xxx</th>
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

  <?php include "../assets/html/footer.php" ?>