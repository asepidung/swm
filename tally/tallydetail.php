<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
$idtally = $_GET['id'];

$querytally = "SELECT tally.*, customers.nama_customer
            FROM tally
            INNER JOIN customers ON tally.idcustomer = customers.idcustomer
            WHERE tally.idtally = $idtally";
$resulttally = mysqli_query($conn, $querytally);
$rowtally = mysqli_fetch_assoc($resulttally);
?>
<div class="content-header">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <a href="index.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Back To List</button></a>
            <a href="printtally.php?id=<?= $idtally ?>"><button type="button" class="btn btn-outline-success">Cetak Tally <i class="fas fa-arrow-alt-circle-right"></i></button></a>
            <span class="text-info float-right">
               <h4><?= $rowtally['nama_customer']; ?></h4>
            </span>
         </div>
      </div>
   </div>
</div>
<!-- Main content -->
<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <form method="POST" action="inputtallydetail.php">
               <div class="card">
                  <div class="card-body">
                     <div id="items-container">
                        <div class="row mb-n2">
                           <div class="col-xs-2">
                              <div class="form-group">
                                 <input type="text" placeholder="Scan Here" class="form-control text-center" name="barcode" id="barcode" autofocus>
                              </div>
                           </div>
                           <input type="hidden" name="idtally" value="<?= $idtally ?>">
                           <div class="col-1">
                              <div class="form-group">
                                 <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
                           </div>
                           <div class="col">
                              <?php if ($_GET['stat'] == "success") { ?>
                                 <h3 class="headline text-success"><i class="fas fa-check-circle"></i> Success</h3>
                              <?php } elseif ($_GET['stat'] == "ready") { ?>
                                 <h3 class="headline text-secondary"> Ready To Scan</h3>
                              <?php } elseif ($_GET['stat'] == "undeleted") { ?>
                                 <h3 class="headline text-success"> Data berhasil dihapus</h3>
                              <?php } elseif ($_GET['stat'] == "duplicate") { ?>
                                 <h3 class="headline text-warning"><i class="fas fa-exclamation-triangle"></i> Barang Sudah Terinput</h3>
                              <?php } elseif ($_GET['stat'] == "unlisted") { ?>
                                 <h3 class="headline text-danger"><i class="fas fa-times-circle"></i> Barang Tidak ada di PO</h3>
                              <?php } elseif ($_GET['stat'] == "unknown") { ?>
                                 <a href="tallymanual.php?id=<?= $idtally ?>">
                                    <span class="headline text-danger">BARANG TIDAK TERDAFTAR <br>
                                       Manual ADD <i class="fas fa-arrow-circle-right"></i>
                                    </span>
                                 </a>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
            <div class="row">
               <div class="col-lg-7">
                  <div class="card">
                     <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>#</th>
                                 <th>Barcode</th>
                                 <th>Item</th>
                                 <th>Weight</th>
                                 <th>Pcs</th>
                                 <th>POD</th>
                                 <th>Origin</th>
                                 <th>Hapus</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $no = 1;
                              $ambildata = mysqli_query($conn, "SELECT tallydetail.*, barang.nmbarang
                              FROM tallydetail
                              INNER JOIN barang ON tallydetail.idbarang = barang.idbarang WHERE idtally = $idtally ORDER BY idtallydetail DESC");
                              while ($tampil = mysqli_fetch_array($ambildata)) {
                                 $origin = $tampil['origin'];
                                 $nmbarang = $tampil['nmbarang'];
                                 $pod = $tampil['pod'];
                                 $podDate = new DateTime($pod);
                                 $today = new DateTime();
                                 $interval = $today->diff($podDate);
                                 $daysDiff = $interval->days;
                              ?>
                                 <tr class="text-center">
                                    <td><?= $no; ?></td>
                                    <td><?= $tampil['barcode']; ?></td>
                                    <td class="text-left"><?= $nmbarang; ?></td>
                                    <td><?= $tampil['weight']; ?></td>
                                    <td><?= $tampil['pcs']; ?></td>
                                    <?php
                                    if ($daysDiff >= 14) { ?>
                                       <td class="text-danger"><?= $daysDiff . " " . "Hari"; ?></td>
                                    <?php } else { ?>
                                       <td><?= $daysDiff . " " . "Hari"; ?></td>
                                    <?php  } ?>
                                    <td>
                                       <?php
                                       if ($origin == 1) {
                                          echo "BONING";
                                       } elseif ($origin == 2) {
                                          echo "TRADING";
                                       } elseif ($origin == 3) {
                                          echo "REPACK";
                                       } elseif ($origin == 4) {
                                          echo "RELABEL";
                                       } elseif ($origin == 5) {
                                          echo "IMPORT";
                                       } else {
                                          echo "Unindentified";
                                       }
                                       ?>
                                    </td>
                                    <td class="text-center">
                                       <a href="deletetallydetail.php?iddetail=<?= $tampil['idtallydetail']; ?>&id=<?= $idtally; ?>" class="text-info" onclick="return confirm('Yakin Lu?')">
                                          <i class="far fa-times-circle"></i>
                                       </a>
                                    </td>
                                 </tr>
                              <?php
                                 $no++;
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="col-lg-5">
                  <div class="card">
                     <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>Prod</th>
                                 <th>PO</th>
                                 <th>Qty</th>
                                 <th>Box</th>
                                 <th>Balance</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $idso_query = "SELECT idso FROM tally WHERE idtally = $idtally";
                              $idso_result = mysqli_query($conn, $idso_query);

                              if ($idso_result && $idso_row = mysqli_fetch_assoc($idso_result)) {
                                 $idso = $idso_row['idso'];

                                 $query = "SELECT sodetail.idbarang, barang.nmbarang, sodetail.weight
                                 FROM salesorderdetail AS sodetail
                                 INNER JOIN barang ON sodetail.idbarang = barang.idbarang
                                 WHERE sodetail.idso = $idso";

                                 $result = mysqli_query($conn, $query);
                                 while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                       <td class="ml-1"> <?= $row['nmbarang'] ?></td>
                                       <td class="text-center"><?= $row['weight'] ?></td>
                                       <td class="text-right">
                                          <?php
                                          $totalWeightQuery = "SELECT SUM(weight) AS total_weight
                                          FROM tallydetail
                                          WHERE idtally = $idtally AND idbarang = " . $row['idbarang'];
                                          $totalWeightResult = mysqli_query($conn, $totalWeightQuery);
                                          if ($totalWeightResult && $totalWeightRow = mysqli_fetch_assoc($totalWeightResult)) {
                                             echo $totalWeightRow['total_weight'];
                                          } else {
                                             echo "0"; // Jika tidak ada data, tampilkan 0
                                          }
                                          ?>
                                       </td>
                                       <td class="text-center">
                                          <?php
                                          $totalCountQuery = "SELECT COUNT(weight) AS total_weight
                                          FROM tallydetail
                                          WHERE idtally = $idtally AND idbarang = " . $row['idbarang'];
                                          $totalCountResult = mysqli_query($conn, $totalCountQuery);
                                          if ($totalCountResult && $totalCountRow = mysqli_fetch_assoc($totalCountResult)) {
                                             echo $totalCountRow['total_weight'];
                                          } else {
                                             echo "0"; // Jika tidak ada data, tampilkan 0
                                          }
                                          ?>
                                       </td>
                                       <?php
                                       $po = $row['weight'];
                                       $scan = $totalWeightRow['total_weight'];
                                       $sisa = $totalWeightRow['total_weight'] - $row['weight'];
                                       ?>
                                       <td class="text-right">
                                          <?php if ($sisa > 0) { ?>
                                             <span class="text-danger"><?= number_format($sisa, 2); ?></span>
                                          <?php } else { ?>
                                             <?= number_format($sisa, 2); ?>
                                          <?php } ?>
                                       </td>
                                    </tr>
                              <?php }
                              }
                              ?>
                           </tbody>
                           <?php
                           $totalPOQuery = "SELECT SUM(weight) AS total_weight FROM salesorderdetail WHERE idso = $idso";
                           $totalPOResult = mysqli_query($conn, $totalPOQuery);
                           if ($totalPOResult && $totalPORow = mysqli_fetch_assoc($totalPOResult)) {
                              $totalPO = $totalPORow['total_weight'];
                           } else {
                              $totalPO = 0; // Atur ke 0 jika tidak ada hasil
                           }

                           $totalQtyQuery = "SELECT SUM(weight) AS total_qty FROM tallydetail WHERE idtally = $idtally";
                           $totalQtyResult = mysqli_query($conn, $totalQtyQuery);
                           if ($totalQtyResult && $totalQtyRow = mysqli_fetch_assoc($totalQtyResult)) {
                              $totalQty = $totalQtyRow['total_qty'];
                           } else {
                              $totalQty = 0; // Atur ke 0 jika tidak ada hasil
                           }

                           $totalBoxQuery = "SELECT COUNT(weight) AS total_box FROM tallydetail WHERE idtally = $idtally";
                           $totalBoxResult = mysqli_query($conn, $totalBoxQuery);
                           if ($totalBoxResult && $totalBoxRow = mysqli_fetch_assoc($totalBoxResult)) {
                              $totalBox = $totalBoxRow['total_box'];
                           } else {
                              $totalBox = 0; // Atur ke 0 jika tidak ada hasil
                           }
                           $totalBalance = $totalQty - $totalPO;
                           ?>
                           <tfoot>
                              <tr class="text-right">
                                 <th>Total</th>
                                 <th class="text-center"><?= number_format($totalPO); ?></th>
                                 <th><?= number_format($totalQty, 2); ?></th>
                                 <th class="text-center"><?= number_format($totalBox); ?></th>
                                 <th><?= $totalBalance; ?></th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<script>
   document.title = "Tally Sheet";
</script>
<?php
include "../footer.php" ?>