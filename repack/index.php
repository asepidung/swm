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
          <a href="newrepack.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
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
                    <th>repack Number</th>
                    <th>repack Date</th>
                    <th>Bahan</th>
                    <th>Hasil</th>
                    <th>Susut</th>
                    <th>Made By</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $ambildata = mysqli_query($conn, "SELECT a.*, u.fullname FROM repack a JOIN users u ON a.idusers = u.idusers ORDER BY norepack DESC; ");
                  while ($tampil = mysqli_fetch_array($ambildata)) {
                    $idrepack = $tampil['idrepack'];
                  ?>
                    <tr data-widget="expandable-table" aria-expanded="false">
                      <td class="text-center"><?= $no; ?></td>
                      <td class="text-center"><?= $tampil['norepack']; ?></td>
                      <td class="text-center"><?= date("d-M-y", strtotime($tampil['tglrepack'])); ?></td>
                      <td class="text-right"><?= number_format($tampil['bahanweight'], 2); ?></td>
                      <td class="text-right"><?= number_format($tampil['hasilweight'], 2); ?></td>
                      <td><?= $tampil['proses']; ?></td>
                      <td class="text-center"><?= $tampil['fullname']; ?></td>
                      <td class="text-center">
                        <a href="editrepack.php?idrepack=<?= $tampil['idrepack']; ?>" class="mx-auto p-2 text-succes">
                          <i class="fas fa-pencil-alt"></i> EDIT
                        </a>
                        <a href="deleterepack.php?idrepack=<?= $tampil['idrepack']; ?>" class="mx-auto p-2 text-danger" onclick="return confirm('apakah anda yakin ingin menghapus repack ini?')"><i class="fas fa-times"></i> DELETE</a>
                      </td>
                    </tr>
                    <tr class="expandable-body">
                      <td colspan="8">
                        <?php
                        $query_detail = "SELECT repackdetail.*, barang.kdbarang, barang.nmbarang, grade.nmgrade
                                            FROM repackdetail
                                            INNER JOIN grade ON repackdetail.idgrade = grade.idgrade
                                            INNER JOIN barang ON repackdetail.idbarang = barang.idbarang
                                            WHERE repackdetail.idrepack = '$idrepack'";
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
  document.title = "repack List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>