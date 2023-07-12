<?php
// ...
$totalpenjualan = 0;

// Mengambil data dari tabel dodetail dengan menggabungkan tabel yang terkait
$query = "SELECT idbarang, SUM(weight) AS total FROM dodetail GROUP BY idbarang";
$result = mysqli_query($conn, $query);

// Membuat array untuk menyimpan total penjualan per idbarang
$totalpenjualan_per_idbarang = array();

while ($row = mysqli_fetch_assoc($result)) {
   $idbarang = $row['idbarang'];
   $total = $row['total'];

   // Menyimpan total penjualan per idbarang ke dalam array
   $totalpenjualan_per_idbarang[$idbarang] = $total;
}
