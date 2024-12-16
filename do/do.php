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
$queryMaxDate = "SELECT MAX(deliverydate) AS max_date FROM do";
$resultMaxDate = mysqli_query($conn, $queryMaxDate);
$rowMaxDate = mysqli_fetch_assoc($resultMaxDate);
$maxDate = $rowMaxDate['max_date'];

// Tentukan $akhir sebagai tanggal maksimum dari kolom deliverydate
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : $maxDate;

$queryApprovedCount = "SELECT COUNT(*) AS approved_count FROM tally WHERE stat = 'Approved'";
$resultApprovedCount = mysqli_query($conn, $queryApprovedCount);
$rowApprovedCount = mysqli_fetch_assoc($resultApprovedCount);
$approvedCount = $rowApprovedCount['approved_count'];
?>
<div class="content-wrapper">
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
            <div class="col-2">
               <a href="draftdo.php" class="btn btn-block btn-sm btn-outline-primary"><span class="badge badge-danger"><?= $approvedCount ?></span> Draft</a>
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
                              <th>DO Number</th>
                              <th>Tgl Kirim</th>
                              <th>Customer</th>
                              <th>PO</th>
                              <th>Tally</th>
                              <th>xQty</th>
                              <th>rQty</th>
                              <th>Catatan</th>
                              <th>Status</th>
                              <!-- <th>Made By</th> -->
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT do.*, customers.nama_customer, users.fullname, tally.notally FROM do
                              JOIN customers ON do.idcustomer = customers.idcustomer
                              JOIN users ON do.idusers = users.idusers
                              LEFT JOIN tally ON do.idtally = tally.idtally
                              WHERE do.deliverydate BETWEEN '$awal' AND '$akhir'
                              ORDER BY iddo DESC;
                           ");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $idso = $tampil['idso'];
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center">
                                    <a href="cetakdo.php?iddo=<?= $tampil['iddo']; ?>"><?= $tampil['donumber']; ?></a>
                                 </td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td><?= $tampil['po']; ?></td>
                                 <td><a href="../tally/printtally.php?id=<?= $tampil['idtally'] ?> "><?= $tampil['notally']; ?></a></td>
                                 <td class="text-right"><?= number_format($tampil['xweight'], 2); ?></td>
                                 <td class="text-right"><?= number_format($tampil['rweight'], 2); ?></td>
                                 <td><?= $tampil['note']; ?></td>
                                 <td class="text-center">
                                    <?php if ($tampil['status'] == "Approved") { ?>
                                       <a href="editapprovedo.php?iddo=<?= $tampil['iddo'] ?>" onclick="return confirm('Apakah Anda yakin akan melakukan Approve ulang?')">
                                          <span class="text-primary" data-toggle="tooltip" data-placement="bottom" title="Approve By <?= $fullname ?>">
                                             <?= $tampil['status']; ?>
                                          </span>
                                       </a>
                                    <?php } elseif ($tampil['status'] == "Unapproved") { ?>
                                       <a href="approvedo.php?iddo=<?= $tampil['iddo'] ?>">
                                          <span class="text-danger" data-toggle="tooltip" data-placement="bottom" title="Klik Untuk Approve"><?= $tampil['status']; ?></span>
                                       </a>
                                    <?php } elseif ($tampil['status'] == "Rejected") { ?>
                                       <span class="text-warning"> <?= $tampil['status']; ?></span>
                                    <?php } else {
                                       echo $tampil['status'];
                                    } ?>
                                 </td>
                                 <!-- <td class="text-center"><?= $tampil['fullname']; ?></td> -->
                                 <td class="text-center">
                                    <div class="btn-group">
                                       <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Actions">
                                          <i class="fas fa-bars"></i>
                                       </button>
                                       <div class="dropdown-menu">
                                          <a class="dropdown-item" href="lihatdo.php?iddo=<?= $tampil['iddo']; ?>" title="Lihat">
                                             <i class="fas fa-eye"></i> Lihat
                                          </a>
                                          <?php if ($tampil['status'] !== "Rejected") { ?>
                                             <a class="dropdown-item" href="editdo.php?iddo=<?= $tampil['iddo']; ?>" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                             </a>
                                             <?php if ($tampil['status'] !== "Invoiced") { ?>
                                                <a class="dropdown-item" href="rejectdo.php?iddo=<?= $tampil['iddo']; ?>&idso=<?= $idso ?>" onclick="return confirm('apakah anda yakin ingin Mereject DO ini?')" title="Reject">
                                                   <i class="fas fa-eject"></i> Reject
                                                </a>
                                                <a class="dropdown-item" href="deletedo.php?iddo=<?= $tampil['iddo']; ?>" onclick="return confirm('apakah anda yakin ingin menghapus Surat Jalan ini?')" title="Hapus">
                                                   <i class="fas fa-trash"></i> Hapus
                                                </a>
                                             <?php } ?>
                                          <?php } ?>
                                       </div>
                                    </div>
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
   document.title = "Delivery Order";
</script>
<?php
include "../footer.php";
?>