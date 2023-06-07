<?php
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1 class="m-0">DATA BONING</h1> -->
          <a href="newboning.php"><button type="button" class="btn btn-info"> Buat Data Boning Baru</button></a>
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
                <thead class="text-center">
                  <tr>
                    <th>#</th>
                    <th>Batch Number</th>
                    <th>Tgl Boning</th>
                    <th>Supplier</th>
                    <th>Jml Sapi</th>
                    <th>Ttl Weight</th>
                    <th>AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query_total_sapi = "SELECT SUM(qtysapi) AS total_sapi FROM boning";
                  $result_total_sapi = mysqli_query($conn, $query_total_sapi);
                  $row_total_sapi = mysqli_fetch_assoc($result_total_sapi);
                  $total_sapi = $row_total_sapi['total_sapi'];
                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT b.*, p.nmsupplier FROM boning b JOIN supplier p ON b.idsupplier = p.idsupplier ORDER BY b.batchboning DESC");
                  while ($tampil = mysqli_fetch_array($ambildata)) {
                    $tglboning = date("d-M-Y", strtotime($tampil['tglboning']));
                  ?>
                    <tr class="text-center">
                      <td><?= $no; ?></td>
                      <td><?= $tampil['batchboning']; ?></td>
                      <td><?= $tglboning; ?></td>
                      <td class="text-left"><?= $tampil['nmsupplier']; ?></td>
                      <td><?= $tampil['qtysapi']; ?></td>
                      <td><?= "EWIGHT TOAL"; ?></td>
                      </button>
                      <td class="text-center">
                        <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Buat Label" onclick="window.location.href='labelboning.php?id=<?php echo $tampil['idboning']; ?>'">
                          <i class="fas fa-barcode"></i>
                          Label
                        </a>
                        <a class="btn btn-primary btn-sm" href="#">
                          <i class="fas fa-eye">
                          </i>
                          View
                        </a>
                        <a class="btn btn-info btn-sm" href="#">
                          <i class="fas fa-pencil-alt">
                          </i>
                          Edit
                        </a>
                      </td>
                    </tr>
                  <?php
                    $no++;
                  }
                  ?>
                </tbody>
                <tfoot>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="text-center"><?= $total_sapi . " " . "Sapi"; ?> </td>
                  <td></td>
                  <td></td>
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
    document.title = "Data Boning";
  </script>
  <?php
  include "../footer.php" ?>