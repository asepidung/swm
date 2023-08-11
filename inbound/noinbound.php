<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idinbound) as maxID from inbound");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$noinbound = "INB-" . sprintf("%06s", $kode);
