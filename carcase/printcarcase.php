<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";
// include "../header.php";

// Validasi dan sanitasi idcarcase dari URL
$idcarcase = $_GET['idcarcase'] ?? null;
if (!$idcarcase || !is_numeric($idcarcase)) {
   echo "<script>alert('ID Carcase tidak valid!'); window.location='carcase.php';</script>";
   exit();
}

// Query untuk mendapatkan data carcase dan supplier
$query = "SELECT carcase.*, supplier.nmsupplier, carcase.killdate, carcase.breed, carcase.note
          FROM carcase
          INNER JOIN supplier ON carcase.idsupplier = supplier.idsupplier
          WHERE carcase.idcarcase = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idcarcase);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Menangani jika data tidak ditemukan
if (!$row) {
   echo "<script>alert('Data tidak ditemukan!'); window.location='carcase.php';</script>";
   exit();
}

// Query untuk menghitung jumlah eartag pada carcasedetail
$countQuery = "SELECT COUNT(*) as head_count FROM carcasedetail WHERE idcarcase = ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("i", $idcarcase);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$headCount = $countRow['head_count'];

// Ambil data dari tabel carcasedetail yang berhubungan dengan idcarcase
$detailQuery = "SELECT * FROM carcasedetail WHERE idcarcase = ?";
$detailStmt = $conn->prepare($detailQuery);
$detailStmt->bind_param("i", $idcarcase);
$detailStmt->execute();
$detailsResult = $detailStmt->get_result();

// Inisialisasi variabel total untuk setiap kolom
$totalBerat = 0;
$totalCarcase1 = 0;
$totalCarcase2 = 0;
$totalHides = 0;
$totalTails = 0;

while ($detailRow = $detailsResult->fetch_assoc()) {
   // Tambahkan nilai kolom ke variabel total masing-masing
   $totalBerat += $detailRow['berat'];
   $totalCarcase1 += $detailRow['carcase1'];
   $totalCarcase2 += $detailRow['carcase2'];
   $totalHides += $detailRow['hides'];
   $totalTails += $detailRow['tail'];
}

// Hitung nilai Offal, Kulit, dan Karkas %
$totalOffal = $totalCarcase1 + $totalCarcase2 + $totalTails;
$totalKulit = $totalHides;
$karkasPercentage = ($totalBerat > 0) ? (($totalCarcase1 + $totalCarcase2) / $totalBerat) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Data Karkas</title>
   <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Pastikan jalur CSS benar -->
</head>

<body>
   <div class="container">
      <div class="row mb-2">
         <img src="../dist/img/headerquo.png" alt="quotations" class="img-fluid">
      </div>
      <h4 class="text-right">DATA KARKAS</h4>
      <h5 class="text-right"><?= htmlspecialchars(date("D d-M-Y", strtotime($row['killdate']))); ?></h5>
      <div class="row">
         <div class="col">
            <table class="table table-responsive table-borderless table-sm">
               <tr>
                  <td>Supplier</td>
                  <td>:</td>
                  <th><?= htmlspecialchars($row['nmsupplier']); ?></th>
               </tr>
               <tr>
                  <td>Head</td>
                  <td>:</td>
                  <th><?= $headCount . " " . "Ekor"; ?></th>
               </tr>
               <tr>
                  <td>Breed</td>
                  <td>:</td>
                  <th><?= htmlspecialchars($row['breed']); ?></th>
               </tr>
            </table>
         </div>
         <div class="col">
            <table class="table table-responsive table-borderless table-sm">
               <tr>
                  <td>Offal</td>
                  <td>:</td>
                  <th><?= number_format($totalOffal, 2); ?></th>
               </tr>
               <tr>
                  <td>Kulit</td>
                  <td>:</td>
                  <th><?= number_format($totalKulit, 2); ?></th>
               </tr>
               <tr>
                  <td>Karkas %</td>
                  <td>:</td>
                  <th><?= number_format($karkasPercentage, 2) . "%"; ?></th>
               </tr>
            </table>
         </div>
      </div>
      <table class="table-sm table table-bordered text-right">
         <thead class="thead-dark">
            <tr class="text-center">
               <th>#</th>
               <th>Eartag</th>
               <th>Bobot</th>
               <th>Karkas A</th>
               <th>Karkas B</th>
               <th>Kulit</th>
               <th>Tails</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $detailsResult->data_seek(0); // Kembali ke awal hasil untuk ditampilkan
            $count = 1;
            while ($detailRow = $detailsResult->fetch_assoc()) {
               echo "<tr>";
               echo "<td class='text-center'>" . $count++ . "</td>";
               echo "<td class='text-center'>" . htmlspecialchars($detailRow['eartag']) . "</td>";
               echo "<td>" . htmlspecialchars($detailRow['berat']) . "</td>";
               echo "<td>" . htmlspecialchars($detailRow['carcase1']) . "</td>";
               echo "<td>" . htmlspecialchars($detailRow['carcase2']) . "</td>";
               echo "<td>" . htmlspecialchars($detailRow['hides']) . "</td>";
               echo "<td>" . htmlspecialchars($detailRow['tail']) . "</td>";
               echo "</tr>";
            }
            ?>
         </tbody>
         <tfoot>
            <tr>
               <th colspan="2">Jumlah</th>
               <th><?= number_format($totalBerat, 2); ?></th>
               <th colspan="2"><?= number_format($totalCarcase1 + $totalCarcase2, 2); ?></th>
               <th><?= number_format($totalHides, 2); ?></th>
               <th><?= number_format($totalTails, 2); ?></th>
            </tr>
         </tfoot>
      </table>
      <div class="text-right mt-3">
         <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
         <a href="editcarcase.php?idcarcase=<?= $idcarcase ?>" class="btn btn-primary">Edit</a>
         <a href="printcarcase.php?idcarcase=<?= $idcarcase ?>" class="btn btn-success" target="_blank">Print</a>
      </div>

   </div>
</body>

</html>
<script>
   window.addEventListener("load", function() {
      window.print();
      setTimeout(function() {
         window.location.href = "datacarcase.php";
      }, 1000);
   });
</script>
<?php include "../footer.php"; ?>