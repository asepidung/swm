<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: verifications/login.php");
   exit(); // Tambahkan exit setelah redirect
}
require "../konak/conn.php";

$idgr = $_GET['idgr'];
$idusers = $_SESSION['idusers'];

// Query untuk mengambil data dari tabel gr
$query = "
SELECT gr.*, supplier.nmsupplier 
FROM gr 
JOIN supplier ON gr.idsupplier = supplier.idsupplier 
WHERE gr.idgr = $idgr";
$result = mysqli_query($conn, $query);
if (!$result) {
   die("Query error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" href="../dist/img/favicon.png" type="image/x-icon">
   <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
   <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
   <div class="wrapper">
      <div class="container">
         <div class="row mb-2">
            <img src="../dist/img/headerquo.png" alt="Logo-poproduct" class="img-fluid">
         </div>
         <?php while ($row = mysqli_fetch_assoc($result)) {
            $note = $row['note'];
            $grnumber = $row['grnumber'];
         ?>
            <h4 class="text-center">BUKTI TERIMA BARANG</h4>
            <h5 class="text-center">No :<b><i> <?= $grnumber; ?></i></b></h5>
            <table class="table table-sm table-borderless mt-3">
               <tr>
                  <td width="23%">Supplier</td>
                  <td width="1%">:</td>
                  <td><?= htmlspecialchars($row['nmsupplier']); ?></td>
               </tr>
               <tr>
                  <td width="23%">Receiving Date</td>
                  <td width="1%">:</td>
                  <td><?= htmlspecialchars(date('d-M-Y', strtotime($row['receivedate']))); ?></td>
               </tr>
            </table>
         <?php } ?>
         <table class="table table-sm table-bordered">
            <thead>
               <tr class="text-center">
                  <th width="2%">NO</th>
                  <th>Item Descriptions</th>
                  <th>Box</th>
                  <th>Quantity</th>
               </tr>
            </thead>
            <tbody>
               <?php
               // Query untuk mengambil detail dari tabel grdetail dengan penggabungan idbarang yang sama
               $querydetail = "
               SELECT barang.nmbarang, 
                     SUM(grdetail.qty) AS total, 
                     COUNT(grdetail.idbarang) AS box
               FROM grdetail 
               JOIN barang ON grdetail.idbarang = barang.idbarang 
               WHERE grdetail.idgr = $idgr
               GROUP BY grdetail.idbarang";

               $resultdetail = mysqli_query($conn, $querydetail);
               if (!$resultdetail) {
                  die("Query error: " . mysqli_error($conn));
               }

               $counter = 1;
               $boxtotal = 0;
               $totaljumlah = 0;

               while ($rowdetail = mysqli_fetch_assoc($resultdetail)) {
                  $boxtotal += $rowdetail['box'];
                  $totaljumlah += $rowdetail['total'];
               ?>
                  <tr>
                     <td class="text-center"><?= $counter; ?></td>
                     <td><?= htmlspecialchars($rowdetail['nmbarang']); ?></td>
                     <td class="text-center"><?= htmlspecialchars($rowdetail['box']); ?></td>
                     <td class="text-right"><?= htmlspecialchars($rowdetail['total']); ?></td>
                  </tr>
               <?php
                  $counter++;
               }
               ?>
            </tbody>
            <tfoot>
               <tr>
                  <th colspan="2" class="text-center">TOTAL </th>
                  <th class="text-center"><?= htmlspecialchars($boxtotal); ?></th>
                  <th class="text-right"><?= htmlspecialchars($totaljumlah); ?></th>
               </tr>
            </tfoot>
         </table>
         <div class="row mt-3">
            <div class="col-6 float-right text-justify">
               <?php if ($note == !null) { ?>
                  Catatan : <strong><?= $note; ?></strong>
               <?php } ?>
            </div>
         </div>
         <div class="row mt-4">
            <div class="col-8"></div>
            <div class="col-4 text-center">
               RECEIVING STAFF
               <br><br><br><br><br>
               <?= $_SESSION['fullname']; ?>
            </div>
         </div>
         <div class="col">
            <p><strong>Penting !</strong></p>
            <li>Dokumen ini diperuntukkan sebagai bukti penerimaan barang oleh PT. SANTI WIJAYA MEAT</li>
            <li>Dokumen Ini Wajib Dibawa Ketika Akan Melakukan Tukar Faktur</li>
         </div>
      </div>
   </div>
   <script>
      // Mengubah judul halaman web
      document.title = "<?= $grnumber; ?>";

      // Trigger the print dialog when the page loads
      window.onload = function() {
         window.print();
      };

      // Close the window after printing (optional)
      window.onafterprint = function() {
         window.location.href = 'index.php';
      };
   </script>
   <script src="../plugins/jquery/jquery.min.js"></script>
   <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="../dist/js/adminlte.min.js"></script>
</body>

</html>