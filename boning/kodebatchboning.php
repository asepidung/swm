<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idboning) as maxID from boning");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$kodeauto = sprintf("%04s", $kode);

// echo $kodeauto;
