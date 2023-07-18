<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Mengambil data dari tabel invoice
// $queryInvoice = "SELECT * FROM invoice";
// $resultInvoice = mysqli_query($conn, $queryInvoice);
// $count = 1;

?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <a href="invdraft.php"><button type="button" class="btn btn-outline-danger btn-sm"><i class="fab fa-firstdraft"></i></i> Buka Draft</button></a>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead>
                           <tr>#</tr>
                           <tr>Inv No</tr>
                           <tr>DO No</tr>
                           <tr>PO No</tr>
                           <tr>xweight</tr>
                           <tr>xdiscount</tr>
                           <tr>balance</tr>
                           <tr>Actions</tr>
                        </thead>
                        <tbody>
                           <?php while ($rowInvoice = mysqli_fetch_assoc($resultInvoice)) { ?>
                              <tr>
                                 <td><?= $count; ?></td>
                                 <td><?= $rowInvoice['invoice_number']; ?></td>
                                 <td><?= $rowInvoice['donumber']; ?></td>
                                 <td>po</td>
                                 <td><?= $rowInvoice['xweight']; ?></td>
                                 <td>xdiscount</td>
                                 <td><?= $rowInvoice['balance']; ?></td>
                                 <td></td>
                              </tr>
                           <?php
                              $count++;
                           } ?>
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
   document.title = "Invoice Approved List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>