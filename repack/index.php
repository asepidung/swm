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
               <a href="newrepack.php"><button type="button" class="btn btn-info"><i class="fas fa-plus-circle"></i> Baru</button></a>
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
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>No Proses</th>
                              <th>Tgl Proses</th>
                              <th>Bahan</th>
                              <th>Hasil</th>
                              <th>Lost</th>
                              <th>Catatan</th>
                              <th>AKSI</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT repack.*, users.fullname
                           FROM repack 
                           INNER JOIN users ON repack.idusers = users.idusers 
                           ORDER BY idrepack DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              include "hitungtotal.php";
                           ?>
                              <tr class="text-center">
                                 <td><?= $no; ?></td>
                                 <td><?= $tampil['norepack']; ?></td>
                                 <td><?= date("d-M-y", strtotime($tampil['tglrepack'])); ?></td>
                                 <td class="text-right"><?= number_format($rowTotalBahan['total_bahan'], 2); ?></td>
                                 <td class="text-right"><?= number_format($rowTotalHasil['total_hasil'], 2); ?></td>
                                 <td class="text-right">
                                    <?= $lost; ?>
                                 </td>
                                 <td class="text-left"><?= $tampil['note']; ?></td>
                                 <td>
                                    <a href="detailbahan.php?id=<?= $tampil['idrepack'] ?>&stat=ready" class="btn btn-sm btn-warning">
                                       <i class="fas fa-box-open"></i>
                                    </a>
                                    <a href="detailhasil.php?id=<?= $tampil['idrepack'] ?>" class="btn btn-sm btn-success">
                                       <i class="fas fa-tags"></i>
                                    </a>
                                    <a href="printrepack.php?id=<?= $tampil['idrepack'] ?>" class="btn btn-sm btn-primary">
                                       <i class="fas fa-print"></i>
                                    </a>
                                 </td>
                              </tr>
                           <?php $no++;
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
   document.title = "Data Repack";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>