<?php
$query = "SELECT * FROM barang ORDER BY nmbarang ASC";
$result = mysqli_query($conn, $query);

$options = '<option value="">--Pilih--</option>';
while ($row = mysqli_fetch_assoc($result)) {
   $idbarang = $row['idbarang'];
   $nmbarang = $row['nmbarang'];
   $options .= '<option value="' . $idbarang . '">' . $nmbarang . '</option>';
}

echo $options;
