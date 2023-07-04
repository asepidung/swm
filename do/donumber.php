<?php
require "../konak/conn.php";
$sql = mysqli_query($conn, "SELECT MAX(iddo) as maxID from do");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;
$donumber = sprintf("%05s", $kode);
$currentYear = date("y");  // Mendapatkan dua digit tahun saat ini (misalnya 23 untuk tahun 2023)
$currentMonth = date("m");  // Mendapatkan dua digit bulan saat ini

function angkaToRomawi($angka)
{
   $romawi = [
      'I', 'II', 'III', 'IV', 'V', 'VI',
      'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
   ];

   return $romawi[$angka - 1];
}

$romawiMonth = angkaToRomawi($currentMonth);

$kodeauto = "DO-SWM/" . $currentYear . "/" . $romawiMonth . "/" . $donumber;
// echo $kodeauto;
