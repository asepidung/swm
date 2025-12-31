<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="newrepack.php" class="btn btn-info">
                        <i class="fas fa-plus-circle"></i> Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
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
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $q = mysqli_query($conn, "
                                SELECT r.*, u.fullname
                                FROM repack r
                                JOIN users u ON r.idusers = u.idusers
                                WHERE r.is_deleted = 0
                                ORDER BY r.idrepack DESC
                            ");

                            while ($r = mysqli_fetch_assoc($q)):
                                $idrepack = (int)$r['idrepack'];

                                // total bahan
                                $qb = mysqli_query($conn, "
                                    SELECT SUM(qty) AS total 
                                    FROM detailbahan 
                                    WHERE idrepack = $idrepack
                                ");
                                $totalBahan = (float)(mysqli_fetch_assoc($qb)['total'] ?? 0);

                                // total hasil
                                $qh = mysqli_query($conn, "
                                    SELECT SUM(qty) AS total 
                                    FROM detailhasil 
                                    WHERE idrepack = $idrepack AND is_deleted = 0
                                ");
                                $totalHasil = (float)(mysqli_fetch_assoc($qh)['total'] ?? 0);

                                $balance = $totalHasil - $totalBahan;

                                // cek syarat approve
                                $bolehApprove = ($totalBahan > 0 && $totalHasil > 0 && $r['kunci'] == 1);

                                // disable states
                                $lock = (int)$r['kunci'];
                            ?>
                                <tr class="text-center align-middle">
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($r['norepack']); ?></td>
                                    <td><?= date('d-M-y', strtotime($r['tglrepack'])); ?></td>

                                    <td class="text-right"><?= number_format($totalBahan, 2); ?></td>
                                    <td class="text-right"><?= number_format($totalHasil, 2); ?></td>

                                    <td class="text-right">
                                        <?= $balance < 0
                                            ? "<span class='text-danger'>" . number_format($balance, 2) . "</span>"
                                            : number_format($balance, 2); ?>
                                    </td>

                                    <td class="text-left"><?= htmlspecialchars($r['note']); ?></td>

                                    <!-- STATUS -->
                                    <td>
                                        <?php
                                        if ($lock == 0) {
                                            echo '<span class="badge badge-secondary">On Process</span>';
                                        } elseif ($lock == 1) {
                                            echo '<span class="badge badge-warning">Ready</span>';
                                        } else {
                                            echo '<span class="badge badge-dark">Locked</span>';
                                        }
                                        ?>
                                    </td>

                                    <!-- AKSI -->
                                    <td>
                                        <div class="btn-group btn-group-sm">

                                            <!-- APPROVE -->
                                            <?php if ($bolehApprove): ?>
                                                <a href="approverepack.php?id=<?= $idrepack ?>"
                                                    class="btn btn-outline-primary"
                                                    title="Approve">
                                                    <i class="far fa-calendar-check"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-outline-secondary"
                                                    title="Belum Bisa Approve"
                                                    onclick="alert('Approve membutuhkan bahan dan hasil!')">
                                                    <i class="far fa-calendar-check"></i>
                                                </button>
                                            <?php endif; ?>

                                            <!-- DETAIL BAHAN -->
                                            <a href="detailbahan.php?id=<?= $idrepack ?>"
                                                class="btn btn-outline-warning">
                                                <i class="fas fa-box-open"></i>
                                            </a>

                                            <!-- DETAIL HASIL -->
                                            <a href="detailhasil.php?id=<?= $idrepack ?>"
                                                class="btn btn-outline-success"
                                                <?= ($totalBahan == 0 ? 'disabled style="opacity:.5"' : '') ?>>
                                                <i class="fas fa-tags"></i>
                                            </a>

                                            <!-- LIHAT -->
                                            <a href="lihatrepack.php?id=<?= $idrepack ?>"
                                                class="btn btn-outline-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- EDIT -->
                                            <a href="editrepack.php?id=<?= $idrepack ?>"
                                                class="btn btn-outline-dark"
                                                <?= ($lock == 2 ? 'disabled style="opacity:.5"' : '') ?>>
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- DELETE -->
                                            <?php if ($lock == 0 && $totalBahan == 0): ?>
                                                <a href="deleterepack.php?id=<?= $idrepack ?>"
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('Hapus data repack ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-outline-danger" disabled style="opacity:.5">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.title = "Data Repack";
</script>

<?php include "../footer.php"; ?>