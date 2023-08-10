<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idadjustment) as maxID from adjustment");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$noadjustment = "JUST-" . sprintf("%06s", $kode);
