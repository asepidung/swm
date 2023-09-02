<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idrepack) as maxID from repack");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$norepack = "RPC-" . sprintf("%06s", $kode);
