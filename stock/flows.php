<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 mt-3">
               <div class="card">
                  <div class="card-body">
                     <div class="col">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Tanggal</th>
                                 <th>Transaksi</th>
                                 <th>ID Transaksi</th>
                                 <th>Item</th>
                                 <th>IN</th>
                                 <th>OUT</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              // Reset nomor urut
                              $no = 1;

                              // Query data dari tabel boning, repack, gr, returjual, dan grdetail
                              $query = "SELECT tanggal, transaksi, id_transaksi, item, SUM(qty_in) AS qty_in, SUM(qty_out) AS qty_out
                              FROM (
                                    SELECT tglboning AS tanggal, 'Hasil Boning' AS transaksi, batchboning AS id_transaksi, nmbarang AS item, SUM(qty) AS qty_in, 0 AS qty_out
                                    FROM boning b
                                    JOIN labelboning lb ON b.idboning = lb.idboning
                                    JOIN barang br ON lb.idbarang = br.idbarang
                                    GROUP BY b.idboning, lb.idbarang
                                    UNION
                                    SELECT tglrepack AS tanggal, 'Hasil Repack' AS transaksi, norepack AS id_transaksi, nmbarang AS item, SUM(qty) AS qty_out, 0 AS qty_in
                                    FROM repack r
                                    JOIN detailhasil dh ON r.idrepack = dh.idrepack
                                    JOIN barang br ON dh.idbarang = br.idbarang
                                    GROUP BY r.idrepack, dh.idbarang
                                    UNION
                                    SELECT receivedate AS tanggal, 'Goods Receipt' AS transaksi, grnumber AS id_transaksi, nmbarang AS item, SUM(qty) AS qty_in, 0 AS qty_out
                                    FROM gr g
                                    JOIN grdetail gd ON g.idgr = gd.idgr
                                    JOIN barang br ON gd.idbarang = br.idbarang
                                    GROUP BY g.idgr, gd.idbarang
                                    UNION
                                    SELECT returdate AS tanggal, 'Retur Penjualan' AS transaksi, returnnumber AS id_transaksi, nmbarang AS item, SUM(qty) AS qty_in, 0 AS qty_out
                                    FROM returjual r
                                    JOIN returjualdetail rd ON r.idreturjual = rd.idreturjual
                                    JOIN barang br ON rd.idbarang = br.idbarang
                                    GROUP BY r.idreturjual, rd.idbarang
                              ) AS combined_data
                              GROUP BY tanggal, transaksi, id_transaksi, item
                              ORDER BY tanggal ASC";

                              $result = mysqli_query($conn, $query);

                              // Mengambil data dari query
                              while ($row = mysqli_fetch_assoc($result)) {
                              ?>
                                 <tr class='text-center'>
                                    <td><?= $no ?></td>
                                    <td><?= date('d-M-Y', strtotime($row['tanggal'])) ?></td>
                                    <td class='text-left'><?= $row['transaksi'] ?></td>
                                    <td><?= $row['id_transaksi'] ?></td>
                                    <td class='text-left'><?= $row['item'] ?></td>
                                    <td class='text-right'><?= number_format($row['qty_in'], 2) ?></td>
                                    <td class='text-right'><?= number_format($row['qty_out'], 2) ?></td>
                                 </tr>
                              <?php
                                 $no++;
                              }
                              ?>
                           </tbody>
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