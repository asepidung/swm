<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location: ../verifications/login.php");
    exit;
}

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
                <div class="col">
                    <a href="stockout.php" class="btn btn-sm btn-danger mt-2">Proses Stock Out</a>
                    <a href="history.php" class="btn btn-sm btn-primary mt-2">Riwayat Pengeluaran</a>
                </div>
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
                                        // Query untuk mendapatkan data
                                        $sql = "SELECT 
                                        rm.kdrawmate AS kode,
                                        rm.nmrawmate AS description,
                                        rc.nmcategory AS category,
                                        SUM(sr.qty) AS stock
                                    FROM stockraw sr
                                    JOIN rawmate rm ON sr.idrawmate = rm.idrawmate
                                    JOIN rawcategory rc ON rm.idrawcategory = rc.idrawcategory
                                    WHERE sr.is_deleted = 0 AND rm.stock = 1
                                    GROUP BY rm.kdrawmate, rm.nmrawmate, rc.nmcategory
                                    ORDER BY rm.nmrawmate ASC";

                                        $result = $conn->query($sql);


                                        // Debug jika query gagal
                                        if (!$result) {
                                            die("<tr><td colspan='5' class='text-center'>Query Error: " . $conn->error . "</td></tr>");
                                        }

                                        if ($result->num_rows > 0) {
                                            $no = 1; // Inisialisasi nomor urut
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                        <td class='text-center'>{$no}</td>
                                                        <td class='text-center'>{$row['kode']}</td>
                                                        <td>{$row['description']}</td>
                                                        <td>{$row['category']}</td>
                                                         <td class='text-right'>" . number_format($row['stock'], 2) . "</td>
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
    // Mengubah judul halaman web
    document.title = "DATA STOCK RAW";
</script>

<?php
include "../footer.php";
?>