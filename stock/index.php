<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// Query utama: ambil data produk per kategori cut
$sql = "SELECT
            b.kdbarang,
            b.nmbarang,
            s.idbarang,
            c.idcut,
            c.nmcut,
            SUM(CASE WHEN s.idgrade = 1 THEN s.qty ELSE 0 END) AS chill_jonggol,
            SUM(CASE WHEN s.idgrade = 2 THEN s.qty ELSE 0 END) AS frozen_jonggol,
            SUM(CASE WHEN s.idgrade = 3 THEN s.qty ELSE 0 END) AS chill_perum,
            SUM(CASE WHEN s.idgrade = 4 THEN s.qty ELSE 0 END) AS frozen_perum,
            SUM(s.qty) AS total_qty
        FROM stock s
        JOIN barang b ON s.idbarang = b.idbarang
        JOIN cuts c ON b.idcut = c.idcut
        GROUP BY b.idbarang, b.kdbarang, b.nmbarang, c.idcut, c.nmcut
        ORDER BY c.idcut, b.nmbarang";

$result = $conn->query($sql);

// Query total per kategori cut
$totalCutSql = "SELECT
    c.idcut,
    c.nmcut,
    SUM(CASE WHEN s.idgrade = 1 THEN s.qty ELSE 0 END) AS total_chill_jonggol,
    SUM(CASE WHEN s.idgrade = 2 THEN s.qty ELSE 0 END) AS total_frozen_jonggol,
    SUM(CASE WHEN s.idgrade = 3 THEN s.qty ELSE 0 END) AS total_chill_perum,
    SUM(CASE WHEN s.idgrade = 4 THEN s.qty ELSE 0 END) AS total_frozen_perum,
    SUM(s.qty) AS total_qty
FROM stock s
JOIN barang b ON s.idbarang = b.idbarang
JOIN cuts c ON b.idcut = c.idcut
GROUP BY c.idcut, c.nmcut
ORDER BY c.idcut";

$totalCutResult = $conn->query($totalCutSql);
$totalsPerCut = [];
while ($totalRow = $totalCutResult->fetch_assoc()) {
   $totalsPerCut[$totalRow['nmcut']] = $totalRow;
}

// Query total semua grade
$totalGradeSql = "SELECT
    SUM(CASE WHEN idgrade = 1 THEN qty ELSE 0 END) AS total_chill_jonggol,
    SUM(CASE WHEN idgrade = 2 THEN qty ELSE 0 END) AS total_frozen_jonggol,
    SUM(CASE WHEN idgrade = 3 THEN qty ELSE 0 END) AS total_chill_perum,
    SUM(CASE WHEN idgrade = 4 THEN qty ELSE 0 END) AS total_frozen_perum,
    SUM(qty) AS total_qty
    FROM stock";

$totalGradeResult = $conn->query($totalGradeSql);
$totalGradeRow = $totalGradeResult->fetch_assoc();
?>

<style>
   /* Responsive & nyaman di mobile */
   @media (max-width: 767.98px) {
      .table-responsive {
         overflow-x: auto;
         -webkit-overflow-scrolling: touch;
      }

      #searchInput {
         width: 100% !important;
         margin-bottom: 0.5rem;
      }

      .btn-group,
      #exportPdfBtn {
         width: 100% !important;
         margin-bottom: 0.5rem;
      }

      .btn-group .dropdown-menu {
         width: 100%;
      }
   }

   @media (min-width: 768px) {
      .search-export-container {
         display: flex;
         gap: 0.75rem;
         align-items: center;
      }

      #searchInput {
         flex-shrink: 0;
         width: 250px;
         max-width: 100%;
      }

      #exportPdfBtn {
         flex-shrink: 0;
         white-space: nowrap;
      }
   }
</style>

