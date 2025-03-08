<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// Query untuk mendapatkan data barang
$query_barang = "SELECT idbarang, nmbarang FROM barang";
$result_barang = mysqli_query($conn, $query_barang);

// Query untuk mendapatkan data grade
$query_grade = "SELECT idgrade, nmgrade FROM grade";
$result_grade = mysqli_query($conn, $query_grade);

// Query untuk mendapatkan data dari tabel stockin
$query_stockin = "
    SELECT si.id, si.kdbarcode, g.nmgrade, b.nmbarang, si.qty, si.pcs, si.creatime 
    FROM stockin si
    JOIN barang b ON si.idbarang = b.idbarang
    JOIN grade g ON si.idgrade = g.idgrade
    WHERE si.is_deleted = 0
    ORDER BY si.creatime DESC";
$result_stockin = mysqli_query($conn, $query_stockin);
?>
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <!-- Form Card -->
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="stockin.php">
                                <!-- Dropdown Barang -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control" name="idbarang" id="idbarang" required>
                                            <option value="">Pilih Barang</option>
                                            <?php while ($row_barang = mysqli_fetch_assoc($result_barang)) : ?>
                                                <option value="<?= $row_barang['idbarang'] ?>" <?= (isset($_SESSION['idbarang']) && $_SESSION['idbarang'] == $row_barang['idbarang']) ? 'selected' : ''; ?>>
                                                    <?= $row_barang['nmbarang'] ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Dropdown Grade -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control" name="idgrade" id="idgrade" required>
                                            <option value="">Pilih Grade</option>
                                            <?php while ($row_grade = mysqli_fetch_assoc($result_grade)) : ?>
                                                <option value="<?= $row_grade['idgrade'] ?>" <?= (isset($_SESSION['idgrade']) && $_SESSION['idgrade'] == $row_grade['idgrade']) ? 'selected' : ''; ?>>
                                                    <?= $row_grade['nmgrade'] ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- POD Input -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="pod" id="pod" value="<?= $_SESSION['pod'] ?? ''; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" value="<?= $_SESSION['qty'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn bg-gradient-primary btn-block" name="submit">Print</button>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- Tabel Data Stockin -->
                <div class="col-lg">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Barcode</th>
                                        <th>Product</th>
                                        <th>Grade</th>
                                        <th>Qty</th>
                                        <th>Pcs</th>
                                        <th>Create</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result_stockin)) :
                                    ?>
                                        <tr class="text-center">
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= $row['kdbarcode']; ?></td>
                                            <td class="text-left"><?= $row['nmbarang']; ?></td>
                                            <td><?= $row['nmgrade']; ?></td>
                                            <td class="text-right"><?= number_format($row['qty'], 2); ?></td>
                                            <td class="text-center"><?= $row['pcs'] ?? '-'; ?></td>
                                            <td class="text-center"><?= date('d-M-Y H:i:s', strtotime($row['creatime'])); ?></td>
                                            <td class="text-center">
                                                <a href="delete_stockin.php?kdbarcode=<?= $row['kdbarcode']; ?>" onclick="return confirm('Yakin ingin menghapus?');" class="text-danger"> <i class="fas fa-minus-square"></i></a>
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
    </div>
    <script>
        document.title = "Stock In";
    </script>
</div>
<?php
require "../footer.php";
?>