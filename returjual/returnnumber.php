<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idreturjual) as maxID from returjual");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$returnnumber = "RN-" .  sprintf("%05s", $kode);
