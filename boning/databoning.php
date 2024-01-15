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
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1 class="m-0">DATA BONING</h1> -->
          <a href="newboning.php"><button type="button" class="btn btn-info"><i class="fas fa-plus-circle"></i> Baru</button></a>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
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
                    <th>MBR</th>
                    <th>Catatan</th>
                    <th>AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query_total_sapi = "SELECT SUM(qtysapi) AS total_sapi FROM boning";
                  $result_total_sapi = mysqli_query($conn, $query_total_sapi);
                  $row_total_sapi = mysqli_fetch_assoc($result_total_sapi);
                  $total_sapi = $row_total_sapi['total_sapi'];

                  $query_total_berat_keseluruhan = "SELECT SUM(qty) AS total_berat_keseluruhan FROM labelboning";
                  $result_total_berat_keseluruhan = mysqli_query($conn, $query_total_berat_keseluruhan);
                  $row_total_berat_keseluruhan = mysqli_fetch_assoc($result_total_berat_keseluruhan);
                  $total_berat_keseluruhan = $row_total_berat_keseluruhan['total_berat_keseluruhan'];

                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT b.*, p.nmsupplier FROM boning b JOIN supplier p ON b.idsupplier = p.idsupplier ORDER BY b.batchboning DESC");
                  while ($tampil = mysqli_fetch_array($ambildata)) {

                    $query_total_weight = "SELECT SUM(qty) AS total_weight FROM labelboning WHERE idboning = " . $tampil['idboning'];
                    $result_total_weight = mysqli_query($conn, $query_total_weight);
                    $row_total_weight = mysqli_fetch_assoc($result_total_weight);
                    $total_weight = $row_total_weight['total_weight'];

                    $avg_weight_per_sapi = 0;
                    if ($tampil['qtysapi'] > 0) {
                      $avg_weight_per_sapi = $total_weight / $tampil['qtysapi'];
                    }
                  ?>
                    <tr class="text-center">
                      <td><?= $no; ?></td>
                      <td><?= $tampil['batchboning']; ?></td>
                      <td><?= date("d-M-Y", strtotime($tampil['tglboning'])); ?></td>
                      <td class="text-left"><?= $tampil['nmsupplier']; ?></td>
                      <td><?= $tampil['qtysapi']; ?></td>
                      <td class="text-right"><?= number_format($total_weight, 2); ?></td>
                      <td class="text-right"><?= number_format($avg_weight_per_sapi, 2); ?></td>
                      <td class="text-left"><?= $tampil['keterangan']; ?></td>
                      </button>
                      <td>
                        <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Buat Label" onclick="window.location.href='labelboning.php?id=<?php echo $tampil['idboning']; ?>'">
                          <i class="fas fa-barcode"></i>
                        </a>
                        <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Lihat Hasil Boning" onclick="window.location.href='boningdetail.php?id=<?php echo $tampil['idboning']; ?>'">
                          <i class="fas fa-eye">
                          </i>
                        </a>
                        <a class="btn btn-info btn-sm" href="editdataboning.php?idboning=<?= $tampil['idboning'] ?>">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a class="btn btn-danger btn-sm" href="deletedataboning.php?idboning=<?= $tampil['idboning'] ?>" onclick="return confirm('Apakah kamu yakin ingin menghapus data boning ini?')">
                          <i class="fas fa-minus-circle"></i>
                        </a>
                      </td>
                    </tr>
                  <?php
                    $no++;
                  }
                  ?>
                </tbody>
                <tfoot>
                  <th colspan="4"></th>
                  <th class="text-center"><?= number_format($total_sapi); ?> </td>
                  <th class="text-right"><?= number_format($total_berat_keseluruhan, 2); ?></td>
                  <th colspan="3"></th>
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
  // require "../footnote.php";
  include "../footer.php" ?>