<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idtally) as maxID from tally");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$tallynumber = sprintf("%05s", $kode);
$kodeauto = "TS" . $tallynumber;
