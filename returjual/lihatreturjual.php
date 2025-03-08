<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
$idreturjual = $_GET['idreturjual'];
$query = "SELECT returjual.*, customers.nama_customer
FROM returjual 
INNER JOIN customers ON returjual.idcustomer = customers.idcustomer
WHERE idreturjual = $idreturjual";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<div class="container mt-4">
   <div class="col text-center">
      <h4 class="mb-n1">SALES RETURN</h4>
      <span><strong><?= $row['returnnumber']; ?></strong></span>
   </div>
   <hr>
   <div class="row mt-2">
      <div class="col-5">
         <table class="table table-responsive table-borderless table-sm">
            <tr>
               <td>Customer</td>
               <td>:</td>
               <th><?= $row['nama_customer']; ?></th>
            </tr>
            <tr>
               <td>DO Number</td>
               <td>:</td>
               <th><?= $row['donumber']; ?></th>
            </tr>
            <tr>
               <td>Return Date</td>
               <td>:</td>
               <th><?= $row['returdate']; ?></th>
            </tr>
         </table>
      </div>
      <div class="col-2"></div>
   </div>
   <table class="table table-sm table-striped table-bordered">
      <thead class="thead-dark">
         <tr class="text-center">
            <th>#</th>
            <th>Product Desc</th>
            <th>Box</th>
            <th>Qty</th>
            <th>Notes</th>
         </tr>
      </thead>
      <tbody>
         <?php
         $no = 1;
         $query_returjualdetail = "SELECT returjualdetail.*, barang.nmbarang
             FROM returjualdetail
             INNER JOIN barang ON returjualdetail.idbarang = barang.idbarang
             WHERE idreturjual = '$idreturjual'";
         $result_returjualdetail = mysqli_query($conn, $query_returjualdetail);
         while ($row_returjualdetail = mysqli_fetch_assoc($result_returjualdetail)) { ?>
            <tr>
               <td class="text-center"><?= $no; ?></td>
               <td><?= $row_returjualdetail['nmbarang']; ?></td>
               <td class="text-center"><?= $row_returjualdetail['box']; ?></td>
               <td class="text-right"><?= number_format($row_returjualdetail['weight'], 2); ?></td>
               <td><?= $row_returjualdetail['notes']; ?></td>
            </tr>
         <?php $no++;
         } ?>
      </tbody>
      <tfoot>
         <tr>
            <th colspan="2" class="text-right">Total</th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th></th>
         </tr>
      </tfoot>
   </table>
   <p class="mb-n1">
      <?php
      if ($row['note'] !== "") { ?>
         <strong>Catatan :</strong>
      <?php } else {
         echo "-";
      } ?>
   </p>
   <p>
      <i><?= $row['note']; ?></i>
   </p>
   <div class="row mt-3">
      <div class="col-9"></div>
      <div class="col-1">
         <a href="index.php">
            <button type="button" class="btn btn-block btn-warning"><i class="fas fa-undo"></i> Back</button>
         </a>
      </div>
      <div class="col-1">
         <a href="editso.php?idreturjual=<?= $idreturjual ?>">
            <button type="button" class="btn btn-block btn-info"><i class="fas fa-edit"></i> Edit</button>
         </a>
      </div>
      <div class="col-1">
         <a href="printreturjual.php?idreturjual=<?= $idreturjual ?>">
            <button type="button" class="btn btn-block btn-primary"><i class="fas fa-print"></i> Print</button>
         </a>
      </div>
   </div>
</div>
</body>
<script>
   document.title = "<?= $row['returnnumber']; ?>"
</script>
<?php

include "../footer.php"
?>