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
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-7">
               <a href="../tally/"><button type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-eye"></i> Tally</button></a>
               <a href="dodetail.php"><button type="button" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i> Detail Delivery</button></a>
            </div>
            <div class="col-5 row">
               <div class="col">
                  <form method="POST" action="your_action_page.php">
                     <div class="form-group row align-items-center">
                        <label for="awal" class="col-form-label col-sm-4">Periode :</label>
                        <div class="col-8">
                           <input type="date" class="form-control form-control-sm" name="awal" value="<?= date('Y-m-01'); ?>">
                        </div>
                     </div>
                     <!-- ... (form action and submit button can be added here) ... -->
                  </form>
               </div>
               <div class="col">
                  <form method="POST" action="your_action_page.php">
                     <div class="form-group row align-items-center">
                        <label for="akhir" class="col-form-label col-sm-4">Sampai</label>
                        <div class="col-8">
                           <input type="date" class="form-control form-control-sm" name="akhir" value="<?= date('Y-m-d'); ?>">
                        </div>
                     </div>
                     <!-- ... (form action and submit button can be added here) ... -->
                  </form>
               </div>
            </div>
         </div><!-- /.container-fluid -->
      </div>
   </div>
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>DO Number</th>
                              <th>Tgl Kirim</th>
                              <th>Customer</th>
                              <th>PO</th>
                              <th>xQty</th>
                              <th>rQty</th>
                              <th>Catatan</th>
                              <th>Status</th>
                              <th>Made By</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <?php
                        $query_total_weight_keseluruhan = "SELECT SUM(xweight) AS total_weight_keseluruhan FROM do";
                        $result_total_weight_keseluruhan = mysqli_query($conn, $query_total_weight_keseluruhan);
                        $row_total_weight_keseluruhan = mysqli_fetch_assoc($result_total_weight_keseluruhan);
                        $total_weight_keseluruhan = $row_total_weight_keseluruhan['total_weight_keseluruhan'];
                        ?>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT do.*, customers.nama_customer, users.fullname FROM do
                              JOIN customers ON do.idcustomer = customers.idcustomer
                              JOIN users ON do.idusers = users.idusers
                              ORDER BY iddo DESC;
                           ");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['donumber']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td><?= $tampil['po']; ?></td>
                                 <td class="text-right"><?= number_format($tampil['xweight'], 2); ?></td>
                                 <td class="text-right"><?= number_format($tampil['rweight'], 2); ?></td>
                                 <td><?= $tampil['note']; ?></td>
                                 <td class="text-center">
                                    <?php if ($tampil['status'] == "Approved") { ?>
                                       <span class="text-primary" data-toggle="tooltip" data-placement="bottom" title="Approve By <?= $fullname ?>"><?= $tampil['status']; ?></span>
                                    <?php } elseif ($tampil['status'] == "Unapproved") { ?>
                                       <a href="approvedo.php?iddo=<?= $tampil['iddo'] ?>">
                                          <span class="text-danger" data-toggle="tooltip" data-placement="bottom" title="Klik Untuk Approve"><?= $tampil['status']; ?></span>
                                       </a>
                                    <?php } else {
                                       echo $tampil['status'];
                                    } ?>
                                 </td>
                                 <td class="text-center"><?= $tampil['fullname']; ?></td>
                                 <td class="text-center">
                                    <?php if ($tampil['status'] !== "Invoiced") { ?>
                                       <a href="cetakdo.php?iddo=<?= $tampil['iddo']; ?>" class="btn btn-sm btn-primary">
                                          <i class="fas fa-print"></i>
                                       </a>
                                       <a href="editdo.php?iddo=<?= $tampil['iddo']; ?>" class="btn btn-sm btn-warning">
                                          <i class="fas fa-edit"></i>
                                       </a>
                                       <a href="deletedo.php?iddo=<?= $tampil['iddo']; ?>" onclick="return confirm('apakah anda yakin ingin menghapus Surat Jalan ini?')" class="btn btn-sm btn-danger">
                                          <i class="fas fa-trash"></i>
                                       </a>
                                    <?php } else { ?>
                                       <div class="row">
                                          <div class="col">
                                             <a href="cetakdo.php?iddo=<?= $tampil['iddo']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-print"></i>
                                             </a>
                                             <a href="#" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-edit"></i>
                                             </a>
                                             <a href="#" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-trash"></i>
                                             </a>
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
                              <th colspan="5"></th>
                           </tr>
                        </tfoot>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<script>
   document.title = "Delivery Order";
</script>
<?php
include "../footer.php";
?>