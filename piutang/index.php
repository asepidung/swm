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
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 mt-2">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>GROUP</th>
                              <th>BLM JATUH TEMPO</th>
                              <th>JATUH TEMPO</th>
                              <th>BLM TUKAR FAKTUR</th>
                           </tr>
                        </thead>
                        <?php
                        $no = 1;
                        $ambildata = mysqli_query($conn, "SELECT piutang.idgroup, groupcs.nmgroup, SUM(piutang.balance) AS total_balance, invoice.noinvoice, invoice.invoice_date, piutang.duedate
                        FROM piutang
                        JOIN groupcs ON piutang.idgroup = groupcs.idgroup
                        JOIN invoice ON piutang.idinvoice = invoice.idinvoice
                        GROUP BY piutang.idgroup");
                        while ($tampil = mysqli_fetch_array($ambildata)) {
                           // Hitung selisih hari antara duedate dan hari ini
                           $today = new DateTime();
                           $dueDate = new DateTime($tampil['duedate']);
                           $difference = $today->diff($dueDate);
                           $daysDifference = $difference->days;
                           // Tentukan status jatuh tempo
                           $statusJatuhTempo = ($today > $dueDate) ? '<span class="text-red">J.T ' . $daysDifference . ' Hari</span>' : $daysDifference . ' Hari Lagi';
                        ?>
                           <tr class="text-center">
                              <td><?= $no; ?></td>
                              <td class="text-left"><?= $tampil['nmgroup']; ?></td>
                              <td class="text-right"><?= ($today < $dueDate) ? number_format($tampil['total_balance'], 2) : ''; ?></td>
                              <td class="text-right"><?= ($today >= $dueDate) ? number_format($tampil['total_balance'], 2) : ''; ?></td>
                              <td class="text-right"></td>
                           </tr>
                        <?php
                           $no++;
                        }
                        ?>
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
   document.title = "DATA PIUTANG";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>