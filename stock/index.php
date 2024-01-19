<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Make sure to establish a connection before running the query
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from stock and barang tables
$sql = "SELECT
            b.nmbarang,
            s.idbarang,
            SUM(CASE WHEN s.idgrade = 1 THEN s.qty ELSE 0 END) AS grade1,
            SUM(CASE WHEN s.idgrade = 2 THEN s.qty ELSE 0 END) AS grade2,
            SUM(CASE WHEN s.idgrade = 5 THEN s.qty ELSE 0 END) AS grade5,
            SUM(CASE WHEN s.idgrade = 3 THEN s.qty ELSE 0 END) AS grade3,
            SUM(CASE WHEN s.idgrade = 4 THEN s.qty ELSE 0 END) AS grade4,
            SUM(CASE WHEN s.idgrade = 6 THEN s.qty ELSE 0 END) AS grade6,
            SUM(s.qty) AS total_qty
        FROM stock s
        JOIN barang b ON s.idbarang = b.idbarang
        GROUP BY b.idbarang, b.nmbarang";

$result = $conn->query($sql);
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 mt-3">
               <a href="detail.php" class="btn btn-primary mb-2">Detail By Box</a>
               <a href="aging.php" class="btn btn-primary mb-2">Group By Age</a>
               <div class="card">
                  <div class="card-body">
                     <div class="col">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th rowspan="2">Nama Product</th>
                                 <th colspan="3">G. Jonggol</th>
                                 <th colspan="3">G. Perum</th>
                                 <th rowspan="2">Total</th>
                              </tr>
                              <tr>
                                 <th>CHILL (J)</th>
                                 <th>FROZEN (J)</th>
                                 <th>GRADE (J)</th>
                                 <th>CHILL (P)</th>
                                 <th>FROZEN (P)</th>
                                 <th>GRADE (P)</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              // Loop through the query results
                              while ($row = $result->fetch_assoc()) {
                                 // Check if any of the grade columns or total_qty is not 0.00 or 0
                                 if (
                                    (float)$row['grade1'] != 0.00 || (float)$row['grade2'] != 0.00 || (float)$row['grade5'] != 0.00 ||
                                    (float)$row['grade3'] != 0.00 || (float)$row['grade4'] != 0.00 || (float)$row['grade6'] != 0.00 ||
                                    (float)$row['total_qty'] != 0.00
                                 ) {
                                    echo "<tr class='text-right'>";
                                    echo "<td class='text-left'><a href='detailitem.php?id={$row['idbarang']}'>{$row['nmbarang']}</a></td>";
                                    echo "<td" . ((float)$row['grade1'] < 0 ? ' style="color: red;"' : '') . ">" . ((float)$row['grade1'] != 0.00 ? number_format((float)$row['grade1'], 2) : '') . "</td>";
                                    echo "<td" . ((float)$row['grade2'] < 0 ? ' style="color: red;"' : '') . ">" . ((float)$row['grade2'] != 0.00 ? number_format((float)$row['grade2'], 2) : '') . "</td>";
                                    echo "<td" . ((float)$row['grade5'] < 0 ? ' style="color: red;"' : '') . ">" . ((float)$row['grade5'] != 0.00 ? number_format((float)$row['grade5'], 2) : '') . "</td>";
                                    echo "<td" . ((float)$row['grade3'] < 0 ? ' style="color: red;"' : '') . ">" . ((float)$row['grade3'] != 0.00 ? number_format((float)$row['grade3'], 2) : '') . "</td>";
                                    echo "<td" . ((float)$row['grade4'] < 0 ? ' style="color: red;"' : '') . ">" . ((float)$row['grade4'] != 0.00 ? number_format((float)$row['grade4'], 2) : '') . "</td>";
                                    echo "<td" . ((float)$row['grade6'] < 0 ? ' style="color: red;"' : '') . ">" . ((float)$row['grade6'] != 0.00 ? number_format((float)$row['grade6'], 2) : '') . "</td>";
                                    echo "<th" . ((float)$row['total_qty'] < 0 ? ' style="color: red;"' : '') . ">" . ((float)$row['total_qty'] != 0.00 ? number_format((float)$row['total_qty'], 2) : '') . "</th>";
                                    echo "</tr>";
                                 }
                              }
                              ?>
                           </tbody>
                           <tfoot>
                              <tr class="text-right">
                                 <th>TOTAL</th>
                                 <?php
                                 // Query to get total quantities per grade
                                 $totalGradeSql = "SELECT
                        SUM(CASE WHEN idgrade = 1 THEN qty ELSE 0 END) AS total_grade1,
                        SUM(CASE WHEN idgrade = 2 THEN qty ELSE 0 END) AS total_grade2,
                        SUM(CASE WHEN idgrade = 5 THEN qty ELSE 0 END) AS total_grade5,
                        SUM(CASE WHEN idgrade = 3 THEN qty ELSE 0 END) AS total_grade3,
                        SUM(CASE WHEN idgrade = 4 THEN qty ELSE 0 END) AS total_grade4,
                        SUM(CASE WHEN idgrade = 6 THEN qty ELSE 0 END) AS total_grade6,
                        SUM(qty) AS total_qty
                    FROM stock";

                                 $totalGradeResult = $conn->query($totalGradeSql);
                                 $totalGradeRow = $totalGradeResult->fetch_assoc();

                                 echo "<th>" . ((float)$totalGradeRow['total_grade1'] != 0.00 ? number_format((float)$totalGradeRow['total_grade1'], 2) : '') . "</th>";
                                 echo "<th>" . ((float)$totalGradeRow['total_grade2'] != 0.00 ? number_format((float)$totalGradeRow['total_grade2'], 2) : '') . "</th>";
                                 echo "<th>" . ((float)$totalGradeRow['total_grade5'] != 0.00 ? number_format((float)$totalGradeRow['total_grade5'], 2) : '') . "</th>";
                                 echo "<th>" . ((float)$totalGradeRow['total_grade3'] != 0.00 ? number_format((float)$totalGradeRow['total_grade3'], 2) : '') . "</th>";
                                 echo "<th>" . ((float)$totalGradeRow['total_grade4'] != 0.00 ? number_format((float)$totalGradeRow['total_grade4'], 2) : '') . "</th>";
                                 echo "<th>" . ((float)$totalGradeRow['total_grade6'] != 0.00 ? number_format((float)$totalGradeRow['total_grade6'], 2) : '') . "</th>";
                                 echo "<th>" . ((float)$totalGradeRow['total_qty'] != 0.00 ? number_format((float)$totalGradeRow['total_qty'], 2) : '') . "</th>";
                                 ?>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   // Mengubah judul halaman web
   document.title = "DATA STOCK";
</script>
<?php
// Close the database connection
$conn->close();

include "../footer.php";
?>