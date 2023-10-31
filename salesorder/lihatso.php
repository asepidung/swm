<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";

$idso = $_GET['idso'];

$query = "SELECT salesorder.*, customers.nama_customer, customers.alamat1, customers.alamat2, customers.alamat3
FROM salesorder 
INNER JOIN customers ON salesorder.idcustomer = customers.idcustomer
WHERE idso = $idso";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<div class="container mt-4">
   <div class="col text-center">
      <h4 class="mb-n1">SALES ORDER</h4>
      <span><strong><?= $row['sonumber']; ?></strong></span>
   </div>
   <hr>
   <div class="row mt-2">
      <div class="col-md">
         <table class="table table-responsive table-borderless table-sm">
            <tr>
               <td>Customer</td>
               <td>:</td>
               <th><?= $row['nama_customer']; ?></th>
            </tr>
            <tr>
               <td>PO Numb</td>
               <td>:</td>
               <th><?= $row['po']; ?></th>
            </tr>
         </table>
      </div>
      <div class="col-xs">
         <table class="table table-responsive table-borderless table-sm">
            <tr>
               <td>Delivery Date</td>
               <td>:</td>
               <th><?= date('d-M-Y', strtotime($row['deliverydate'])); ?></th>
            </tr>
            <tr>
               <td>Ship To</td>
               <td>:</td>
               <th><?= $row['alamat1']; ?></th>
            </tr>
         </table>
      </div>
   </div>
   <table class="table table-sm table-striped table-bordered">
      <thead class="thead-dark">
         <tr class="text-center">
            <th>#</th>
            <th>Product Desc</th>
            <th>PO Qty</th>
            <th>Price</th>
            <th>Notes</th>
         </tr>
      </thead>
      <tbody>
         <?php
         $no = 1;
         $query_salesorderdetail = "SELECT salesorderdetail.*, barang.nmbarang
             FROM salesorderdetail
             INNER JOIN barang ON salesorderdetail.idbarang = barang.idbarang
             WHERE idso = '$idso'";
         $result_salesorderdetail = mysqli_query($conn, $query_salesorderdetail);
         while ($row_salesorderdetail = mysqli_fetch_assoc($result_salesorderdetail)) { ?>
            <tr>
               <td class="text-center"><?= $no; ?></td>
               <td><?= $row_salesorderdetail['nmbarang']; ?></td>
               <td class="text-right"><?= number_format($row_salesorderdetail['weight'], 2); ?></td>
               <td class="text-right"><?= number_format($row_salesorderdetail['price']); ?></td>
               <td><?= $row_salesorderdetail['notes']; ?></td>
            </tr>
         <?php $no++;
         } ?>
      </tbody>
   </table>
   <p class="mb-n1">
      <?php
      if ($row['note'] !== "") { ?>
         <strong>Catatan :</strong>
      <?php } else {
      } ?>
   </p>
   <p>
      <i><?= $row['note']; ?></i>
   </p>
   <div class="row">
      <div class="col-8"></div>
      <div class="col-1">
         <a href="hideprice.php?idso=<?= $idso ?>">
            <button type="button" class="btn btn-block btn-secondary"><i class="fas fa-eye-slash"></i></button>
         </a>
      </div>
      <div class="col-1">
         <a href="index.php">
            <button type="button" class="btn btn-block btn-warning"><i class="fas fa-undo"></i> Back</button>
         </a>
      </div>
      <div class="col-1">
         <a href="editso.php?idso=<?= $idso ?>">
            <button type="button" class="btn btn-block btn-info"><i class="fas fa-edit"></i> Edit</button>
         </a>
      </div>
      <div class="col-1">
         <a href="printso.php?idso=<?= $idso ?>">
            <button type="button" class="btn btn-block btn-primary"><i class="fas fa-print"></i> Print</button>
         </a>
      </div>
   </div>
</div>
</body>
<script>
   document.title = "<?= $row['nama_customer'] . " " . "Price List" ?>"
</script>
<?php

include "../footer.php"
?>