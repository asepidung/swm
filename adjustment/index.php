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
               <a href="newadjustment.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
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
                              <th>Adjust Number</th>
                              <th>Adjusting Date</th>
                              <th>Event</th>
                              <th>xQty</th>
                              <th>Catatan</th>
                              <th>Made By</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT * FROM adjustment");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['noadjustment']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['tgladjustment'])); ?></td>
                                 <td><?= $tampil['eventadjustment']; ?></td>
                                 <td class="text-right"><?= number_format($tampil['xweight'], 2); ?></td>
                                 <td><?= $tampil['note']; ?></td>
                                 <!-- <td class="text-center"><?= $userid ?></td> -->
                                 <td class="text-center">
                                    <a href="print.php?idadjustment=<?= $tampil['idadjustment']; ?>" class="mx-auto p-2">
                                       <i class="far fa-eye text-primary"></i>
                                    </a>
                                    <a href="editadjustment.php?idadjustment=<?= $tampil['idadjustment']; ?>" class="mx-auto p-2">
                                       <i class="far fa-edit text-success"></i>
                                    </a>
                                    <a href="deleteadjustment.php?idadjustment=<?= $tampil['idadjustment']; ?>" class="mx-auto p-2" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                       <i class="far fa-trash-alt text-danger"></i>
                                    </a>
                                 </td>
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
</div>
<!-- /.content-wrapper -->

<script>
   // Mengubah judul halaman web
   document.title = "Adjustment List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>