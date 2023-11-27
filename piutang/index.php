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
                              <th>TOTAL</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $total_belum_jatuh_tempo = 0;
                           $total_sudah_jatuh_tempo = 0;

                           $ambildata = mysqli_query($conn, "SELECT piutang.idgroup, groupcs.nmgroup, 
                            SUM(CASE WHEN piutang.duedate > CURDATE() THEN piutang.balance ELSE 0 END) AS belum_jatuh_tempo,
                            SUM(CASE WHEN piutang.duedate <= CURDATE() THEN piutang.balance ELSE 0 END) AS sudah_jatuh_tempo
                           FROM piutang
                           JOIN groupcs ON piutang.idgroup = groupcs.idgroup
                           GROUP BY piutang.idgroup");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $total_belum_jatuh_tempo += $tampil['belum_jatuh_tempo'];
                              $total_sudah_jatuh_tempo += $tampil['sudah_jatuh_tempo'];
                              $totalpiutang = $total_belum_jatuh_tempo + $total_sudah_jatuh_tempo;
                           ?>
                              <tr class="text-center">
                                 <td><?= $no; ?></td>
                                 <td class="text-left">
                                    <a href="piutangcs.php?id=<?= $tampil['idgroup'] ?>">
                                       <?= $tampil['nmgroup']; ?>
                                    </a>
                                 </td>
                                 <td class="text-right">
                                    <a href="bjt.php?id=<?= $tampil['idgroup'] ?>">
                                       <?= number_format($tampil['belum_jatuh_tempo'], 2); ?>
                                    </a>
                                 </td>
                                 <td class="text-right">
                                    <a href="sjt.php?id=<?= $tampil['idgroup'] ?>">
                                       <?= number_format($tampil['sudah_jatuh_tempo'], 2); ?>
                                    </a>
                                 </td>
                                 <td class="text-right"><?= number_format($totalpiutang, 2); ?></td>
                              </tr>
                           <?php
                              $no++;
                           }
                           ?>
                        </tbody>
                        <tfoot>
                           <tr class="text-center">
                              <td colspan="2"><strong>SUB Total</strong></td>
                              <td class="text-right"><strong><?= number_format($total_belum_jatuh_tempo, 2); ?></strong></td>
                              <td class="text-right"><strong><?= number_format($total_sudah_jatuh_tempo, 2); ?></strong></td>
                              <td class="text-right"></td>
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
   document.title = "DATA PIUTANG";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>