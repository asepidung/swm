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
                                                <?php if ($tampil['kunci'] == 1) { ?>
                                                    <!-- Tombol Unapprove jika kunci = 1 -->
                                                    <a class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Unapprove" href="unapproverepack.php?id=<?= htmlspecialchars($idrepack) ?>">
                                                        <i class="fas fa-calendar-times"></i>
                                                    </a>
                                                <?php } else { ?>
                                                    <!-- Tombol Approve jika kunci = 0 -->
                                                    <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Approve" href="approverepack.php?id=<?= htmlspecialchars($idrepack) ?>">
                                                        <i class="far fa-calendar-check"></i>
                                                    </a>
                                                <?php } ?>

                                                <!-- Tombol Detail Bahan -->
                                                <?php
                                                // Jika tombol Detail Bahan tidak aktif, hanya akan aktif jika kunci = 0
                                                $disabledBahan = ($tampil['kunci'] == 1) ? 'disabled' : '';  // Disable tombol jika kunci = 1
                                                ?>

                                                <a href="detailbahan.php?id=<?= $idrepack ?>&stat=ready" class="btn btn-sm btn-warning <?= $disabledBahan; ?>" title="Detail Bahan" <?= $disabledBahan ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                                                    <i class="fas fa-box-open"></i>
                                                </a>

                                                <!-- Tombol Detail Hasil -->
                                                <?php
                                                // Query untuk memeriksa apakah ada data di tabel detailbahan
                                                $queryDetailBahan = "SELECT COUNT(*) AS total FROM detailbahan WHERE idrepack = $idrepack";
                                                $resultDetailBahan = mysqli_query($conn, $queryDetailBahan);
                                                $rowDetailBahan = mysqli_fetch_assoc($resultDetailBahan);

                                                // Cek jika ada data di detailbahan dan kunci = 0, maka tombol tidak di-disable
                                                $disabledHasil = ($rowDetailBahan['total'] == 0 || $tampil['kunci'] == 1) ? 'disabled' : '';  // Disable tombol jika kunci = 1 atau detailbahan kosong
                                                ?>

                                                <a href="detailhasil.php?id=<?= $idrepack ?>" class="btn btn-sm btn-success <?= $disabledHasil; ?>" title="Detail Hasil" <?= $disabledHasil ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                                                    <i class="fas fa-tags"></i>
                                                </a>

                                                <!-- Tombol Lihat Proses Repack -->
                                                <a href="lihatrepack.php?id=<?= $idrepack ?>" class="btn btn-sm btn-primary" title="Lihat Proses Repack">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Tombol Edit Proses Repack -->
                                                <a href="editrepack.php?id=<?= $idrepack ?>" class="btn btn-sm btn-dark" title="Edit Proses Repack" <?= $tampil['kunci'] == 1 ? 'disabled' : ''; ?>>
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Tombol Hapus Proses Repack -->
                                                <?php
                                                $query = "SELECT COUNT(*) AS total FROM detailbahan WHERE idrepack = $idrepack";
                                                $result_detailbahan = mysqli_query($conn, $query);
                                                $row_detailbahan = mysqli_fetch_assoc($result_detailbahan);

                                                $query = "SELECT COUNT(*) AS total FROM detailhasil WHERE idrepack = $idrepack AND is_deleted = 0";
                                                $result_detailhasil = mysqli_query($conn, $query);
                                                $row_detailhasil = mysqli_fetch_assoc($result_detailhasil);

                                                if ($row_detailbahan['total'] == 0 && $row_detailhasil['total'] == 0 && $tampil['kunci'] == 0) {
                                                    echo '<a href="deleterepack.php?id=' . $idrepack . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah kamu yakin ingin menghapus repack ini?\')" title="Hapus Proses Repack">
                                                                <i class="fas fa-trash"></i>
                                                            </a>';
                                                } else {
                                                    echo '<a href="#" class="btn btn-sm btn-secondary" title="Tidak Bisa Dihapus">
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

<!-- Styling untuk Tabel -->
<style>
    table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }

    .btn-tooltip {
        position: relative;
    }

    .btn-tooltip:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px;
        background-color: #333;
        color: #fff;
        font-size: 12px;
        border-radius: 5px;
        white-space: nowrap;
    }
</style>