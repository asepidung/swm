<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";

/* =========================
   AMBIL PARAMETER
========================= */
$iddo = isset($_GET['iddo']) ? (int)$_GET['iddo'] : 0;
$idso = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($iddo <= 0) {
   echo "Invalid DO ID";
   exit();
}

/* =========================
   AMBIL DO + IDTALLY
========================= */
$qdo = mysqli_prepare($conn, "
    SELECT d.idtally, d.idso, c.nama_customer
    FROM do d
    LEFT JOIN customers c ON d.idcustomer = c.idcustomer
    WHERE d.iddo = ?
");
mysqli_stmt_bind_param($qdo, "i", $iddo);
mysqli_stmt_execute($qdo);
$resdo = mysqli_stmt_get_result($qdo);
$do = mysqli_fetch_assoc($resdo);

if (!$do || empty($do['idtally'])) {
   echo "DO tidak memiliki tally";
   exit();
}

$idtally = (int)$do['idtally'];
$nama_customer = $do['nama_customer'] ?? '-';

/* =========================
   AMBIL DATA TALLY DETAIL
========================= */
$query = "
SELECT 
    td.*,
    b.nmbarang,
    g.nmgrade
FROM tallydetail td
INNER JOIN barang b ON td.idbarang = b.idbarang
INNER JOIN grade g ON td.idgrade = g.idgrade
WHERE td.idtally = ?
ORDER BY b.nmbarang
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $idtally);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <h4 class="text-info">
               Pilih Item Yang Akan Dikembalikan Ke Stock<br>
               <small><?= htmlspecialchars($nama_customer); ?></small>
            </h4>
         </div>
      </div>
   </div>
</div>

<section class="content">
   <div class="container-fluid">
      <form method="POST" action="pengembalianproduct.php">
         <input type="hidden" name="iddo" value="<?= $iddo; ?>">
         <input type="hidden" name="idso" value="<?= $idso; ?>">
         <input type="hidden" name="idtally" value="<?= $idtally; ?>">

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
                        <th>POD</th>
                        <th>Origin</th>
                        <th>Pilih</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $no = 1;
                     if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {

                           switch ($row['origin']) {
                              case 1:
                                 $origin = 'BONING';
                                 break;
                              case 2:
                                 $origin = 'TRADING';
                                 break;
                              case 3:
                                 $origin = 'REPACK';
                                 break;
                              case 4:
                                 $origin = 'RELABEL';
                                 break;
                              case 5:
                                 $origin = 'IMPORT';
                                 break;
                              case 6:
                                 $origin = 'RTN';
                                 break;
                              default:
                                 $origin = 'UNKNOWN';
                           }
                     ?>
                           <tr class="text-center">
                              <td><?= $no++; ?></td>
                              <td><?= htmlspecialchars($row['barcode']); ?></td>
                              <td class="text-left"><?= htmlspecialchars($row['nmbarang']); ?></td>
                              <td><?= htmlspecialchars($row['nmgrade']); ?></td>
                              <td><?= number_format($row['weight'], 2); ?></td>
                              <td><?= $row['pcs'] > 0 ? $row['pcs'] : ''; ?></td>
                              <td><?= date('d-M-Y', strtotime($row['pod'])); ?></td>
                              <td><?= $origin; ?></td>
                              <td>
                                 <input type="checkbox" name="items[]" value="<?= $row['idtallydetail']; ?>">
                              </td>
                           </tr>
                     <?php
                        }
                     } else {
                        echo "<tr><td colspan='9' class='text-center text-muted'>Tidak ada data</td></tr>";
                     }
                     ?>
                  </tbody>
               </table>

               <button type="button" class="btn btn-success btn-sm" id="check-all">
                  <i class="fas fa-check-double"></i> Check All
               </button>
            </div>
         </div>

         <a href="approvedo.php?iddo=<?= $iddo; ?>" class="btn btn-warning">Cancel</a>
         <button type="submit" class="btn btn-primary">
            Proses Pengembalian <i class="fas fa-arrow-circle-right"></i>
         </button>
      </form>
   </div>
</section>

<script>
   document.title = "Tally <?= addslashes($nama_customer); ?>";

   document.getElementById('check-all').addEventListener('click', function() {
      const boxes = document.querySelectorAll('input[name="items[]"]');
      let allChecked = true;

      boxes.forEach(b => {
         if (!b.checked) allChecked = false;
      });
      boxes.forEach(b => b.checked = !allChecked);
   });
</script>

<?php include "../footer.php"; ?>