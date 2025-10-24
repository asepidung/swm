<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="col">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead class="text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Item Description</th>
                                            <th>Category</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        /*
                                         * Stok = total masuk (GR) - total keluar (Raw Usage)
                                         * - Masuk: grrawdetail.qty (hanya yang tidak dihapus + header aktif)
                                         * - Keluar: raw_usage.qty (asumsi belum ada is_deleted; tambahkan filter jika nanti ada)
                                         * - Tampilkan HANYA bahan dengan rm.stock = 1
                                         */

                                        $sql = "
                                            SELECT 
                                                rm.kdrawmate AS kode,
                                                rm.nmrawmate AS description,
                                                rc.nmcategory AS category,
                                                (COALESCE(msk.qty_in, 0) - COALESCE(klr.qty_out, 0)) AS stock
                                            FROM rawmate rm
                                            JOIN rawcategory rc ON rc.idrawcategory = rm.idrawcategory
                                            LEFT JOIN (
                                                SELECT gd.idrawmate, SUM(gd.qty) AS qty_in
                                                FROM grrawdetail gd
                                                JOIN grraw g ON g.idgr = gd.idgr
                                                WHERE gd.is_deleted = 0 AND g.is_deleted = 0
                                                GROUP BY gd.idrawmate
                                            ) msk ON msk.idrawmate = rm.idrawmate
                                            LEFT JOIN (
                                                SELECT ru.idrawmate, SUM(ru.qty) AS qty_out
                                                FROM raw_usage ru
                                                GROUP BY ru.idrawmate
                                            ) klr ON klr.idrawmate = rm.idrawmate
                                            WHERE rm.stock = 1
                                            ORDER BY rm.nmrawmate ASC
                                        ";

                                        $result = $conn->query($sql);

                                        if (!$result) {
                                            echo "<tr><td colspan='5' class='text-center text-danger'>Query Error: " . htmlspecialchars($conn->error) . "</td></tr>";
                                        } elseif ($result->num_rows > 0) {
                                            $no = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                        <td class='text-center'>" . $no . "</td>
                                                        <td class='text-center'>" . htmlspecialchars($row['kode']) . "</td>
                                                        <td>" . htmlspecialchars($row['description']) . "</td>
                                                        <td>" . htmlspecialchars($row['category']) . "</td>
                                                        <td class='text-right'>" . number_format((float)$row['stock'], 2) . "</td>
                                                     </tr>";
                                                $no++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.title = "DATA STOCK RAW";
</script>

<?php include "../footer.php"; ?>