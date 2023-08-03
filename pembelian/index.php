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
               <a href="newpembelian.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
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
                              <th>PR Number</th>
                              <th>Receiping Date</th>
                              <th>Supplier</th>
                              <th>xQty</th>
                              <th>Catatan</th>
                              <th>Made By</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <?php
                        $query_total_weight_keseluruhan = "SELECT SUM(xweight) AS total_weight_keseluruhan FROM pembelian";
                        $result_total_weight_keseluruhan = mysqli_query($conn, $query_total_weight_keseluruhan);
                        $row_total_weight_keseluruhan = mysqli_fetch_assoc($result_total_weight_keseluruhan);
                        $total_weight_keseluruhan = $row_total_weight_keseluruhan['total_weight_keseluruhan'];
                        ?>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT pembelian.*, supplier.nmsupplier FROM pembelian
                           JOIN supplier ON pembelian.idsupplier = supplier.idsupplier
                           ORDER BY nmsupplier DESC ;
                           ");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['prnumber']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['receivedate'])); ?></td>
                                 <td><?= $tampil['nmsupplier']; ?></td>
                                 <td class="text-right"><?= number_format($tampil['xweight'], 2); ?></td>
                                 <td><?= $tampil['note']; ?></td>
                                 <td class="text-center"><?= $userid ?></td>
                                 <td class="text-center">
                                    <?php if ($tampil['status'] !== "Invoiced") { ?>
                                       <div class="row">
                                          <div class="col-4">
                                             <a href="cetakdo.php?iddo=<?= $tampil['iddo']; ?>">
                                                <span class="text-info"><i class="fas fa-print"></i></span>
                                             </a>
                                          </div>
                                          <div class="col-4">
                                             <a href="editdo.php?iddo=<?= $tampil['iddo']; ?>">
                                                <span class="text-success"><i class="fas fa-edit"></i></span>
                                             </a>
                                          </div>
                                          <div class="col-4">
                                             <a href="deletedo.php?iddo=<?= $tampil['iddo']; ?>" onclick="return confirm('Segala perubahan yang anda lakukan akan terekam dalam sistem, apakah anda yakin ingin menghapus Surat Jalan ini?')">
                                                <span class="text-danger"><i class="fas fa-trash"></i></span>
                                             </a>
                                          </div>
                                       </div>
                                    <?php } else { ?>
                                       <div class="row">
                                          <div class="col">
                                             <a href="cetakdo.php?iddo=<?= $tampil['iddo']; ?>" target="_blank" class="btn btn-sm btn-success">
                                                <i class="fas fa-edit"></i> print </a>
                                          </div>
                                       </div>
                                    <?php } ?>
                                 </td>
                              </tr>
                           <?php $no++;
                           } ?>
                        </tbody>
                        <tfoot>
                           <tr>
                              <th class="text-right" colspan="5">SUBTOTAL</th>
                              <th class="text-right"><?= number_format($total_weight_keseluruhan, 2); ?></th>
                              <th colspan="4"></th>
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
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
   <!-- </div> -->
   <!-- /.content-wrapper -->

   <script>
      // Mengubah judul halaman web
      document.title = "Purchase Receipt";
   </script>
   <?php
   // require "../footnote.php";
   include "../footer.php" ?>