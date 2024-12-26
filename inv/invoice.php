<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
$awal = isset($_GET['awal']) ? $_GET['awal'] : date('Y-m-01');
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : date('Y-m-d');

$queryApprovedCount = "SELECT COUNT(*) AS approved_count FROM do WHERE status = 'Approved'";
$resultApprovedCount = mysqli_query($conn, $queryApprovedCount);
$rowApprovedCount = mysqli_fetch_assoc($resultApprovedCount);
$approvedCount = $rowApprovedCount['approved_count'];
?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-2">
               <form method="GET" action="">
                  <input type="date" class="form-control form-control-sm" name="awal" value="<?= $awal; ?>">
            </div>
            <div class="col-2">
               <input type="date" class="form-control form-control-sm" name="akhir" value="<?= $akhir; ?>">
            </div>
            <div class="col">
               <button type="submit" class="btn btn-sm btn-primary" name="search"><i class="fas fa-search"></i></button>
               </form>
            </div>
            <div class="col-1">
               <a href="invdraft.php" class="btn btn-sm btn-outline-primary float right"><span class="badge badge-danger"> <?= $approvedCount; ?></span> Draft</button></a>
            </div>
         </div>
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
                           WHERE invoice.invoice_date BETWEEN '$awal' AND '$akhir'
                           ORDER BY idinvoice DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $tukarfaktur = $tampil['tukarfaktur'];
                              $status = $tampil['status'];
                              $tgltf = $tampil['tgltf'];
                              $top = $tampil['top'];
                              if (!empty($tgltf)) {
                                 $tgltf_timestamp = strtotime($tgltf);
                             } else {
                                 $tgltf_timestamp = null; // atau nilai default lainnya
                             }
                             
                              $duedate_timestamp = strtotime("+$top day", $tgltf_timestamp);
                              $duedate = date('d-M-y', $duedate_timestamp);
                              $iddo = $tampil['iddo'];
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= $tampil['noinvoice']; ?></td>
                                 <td class="text-center">
                                    <a href="../do/lihatdo.php?iddo=<?= $iddo ?>">
                                       <?= substr($tampil['donumber'], -5); ?>
                                    </a>
                                 </td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['invoice_date'])); ?></td>
                                 <td><?= $tampil['pocustomer']; ?></td>
                                 <td class="text-right"><?= number_format($tampil['balance'], 2); ?></td>
                                 <!-- <td class="text-center"><?= date("d-M-y", strtotime($tampil['duedate'])); ?></td> -->
                                 <td class="text-center">
                                    <?php if ($tampil['status'] == '-') {
                                       echo "-";
                                    } else if ($tampil['status'] == 'Belum TF') { ?>
                                       <a href="tukarfaktur.php?idinvoice=<?= $tampil['idinvoice'] ?>">
                                          <span class="text-success" data-toggle="tooltip" data-placement="bottom" title="Klik Untuk Tukar Faktur">Belum TF</span>
                                       </a>
                                    <?php } else { ?>
                                       <span class="text-primary" data-toggle="tooltip" data-placement="left" title="<?= date("d-M-y", strtotime($tampil['tgltf'])) . " " . $tampil['note']; ?>"><?= $tampil['status']; ?></span>
                                    <?php } ?>
                                 </td>
                                 <td class="text-center">
                                    <!-- <div class="row"> -->
                                    <!-- <div class="col"> -->
                                    <a href="lihatinvoice.php?idinvoice=<?= $tampil['idinvoice']; ?>">
                                       <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                                    </a>
                                    <!-- </div> -->
                                    <!-- <div class="col"> -->
                                    <a href="pib.php?idinvoice=<?= $tampil['idinvoice']; ?>">
                                       <button type="button" class="btn btn-sm btn-success"><i class="fas fa-print"></i></button>
                                    </a>
                                    <!-- </div>
                                       <div class="col"> -->
                                    <a href="editinvoice.php?idinvoice=<?= $tampil['idinvoice']; ?>">
                                       <button type="button" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></button>
                                    </a>
                                    <!-- </div>
                                       <div class="col"> -->
                                    <a href="deleteinvoice.php?idinvoice=<?= $tampil['idinvoice']; ?>&iddo=<?= $tampil['iddo']; ?>" onclick="return confirm('Anda yakin ingin Membatalkan invoice ini?');">
                                       <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </a>
                                    <!-- </div> -->
                                    <!-- </div> -->
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