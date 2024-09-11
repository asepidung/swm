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
               <a href="newreturjual.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
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
                              <th>Return Number</th>
                              <th>Customer</th>
                              <th>Total qty</th>
                              <th>Catatan</th>
                              <th>Made By</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT returjual.*, customers.nama_customer
                         FROM returjual
                         JOIN customers ON returjual.idcustomer = customers.idcustomer ORDER BY returnnumber DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['returnnumber']; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td></td>
                                 <td><?= $tampil['note']; ?></td>
                                 <td class="text-center"><?= $fullname ?></td>
                                 <td class="text-center">
                                    <a href="detailrj.php?idreturjual=<?= $tampil['idreturjual']; ?>" class="btn btn-sm btn-secondary">
                                       <i class="fas fa-barcode"></i>
                                    </a>
                                    <a href="lihatrj.php?idreturjual=<?= $tampil['idreturjual']; ?>" class="btn btn-sm btn-primary">
                                       <i class="far fa-eye"></i>
                                    </a>
                                    <a href="printrj.php?idreturjual=<?= $tampil['idreturjual']; ?>" class="btn btn-sm btn-success">
                                       <i class="fas fa-print"></i></a>
                                    <a href="editrj.php?idreturjual=<?= $tampil['idreturjual']; ?>" class="btn btn-sm btn-warning">
                                       <i class="fas fa-pencil-alt"></i></a>
                                    <a href="editrj.php?idreturjual=<?= $tampil['idreturjual']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('apakah anda yakin ingin menghapus data ini?')">
                                       <i class="fas fa-minus-circle"></i></a>
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
   document.title = "Sales Return";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>