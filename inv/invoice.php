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
               <!-- <h1 class="m-0">DATA BONING</h1> -->
               <a href="invdraft.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Draft</button></a>
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
                              <th>Customer</th>
                              <th>No Invoice</th>
                              <th>No DO</th>
                              <th>Tgl Invoice</th>
                              <!-- <th>Tgl Do</th> -->
                              <th>PO</th>
                              <th>Amount</th>
                              <!-- <th>Due Date</th> -->
                              <th>Status</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT invoice.*, customers.nama_customer, customers.tukarfaktur, do.iddo
                           FROM invoice 
                           INNER JOIN customers ON invoice.idcustomer = customers.idcustomer 
                           LEFT JOIN do ON invoice.donumber = do.donumber
                           ORDER BY noinvoice DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $tukarfaktur = $tampil['tukarfaktur'];
                              $status = $tampil['status'];
                              $tgltf = $tampil['tgltf'];
                              $top = $tampil['top'];
                              $tgltf_timestamp = strtotime($tgltf);
                              $duedate_timestamp = strtotime("+$top day", $tgltf_timestamp);
                              $duedate = date('d-M-y', $duedate_timestamp);
                              $iddo = $tampil['iddo'];
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= $tampil['noinvoice']; ?></td>
                                 <td class="text-center"><?= substr($tampil['donumber'], 15); ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['invoice_date'])); ?></td>
                                 <td><?= $tampil['pocustomer']; ?></td>
                                 <td class="text-right"><?= number_format($tampil['balance'], 2); ?></td>
                                 <td class="text-center">
                                    <?php if ($tampil['status'] == '-') {
                                       echo "-";
                                    } else if ($tampil['status'] == 'Belum TF') { ?>
                                       <a href="tukarfaktur.php?idinvoice=<?= $tampil['idinvoice'] ?>">
                                          <span class="text-success" data-toggle="tooltip" data-placement="bottom" title="Klik Untuk Tukar Faktur">Belum TF</span>
                                       </a>
                                    <?php } else { ?>
                                       <span class="text-primary" data-toggle="tooltip" data-placement="bottom" title="<?= date("d-M-y", strtotime($tampil['tgltf'])); ?>"><?= $tampil['status']; ?></span>
                                    <?php } ?>
                                 </td>
                                 <td class="text-center">
                                    <div class="row">
                                       <div class="col-1"></div>
                                       <div class="col">
                                          <a href="lihatinvoice.php?idinvoice=<?= $tampil['idinvoice']; ?>"><i class="fas fa-eye"></i></a>
                                       </div>
                                       <div class="col">
                                          <a href="printinvoice.php?idinvoice=<?= $tampil['idinvoice']; ?>"><i class="fas fa-print text-success"></i></a>
                                       </div>
                                       <div class="col">
                                          <a href="deleteinvoice.php?idinvoice=<?= $tampil['idinvoice']; ?>&iddo=<?= $tampil['iddo']; ?>" onclick="return confirm('Anda yakin ingin Membatalkan invoice ini?');">
                                             <i class="fas fa-eject text-danger"></i>
                                          </a>
                                       </div>
                                       <div class="col-1"></div>
                                    </div>
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
   document.title = "Invoice List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>