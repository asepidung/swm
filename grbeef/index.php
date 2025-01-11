<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location: ../verifications/login.php");
    exit();
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <a href="draft.php"><button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Draft</button></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>GR Number</th>
                                        <th>Receiving Date</th>
                                        <th>Supplier</th>
                                        <th>ID Number</th>
                                        <th>Note</th>
                                        <th>Made By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $ambildata = mysqli_query($conn, "
                                        SELECT grbeef.*, supplier.nmsupplier, pobeef.idpo, users.fullname 
                                        FROM grbeef
                                        LEFT JOIN pobeef ON grbeef.idpo = pobeef.idpo
                                        JOIN supplier ON grbeef.idsupplier = supplier.idsupplier
                                        LEFT JOIN users ON grbeef.idusers = users.idusers
                                        WHERE grbeef.is_deleted = 0
                                        ORDER BY grbeef.idgr DESC
                                    ");

                                    if (!$ambildata) {
                                        die("Query error: " . mysqli_error($conn));
                                    }

                                    if (mysqli_num_rows($ambildata) > 0) {
                                        while ($tampil = mysqli_fetch_array($ambildata)) {
                                            $idgr = $tampil['idgr'];
                                            $idpo = $tampil['idpo'];
                                            $fullname = $tampil['fullname'];
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <td class="text-center"><?= htmlspecialchars($tampil['grnumber']); ?></td>
                                                <td class="text-center"><?= htmlspecialchars(date("d-M-y", strtotime($tampil['receivedate']))); ?></td>
                                                <td><?= htmlspecialchars($tampil['nmsupplier']); ?></td>
                                                <td><?= htmlspecialchars($tampil['idnumber'] ?? '-'); ?></td>
                                                <td><?= htmlspecialchars($tampil['note'] ?? '-'); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($fullname); ?></td>
                                                <td class="text-center">
                                                    <a href="grscan.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-warning" title="Scan">
                                                        <i class="fas fa-barcode"></i>
                                                    </a>
                                                    <a href="grdetail.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-primary" title="Label">
                                                        <i class="fas fa-tag"></i>
                                                    </a>
                                                    <a href="view.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-success" title="Lihat">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                    <a href="delete.php?idgr=<?= $idgr; ?>&idpo=<?= $idpo; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>Data tidak ditemukan</td></tr>";
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
    document.title = "Goods Receipt List";
</script>
<?php
include "../footer.php";
?>