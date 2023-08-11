<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idoutbound) as maxID from outbound");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$nooutbound = "OTB-" . sprintf("%06s", $kode);
