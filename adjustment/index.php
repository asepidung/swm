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
                     <table id="example1" class="table table-bordered table-sm table-hover">
                        <thead class="text-center thead-dark">
                           <tr>
                              <th>#</th>
                              <th>Adjust Number</th>
                              <th>Adjusting Date</th>
                              <th>xQty</th>
                              <th>Made By</th>
                              <th>Event</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT a.*, u.userid
                           FROM adjustment a
                           JOIN users u ON a.idusers = u.idusers ORDER BY idadjustment DESC;");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $xweight = $tampil['xweight'];
                              $idadjustment = $tampil['idadjustment'];
                           ?>
                              <?php if ($xweight < 0) { ?>
                                 <tr data-widget="expandable-table" aria-expanded="false" class="text-danger">
                                 <?php } else { ?>
                                 <tr data-widget="expandable-table" aria-expanded="false">
                                 <?php } ?>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['noadjustment']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['tgladjustment'])); ?></td>
                                 <td class="text-right"><?= number_format($xweight, 2); ?></td>
                                 <td class="text-center"><?= $tampil['userid']; ?></td>
                                 <td><?= $tampil['eventadjustment']; ?></td>
                                 </tr>
                                 <tr class="expandable-body">
                                    <td colspan="6">
                                       <?php
                                       $query_detail = "SELECT adjustmentdetail.*, barang.kdbarang, barang.nmbarang, grade.nmgrade
                                                         FROM adjustmentdetail
                                                         INNER JOIN grade ON adjustmentdetail.idgrade = grade.idgrade
                                                         INNER JOIN barang ON adjustmentdetail.idbarang = barang.idbarang
                                                         WHERE adjustmentdetail.idadjustment = '$idadjustment'";
                                       $result_detail = mysqli_query($conn, $query_detail);
                                       while ($row_detail = mysqli_fetch_assoc($result_detail)) { ?>
                                          <div class="row">
                                             <div class="col-1">
                                                <?= $row_detail['nmgrade']; ?>
                                             </div>
                                             <div class="col-2">
                                                <?= $row_detail['nmbarang']; ?>
                                             </div>
                                             <div class="col-2">
                                                <?= $row_detail['weight']; ?>
                                             </div>
                                             <div class="col">
                                                <?= $row_detail['notes']; ?>
                                             </div>
                                          </div>
                                       <?php } ?>
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