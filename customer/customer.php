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
         <div class="row mb-2">
            <div class="col-sm-6">
               <!-- <h1 class="m-0">DATA BONING</h1> -->
               <a href="newcustomer.php"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-plus"></i> Baru</button></a>
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
                           <tr>
                              <th>#</th>
                              <th>Nama Customer</th>
                              <th>Alamat</th>
                              <th>Segment</th>
                              <th>T.O.P</th>
                              <th>Sales Ref</th>
                              <th>Pajak</th>
                              <th>Telepon</th>
                              <th>Email</th>
                              <th>Catatan</th>
                              <th>Aksi</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT * FROM customers ORDER BY nama_customer ASC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr class="text-center">
                                 <td><?= $no; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td><?= $tampil['alamat']; ?></td>
                                 <td><?= $tampil['idsegment']; ?></td>
                                 <td><?= $tampil['top']; ?></td>
                                 <td><?= $tampil['sales_referensi']; ?></td>
                                 <td><?= $tampil['pajak']; ?></td>
                                 <td><?= $tampil['telepon']; ?></td>
                                 <td><?= $tampil['email']; ?></td>
                                 <td><?= $tampil['catatan']; ?></td>
                                 <td class="text-center">
                                    <a href="#"><i class="fas fa-pen"></i></a>
                                    |
                                    <a href="#"><i class="fas fa-trash-alt text-danger"></i></a>
                                 </td>
                              </tr>
                           <?php
                              $no++;
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
   <!-- </div> -->
   <!-- /.content-wrapper -->

   <?php include "../footer.php" ?>