<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
$idgr = $_GET['idgr'];
?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <a href="index.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Back To List</button></a>
         </div>
      </div>
   </div>
</div>

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <form method="POST" action="prosesscangr.php" id="scanForm">
               <div class="card">
                  <div class="card-body">
                     <div id="items-container">
                        <div class="row mb-n2">
                           <div class="col-xs-2">
                              <div class="form-group">
                                 <input type="number" placeholder="Scan Here" class="form-control text-center" name="barcode" id="barcode" autofocus>
                              </div>
                           </div>
                           <input type="hidden" name="idgr" value="<?= $idgr ?>">
                           <div class="col-1">
                              <div class="form-group">
                                 <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>

            <div class="row">
               <div class="col-lg">
                  <div class="card">
                     <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>#</th>
                                 <th>Barcode</th>
                                 <th>Item</th>
                                 <th>Code</th>
                                 <th>Weight</th>
                                 <th>Pcs</th>
                                 <th>Hapus</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $no = 1;
                              $ambildata = mysqli_query($conn, "SELECT grdetail.*, barang.nmbarang, grade.nmgrade
                              FROM grdetail
                              INNER JOIN barang ON grdetail.idbarang = barang.idbarang
                              INNER JOIN grade ON grdetail.idgrade = grade.idgrade
                              WHERE idgr = $idgr ORDER BY idgrdetail DESC");
                              while ($tampil = mysqli_fetch_array($ambildata)) {
                                 $nmbarang = htmlspecialchars($tampil['nmbarang']);
                                 $nmgrade = htmlspecialchars($tampil['nmgrade']);
                              ?>
                                 <tr class="text-center">
                                    <td><?= $no; ?></td>
                                    <td><?= htmlspecialchars($tampil['kdbarcode']); ?></td>
                                    <td class="text-left"><?= $nmbarang; ?></td>
                                    <td><?= $nmgrade; ?></td>
                                    <td><?= number_format($tampil['qty'], 2); ?></td>
                                    <?php
                                    $pcs = $tampil['pcs'] < 1 ? "" : $tampil['pcs'];
                                    ?>
                                    <td><?= $pcs; ?></td>
                                    <td class="text-center">
                                       <a href="deletegrdetail.php?idgr=<?= $idgr; ?>&idgrdetail=<?= $tampil['idgrdetail']; ?>&from=grscan" class="text-info" onclick="return confirm('Yakin Lu?')">
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
            </div>
         </div>
      </div>
   </div>
</section>

<script>
   // Autofocus kembali ke input setelah submit
   document.getElementById("barcode").focus();

   // Prevent multiple form submissions
   const form = document.getElementById("scanForm");
   const submitBtn = document.getElementById("submitBtn");

   form.addEventListener("submit", function() {
      submitBtn.disabled = true;
   });

   // Submit form when pressing Enter in barcode input
   document.getElementById("barcode").addEventListener("keypress", function(event) {
      if (event.key === "Enter") {
         event.preventDefault();
         form.submit();
      }
   });
</script>

<?php include "../footer.php" ?>