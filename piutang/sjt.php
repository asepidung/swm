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
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>NAMA CUSTOMER</th>
                              <th>TGL INVOICE</th>
                              <th>NO INVOICE</th>
                              <th>NILAI</th>
                              <th>DUE DATE</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $idgroup = $_GET['id'];
                           $no = 1;
                           $sekarang = date('Y-m-d'); // Tanggal sekarang
                           $ambildata = mysqli_query($conn, "SELECT piutang.*, customers.nama_customer, invoice.noinvoice, invoice.invoice_date 
                                      FROM piutang
                                      JOIN customers ON piutang.idcustomer = customers.idcustomer
                                      JOIN invoice ON piutang.idinvoice = invoice.idinvoice
                                      WHERE piutang.idgroup = $idgroup AND piutang.duedate <= '$sekarang'");

                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $balance = $tampil['balance'];
                           ?>
                              <tr class="text-center">
                                 <td><?= $no; ?></td>
                                 <td class="text-left"><?= $tampil['nama_customer']; ?></td>
                                 <td><?= date("d-M-y", strtotime($tampil['invoice_date'])); ?></td>
                                 <td>
                                    <a href="../inv/lihatinvoice.php?idinvoice=<?= $tampil['idinvoice'] ?>">
                                       <?= $tampil['noinvoice']; ?>
                                    </a>
                                 </td>
                                 <td class="text-right"><?= number_format($balance, 2); ?></td>
                                 <td><?= date("d-M-y", strtotime($tampil['duedate'])); ?></td>
                              </tr>
                           <?php
                              $no++;
                           }
                           ?>
                        </tbody>

                     </table>
                  </div>
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
   document.title = "Piutang Jatuh Tempo";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>