<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "flowstock.php";
?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <!-- <h1 class="m-0">DATA BONING</h1> -->
               <a href="newbarang.php"><button type="button" class="btn btn-info"> Product Baru</button></a>
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
                     <div class="col">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>#</th>
                                 <th>Kode</th>
                                 <th>Nama Product</th>
                                 <!-- <th>xPembelian</th>
                                 <th>xPenjualan</th>
                                 <th>xRetur Beli</th>
                                 <th>xRetur Jual</th>
                                 <th>Stock Akhir</th> -->
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              </tr>
                              <?php
                              $no = 1;
                              $ambildata = mysqli_query($conn, "SELECT * FROM barang");
                              while ($tampil = mysqli_fetch_array($ambildata)) {
                              ?>
                                 <tr class="text-center">
                                    <td><?= $no; ?></td>
                                    <td><?= $tampil['kdbarang']; ?></td>
                                    <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                                    <!-- <td></td>
                                    <td class="text-right"><?= $totalpenjualan_per_idbarang[$tampil['idbarang']] ?? 0; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><?= number_format($tampil['stock'], 2); ?></td> -->
                                    <td>
                                       <div class="row">
                                          <div class="col"></div>
                                          <div class="col-3">
                                             <a href="editbarang.php?idbarang=<?= $tampil['idbarang'] ?>"><span class="text-success"><i class="fas fa-edit"></i></span></a>
                                          </div>
                                          <div class="col-3">
                                             <a href="deletebarang.php?idbarang=<?= $tampil['idbarang'] ?>" onclick="return confirm('Apakah kamu yakin? Ingat! Segala aktivitasmu akan terekam oleh sistem.');"><span class="text-danger"><i class="fas fa-trash"></i></span></a>
                                          </div>
                                          <div class="col"></div>
                                       </div>
                                    </td>
                                 </tr>
                              <?php
                                 $no++;
                              }
                              ?>
                           </tbody>
                           <tfoot>
                           </tfoot>
                        </table>
                     </div>
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

   <?php
   include "../footer.php";
   include "../footnote.php";
   ?>