<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 mt-3">

               <div class="btn-group mb-2">
                  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Sort By
                  </button>
                  <div class="dropdown-menu">
                     <a class="dropdown-item" href="aging.php">Stock By Aging</a>
                     <a class="dropdown-item" href="detail.php">Box Detail</a>
                     <a class="dropdown-item" href="flows.php">Flows Product</a>
                  </div>
               </div>

               <div class="card">
                  <div class="card-body">
                     <div class="col">
                        <div class="search-export-container mb-2">
                           <input type="text" id="searchInput" autofocus placeholder="Search table..." class="form-control" />
                           <button id="exportPdfBtn" class="btn btn-sm btn-danger">Export PDF</button>
                        </div>

                        <div class="table-responsive">
                           <table class="table table-bordered table-striped table-sm" id="stockTable">
                              <thead class="text-center">
                                 <tr>
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Product Name</th>
                                    <th colspan="2">G. Jonggol</th>
                                    <th colspan="2">G. Perum</th>
                                    <th rowspan="2">Total</th>
                                 </tr>
                                 <tr>
                                    <th>CHILL (J)</th>
                                    <th>FROZEN (J)</th>
                                    <th>CHILL (P)</th>
                                    <th>FROZEN (P)</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php
                                 $currentCut = "";
                                 while ($row = $result->fetch_assoc()):
                                    if ($row['nmcut'] != $currentCut):
                                       $currentCut = $row['nmcut'];
                                       $totalCut = $totalsPerCut[$currentCut];
                                 ?>
                                       <tr>
                                          <td colspan="2" class="bg-secondary text-white text-center font-weight-bold"><?= htmlspecialchars($currentCut) ?></td>
                                          <td class="bg-secondary text-white text-right font-weight-bold"><?= number_format((float)$totalCut['total_chill_jonggol'], 2) ?></td>
                                          <td class="bg-secondary text-white text-right font-weight-bold"><?= number_format((float)$totalCut['total_frozen_jonggol'], 2) ?></td>
                                          <td class="bg-secondary text-white text-right font-weight-bold"><?= number_format((float)$totalCut['total_chill_perum'], 2) ?></td>
                                          <td class="bg-secondary text-white text-right font-weight-bold"><?= number_format((float)$totalCut['total_frozen_perum'], 2) ?></td>
                                          <td class="bg-secondary text-white text-right font-weight-bold"><?= number_format((float)$totalCut['total_qty'], 2) ?></td>
                                       </tr>
                                    <?php
                                    endif;
                                    ?>
                                    <tr class="text-right">
                                       <td class="text-center"><?= htmlspecialchars($row['kdbarang']) ?></td>
                                       <td class="text-left"><a href="detailitem.php?id=<?= $row['idbarang'] ?>"><?= htmlspecialchars($row['nmbarang']) ?></a></td>
                                       <td><?= ((float)$row['chill_jonggol'] != 0.00 ? number_format((float)$row['chill_jonggol'], 2) : '') ?></td>
                                       <td><?= ((float)$row['frozen_jonggol'] != 0.00 ? number_format((float)$row['frozen_jonggol'], 2) : '') ?></td>
                                       <td><?= ((float)$row['chill_perum'] != 0.00 ? number_format((float)$row['chill_perum'], 2) : '') ?></td>
                                       <td><?= ((float)$row['frozen_perum'] != 0.00 ? number_format((float)$row['frozen_perum'], 2) : '') ?></td>
                                       <td><?= ((float)$row['total_qty'] != 0.00 ? number_format((float)$row['total_qty'], 2) : '') ?></td>
                                    </tr>
                                 <?php endwhile; ?>
                              </tbody>
                              <tfoot>
                                 <tr class="text-right">
                                    <th colspan="2">TOTAL</th>
                                    <th><?= ((float)$totalGradeRow['total_chill_jonggol'] != 0.00 ? number_format((float)$totalGradeRow['total_chill_jonggol'], 2) : '') ?></th>
                                    <th><?= ((float)$totalGradeRow['total_frozen_jonggol'] != 0.00 ? number_format((float)$totalGradeRow['total_frozen_jonggol'], 2) : '') ?></th>
                                    <th><?= ((float)$totalGradeRow['total_chill_perum'] != 0.00 ? number_format((float)$totalGradeRow['total_chill_perum'], 2) : '') ?></th>
                                    <th><?= ((float)$totalGradeRow['total_frozen_perum'] != 0.00 ? number_format((float)$totalGradeRow['total_frozen_perum'], 2) : '') ?></th>
                                    <th><?= ((float)$totalGradeRow['total_qty'] != 0.00 ? number_format((float)$totalGradeRow['total_qty'], 2) : '') ?></th>
                                 </tr>
                              </tfoot>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<!-- CDN jsPDF & autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
   // Mengubah judul halaman web
   document.title = "DATA STOCK";

   // Search filter
   document.getElementById('searchInput').addEventListener('keyup', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#stockTable tbody tr');

      rows.forEach(row => {
         const isCategoryRow = row.querySelector('td[colspan="2"]') !== null;
         if (isCategoryRow) {
            row.style.display = filter === '' || row.textContent.toLowerCase().includes(filter) ? '' : 'none';
         } else {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
         }
      });
   });

   // Export PDF
   document.getElementById('exportPdfBtn').addEventListener('click', () => {
      const {
         jsPDF
      } = window.jspdf;
      const doc = new jsPDF();

      const headers = [
         ['#', 'Product Name', 'CHILL (J)', 'FROZEN (J)', 'CHILL (P)', 'FROZEN (P)', 'Total']
      ];

      const rows = [];
      document.querySelectorAll('#stockTable tbody tr').forEach(row => {
         if (row.style.display === 'none') return;

         if (row.querySelector('td[colspan="2"]')) {
            const kategori = row.querySelector('td[colspan="2"]').textContent.trim();
            rows.push([kategori, '', '', '', '', '', '']);
         } else {
            const cols = Array.from(row.querySelectorAll('td')).map(td => td.textContent.trim());
            rows.push(cols);
         }
      });

      doc.autoTable({
         head: headers,
         body: rows,
         startY: 10,
         styles: {
            fontSize: 7
         },
         theme: 'grid',
         headStyles: {
            fillColor: [60, 60, 60]
         },
         didDrawCell: data => {
            if (data.row.index !== undefined) {
               const row = rows[data.row.index];
               if (row[1] === '' && row[2] === '') {
                  if (data.column.index === 0) {
                     data.cell.colSpan = 7;
                     data.cell.styles.fillColor = [60, 60, 60];
                     data.cell.styles.textColor = 255;
                     data.cell.styles.fontStyle = 'bold';
                  } else {
                     data.cell.styles.cellPadding = 0;
                     data.cell.styles.fillColor = [255, 255, 255, 0];
                  }
               }
            }
         }
      });

      const now = new Date();
      const timestamp = now.getFullYear().toString() +
         ('0' + (now.getMonth() + 1)).slice(-2) +
         ('0' + now.getDate()).slice(-2) + '_' +
         ('0' + now.getHours()).slice(-2) +
         ('0' + now.getMinutes()).slice(-2);

      doc.save(`Data-Stock_${timestamp}.pdf`);
   });
</script>

<?php
include "../footer.php";
?>