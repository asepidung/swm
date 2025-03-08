<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            // Query data dari tabel po
                            $sql = "SELECT p.idpo, p.nopo, p.duedate, p.note, p.creatime, p.xamount, p.taxrp, p.tax, p.top,
                                           r.norequest, s.nmsupplier
                                    FROM po p
                                    LEFT JOIN request r ON p.idrequest = r.idrequest
                                    LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
                                    WHERE p.is_deleted = 0
                                    ORDER BY p.idpo DESC";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            ?>

                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>PO Number</th>
                                        <th>Request Number</th>
                                        <th>Supplier</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Tax</th>
                                        <th>Terms</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php
                                    if ($result->num_rows > 0) {
                                        $i = 1;
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= htmlspecialchars($row['nopo']) ?></td>
                                                <td><?= htmlspecialchars($row['norequest']) ?></td>
                                                <td><?= htmlspecialchars($row['nmsupplier']) ?></td>
                                                <td><?= htmlspecialchars(date("D, d-M-y", strtotime($row['duedate']))) ?></td>
                                                <td><?= number_format($row['xamount'], 2) ?></td>
                                                <td><?= htmlspecialchars($row['tax']) ?> (<?= number_format($row['taxrp'], 2) ?>)</td>
                                                <td><?= htmlspecialchars($row['top']) ?> Hari</td>
                                                <td class="text-left"><?= htmlspecialchars($row['note']) ?></td>
                                                <td>
                                                    <a href="view.php?id=<?= intval($row['idpo']) ?>" class='btn btn-info btn-sm' title="View PO"><i class="fas fa-eye"></i></a>
                                                    <a href="delete.php?id=<?= intval($row['idpo']) ?>"
                                                        class="btn btn-danger btn-sm"
                                                        title="Delete PO"
                                                        onclick="return confirm('Are you sure you want to delete this PO?');">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                            $i++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' class='text-center'>No data available</td></tr>";
                                    }
                                    $stmt->close();
                                    $conn->close();
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
    document.title = "PO List";
</script>

<?php
include "../footer.php";
?>