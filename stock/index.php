<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

/**
 * SYARAT kdbarang VALID:
 * - tidak NULL
 * - bukan string kosong / '-' / '–' / '—'
 * - mengandung minimal satu huruf/angka
 */
$validCodeWhere = "b.kdbarang IS NOT NULL
                   AND TRIM(b.kdbarang) <> ''
                   AND TRIM(b.kdbarang) NOT IN ('-', '–', '—')
                   AND TRIM(b.kdbarang) REGEXP '[[:alnum:]]'";

/**
 * QUERY UTAMA
 * - cuts -> barang -> LEFT JOIN stock
 * - Filter barang berkode valid
 * - GROUP BY lengkap (ONLY_FULL_GROUP_BY safe)
 */
$sql = "SELECT
            b.idbarang,
            b.kdbarang,
            b.nmbarang,
            c.idcut,
            c.nmcut,
            COALESCE(SUM(CASE WHEN s.idgrade = 1 THEN s.qty ELSE 0 END), 0) AS chill_jonggol,
            COALESCE(SUM(CASE WHEN s.idgrade = 2 THEN s.qty ELSE 0 END), 0) AS frozen_jonggol,
            COALESCE(SUM(CASE WHEN s.idgrade = 3 THEN s.qty ELSE 0 END), 0) AS chill_perum,
            COALESCE(SUM(CASE WHEN s.idgrade = 4 THEN s.qty ELSE 0 END), 0) AS frozen_perum,
            COALESCE(SUM(s.qty), 0) AS total_qty
        FROM cuts c
        JOIN barang b ON b.idcut = c.idcut
        LEFT JOIN stock s ON s.idbarang = b.idbarang
        WHERE $validCodeWhere
        GROUP BY b.idbarang, b.kdbarang, b.nmbarang, c.idcut, c.nmcut
        ORDER BY c.idcut, b.kdbarang";
$result = $conn->query($sql);

/**
 * TOTAL PER KATEGORI CUT
 * - Filter barang berkode valid
 */
$totalCutSql = "SELECT
    c.idcut,
    c.nmcut,
    COALESCE(SUM(CASE WHEN s.idgrade = 1 THEN s.qty ELSE 0 END), 0) AS total_chill_jonggol,
    COALESCE(SUM(CASE WHEN s.idgrade = 2 THEN s.qty ELSE 0 END), 0) AS total_frozen_jonggol,
    COALESCE(SUM(CASE WHEN s.idgrade = 3 THEN s.qty ELSE 0 END), 0) AS total_chill_perum,
    COALESCE(SUM(CASE WHEN s.idgrade = 4 THEN s.qty ELSE 0 END), 0) AS total_frozen_perum,
    COALESCE(SUM(s.qty), 0) AS total_qty
FROM cuts c
JOIN barang b ON b.idcut = c.idcut
LEFT JOIN stock s ON s.idbarang = b.idbarang
WHERE $validCodeWhere
GROUP BY c.idcut, c.nmcut
ORDER BY c.idcut";
$totalCutResult = $conn->query($totalCutSql);
$totalsPerCut = [];
while ($totalRow = $totalCutResult->fetch_assoc()) {
   $totalsPerCut[(int)$totalRow['idcut']] = $totalRow;
}

/**
 * GRAND TOTAL (selaras dengan filter barang berkode valid)
 */
$totalGradeSql = "SELECT
    COALESCE(SUM(CASE WHEN s.idgrade = 1 THEN s.qty ELSE 0 END), 0) AS total_chill_jonggol,
    COALESCE(SUM(CASE WHEN s.idgrade = 2 THEN s.qty ELSE 0 END), 0) AS total_frozen_jonggol,
    COALESCE(SUM(CASE WHEN s.idgrade = 3 THEN s.qty ELSE 0 END), 0) AS total_chill_perum,
    COALESCE(SUM(CASE WHEN s.idgrade = 4 THEN s.qty ELSE 0 END), 0) AS total_frozen_perum,
    COALESCE(SUM(s.qty), 0) AS total_qty
FROM barang b
LEFT JOIN stock s ON s.idbarang = b.idbarang
WHERE $validCodeWhere";
$totalGradeResult = $conn->query($totalGradeSql);
$totalGradeRow = $totalGradeResult->fetch_assoc();
?>

