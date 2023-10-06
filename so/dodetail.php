<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
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
            <div class="col-12 mt-3">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <?php
                     $query = "SELECT i.iddo, c.nama_customer, i.deliverydate, i.donumber, i.po,
                     b.nmbarang, id.weight, g.nmgrade, id.notes
                     FROM do i
                     INNER JOIN customers c ON i.idcustomer = c.idcustomer
                     LEFT JOIN dodetail id ON i.iddo = id.iddo
                     LEFT JOIN barang b ON id.idbarang = b.idbarang
                     LEFT JOIN grade g ON id.idgrade = g.idgrade
                     ORDER BY i.iddo DESC";  // Urutkan berdasarkan iddo
                     $result = $conn->query($query);
                     ?>
                     <!-- Bagian HTML -->
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Customer</th>
                              <th>Tgl do</th>
                              <th>No DO</th>
                              <th>PO</th>
                              <th>Kode</th>
                              <th>Barang</th>
                              <th>Weight</th>
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
                                 <td class="text-center"> <?= $row["deliverydate"]; ?> </td>
                                 <td class="text-center"> <?= $row["donumber"]; ?> </td>
                                 <td class="text-left"> <?= $row["po"]; ?> </td>
                                 <td class="text-center"> <?= $row["nmgrade"]; ?> </td>
                                 <td class="text-left"> <?= $row["nmbarang"]; ?> </td>
                                 <td> <?= number_format($row["weight"], 2); ?> </td>
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
   document.title = "DO Detail";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>