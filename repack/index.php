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
                    <a href="newrepack.php"><button type="button" class="btn btn-info"><i class="fas fa-plus-circle"></i> Baru</button></a>
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
                                        <th>No Proses</th>
                                        <th>Tgl Proses</th>
                                        <th>Bahan</th>
                                        <th>Hasil</th>
                                        <th>Balance</th>
                                        <th>Catatan</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $ambildata = mysqli_query($conn, "SELECT repack.*, users.fullname
                                    FROM repack 
                                    INNER JOIN users ON repack.idusers = users.idusers 
                                    WHERE repack.is_deleted = 0 
                                    ORDER BY idrepack DESC");

                                while ($tampil = mysqli_fetch_array($ambildata)) {
                                    include "hitungtotal.php";
                                    $idrepack = $tampil['idrepack'];

                                    // Hitung total bahan dengan kondisi is_deleted = 0
                                    $queryBahan = "SELECT SUM(qty) AS total_bahan 
                                                   FROM detailbahan 
                                                   WHERE idrepack = $idrepack";
                                    $resultBahan = mysqli_query($conn, $queryBahan);
                                    $rowTotalBahan = mysqli_fetch_assoc($resultBahan);

                                    // Hitung total hasil dengan kondisi is_deleted = 0
                                    $queryHasil = "SELECT SUM(qty) AS total_hasil 
                                                   FROM detailhasil 
                                                   WHERE idrepack = $idrepack AND is_deleted = 0";
                                    $resultHasil = mysqli_query($conn, $queryHasil);
                                    $rowTotalHasil = mysqli_fetch_assoc($resultHasil);

                                    $lost = ($rowTotalHasil['total_hasil'] ?? 0) - ($rowTotalBahan['total_bahan'] ?? 0);
                                ?>
                                    <tr class="text-center">
                                        <td><?= $no; ?></td>
                                        <td><?= $tampil['norepack']; ?></td>
                                        <td><?= date("d-M-y", strtotime($tampil['tglrepack'])); ?></td>
                                        <td class="text-right"><?= number_format($rowTotalBahan['total_bahan'] ?? 0, 2); ?></td>
                                        <td class="text-right"><?= number_format($rowTotalHasil['total_hasil'] ?? 0, 2); ?></td>
                                        <td class="text-right">
                                            <?php if ($lost < 0) { ?>
                                                <span class="text-danger"><?= number_format($lost, 2); ?></span>
                                            <?php } else {
                                                echo number_format($lost, 2);
                                            } ?>
                                        </td>
                                        <td class="text-left"><?= $tampil['note']; ?></td>
                                        <td>
                                            <a href="detailbahan.php?id=<?= $idrepack ?>&stat=ready" class="btn btn-sm btn-warning">
                                                <i class="fas fa-box-open"></i>
                                            </a>
                                            <a href="detailhasil.php?id=<?= $idrepack ?>" class="btn btn-sm btn-success">
                                                <i class="fas fa-tags"></i>
                                            </a>
                                            <a href="printrepack.php?id=<?= $idrepack ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="editrepack.php?id=<?= $idrepack ?>" class="btn btn-sm btn-dark">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php
                                            $query = "SELECT COUNT(*) AS total FROM detailbahan WHERE idrepack = $idrepack";
                                            $result_detailbahan = mysqli_query($conn, $query);
                                            $row_detailbahan = mysqli_fetch_assoc($result_detailbahan);

                                            $query = "SELECT COUNT(*) AS total FROM detailhasil WHERE idrepack = $idrepack AND is_deleted = 0";
                                            $result_detailhasil = mysqli_query($conn, $query);
                                            $row_detailhasil = mysqli_fetch_assoc($result_detailhasil);

                                            if ($row_detailbahan['total'] == 0 && $row_detailhasil['total'] == 0) {
                                                echo '<a href="deleterepack.php?id=' . $idrepack . '" 
                                                          class="btn btn-sm btn-danger" 
                                                          onclick="return confirm(\'Apakah kamu yakin ingin menghapus repack ini?\')">
                                                          <i class="fas fa-trash"></i>
                                                      </a>';
                                            } else {
                                                echo '<a href="#" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-trash"></i>
                                                  </a>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php $no++;
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
</div>
<!-- /.content-wrapper -->

<script>
    document.title = "Data Repack";
</script>
<?php
include "../footer.php";
?>
