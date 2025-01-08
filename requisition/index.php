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
            <div class="col-12 col-sm-2">
               <a href="request.php" class="btn btn-block btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Request</a>
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
                     <?php
                     $sql = "SELECT r.*, u.fullname
                        FROM request r
                        INNER JOIN users u ON r.iduser = u.idusers
                        WHERE r.is_deleted IS NULL
                        ORDER BY r.idrequest DESC";
                     $result = $conn->query($sql);
                     ?>

                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Request Date</th>
                              <th>Request Number</th>
                              <th>User</th>
                              <th>Due Date</th>
                              <th>Notes</th>
                              <th>Status</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody class="text-center">
                           <?php
                           if ($result->num_rows > 0) {
                              // Output data untuk setiap baris
                              $i = 1; // untuk nomor urut
                              while ($row = $result->fetch_assoc()) {
                           ?>
                                 <tr>
                                    <td><?= $i ?></td>
                                    <td><?= date("D, d-M-y", strtotime($row['creatime'])) ?></td>
                                    <td><?= $row['norequest'] ?></td>
                                    <td><?= $row['fullname'] ?></td>
                                    <td><?= date("D, d-M-y", strtotime($row['duedate'])) ?></td>
                                    <td class="text-left"><?= $row['note'] ?></td>
                                    <td>
                                       <?php
                                       if ($row['stat'] === 'Request' && $_SESSION['idusers'] == 13) {
                                          // Jika status adalah 'Request' dan user ID adalah 16, tampilkan tombol Accept
                                          echo '<a href="accept.php?id=' . htmlspecialchars($row['idrequest']) . '" class="btn btn-sm btn-primary">
                                                   Accept
                                                </a>';
                                       } elseif ($row['stat'] === 'Waiting' && $_SESSION['idusers'] == 15) {
                                          // Jika status adalah 'Waiting' dan user ID adalah 1, tampilkan tombol Approved
                                          echo '<a href="wtoap.php?id=' . htmlspecialchars($row['idrequest']) . '" class="btn btn-sm btn-primary">
                                                   Approved
                                                </a>';
                                       } elseif ($row['stat'] === 'Ordering' && $_SESSION['idusers'] == 13) {
                                          // Jika status adalah 'Ordering' dan user ID adalah 16, tampilkan tombol Buat PO
                                          echo '<a href="makepo.php?id=' . htmlspecialchars($row['idrequest']) . '" class="btn btn-sm btn-success">
                                                   Buat PO
                                                </a>';
                                       } elseif ($row['stat'] === 'PO Created') {
                                          // Jika status adalah 'PO Created', tampilkan tombol Cetak PO
                                          echo '<a href="lihatpo.php?idrequest=' . htmlspecialchars($row['idrequest']) . '" class="btn btn-sm btn-secondary">
                                                   Cetak PO
                                                </a>';
                                       } else {
                                          // Jika tidak ada kondisi yang cocok, tampilkan status secara langsung
                                          echo htmlspecialchars($row['stat']);
                                       }
                                       ?>
                                    </td>

                                    <td>
                                       <a href="view.php?id=<?= $row['idrequest'] ?>" class='btn btn-info btn-sm' title="Lihat"><i class="fas fa-eye"></i></a>
                                       <a href="edit.php?id=<?= $row['idrequest'] ?>" class='btn btn-warning btn-sm' title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                       <?php
                                       if ($row['stat'] === 'Request') { ?>
                                          <a href="delete.php?id=<?= htmlspecialchars($row['idrequest']) ?>"
                                             class="btn btn-danger btn-sm"
                                             title="Delete"
                                             onclick="return confirm('Are you sure you want to delete this item?');">
                                             <i class="fas fa-trash-alt"></i>
                                          </a>
                                       <?php } else { ?>
                                          <a href="#" class="btn btn-danger btn-sm disabled" title="Cannot delete">
                                             <i class="fas fa-trash-alt"></i>
                                          </a>
                                       <?php } ?>
                                    </td>
                                 </tr>
                           <?php
                                 $i++;
                              }
                           } else {
                              echo "<tr><td colspan='8' class='text-center'>No data available</td></tr>";
                           }
                           ?>
                        </tbody>
                     </table>

                     <?php
                     // Tutup koneksi
                     $conn->close();
                     ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<script>
   document.title = "Request List";
</script>

<?php
include "../footer.php";
?>