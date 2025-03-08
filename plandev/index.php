<?php
require "../verifications/auth.php";
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
               <a href="newplandev.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md">
               <div class="card">
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm table-hover">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Tgl Kirim</th>
                              <th>Customer</th>
                              <th>Qty</th>
                              <th>Driver</th>
                              <th>Armada</th>
                              <th>Jam</th>
                              <th>Note</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $total_qty = 0;
                           $no = 1;
                           // Query untuk menampilkan data berdasarkan deliverydate mulai besok
                           $ambildata = mysqli_query($conn, "
                           SELECT plandev.*, customers.nama_customer 
                           FROM plandev
                           JOIN customers ON plandev.idcustomer = customers.idcustomer
                           WHERE plandev.plandelivery >= CURDATE() + INTERVAL 1 DAY
                           AND customers.idgroup != 21
                           ORDER BY plandev.plandelivery ASC;
                        ");

                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $total_qty += $tampil['weight'];
                           ?>
                              <tr class="text-center" onclick="window.location.href='editplandev.php?idplandev=<?= $tampil['idplandev']; ?>';" style="cursor: pointer;" title="KLIK UNTUK EDIT">
                                 <td><?= $no; ?></td>
                                 <td><?= date("D, d-M-y", strtotime($tampil['plandelivery'])); ?></td>
                                 <td class="text-left"><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-right"><?= number_format($tampil['weight']) . " " . "Kg"; ?></td>
                                 <td><?= $tampil['driver_name']; ?></td>
                                 <td><?= $tampil['armada']; ?></td>
                                 <td><?= $tampil['loadtime']; ?></td>
                                 <td class="text-left"><?= $tampil['note']; ?></td>
                              </tr>
                           <?php $no++;
                           } ?>
                        </tbody>
                        <tfoot>
                           <tr>
                              <th class="text-right" colspan="3">TOTAL</th>
                              <th class="text-right"><?= number_format($total_qty) . " " . "Kg"; ?></th>
                              <td colspan="4"></td>
                           </tr>
                        </tfoot>
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
   </section>
   <script>
      // Mengubah judul halaman web
      document.title = "Plan Delivery";
   </script>
   <?php
   require "../footnote.php";
   include "../footer.php" ?>