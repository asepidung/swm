<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card mt-3">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Customer</th>
                              <th>Tgl Kirim</th>
                              <th>PO</th>
                              <th>SO</th>
                              <th>Catatan</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT salesorder.*, customers.nama_customer FROM salesorder
                           JOIN customers ON salesorder.idcustomer = customers.idcustomer
                           WHERE progress = 'Waiting' AND is_deleted = 0
                           ORDER BY salesorder.deliverydate ASC");

                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td><?= $tampil['po']; ?></td>
                                 <td class="text-center">
                                    <a href="hideprice.php?idso=<?= $tampil['idso']; ?>">
                                       <?= $tampil['sonumber']; ?>
                                    </a>
                                 </td>
                                 <td><?= $tampil['note']; ?></td>
                                 <td class="text-center">
                                    <a href="newtally.php?idso=<?= $tampil['idso']; ?>"
                                       class="btn btn-sm btn-primary mb-1">
                                       Buat Tally <i class="fas fa-arrow-circle-right"></i>
                                    </a>

                                    <a href="cancel_so.php?idso=<?= $tampil['idso']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin SO ini dibatalkan?')">
                                       Cancel
                                    </a>
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
</div>
<!-- /.content-wrapper -->

<script>
   // Mengubah judul halaman web
   document.title = "DRAFT TALLY";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>