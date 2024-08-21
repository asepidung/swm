<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$queryApprovedCount = "SELECT COUNT(*) AS approved_count FROM salesorder WHERE progress = 'Waiting'";
$resultApprovedCount = mysqli_query($conn, $queryApprovedCount);
$rowApprovedCount = mysqli_fetch_assoc($resultApprovedCount);
$approvedCount = $rowApprovedCount['approved_count'];
?>
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-12 col-md-1 mb-2">
               <a href="drafttally.php">
                  <button type="button" class="btn btn-sm btn-outline-primary btn-block">
                     <span class="badge badge-danger"> <?= $approvedCount; ?></span> Draft
                  </button>
               </a>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </div>
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <?php
         if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
         }
         ?>
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body table-responsive">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Customer</th>
                              <th>Tgl Kirim</th>
                              <th>SO Number</th>
                              <th>Tally ID</th>
                              <th>PO</th>
                              <th>Note</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT tally.*, customers.nama_customer, salesorder.note
                           FROM tally 
                           INNER JOIN customers ON tally.idcustomer = customers.idcustomer
                           INNER JOIN salesorder ON tally.idso = salesorder.idso
                           ORDER BY idtally DESC");

                           if (!$ambildata) {
                              die("Query error: " . mysqli_error($conn));
                           }

                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $idso = $tampil['idso'];

                              if ($tampil['stat'] == 'Rejected') {
                                 echo '<tr class="text-danger">';
                              } else {
                                 echo '<tr>';
                              }
                           ?>
                              <td class="text-center"><?= $no; ?></td>
                              <td><?= htmlspecialchars($tampil['nama_customer']); ?></td>
                              <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                              <td class="text-center">
                                 <a href="printso.php?idso=<?= $tampil['idso']; ?>">
                                    <?= htmlspecialchars($tampil['sonumber']); ?>
                                 </a>
                              </td>
                              <td class="text-center"><?= htmlspecialchars($tampil['notally']); ?></td>
                              <td><?= htmlspecialchars($tampil['po']); ?></td>
                              <td class="text-truncate" style="max-width: 150px;" title="<?= $tampil['note']; ?>"><?= htmlspecialchars($tampil['note']); ?></td>
                              <td class="text-center d-flex justify-content-center flex-wrap">
                                 <a href="lihattally.php?id=<?= $tampil['idtally'] ?>">
                                    <button type="button" class="btn btn-sm btn-warning"><i class="fas fa-eye"></i></button>
                                 </a>
                                 <?php
                                 $query_check_stat = "SELECT stat FROM tally WHERE idtally = {$tampil['idtally']}";
                                 $result_check_stat = mysqli_query($conn, $query_check_stat);

                                 if ($result_check_stat) {
                                    $row_check_stat = mysqli_fetch_assoc($result_check_stat);
                                    $stat = $row_check_stat['stat'];

                                    switch ($stat) {
                                       case "":
                                 ?>
                                          <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Mulai Scan" onclick="window.location.href='tallydetail.php?id=<?= $tampil['idtally'] ?>&stat=ready'">
                                             <i class="fas fa-tasks"></i>
                                          </a>
                                          <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Approve" href="approvetally.php?id=<?= htmlspecialchars($tampil['idtally']) ?>&idso=<?= htmlspecialchars($idso) ?>">
                                             <i class="far fa-calendar-check"></i>
                                          </a>
                                          <a href="deletetally.php?id=<?= $tampil['idtally']; ?>&idso=<?= $idso ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="return confirm('Semua barang yang ada di tally akan kembali ke stock, apa anda yakin ?')">
                                             <i class="fas fa-minus-square"></i>
                                          </a>
                                       <?php
                                          break;

                                       case "Approved":
                                       ?>
                                          <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Scan Denied">
                                             <i class="fas fa-tasks"></i>
                                          </a>
                                          <a class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Unapprove" href="unapprovetally.php?id=<?= htmlspecialchars($tampil['idtally']) ?>&idso=<?= htmlspecialchars($idso) ?>">
                                             <i class="fas fa-calendar-times"></i>
                                          </a>
                                          <a href="#" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Approved">
                                             <i class="fas fa-minus-square"></i>
                                          </a>
                                       <?php
                                          break;

                                       case "DO":
                                       case "Rejected":
                                       ?>
                                          <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Scan Denied">
                                             <i class="fas fa-tasks"></i>
                                          </a>
                                          <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?= ($stat == 'DO') ? 'DO' : 'Rejected'; ?>">
                                             <i class="fas fa-calendar"></i>
                                          </a>
                                          <a href="#" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?= ($stat == 'DO') ? 'DO' : 'Rejected'; ?>">
                                             <i class="fas fa-minus-square"></i>
                                          </a>
                                 <?php
                                          break;

                                       default:
                                          echo "Unknown stat value: $stat";
                                          break;
                                    }
                                 } else {
                                    echo "Error: " . mysqli_error($conn);
                                 }
                                 ?>
                              </td>
                              <!-- <td class="text-center">
                                 <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       Actions
                                    </button>
                                    <div class="dropdown-menu">
                                       <a class="dropdown-item" href="lihattally.php?id=<?= $tampil['idtally'] ?>">
                                          <i class="fas fa-eye"></i> View
                                       </a>
                                       <?php
                                       $query_check_stat = "SELECT stat FROM tally WHERE idtally = {$tampil['idtally']}";
                                       $result_check_stat = mysqli_query($conn, $query_check_stat);

                                       if ($result_check_stat) {
                                          $row_check_stat = mysqli_fetch_assoc($result_check_stat);
                                          $stat = $row_check_stat['stat'];

                                          switch ($stat) {
                                             case "":
                                       ?>
                                                <a class="dropdown-item" href="tallydetail.php?id=<?= $tampil['idtally'] ?>&stat=ready">
                                                   <i class="fas fa-tasks"></i> Start Scan
                                                </a>
                                                <a class="dropdown-item" href="approvetally.php?id=<?= htmlspecialchars($tampil['idtally']) ?>&idso=<?= htmlspecialchars($idso) ?>">
                                                   <i class="far fa-calendar-check"></i> Approve
                                                </a>
                                                <a class="dropdown-item text-danger" href="deletetally.php?id=<?= $tampil['idtally']; ?>&idso=<?= $idso ?>" onclick="return confirm('Semua barang yang ada di tally akan kembali ke stock, apa anda yakin ?')">
                                                   <i class="fas fa-minus-square"></i> Delete
                                                </a>
                                             <?php
                                                break;

                                             case "Approved":
                                             ?>
                                                <a class="dropdown-item disabled">
                                                   <i class="fas fa-tasks"></i> Scan Denied
                                                </a>
                                                <a class="dropdown-item text-danger" href="unapprovetally.php?id=<?= htmlspecialchars($tampil['idtally']) ?>&idso=<?= htmlspecialchars($idso) ?>">
                                                   <i class="fas fa-calendar-times"></i> Unapprove
                                                </a>
                                                <a class="dropdown-item disabled">
                                                   <i class="fas fa-minus-square"></i> Approved
                                                </a>
                                             <?php
                                                break;

                                             case "DO":
                                             case "Rejected":
                                             ?>
                                                <a class="dropdown-item disabled">
                                                   <i class="fas fa-tasks"></i> Scan Denied
                                                </a>
                                                <a class="dropdown-item disabled">
                                                   <i class="fas fa-calendar"></i> <?= ($stat == 'DO') ? 'DO' : 'Rejected'; ?>
                                                </a>
                                                <a class="dropdown-item disabled">
                                                   <i class="fas fa-minus-square"></i> <?= ($stat == 'DO') ? 'DO' : 'Rejected'; ?>
                                                </a>
                                       <?php
                                                break;

                                             default:
                                                echo "Unknown stat value: $stat";
                                                break;
                                          }
                                       } else {
                                          echo "Error: " . mysqli_error($conn);
                                       }
                                       ?>
                                    </div>
                                 </div>
                              </td> -->
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
   document.title = "Tally Sheet";
</script>
<?php
include "../footer.php";
?>