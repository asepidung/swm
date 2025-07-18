<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
$idso = $_GET['idso'];
include "totalpo.php";

$query = "SELECT salesorder.*, customers.nama_customer, customers.alamat1, customers.alamat2, customers.alamat3
FROM salesorder 
INNER JOIN customers ON salesorder.idcustomer = customers.idcustomer
WHERE idso = $idso";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<div class="container mt-4">
   <div class="row justify-content-center">
      <div class="col-lg-8">
         <div class="text-center">
            <h4 class="mb-n1">SALES ORDER</h4>
            <span><strong><?= $row['sonumber']; ?></strong></span>
         </div>
         <hr>
         <div class="row mt-2">
            <div class="col-md-6 mb-2">
               <table class="table table-borderless table-sm">
                  <tr>
                     <td>Customer</td>
                     <td>:</td>
                     <th><?= $row['nama_customer']; ?></th>
                  </tr>
                  <tr>
                     <td>Ship To</td>
                     <td>:</td>
                     <th><?= $row['alamat1']; ?></th>
                  </tr>
               </table>
            </div>
            <div class="col-md-6 mb-2">
               <table class="table table-borderless table-sm">
                  <tr>
                     <td>PO Numb</td>
                     <td>:</td>
                     <th><?= $row['po']; ?></th>
                  </tr>
                  <tr>
                     <td>Delivery Date</td>
                     <td>:</td>
                     <th><?= date('d-M-Y', strtotime($row['deliverydate'])); ?></th>
                  </tr>
               </table>
            </div>
         </div>
         <table class="table table-sm table-striped table-bordered">
            <thead class="thead-dark">
               <tr class="text-center">
                  <th>#</th>
                  <th>Product Code</th>
                  <th>Product Name</th>
                  <th>Order Qty</th>
                  <!-- <th>Price</th> -->
                  <th>Notes</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $no = 1;
               $query_salesorderdetail = "SELECT salesorderdetail.*, barang.nmbarang, barang.kdbarang
                FROM salesorderdetail
                INNER JOIN barang ON salesorderdetail.idbarang = barang.idbarang
                WHERE idso = '$idso'";
               $result_salesorderdetail = mysqli_query($conn, $query_salesorderdetail);
               while ($row_salesorderdetail = mysqli_fetch_assoc($result_salesorderdetail)) { ?>
                  <tr>
                     <td class="text-center"><?= $no; ?></td>
                     <td><?= $row_salesorderdetail['kdbarang']; ?></td>
                     <td><?= $row_salesorderdetail['nmbarang']; ?></td>
                     <td class="text-right"><?= number_format($row_salesorderdetail['weight'], 2); ?></td>
                     <!-- <td class="text-center">*****</td> -->
                     <td><?= $row_salesorderdetail['notes']; ?></td>
                  </tr>
               <?php $no++;
               } ?>
            </tbody>
            <tfoot>
               <tr>
                  <th colspan="3" class="text-right">Weight Total</th>
                  <th class="text-right"><?= number_format($totalPO, 2); ?></th>
                  <th></th>
               </tr>
            </tfoot>
         </table>
         <p class="mb-3">
            <?php
            if ($row['note'] !== "") { ?>
               <strong>Catatan :</strong>
            <?php } else {
               echo "-";
            } ?>
         </p>
         <p><i><?= $row['note']; ?></i></p>
         <div class="row mt-3">
            <div class="col-6 col-md-3 mb-2">
               <a href="lihatso.php?idso=<?= $idso ?>">
                  <button type="button" class="btn btn-block btn-success"><i class="fas fa-eye"></i></button>
               </a>
            </div>
            <div class="col-6 col-md-3 mb-2">
               <a href="index.php">
                  <button type="button" class="btn btn-block btn-warning"><i class="fas fa-undo"></i> Back</button>
               </a>
            </div>
            <div class="col-6 col-md-3 mb-2">
               <a href="editso.php?idso=<?= $idso ?>">
                  <button type="button" class="btn btn-block btn-info"><i class="fas fa-edit"></i> Edit</button>
               </a>
            </div>
            <div class="col-6 col-md-3 mb-2">
               <a href="printso.php?idso=<?= $idso ?>">
                  <button type="button" class="btn btn-block btn-primary"><i class="fas fa-print"></i> Print</button>
               </a>
            </div>
         </div>
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