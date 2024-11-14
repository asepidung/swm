<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";
include "../header.php";

// Validasi dan sanitasi idcarcase dari URL
$idcarcase = $_GET['idcarcase'] ?? null;
if (!$idcarcase || !is_numeric($idcarcase)) {
   echo "<script>alert('ID Carcase tidak valid!'); window.location='carcase.php';</script>";
   exit();
}

// Query untuk mendapatkan data carcase dan supplier
$query = "SELECT carcase.*, supplier.nmsupplier, carcase.killdate, carcase.note
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
   <style>
      /* Memperbesar ukuran font seluruh halaman */

      /* Memperbesar ukuran font pada tabel */
      table {
         font-size: 1.1em;
         /* Sesuaikan nilai ini untuk ukuran yang diinginkan */
      }

      /* Memperbesar font judul dan heading */
      h4,
      h5 {
         font-size: 1.5em;
         /* Sesuaikan nilai ini untuk ukuran yang diinginkan */
      }

      /* Jika ingin ukuran lebih besar saat dicetak */
      @media print {
         body {
            font-size: 1.3em;
            /* Sesuaikan nilai ini untuk ukuran saat dicetak */
         }

         h4,
         h5 {
            font-size: 1.7em;
         }
      }

      @media print {
         .btn {
            display: none;
         }
      }
   </style>

</head>

<body>
   <div class="container">
      <div class="row mb-2">
         <img src="../dist/img/headerquo.png" alt="quotations" class="img-fluid">
      </div>
      <h4 class="text-right">DATA KARKAS</h4>
      <h5 class="text-right"><?= htmlspecialchars(date("d-M-Y", strtotime($row['killdate']))); ?></h5>
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
               <th>Breed</th>
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
               echo "<td class='text-center'>" . htmlspecialchars($detailRow['breed']) . "</td>";
               echo "<td class='text-center'>" . htmlspecialchars($detailRow['eartag']) . "</td>";
               echo "<td>" . ($detailRow['berat'] != 0 ? number_format($detailRow['berat'], 2) : "") . "</td>"; // Tidak tampilkan 0 atau 0.00
               echo "<td>" . ($detailRow['carcase1'] != 0 ? number_format($detailRow['carcase1'], 2) : "") . "</td>"; // Tidak tampilkan 0 atau 0.00
               echo "<td>" . ($detailRow['carcase2'] != 0 ? number_format($detailRow['carcase2'], 2) : "") . "</td>"; // Tidak tampilkan 0 atau 0.00
               echo "<td>" . ($detailRow['hides'] != 0 ? number_format($detailRow['hides'], 2) : "") . "</td>"; // Tidak tampilkan 0 atau 0.00
               echo "<td>" . ($detailRow['tail'] != 0 ? number_format($detailRow['tail'], 2) : "") . "</td>"; // Tidak tampilkan 0 atau 0.00
               echo "</tr>";
            }
            ?>
         </tbody>
         <tfoot>
            <tr>
               <th colspan="3">Jumlah</th>
               <th><?= $totalBerat != 0 ? number_format($totalBerat, 2) : ""; ?></th> <!-- Tidak tampilkan 0 atau 0.00 -->
               <th colspan="2"><?= ($totalCarcase1 + $totalCarcase2) != 0 ? number_format($totalCarcase1 + $totalCarcase2, 2) : ""; ?></th> <!-- Tidak tampilkan 0 atau 0.00 -->
               <th><?= $totalHides != 0 ? number_format($totalHides, 2) : ""; ?></th> <!-- Tidak tampilkan 0 atau 0.00 -->
               <th><?= $totalTails != 0 ? number_format($totalTails, 2) : ""; ?></th> <!-- Tidak tampilkan 0 atau 0.00 -->
            </tr>
         </tfoot>
      </table>

      <div class="text-right mt-3">
         <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
         <a href="editcarcase.php?idcarcase=<?= $idcarcase ?>" class="btn btn-primary">Edit</a>
         <button onclick="window.print();" class="btn btn-success">Print</button>
      </div>
   </div>

</body>

</html>
<script>
   document.title = "Data Karkas <?= htmlspecialchars(date("d-M-Y", strtotime($row['killdate']))); ?>";
</script>
<?php include "../footer.php"; ?>