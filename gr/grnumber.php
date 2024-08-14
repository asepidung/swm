<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idgr) as maxID from gr");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$gr = "GR/SWM-" . sprintf("%08s", $kode);
