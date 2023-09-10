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
                     // Query untuk mengambil data dari tabel invoice dan invoicedetail dengan JOIN ke tabel barang
                     $query = "SELECT i.idinvoice, c.nama_customer, i.invoice_date, i.noinvoice, i.donumber, i.pocustomer,
                      b.nmbarang, id.weight, id.price, id.discount, id.discountrp, id.amount
                      FROM invoice i
                      INNER JOIN customers c ON i.idcustomer = c.idcustomer
                      LEFT JOIN invoicedetail id ON i.idinvoice = id.idinvoice
                      LEFT JOIN barang b ON id.idbarang = b.idbarang
                      ORDER BY i.idinvoice";  // Urutkan berdasarkan idinvoice
                     $result = $conn->query($query);
                     ?>
                     <!-- Bagian HTML -->
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Customer</th>
                              <th>Tgl Invoice</th>
                              <th>No Invoice</th>
                              <th>No DO</th>
                              <th>PO</th>
                              <th>Barang</th>
                              <th>Weight</th>
                              <th>Price</th>
                              <th>Disc %</th>
                              <th>Disc RP</th>
                              <th>Amount</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $row_number = 1;
                           while ($row = $result->fetch_assoc()) { ?>
                              <tr class="text-right">
                                 <td class="text-center"> <?= $row_number; ?> </td>
                                 <td class="text-left"> <?= $row["nama_customer"]; ?> </td>
                                 <td class="text-center"> <?= $row["invoice_date"]; ?> </td>
                                 <td class="text-center"> <?= $row["noinvoice"]; ?> </td>
                                 <td class="text-center"> <?= $row["donumber"]; ?> </td>
                                 <td class="text-left"> <?= $row["pocustomer"]; ?> </td>
                                 <td class="text-left"> <?= $row["nmbarang"]; ?> </td>
                                 <td> <?= number_format($row["weight"], 2); ?> </td>
                                 <td> <?= number_format($row["price"], 2); ?> </td>
                                 <td class="text-center"> <?= $row["discount"]; ?> </td>
                                 <td> <?= number_format($row["discountrp"], 2); ?> </td>
                                 <td> <?= number_format($row["amount"], 2); ?> </td>
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
   document.title = "Detail Invoice List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>