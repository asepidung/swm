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
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <!-- <h1 class="m-0">DATA BONING</h1> -->
               <a href="invdraft.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Draft</button></a>
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
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Customer</th>
                              <th>No Invoice</th>
                              <th>No DO</th>
                              <th>Tgl Invoice</th>
                              <!-- <th>Tgl Do</th> -->
                              <th>PO</th>
                              <th>Amount</th>
                              <th>Due Date</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT invoice.*, customers.nama_customer 
                           FROM invoice 
                           INNER JOIN customers ON invoice.idcustomer = customers.idcustomer");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr>
                                 <td><?= $no; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= $tampil['noinvoice']; ?></td>
                                 <td class="text-center"><?= $tampil['donumber']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['invoice_date'])); ?></td>
                                 <!-- <td class="text-center"><?= date("d-M-y", strtotime($tampil['invoice_date'])); ?></td> -->
                                 <td><?= $tampil['pocustomer']; ?></td>
                                 <td class="text-right"><?= number_format($tampil['balance'], 2); ?></td>
                                 <td><?= date("d-M-y", strtotime($tampil['duedate'])); ?></td>
                                 <td>Belum Tukar Faktur</td>
                              </tr>
                           <?php $no++;
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
   <!-- </div> -->
   <!-- /.content-wrapper -->

   <script>
      // Mengubah judul halaman web
      document.title = "Invoice List";
   </script>
   <?php
   // require "../footnote.php";
   include "../footer.php" ?>