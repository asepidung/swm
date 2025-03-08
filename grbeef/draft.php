<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Query untuk mendapatkan data dari tabel pobeef dengan kondisi is_deleted = 0 dan stat = 0
$query = "SELECT p.idpo, s.nmsupplier, p.duedate, p.nopo, p.note, p.stat
          FROM pobeef p
          JOIN supplier s ON p.idsupplier = s.idsupplier
          WHERE p.is_deleted = 0 AND p.stat = 0";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Supplier</th>
                                        <th>Due Date</th>
                                        <th>PO Number</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>
                                       <td class="text-center">' . $counter++ . '</td>
                                       <td>' . htmlspecialchars($row['nmsupplier']) . '</td>
                                       <td class="text-center">' . htmlspecialchars($row['duedate']) . '</td>
                                       <td class="text-center">' . htmlspecialchars($row['nopo']) . '</td>
                                       <td>' . htmlspecialchars($row['note']) . '</td>
                                       <td class="text-center">
                                          <a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Buat GR" href="newgr.php?id=' . $row['idpo'] . '">
                                          Proses GR <i class="fas fa-truck"></i>
                                          </a>
                                       </td>
                                    </tr>';
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
    // Mengubah judul halaman web
    document.title = "DRAFT GR";
</script>
<?php
include "../footer.php";
?>