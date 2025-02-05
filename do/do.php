<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "../notifcount.php";

// Ambil parameter pesan dari URL (jika ada)
$message = isset($_GET['message']) ? $_GET['message'] : "";

// Ambil tanggal awal & akhir untuk filter data
$awal = isset($_GET['awal']) ? $_GET['awal'] : date('Y-m-01');
$queryMaxDate = "SELECT MAX(deliverydate) AS max_date FROM do";
$resultMaxDate = mysqli_query($conn, $queryMaxDate);
$rowMaxDate = mysqli_fetch_assoc($resultMaxDate);
$maxDate = $rowMaxDate['max_date'];
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : $maxDate;

?>

<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <?php if (!empty($message)) : ?>
                  <div class="alert alert-warning alert-dismissible fade show" role="alert">
                     <strong>⚠️ Peringatan:</strong> <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
               <?php endif; ?>
            </div>
         </div>
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
               <a href="draftdo.php" class="btn btn-block btn-sm btn-outline-primary">
                  <span class="badge badge-danger"><?= $draftdo ?></span> Draft DO
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
                              <th>DO Number</th>
                              <th>Tgl Kirim</th>
                              <th>Customer</th>
                              <th>PO</th>
                              <th>Tally</th>
                              <th>xQty</th>
                              <th>rQty</th>
                              <th>Catatan</th>
                              <th>Status</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT do.*, customers.nama_customer, users.fullname, tally.notally 
                           FROM do
                           JOIN customers ON do.idcustomer = customers.idcustomer
                           JOIN users ON do.idusers = users.idusers
                           LEFT JOIN tally ON do.idtally = tally.idtally
                           WHERE do.deliverydate BETWEEN '$awal' AND '$akhir' AND do.is_deleted = 0
                           ORDER BY iddo DESC;");

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
                                 <td class="text-right"><?= number_format($tampil['rweight'] ?? 0, 2); ?></td>
                                 <td><?= $tampil['note']; ?></td>
                                 <td class="text-center">
                                    <?php if ($tampil['status'] == "Approved") { ?>
                                       <a href="editapprovedo.php?iddo=<?= $tampil['iddo'] ?>" onclick="return confirm('Apakah Anda yakin ingin meng-unapprove DO ini?')">
                                          <span class="badge badge-primary">Approved</span>
                                       </a>
                                    <?php } elseif ($tampil['status'] == "Unapproved") { ?>
                                       <a href="approvedo.php?iddo=<?= $tampil['iddo'] ?>">
                                          <span class="badge badge-danger">Klik Untuk Approve</span>
                                       </a>
                                    <?php } elseif ($tampil['status'] == "Rejected") { ?>
                                       <span class="badge badge-warning">Rejected</span>
                                    <?php } elseif ($tampil['status'] == "Invoiced") { ?>
                                       <span class="badge badge-success">Invoiced</span>
                                    <?php } else {
                                       echo $tampil['status'];
                                    } ?>
                                 </td>
                                 <td class="text-center">
                                    <div class="btn-group">
                                       <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">
                                          <i class="fas fa-bars"></i>
                                       </button>
                                       <div class="dropdown-menu">
                                          <a class="dropdown-item" href="lihatdo.php?iddo=<?= $tampil['iddo']; ?>">
                                             <i class="fas fa-eye"></i> Lihat
                                          </a>
                                          <?php if ($tampil['status'] !== "Rejected" && $tampil['status'] !== "Invoiced") { ?>
                                             <a class="dropdown-item" href="editdo.php?iddo=<?= $tampil['iddo']; ?>">
                                                <i class="fas fa-edit"></i> Edit
                                             </a>
                                          <?php } ?>
                                          <?php if ($tampil['status'] !== "Rejected" && $tampil['status'] !== "Invoiced") { ?>
                                             <a class="dropdown-item" href="deletedo.php?iddo=<?= $tampil['iddo']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus DO ini?');">
                                                <i class="fas fa-trash"></i> Hapus
                                             </a>
                                          <?php } ?>
                                       </div>
                                    </div>
                                 </td>
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