<?php
require "../konak/conn.php";

// Mendapatkan nomor terakhir dari tabel detailhasil
$sql = mysqli_query($conn, "SELECT MAX(iddetailhasil) as maxID FROM detailhasil");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];
$kode++;

// Format ulang nomor terakhir agar memiliki 9 digit dengan leading zero
$seriallabel = sprintf("%09s", $kode);

// Mendapatkan informasi tanggal saat ini
$currentYear = date("y");  // Mendapatkan dua digit tahun saat ini (misalnya 23 untuk tahun 2023)
$currentMonth = date("m");  // Mendapatkan dua digit bulan saat ini
$currentDay = date("d");  // Mendapatkan dua digit tanggal saat ini

// Menggabungkan informasi tanggal dengan nomor terakhir untuk membentuk kode serial label
$kodeauto = $currentYear . $currentMonth . $currentDay . $seriallabel;
