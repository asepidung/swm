<?php
require "../verifications/auth.php";
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
                    <a href="newrepack.php">
                        <button type="button" class="btn btn-info">
                            <i class="fas fa-plus-circle"></i> Baru
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
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
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $ambildata = mysqli_query($conn, "
                                        SELECT repack.*, users.fullname
                                        FROM repack 
                                        INNER JOIN users ON repack.idusers = users.idusers 
                                        WHERE repack.is_deleted = 0 
                                        ORDER BY idrepack DESC
                                    ");

                                    while ($tampil = mysqli_fetch_array($ambildata)):
                                        include "hitungtotal.php";
                                        $idrepack = (int)$tampil['idrepack'];

                                        // Total bahan
                                        $qBahan = mysqli_query($conn, "SELECT SUM(qty) AS total_bahan FROM detailbahan WHERE idrepack = $idrepack");
                                        $totalBahan = mysqli_fetch_assoc($qBahan)['total_bahan'] ?? 0;

                                        // Total hasil
                                        $qHasil = mysqli_query($conn, "SELECT SUM(qty) AS total_hasil FROM detailhasil WHERE idrepack = $idrepack AND is_deleted = 0");
                                        $totalHasil = mysqli_fetch_assoc($qHasil)['total_hasil'] ?? 0;

                                        $lost = $totalHasil - $totalBahan;

                                        // Cek apakah sudah ada data raw_usage REPACK untuk idrepack ini
                                        $qCekUsage = mysqli_query($conn, "SELECT COUNT(*) AS total_usage FROM raw_usage WHERE sumber = 'REPACK' AND idsumber = $idrepack");
                                        $hasUsage = mysqli_fetch_assoc($qCekUsage)['total_usage'] ?? 0;

                                        // Cek ada bahan (untuk kontrol tombol Detail Hasil & Hapus)
                                        $qDetail = mysqli_query($conn, "SELECT COUNT(*) AS total FROM detailbahan WHERE idrepack = $idrepack");
                                        $adaBahan = mysqli_fetch_assoc($qDetail)['total'] ?? 0;

                                        // Disabled states
                                        $disabledBahan   = ($tampil['kunci'] >= 1) ? 'disabled style="opacity:0.5;"' : '';
                                        $disabledHasil   = ($adaBahan == 0 || $tampil['kunci'] >= 1) ? 'disabled style="opacity:0.5;"' : '';
                                        $disabledUsage   = ($tampil['kunci'] >= 1) ? 'disabled style="pointer-events:none;opacity:0.5;"' : '';
                                    ?>
                                        <tr class="text-center align-middle">
                                            <td><?= $no++; ?></td>

                                            <!-- Norepack sebagai link jika sudah ada raw_usage -->
                                            <td>
                                                <?php if ($hasUsage > 0): ?>
                                                    <a href="laporan_rawusage_repack.php?id=<?= $idrepack ?>"
                                                        class="font-weight-bold text-primary"
                                                        title="Lihat Laporan Pemakaian Bahan">
                                                        <?= htmlspecialchars($tampil['norepack']); ?>
                                                        <i class="fas fa-link small"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <?= htmlspecialchars($tampil['norepack']); ?>
                                                <?php endif; ?>
                                            </td>

                                            <td><?= date("d-M-y", strtotime($tampil['tglrepack'])); ?></td>
                                            <td class="text-right"><?= number_format($totalBahan, 2); ?></td>
                                            <td class="text-right"><?= number_format($totalHasil, 2); ?></td>
                                            <td class="text-right">
                                                <?= ($lost < 0) ? "<span class='text-danger'>" . number_format($lost, 2) . "</span>" : number_format($lost, 2); ?>
                                            </td>
                                            <td class="text-left"><?= htmlspecialchars($tampil['note']); ?></td>

                                            <!-- STATUS -->
                                            <td>
                                                <?php
                                                $idusers = $_SESSION['idusers'] ?? 0;
                                                if ($tampil['kunci'] == 0) {
                                                    echo '<span class="badge badge-secondary">On Process</span>';
                                                } elseif ($tampil['kunci'] == 1) {
                                                    echo ($idusers == 1 || $idusers == 2)
                                                        ? '<a href="lockrepack.php?id=' . $idrepack . '" class="badge badge-warning text-dark" title="Lock">LOCK</a>'
                                                        : '<span class="badge badge-success">Approved</span>';
                                                } elseif ($tampil['kunci'] == 2) {
                                                    echo ($idusers == 1 || $idusers == 2)
                                                        ? '<a href="unlockrepack.php?id=' . $idrepack . '" class="badge badge-danger" title="Unlock">LOCKED</a>'
                                                        : '<span class="badge badge-dark">Locked</span>';
                                                }
                                                ?>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Repack">

                                                    <!-- Approve / Unapprove -->
                                                    <?php if ($tampil['kunci'] == 1): ?>
                                                        <!-- Tombol Unapprove -->
                                                        <a href="unapproverepack.php?id=<?= $idrepack ?>"
                                                            class="btn btn-outline-danger"
                                                            title="Unapprove">
                                                            <i class="fas fa-calendar-times"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <?php if ($tampil['kunci'] == 2): ?>
                                                            <!-- Sudah Locked -->
                                                            <button class="btn btn-outline-secondary" disabled style="opacity:0.5;">
                                                                <i class="far fa-calendar-check"></i>
                                                            </button>
                                                        <?php elseif ($hasUsage == 0): ?>
                                                            <!-- Belum ada raw_usage -->
                                                            <button type="button"
                                                                class="btn btn-outline-primary"
                                                                title="Approve"
                                                                onclick="alert('Tidak dapat Approve karena pemakaian bahan belum dibuat untuk Repack ini!')">
                                                                <i class="far fa-calendar-check"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <!-- Boleh Approve -->
                                                            <a href="approverepack.php?id=<?= $idrepack ?>"
                                                                class="btn btn-outline-primary"
                                                                title="Approve">
                                                                <i class="far fa-calendar-check"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <!-- Detail Bahan -->
                                                    <a href="detailbahan.php?id=<?= $idrepack ?>&stat=ready"
                                                        class="btn btn-outline-warning"
                                                        title="Detail Bahan" <?= $disabledBahan; ?>>
                                                        <i class="fas fa-box-open"></i>
                                                    </a>

                                                    <!-- Detail Hasil -->
                                                    <a href="detailhasil.php?id=<?= $idrepack ?>"
                                                        class="btn btn-outline-success"
                                                        title="Detail Hasil" <?= $disabledHasil; ?>>
                                                        <i class="fas fa-tags"></i>
                                                    </a>

                                                    <!-- Pemakaian Bahan -->
                                                    <a href="rawusage_repack.php?id=<?= $idrepack ?>"
                                                        class="btn btn-outline-info"
                                                        title="Material Bahan" <?= $disabledUsage; ?>>
                                                        <i class="fas fa-cogs"></i>
                                                    </a>

                                                    <!-- Lihat -->
                                                    <a href="lihatrepack.php?id=<?= $idrepack ?>"
                                                        class="btn btn-outline-secondary"
                                                        title="Lihat Proses">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Edit -->
                                                    <a href="editrepack.php?id=<?= $idrepack ?>"
                                                        class="btn btn-outline-dark"
                                                        title="Edit"
                                                        <?= ($tampil['kunci'] == 2) ? 'disabled style="opacity:0.5;"' : ''; ?>>
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Hapus -->
                                                    <?php if ($adaBahan == 0 && $tampil['kunci'] == 0): ?>
                                                        <a href="deleterepack.php?id=<?= $idrepack ?>"
                                                            class="btn btn-outline-danger"
                                                            onclick="return confirm('Yakin ingin menghapus repack ini?')"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-outline-danger" disabled title="Tidak Bisa Dihapus" style="opacity:0.5;">
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
            </div>
        </div>
    </section>
</div>

<script>
    document.title = "Data Repack";
</script>
<?php include "../footer.php"; ?>