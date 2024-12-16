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
               <!-- <a href="newgr.php"><button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a> -->
               <a href="draft.php"><button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Draft</button></a>
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
                              <th>GR Number</th>
                              <th>Receiving Date</th>
                              <th>Supplier</th>
                              <th>ID Number</th>
                              <th>Catatan</th>
                              <th>Made By</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT gr.*, supplier.nmsupplier, poproduct.idpoproduct, users.fullname 
                                        FROM gr
                                        LEFT JOIN poproduct ON gr.idpo = poproduct.idpoproduct
                                        JOIN supplier ON gr.idsupplier = supplier.idsupplier
                                        LEFT JOIN users ON gr.iduser = users.idusers
                                        ORDER BY grnumber DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $idgr = $tampil['idgr'];
                              $idpo = $tampil['idpoproduct'];
                              $fullname = $tampil['fullname']; // Fetch fullname from users table
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['grnumber']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['receivedate'])); ?></td>
                                 <td><?= $tampil['nmsupplier']; ?></td>
                                 <td><?= $tampil['idnumber']; ?></td>
                                 <td><?= $tampil['note']; ?></td>
                                 <td class="text-center"><?= $fullname; ?></td> <!-- Display fullname here -->
                                 <td class="text-center">
                                    <a href="grscan.php?idgr=<?= $tampil['idgr']; ?>" class="btn btn-xs btn-warning" title="Scan">
                                       <i class="fas fa-barcode"></i>
                                    </a>
                                    <a href="grdetail.php?idgr=<?= $tampil['idgr']; ?>" class="btn btn-xs btn-primary" title="Label">
                                       <i class="fas fa-tag"></i>
                                    </a>
                                    <a href="printgr.php?idgr=<?= $tampil['idgr']; ?>" class="btn btn-xs btn-success" title="Print">
                                       <i class="far fa-eye"></i>
                                    </a>
                                    <a href="deletegr.php?idgr=<?= $tampil['idgr']; ?>&idpo=<?= $tampil['idpo']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                       <i class="far fa-trash-alt"></i>
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
   document.title = "Goods Receipt List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>