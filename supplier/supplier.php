<?php
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
          <a href="newsupplier.php"><button type="button" class="btn btn-info"> Supplier Baru</button></a>
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
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>Jenis Usaha</th>
                    <th>Telepon</th>
                    <th>NPWP</th>
                    <th>Hutang</th>
                    <th>Tinjau</th>
                  </tr>
                </thead>
                <tbody>
                  </tr>
                  <?php
                  // $query_total_sapi = "SELECT SUM(qtysapi) AS total_sapi FROM boning";
                  // $result_total_sapi = mysqli_query($conn, $query_total_sapi);
                  // $row_total_sapi = mysqli_fetch_assoc($result_total_sapi);
                  // $total_sapi = $row_total_sapi['total_sapi'];
                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT * FROM supplier ORDER BY nmsupplier ASC");
                  while ($tampil = mysqli_fetch_array($ambildata)) {
                  ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td><?= $tampil['nmsupplier']; ?></td>
                      <td><?= $tampil['alamat']; ?></td>
                      <td><?= $tampil['jenis_usaha']; ?></td>
                      <td><?= $tampil['telepon']; ?></td>
                      <!-- <td><?= $tampil['email']; ?></td> -->
                      <td><?= $tampil['npwp']; ?></td>
                      <td>
                        <!-- <?= $tampil['ttlutang']; ?> -->
                      </td>
                      <!-- <td><?= $tampil['catatan']; ?></td> -->
                      <td class="text-center"><a href="#">EDIT</a> | <a href="#">HAPUS</a></td>
                    </tr>
                  <?php
                    $no++;
                  }
                  ?>
                </tbody>
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

  <?php include "../footer.php" ?>