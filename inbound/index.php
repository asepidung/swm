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
      <div class="row">
        <div class="col">
          <a href="newinbound.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
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
              <table id="example1" class="table table-bordered table-hover table-sm">
                <thead class="text-center">
                  <tr>
                    <th>#</th>
                    <th>Inbound Number</th>
                    <th>Inbound Date</th>
                    <th>xBox</th>
                    <th>xQty</th>
                    <th>Event</th>
                    <th>Made By</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT a.*, u.fullname FROM inbound a JOIN users u ON a.idusers = u.idusers ORDER BY noinbound DESC; ");
                  while ($tampil = mysqli_fetch_array($ambildata)) {
                    $idinbound = $tampil['idinbound'];
                  ?>
                    <tr data-widget="expandable-table" aria-expanded="false">
                      <td class="text-center"><?= $no; ?></td>
                      <td class="text-center"><?= $tampil['noinbound']; ?></td>
                      <td class="text-center"><?= date("d-M-y", strtotime($tampil['tglinbound'])); ?></td>
                      <td class="text-right"><?= number_format($tampil['xbox'], 2); ?></td>
                      <td class="text-right"><?= number_format($tampil['xweight'], 2); ?></td>
                      <td><?= $tampil['proses']; ?></td>
                      <td class="text-center"><?= $tampil['fullname']; ?></td>
                      <td class="text-center">
                        <a href="editinbound.php?idinbound=<?= $tampil['idinbound']; ?>" class="mx-auto p-2 text-succes">
                          <i class="far fa-edit text-success"></i>
                        </a>
                        <a href="deleteinbound.php?idinbound=<?= $tampil['idinbound']; ?>" class="mx-auto p-2 text-danger" onclick="return confirm('apakah anda yakin ingin menghapus Inbound ini?')"> <i class="far fa-trash-alt text-danger"></i></a>
                      </td>
                    </tr>
                    <tr class="expandable-body">
                      <td colspan="8">
                        <?php
                        $query_detail = "SELECT inbounddetail.*, barang.kdbarang, barang.nmbarang, grade.nmgrade
                                            FROM inbounddetail
                                            INNER JOIN grade ON inbounddetail.idgrade = grade.idgrade
                                            INNER JOIN barang ON inbounddetail.idbarang = barang.idbarang
                                            WHERE inbounddetail.idinbound = '$idinbound'";
                        $result_detail = mysqli_query($conn, $query_detail);
                        while ($row_detail = mysqli_fetch_assoc($result_detail)) { ?>
                          <div class="row">
                            <div class="col-1">
                              <?= $row_detail['nmgrade']; ?>
                            </div>
                            <div class="col-2">
                              <?= $row_detail['nmbarang']; ?>
                            </div>
                            <div class="col-1">
                              <?= $row_detail['box']; ?>
                            </div>
                            <div class="col-2">
                              <?= $row_detail['weight']; ?>
                            </div>
                            <div class="col">
                              <?= $row_detail['notes']; ?>
                            </div>
                            <div class="w-100"></div>
                          </div>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php $no++;
                  } ?>
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
</div>
<!-- /.content-wrapper -->

<script>
  // Mengubah judul halaman web
  document.title = "inbound List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>