<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(idlabel) as maxID from label");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$seriallabel = sprintf("%06s", $kode);

$currentYear = date("Y");  // Mendapatkan tahun saat ini (misalnya 2023)
$twoDigitYear = substr($currentYear, -2);  // Mengambil dua digit terakhir dari tahun

$kodeauto = $twoDigitYear . $seriallabel;
// echo $kodeauto;
