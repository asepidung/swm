<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";
include "../header.php";

$idso = $_GET['id'];
$iddo = $_GET['iddo'];

// Amankan nilai $idso untuk menghindari SQL Injection
$idso = mysqli_real_escape_string($conn, $idso);

$querytally = "SELECT tally.*, customers.nama_customer
               FROM tally
               INNER JOIN customers ON tally.idcustomer = customers.idcustomer
               WHERE tally.idso = $idso";

$resulttally = mysqli_query($conn, $querytally);

// Periksa apakah query berhasil
if ($resulttally) {
   $rowtally = mysqli_fetch_assoc($resulttally);
   if (!$rowtally) {
      echo "Data not found.";
      exit();
   }
   // Ambil idtally dari hasil query
   $idtally = $rowtally['idtally'];
} else {
   // Jika query gagal, tampilkan pesan kesalahan
   echo "Error: " . mysqli_error($conn);
   exit();
}
?>

<div class="content-header">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <span class="text-info">
               <h4>Pilih Item Yang Akan dikembalikan Ke Stock </h4>
            </span>
         </div>
      </div>
   </div>
</div>
<!-- Main content -->
<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <form id="returnForm" method="POST" action="pengembalianproduct.php">
               <input type="hidden" name="iddo" value="<?= htmlspecialchars($iddo); ?>">
               <div class="row">
                  <div class="col-8">
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
                                 $ambildata = mysqli_query($conn, "SELECT tallydetail.*, barang.nmbarang, grade.nmgrade
                                 FROM tallydetail
                                 INNER JOIN barang ON tallydetail.idbarang = barang.idbarang
                                 INNER JOIN grade ON tallydetail.idgrade = grade.idgrade
                                 WHERE idtally = $idtally ORDER BY barang.nmbarang");

                                 if ($ambildata) {
                                    while ($tampil = mysqli_fetch_array($ambildata)) {
                                       $origin = $tampil['origin'];
                                       $nmbarang = $tampil['nmbarang'];
                                       $nmgrade = $tampil['nmgrade'];
                                       $barcode = $tampil['barcode'];
                                       $pod = $tampil['pod'];
                                       $podDate = new DateTime($pod);
                                       $today = new DateTime();
                                       $interval = $today->diff($podDate);
                                       $daysDiff = $interval->days;
                                 ?>
                                       <tr class="text-center">
                                          <td><?= $no; ?></td>
                                          <td><?= htmlspecialchars($barcode); ?></td>
                                          <td class="text-left"><?= htmlspecialchars($nmbarang); ?></td>
                                          <td><?= htmlspecialchars($nmgrade); ?></td>
                                          <td><?= number_format($tampil['weight'], 2); ?></td>
                                          <td><?= htmlspecialchars($tampil['pcs'] < 1 ? "" : $tampil['pcs']); ?></td>
                                          <td><?= date('d-M-Y', strtotime($pod)); ?></td>
                                          <td>
                                             <?php
                                             switch ($origin) {
                                                case 1:
                                                   echo "BONING";
                                                   break;
                                                case 2:
                                                   echo "TRADING";
                                                   break;
                                                case 3:
                                                   echo "REPACK";
                                                   break;
                                                case 4:
                                                   echo "RELABEL";
                                                   break;
                                                case 5:
                                                   echo "IMPORT";
                                                   break;
                                                case 6:
                                                   echo "RTN";
                                                   break;
                                                default:
                                                   echo "Unidentified";
                                                   break;
                                             }
                                             ?>
                                          </td>
                                          <td>
                                             <input class="form-check-input" type="checkbox" name="items[]" value="<?= htmlspecialchars(json_encode($tampil)); ?>">
                                          </td>
                                       </tr>
                                 <?php
                                       $no++;
                                    }
                                 } else {
                                    // Jika query gagal, tampilkan pesan kesalahan
                                    echo "<tr><td colspan='8'>Error: " . mysqli_error($conn) . "</td></tr>";
                                 }
                                 ?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col">
                        <button type="button" class="btn btn-success" id="check-all-btn"><i class="fas fa-check-double"></i> Check All</button>
                     </div>
                  </div>
               </div>
               <div class="col">
                  <button type="submit" class="btn btn-primary">Proses Pengembalian <i class="fas fa-arrow-circle-right"></i></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</section>
<script>
   document.title = "<?= 'Tally ' . htmlspecialchars($rowtally['nama_customer']); ?>";

   document.getElementById('check-all-btn').addEventListener('click', function() {
      var checkboxes = document.querySelectorAll('input[name="items[]"]');
      var allChecked = true;

      checkboxes.forEach(function(checkbox) {
         if (!checkbox.checked) {
            allChecked = false;
         }
      });

      checkboxes.forEach(function(checkbox) {
         checkbox.checked = !allChecked;
      });

      this.innerHTML = allChecked ? '<i class="fas fa-check-double"></i> Check All' : '<i class="fas fa-times"></i> Uncheck All';
   });
</script>
<?php
include "../footer.php";
?>