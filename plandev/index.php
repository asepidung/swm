<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
// $query = "DELETE FROM plandev WHERE plandelivery < CURDATE()";
// mysqli_query($conn, $query);
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
            <div class="col">
               <div class="card">
                  <!-- /.card-header -->
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
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT plandev.*, customers.nama_customer FROM plandev
                          JOIN customers ON plandev.idcustomer = customers.idcustomer
                          WHERE plandelivery >= CURDATE()
                          ORDER BY plandelivery ASC;
                          ");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr class="text-center" onclick="window.location.href='editplandev.php?idplandev=<?= $tampil['idplandev']; ?>';" style="cursor: pointer;" title="KLIK UNTUK EDIT">
                                 <td><?= $no; ?></td>
                                 <td><?= date("D, d-M-y", strtotime($tampil['plandelivery'])); ?></td>
                                 <td class="text-left"><?= $tampil['nama_customer']; ?></td>
                                 <td><?= number_format($tampil['weight']); ?></td>
                                 <td><?= $tampil['driver_name']; ?></td>
                                 <td><?= $tampil['armada']; ?></td>
                                 <td><?= $tampil['loadtime']; ?></td>
                                 <td class="text-left"><?= $tampil['note']; ?></td>
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
   </section>
</div>

<script>
   // Mengubah judul halaman web
   document.title = "Plan Delivery";
</script>
<?php
require "../footnote.php";
include "../footer.php" ?>