<style>
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
                                    <th rowspan="2">Code</th>
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
                                 $currentCutId = null;
                                 while ($row = $result->fetch_assoc()):
                                    $rowCutId = (int)$row['idcut'];

                                    if ($rowCutId !== $currentCutId):
                                       $currentCutId = $rowCutId;
                                       $totalCut = $totalsPerCut[$currentCutId] ?? [
                                          'nmcut' => $row['nmcut'],
                                          'total_chill_jonggol' => 0,
                                          'total_frozen_jonggol' => 0,
                                          'total_chill_perum' => 0,
                                          'total_frozen_perum' => 0,
                                          'total_qty' => 0,
                                       ];
                                 ?>
                                       <tr>
                                          <td colspan="2" class="bg-secondary text-white text-center font-weight-bold">
                                             <?= htmlspecialchars($totalCut['nmcut']) ?>
                                          </td>
                                          <td class="bg-secondary text-white text-right font-weight-bold"><?= ((float)$totalCut['total_chill_jonggol']  != 0.00 ? number_format((float)$totalCut['total_chill_jonggol'], 2)  : '') ?></td>
                                          <td class="bg-secondary text-white text-right font-weight-bold"><?= ((float)$totalCut['total_frozen_jonggol'] != 0.00 ? number_format((float)$totalCut['total_frozen_jonggol'], 2) : '') ?></td>
                                          <td class="bg-secondary text-white text-right font-weight-bold"><?= ((float)$totalCut['total_chill_perum']    != 0.00 ? number_format((float)$totalCut['total_chill_perum'], 2)    : '') ?></td>
                                          <td class="bg-secondary text-white text-right font-weight-bold><?= ((float)$totalCut['total_frozen_perum']   != 0.00 ? number_format((float)$totalCut['total_frozen_perum'], 2)   : '') ?></td>
                                          <td class=" bg-secondary text-white text-right font-weight-bold"><?= ((float)$totalCut['total_qty']            != 0.00 ? number_format((float)$totalCut['total_qty'], 2)            : '') ?></td>
                                       </tr>
                                    <?php endif; ?>

                                    <tr class="text-right">
                                       <td class="text-center"><?= htmlspecialchars($row['kdbarang']) ?></td>
                                       <td class="text-left">
                                          <a href="detailitem.php?id=<?= (int)$row['idbarang'] ?>">
                                             <?= htmlspecialchars($row['nmbarang']) ?>
                                          </a>
                                       </td>
                                       <td><?= ((float)$row['chill_jonggol']  != 0.00 ? number_format((float)$row['chill_jonggol'],  2) : '') ?></td>
                                       <td><?= ((float)$row['frozen_jonggol'] != 0.00 ? number_format((float)$row['frozen_jonggol'], 2) : '') ?></td>
                                       <td><?= ((float)$row['chill_perum']    != 0.00 ? number_format((float)$row['chill_perum'],    2) : '') ?></td>
                                       <td><?= ((float)$row['frozen_perum']   != 0.00 ? number_format((float)$row['frozen_perum'],   2) : '') ?></td>
                                       <td><?= ((float)$row['total_qty']      != 0.00 ? number_format((float)$row['total_qty'],      2) : '') ?></td>
                                    </tr>
                                 <?php endwhile; ?>
                              </tbody>
                              <tfoot>
                                 <tr class="text-right">
                                    <th colspan="2">TOTAL</th>
                                    <th><?= ((float)$totalGradeRow['total_chill_jonggol']  != 0.00 ? number_format((float)$totalGradeRow['total_chill_jonggol'],  2) : '') ?></th>
                                    <th><?= ((float)$totalGradeRow['total_frozen_jonggol'] != 0.00 ? number_format((float)$totalGradeRow['total_frozen_jonggol'], 2) : '') ?></th>
                                    <th><?= ((float)$totalGradeRow['total_chill_perum']    != 0.00 ? number_format((float)$totalGradeRow['total_chill_perum'],    2) : '') ?></th>
                                    <th><?= ((float)$totalGradeRow['total_frozen_perum']   != 0.00 ? number_format((float)$totalGradeRow['total_frozen_perum'],   2) : '') ?></th>
                                    <th><?= ((float)$totalGradeRow['total_qty']            != 0.00 ? number_format((float)$totalGradeRow['total_qty'],            2) : '') ?></th>
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
   document.title = "DATA STOCK";

   // Pencarian: jaga header kategori tampil jika ada item match
   document.getElementById('searchInput').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const tbody = document.querySelector('#stockTable tbody');
      const rows = Array.from(tbody.querySelectorAll('tr'));

      let currentCategoryRow = null;
      let anyChildVisibleUnderCategory = false;

      rows.forEach((row) => {
         const isCategoryRow = row.querySelector('td[colspan="2"]') !== null;

         if (isCategoryRow) {
            if (currentCategoryRow) {
               currentCategoryRow.style.display = anyChildVisibleUnderCategory ? '' : (filter === '' ? '' : 'none');
            }
            currentCategoryRow = row;
            anyChildVisibleUnderCategory = false;
            row.style.display = 'none';
         } else {
            const match = row.textContent.toLowerCase().includes(filter);
            row.style.display = (filter === '' || match) ? '' : 'none';
            if (row.style.display !== 'none') anyChildVisibleUnderCategory = true;
         }
      });

      if (currentCategoryRow) {
         currentCategoryRow.style.display = anyChildVisibleUnderCategory ? '' : (filter === '' ? '' : 'none');
      }

      if (filter === '') {
         rows.forEach(row => {
            if (row.querySelector('td[colspan="2"]')) row.style.display = '';
         });
      }
   });

   // Export PDF (mengikuti tampilan; 0 tetap kosong)
   document.getElementById('exportPdfBtn').addEventListener('click', () => {
      const {
         jsPDF
      } = window.jspdf;
      const doc = new jsPDF();

      const headers = [
         ['Code', 'Product Name', 'CHILL (J)', 'FROZEN (J)', 'CHILL (P)', 'FROZEN (P)', 'Total']
      ];

      const rows = [];
      document.querySelectorAll('#stockTable tbody tr').forEach(row => {
         if (row.style.display === 'none') return;

         if (row.querySelector('td[colspan="2"]')) {
            const kategori = row.querySelector('td[colspan="2"]').textContent.trim();
            rows.push([kategori, '', '', '', '', '', '']); // baris kategori
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
            if (data.row && typeof data.row.index === 'number') {
               const row = rows[data.row.index];
               if (row && row[1] === '' && row[2] === '') {
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