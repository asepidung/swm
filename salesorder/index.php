<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$awal = isset($_GET['awal']) ? $_GET['awal'] : date('Y-m-01');
$queryMaxDate = "SELECT MAX(deliverydate) AS max_date FROM salesorder";
$resultMaxDate = mysqli_query($conn, $queryMaxDate);
$rowMaxDate = mysqli_fetch_assoc($resultMaxDate);
$maxDate = $rowMaxDate['max_date'];

// Tentukan $akhir sebagai tanggal maksimum dari kolom deliverydate
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : $maxDate;
?>
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-12 col-md-2 mb-2">
               <a href="newso.php">
                  <button type="button" class="btn btn-sm btn-outline-primary btn-block"><i class="fas fa-plus"></i> Baru</button>
               </a>
            </div>
            <div class="col-12 col-md-6 mb-2">
               <form method="GET" action="">
                  <div class="input-group">
                     <input type="date" class="form-control form-control-sm" name="awal" value="<?= $awal; ?>">
                     <input type="date" class="form-control form-control-sm" name="akhir" value="<?= $akhir; ?>">
                     <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-primary" name="search"><i class="fas fa-search"></i></button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-12 col-md-2 mb-2">
               <a href="salesorderdetail.php">
                  <button type="button" class="btn btn-sm btn-outline-success btn-block"><i class="fas fa-eye"></i> Detail</button>
               </a>
            </div>
         </div>
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
                              <th>SO Number</th>
                              <th>Customer</th>
                              <th>Tgl Kirim</th>
                              <th>PO</th>
                              <th>Progress</th>
                              <th>Made By</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT salesorder.*, customers.nama_customer, users.fullname
                           FROM salesorder 
                           INNER JOIN customers ON salesorder.idcustomer = customers.idcustomer
                           INNER JOIN users ON salesorder.idusers = users.idusers
                           WHERE salesorder.deliverydate BETWEEN '$awal' AND '$akhir' AND salesorder.is_deleted = 0
                           ORDER BY salesorder.idso DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $progress = $tampil['progress'];
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['sonumber']; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td><?= $tampil['po']; ?></td>
                                 <?php
                                 if ($progress == "Delivered") { ?>
                                    <td class="text-success"><i class="fas fa-check-circle"></i> Delivered </td>
                                 <?php } elseif ($progress == "On Process") { ?>
                                    <td class="text-info"><i class="fas fa-spinner fa-pulse"></i> On Process</td>
                                 <?php } elseif ($progress == "On Delivery") { ?>
                                    <td style="color: #92079c;"><i class="fas fa-truck"></i> On Delivery</td>
                                 <?php } elseif ($progress == "DRAFT") { ?>
                                    <td class="text-secondary"><i class="fas fa-pencil-alt"></i> Draft</td>
                                 <?php } elseif ($progress == "Rejected") { ?>
                                    <td class="text-danger"><i class="fas fa-eject"></i> Rejected</td>
                                 <?php } else { ?>
                                    <td class="text-secondary"><i class="fas fa-clock"></i> Waiting</td>
                                 <?php } ?>
                                 <td><?= $tampil['fullname']; ?></td>
                                 <td class="text-center">
                                    <?php
                                    if ($progress == "Waiting") { ?>
                                       <a href="hideprice.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-warning">
                                          <i class="fas fa-eye-slash"></i>
                                       </a>
                                       <a href="lihatso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-primary">
                                          <i class="far fa-eye"></i>
                                       </a>
                                       <a href="editso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-success">
                                          <i class="far fa-edit"></i>
                                       </a>
                                       <a href="deleteso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                          <i class="far fa-trash-alt"></i>
                                       </a>
                                    <?php } elseif ($progress == "On Process") { ?>
                                       <a href="hideprice.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-warning">
                                          <i class="fas fa-eye-slash"></i>
                                       </a>
                                       <a href="lihatso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-primary">
                                          <i class="far fa-eye"></i>
                                       </a>
                                       <a href="editso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-success">
                                          <i class="far fa-edit"></i>
                                       </a>
                                       <a href="#" class="btn btn-sm btn-secondary">
                                          <i class="far fa-trash-alt"></i>
                                       </a>
                                    <?php } else { ?>
                                       <a href="hideprice.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-warning">
                                          <i class="fas fa-eye-slash"></i>
                                       </a>
                                       <a href="lihatso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-primary">
                                          <i class="far fa-eye"></i>
                                       </a>
                                       <a href="#" class="btn btn-sm btn-secondary" disabled>
                                          <i class="far fa-edit"></i>
                                       </a>
                                       <a href="#" class="btn btn-sm btn-secondary" disabled>
                                          <i class="far fa-trash-alt"></i>
                                       </a>
                                    <?php } ?>
                                 </td>
                              </tr>
                           <?php $no++;
                           } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>

   </section>
</div>


<script>
   // Mengubah judul halaman web
   document.title = "Sales Order List";
</script>
<?php
include "../footer.php";
?>