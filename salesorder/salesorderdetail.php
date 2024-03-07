<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
$awal = isset($_GET['awal']) ? $_GET['awal'] : date('Y-m-01');
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : date('Y-m-d');
?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
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
            <div class="col-1">
               <a href="index.php" class="btn btn-sm btn-outline-primary float right"><i class="fas fa-plus"></i> Back</button></a>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 mt-3">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <?php
                     // Query untuk mengambil data dari tabel salesorder dan salesorderdetail
                     $query = "SELECT s.idso, c.nama_customer, s.deliverydate, s.po, sd.weight, sd.price, sd.notes, s.sonumber, b.nmbarang
                     FROM salesorder s
                     INNER JOIN customers c ON s.idcustomer = c.idcustomer
                     INNER JOIN salesorderdetail sd ON s.idso = sd.idso
                     INNER JOIN barang b ON sd.idbarang = b.idbarang
                     WHERE s.deliverydate BETWEEN '$awal' AND '$akhir'
                     ORDER BY s.idso DESC";
                     $result = $conn->query($query);

                     ?>
                     <!-- Bagian HTML -->
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Customer</th>
                              <th>Nomor SO</th>
                              <th>Tgl Kirim</th>
                              <th>Products</th>
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
                                 <td class="text-left"> <?= $row["nama_customer"]; ?> </td>
                                 <td class="text-center"> <?= $row["sonumber"]; ?> </td>
                                 <td class="text-center"> <?= date("d-M-y", strtotime($row["deliverydate"])); ?> </td>
                                 <td class="text-left"> <?= $row["nmbarang"]; ?> </td>
                                 <td class="text-center"> <?= $row["weight"]; ?> </td>
                                 <td class="text-left"> <?= $row["notes"]; ?> </td>
                              </tr>
                           <?php $row_number++;
                           }
                           ?>
                        </tbody>
                     </table>

                  </div>
                  <!-- /.card-body -->
               </div>
               <!-- /.card -->
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
   document.title = "Detail Sales Order List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>