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
               <a href="newpoproduct.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
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
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>PO Number</th>
                              <th>Supplier</th>
                              <th>Delivery Date</th>
                              <th>Qty</th>
                              <th>Amount</th>
                              <th>Terms</th>
                              <th>Notes</th>
                              <th>Status</th>
                              <!-- <th>Users</th> -->
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT p.*, u.fullname, s.nmsupplier
                           FROM poproduct p
                           JOIN users u ON p.idusers = u.idusers
                           LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
                           WHERE p.is_deleted = 0
                           ORDER BY p.idpoproduct DESC;");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $xweight = $tampil['xweight'];
                              $xamount = $tampil['xamount'];
                              $Terms = $tampil['Terms'];
                              $idpo = $tampil['idpoproduct'];
                           ?>
                              <td class="text-center"><?= $no; ?></td>
                              <td><?= $tampil['nopoproduct']; ?></td>
                              <td><?= $tampil['nmsupplier']; ?></td>
                              <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliveryat'])); ?></td>
                              <td class="text-right"><?= number_format($xweight, 2); ?></td>
                              <td class="text-right"><?= number_format($xamount, 2); ?></td>
                              <?php if ($Terms === "COD" || $Terms === "CBD") { ?>
                                 <td class="text-center"><?= $Terms; ?> </td>
                              <?php } else { ?>
                                 <td class="text-center"><?= $Terms . " " . "Hari"; ?> </td>
                              <?php } ?>
                              <td><?= $tampil['note']; ?></td>
                              <td><?= $tampil['stat']; ?></td>
                              <td class="text-center">
                                 <?php if ($tampil['stat'] == 'Waiting') { ?>
                                    <a href="printpoproduct.php?idpoproduct=<?= $tampil['idpoproduct']; ?>" class="btn btn-xs btn-primary">
                                       <i class="fas fa-print"></i>
                                    </a>
                                    <a href="editpoproduct.php?idpoproduct=<?= $tampil['idpoproduct']; ?>" class="btn btn-xs btn-success">
                                       <i class="far fa-edit"></i>
                                    </a>
                                    <a href="deletepoproduct.php?idpoproduct=<?= $tampil['idpoproduct']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                       <i class="far fa-trash-alt"></i>
                                    </a>
                                 <?php } else { ?>
                                    <a href="printpoproduct.php?idpoproduct=<?= $tampil['idpoproduct']; ?>" class="btn btn-xs btn-primary">
                                       <i class="fas fa-print"></i>
                                    </a>
                                    <a href="#" class="btn btn-xs btn-secondary">
                                       <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-xs btn-secondary">
                                       <i class="far fa-trash-alt"></i>
                                    </a>
                                 <?php  } ?>
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
   document.title = "po List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>