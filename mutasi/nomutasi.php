<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idmutasi) as maxID from mutasi");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$nost = sprintf("%05s", $kode);
$kodeauto = "MT-" . $nost;
