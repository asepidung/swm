<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

if (!isset($_GET['id'])) {
  die("Jalankan Dari Modul Produksi");
}

$idboning = intval($_GET['id']);
$idboningWithPrefix = str_pad($idboning, 4, "0", STR_PAD_LEFT);

// Ambil data label boning
$query = "SELECT l.idlabelboning, b.idbarang, b.nmbarang, l.qty, l.pcs
          FROM labelboning l
          LEFT JOIN barang b ON l.idbarang = b.idbarang
          WHERE l.idboning = ? AND l.is_deleted = 0
          ORDER BY b.nmbarang";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idboning);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
  die("Query error: " . $stmt->error);
}

// GROUPING PRODUK BERDASARKAN NAMA
$items = array();
while ($row = $result->fetch_assoc()) {
  $name = $row['nmbarang'] ?? 'Unknown';
  $qty  = floatval($row['qty'] ?? 0);
  $pcs  = intval($row['pcs'] ?? 0);

  if (isset($items[$name])) {
    $items[$name]['qty'] += $qty;
    $items[$name]['pcs'] += $pcs;
    $items[$name]['box'] += 1;
  } else {
    $items[$name] = [
      'idbarang' => (int)$row['idbarang'],
      'qty'      => $qty,
      'pcs'      => $pcs,
      'box'      => 1
    ];
  }
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4>Detail Boning #<?= htmlspecialchars($idboningWithPrefix); ?></h4>
        </div>
        <div class="col-sm-6 text-right">
          <a href="databoning.php" class="btn btn-success btn-sm">
            <i class="fas fa-undo-alt"></i> Kembali
          </a>
          <?php
          // Ambil status kunci dari tabel boning
          $q = $conn->query("SELECT kunci FROM boning WHERE idboning = $idboning LIMIT 1");
          $bon = $q->fetch_assoc();
          $isLocked = (int)($bon['kunci'] ?? 0);
          ?>

          <?php if ($isLocked === 1): ?>
            <button class="btn btn-secondary btn-sm ml-2" disabled title="Data boning sudah dikunci">
              <i class="fas fa-lock"></i> Pemakaian Bahan (Terkunci)
            </button>
          <?php else: ?>
            <a href="rawusage.php?id=<?= $idboning; ?>" class="btn btn-primary btn-sm ml-2">
              <i class="fas fa-boxes"></i> Pemakaian Bahan
            </a>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-dark shadow-sm">
        <div class="card-header">
          <h3 class="card-title">Hasil Produksi</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped table-sm">
              <thead class="text-center">
                <tr>
                  <th>#</th>
                  <th>Product</th>
                  <th>Box</th>
                  <th>Pcs</th>
                  <th>Qty</th>
                  <th>C Top</th>
                  <th>C Bottom</th>
                  <th>Vacuum</th>
                  <th>Linier</th>
                  <th>Tray</th>
                  <th>Karung</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $grandBox = $grandPcs = 0;
                $grandQty = 0.0;

                foreach ($items as $nmbarang => $item) {
                  $idbarang = (int)$item['idbarang'];

                  // Ambil bahan penolong (BOM)
                  $qbom = mysqli_query($conn, "
                    SELECT r.idrawcategory, r.idrawmate, r.nmrawmate, b.qty
                    FROM bom_rawmate b
                    JOIN rawmate r ON b.idrawmate = r.idrawmate
                    WHERE b.idbarang = $idbarang AND b.is_active = 1
                  ");

                  $karton_top    = '';
                  $karton_bottom = '';
                  $plastik       = '';
                  $linier        = 0;
                  $karung        = 0;
                  $tray          = 0;

                  while ($rb = mysqli_fetch_assoc($qbom)) {
                    $cat = (int)$rb['idrawcategory'];
                    $nmM = $rb['nmrawmate'];

                    switch ($cat) {
                      case 2: // Karton
                        if (stripos($nmM, 'TOP') !== false) {
                          $karton_top = $nmM;
                        } else {
                          $karton_bottom = $nmM;
                        }
                        break;

                      case 3: // Plastik
                        if (stripos($nmM, 'LINIER') !== false) {
                          $linier = 1;
                        } else {
                          // anggap plastik vacuum
                          $plastik = $nmM;
                        }
                        break;

                      case 21: // Karung
                        $karung = 1;
                        break;

                      case 22: // Tray
                        $tray = (float)$rb['qty'];
                        break;

                      default:
                        // (Drylog dihilangkan – tidak diproses apa pun di sini)
                        break;
                    }
                  }

                  echo "<tr class='text-center'>
                          <td>" . $no++ . "</td>
                          <td class='text-left'>" . htmlspecialchars($nmbarang) . "</td>
                          <td>" . (int)$item['box'] . "</td>
                          <td>" . (int)$item['pcs'] . "</td>
                          <td class='text-right'>" . number_format((float)$item['qty'], 2) . "</td>
                          <td>" . htmlspecialchars($karton_top) . "</td>
                          <td>" . htmlspecialchars($karton_bottom) . "</td>
                          <td>" . htmlspecialchars($plastik) . "</td>
                          <td>" . ($linier ? '1' : '') . "</td>
                          <td>" . ($tray ? (0 + $tray) : '') . "</td>
                          <td>" . ($karung ? '1' : '') . "</td>
                        </tr>";

                  $grandBox += (int)$item['box'];
                  $grandPcs += (int)$item['pcs'];
                  $grandQty += (float)$item['qty'];
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2" class="text-right">GRAND TOTAL</th>
                  <th class="text-center"><?= (int)$grandBox; ?></th>
                  <th class="text-center"><?= (int)$grandPcs; ?></th>
                  <th class="text-right"><?= number_format((float)$grandQty, 2); ?></th>
                  <!-- kolom sisa setelah Qty: C Top, C Bottom, Vacuum, Linier, Tray, Karung = 6 -->
                  <th colspan="6"></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  $(function() {
    $("#example1").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: false,
      paging: false,
      searching: true,
      info: false,
      buttons: ["copy", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
  document.title = "Detail Boning - <?= addslashes($idboningWithPrefix); ?>";
</script>

<?php include "../footnote.php"; ?>
<?php include "../footer.php"; ?>