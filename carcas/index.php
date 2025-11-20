<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function tgl($d)
{
    return $d ? date('d-M-Y', strtotime($d)) : '-';
}

// ====================
// Query utama CARCASE
// ====================
$sql = "
SELECT
    c.idcarcase,
    c.killdate,
    s.nmsupplier,
    u.fullname,
    COALESCE(SUM(cd.berat), 0)                        AS total_berat,
    COUNT(cd.iddetail)                                AS total_eartag,
    COALESCE(SUM(cd.carcase1 + cd.carcase2), 0)       AS total_carcase,
    COALESCE(SUM(cd.hides + cd.tail), 0)              AS total_carcase_tail,
    COALESCE(SUM(cd.hides), 0)                        AS total_hides,
    COALESCE(SUM(cd.tail), 0)                         AS total_tails,
    -- ambil PO number dari hubungan melalui carcasedetail -> weight_cattle_detail -> weight_cattle -> cattle_receive -> pocattle
    MIN(p.nopo) AS nopo
FROM carcase c
LEFT JOIN supplier s
       ON s.idsupplier = c.idsupplier
LEFT JOIN users u
       ON u.idusers = c.idusers
LEFT JOIN carcasedetail cd
       ON cd.idcarcase = c.idcarcase

-- join ke weight_cattle_detail (kemungkinan null)
LEFT JOIN weight_cattle_detail wcd
       ON wcd.idweighdetail = cd.idweightdetail
LEFT JOIN weight_cattle w
       ON w.idweigh = wcd.idweigh
LEFT JOIN cattle_receive r
       ON r.idreceive = w.idreceive
LEFT JOIN pocattle p
       ON p.idpo = r.idpo

WHERE c.is_deleted = 0
GROUP BY
    c.idcarcase,
    c.killdate,
    s.nmsupplier,
    u.fullname
ORDER BY
    c.killdate DESC,
    c.idcarcase DESC
";

$result = $conn->query($sql);
if (!$result) {
    die("Query error: " . e($conn->error));
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-12 col-md-2 mb-2">
                    <a href="draft.php" class="btn btn-sm btn-outline-primary btn-block">
                        <i class="fas fa-file-alt"></i> Draft from Weighing
                    </a>
                </div>

            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="example1" class="table table-bordered table-striped table-sm text-right">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>No PO</th>
                                        <th>Killing Date</th>
                                        <th>Supplier</th>
                                        <th>Berat &Sigma;</th>
                                        <th>Head &Sigma;</th>
                                        <th>Carcase &Sigma;</th>
                                        <th>Offal</th>
                                        <th>Hides &Sigma;</th>
                                        <th>Tails &Sigma;</th>
                                        <th>Carcase %</th>
                                        <th>User</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        $no = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $total_berat   = (float)$row['total_berat'];
                                            $total_carcase = (float)$row['total_carcase'];
                                            $total_tails   = (float)$row['total_tails'];

                                            // Offal = total carcase + total tails (tanpa hides)
                                            $offal = $total_carcase + $total_tails;

                                            $carcase_percentage = 0;
                                            if ($total_berat > 0) {
                                                $carcase_percentage = ($total_carcase / $total_berat) * 100;
                                            }
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>

                                                <!-- tampilkan No PO, fallback '-' jika null -->
                                                <td class="text-center"><?= e($row['nopo'] ?? '-') ?></td>

                                                <td class="text-center"><?= e(tgl($row['killdate'])) ?></td>
                                                <td class="text-left"><?= e($row['nmsupplier'] ?? '-') ?></td>
                                                <td><?= number_format($total_berat, 2) ?></td>
                                                <td class="text-center"><?= (int)$row['total_eartag'] ?></td>
                                                <td><?= number_format($total_carcase, 2) ?></td>

                                                <!-- kolom Offal pakai rumus baru -->
                                                <td><?= number_format($offal, 2) ?></td>

                                                <td><?= number_format((float)$row['total_hides'], 2) ?></td>
                                                <td><?= number_format($total_tails, 2) ?></td>
                                                <td><?= number_format($carcase_percentage, 2) ?></td>
                                                <td class="text-left"><?= e($row['fullname'] ?? '-') ?></td>
                                                <td class="text-center">
                                                    <a href="view.php?id=<?= (int)$row['idcarcase'] ?>" class="btn btn-info btn-sm" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit.php?id=<?= (int)$row['idcarcase'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="delete.php?id=<?= (int)$row['idcarcase'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-minus-square"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='13' class='text-center text-muted'>Tidak ada data ditemukan</td></tr>";
                                    }
                                    ?>
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
    document.title = "Data Carcas";
</script>

<?php
include "../footer.php";
?>