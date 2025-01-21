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
$awal = isset($_GET['awal']) ? $_GET['awal'] : date('Y-m-01');
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : date('Y-m-d');
?>
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-2">
               <form method="GET" action="">
                  <input type="date" class="form-control form-control-sm" name="awal" value="<?= $awal; ?>">
            </div>
            <div class="col-2">
               <input type="date" class="form-control form-control-sm" name="akhir" value="<?= $akhir; ?>">
            </div>
            <div class="col">
               <button type="submit" class="btn btn-sm btn-primary" name="search"><i class="fas fa-search"></i></button>
               </form>
            </div>
            <div class="col-2">
               <a href="do.php" class="btn btn-sm btn-outline-success float-right"><i class="fas fa-eye"></i> Kembali</a>
            </div>
         </div>
      </div>
   </div>
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 mt-3">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <?php
                     // Query yang diupdate
                     $query = "SELECT i.iddo, c.nama_customer, i.deliverydate, i.donumber, i.po,
                 b.nmbarang, id.weight AS do_weight, id.notes,
                 s.sonumber,
                 (
                     SELECT weight
                     FROM salesorderdetail sod
                     WHERE sod.idbarang = id.idbarang AND sod.idso = s.idso
                     LIMIT 1
                 ) AS so_weight
          FROM do i
          INNER JOIN customers c ON i.idcustomer = c.idcustomer
          LEFT JOIN dodetail id ON i.iddo = id.iddo
          LEFT JOIN barang b ON id.idbarang = b.idbarang
          LEFT JOIN salesorder s ON i.idso = s.idso
          WHERE i.deliverydate BETWEEN ? AND ?
          AND i.is_deleted = 0
          ORDER BY i.iddo DESC";

                     $stmt = $conn->prepare($query);
                     if ($stmt === false) {
                        die('Prepare error: ' . htmlspecialchars($conn->error));
                     }
                     $stmt->bind_param("ss", $awal, $akhir);
                     $stmt->execute();
                     $result = $stmt->get_result();
                     ?>
                     <!-- Bagian HTML -->
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Customer</th>
                              <th>No SO</th>
                              <th>Tgl Kirim</th>
                              <th>No DO</th>
                              <th>PO</th>
                              <th>Barang</th>
                              <th>SO</th>
                              <th>Qty</th>
                              <th>Notes</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $row_number = 1;
                           while ($row = $result->fetch_assoc()) { ?>
                              <tr class="text-right">
                                 <td class="text-center"> <?= $row_number; ?> </td>
                                 <td class="text-left"> <?= htmlspecialchars($row["nama_customer"]); ?> </td>
                                 <td class="text-center"><?= htmlspecialchars($row["sonumber"]); ?></td>
                                 <td class="text-center"> <?= htmlspecialchars(date('d-M-Y', strtotime($row["deliverydate"]))); ?> </td>
                                 <td class="text-center"> <?= htmlspecialchars($row["donumber"]); ?> </td>
                                 <td class="text-left"> <?= htmlspecialchars($row["po"]); ?> </td>
                                 <td class="text-left"> <?= htmlspecialchars($row["nmbarang"]); ?> </td>
                                 <td class="text-right"> <?= number_format($row["so_weight"], 2); ?> </td>
                                 <td> <?= number_format($row["do_weight"], 2); ?> </td>
                                 <td class="text-left"> <?= htmlspecialchars($row["notes"]); ?> </td>
                              </tr>
                           <?php $row_number++;
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
                  <!-- /.card-body -->
               </div>

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
   // Mengubah judul halaman web
   document.title = "DO Detail";
</script>
<?php
// require "../footnote.php";
include "../footer.php";
?>