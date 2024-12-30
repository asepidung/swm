<?php
require "../konak/conn.php";

// Mendapatkan tahun, bulan, dan tanggal sekarang
$currentYear = date("y");  // Tahun dalam format 2 digit (misalnya 23 untuk 2023)
$currentMonth = date("m"); // Bulan dalam format 2 digit
$currentDay = date("d");   // Tanggal dalam format 2 digit

// Membuat prefix berdasarkan tahun, bulan, dan tanggal: YYMMDD
$prefix = $currentYear . $currentMonth . $currentDay;  // Misalnya: 240229

// Mengambil ID terbesar untuk tahun ini dari tabel labelboning, termasuk yang sudah dihapus
$sql = mysqli_query($conn, "SELECT MAX(iddetailhasil) AS maxID FROM detailhasil WHERE YEAR(creatime) = YEAR(CURRENT_DATE)");
$data = mysqli_fetch_array($sql);
$kode = $data['maxID'];  // Mengambil ID terbesar untuk tahun ini

// Jika tidak ada data pada tahun ini, mulai dari 1
if (!$kode) {
    $kode = 1;
} else {
    $kode++;  // Jika ada, tambahkan 1 untuk ID berikutnya
}

// Format nomor urut menjadi 3 digit
$seriallabel = sprintf("%09s", $kode);

// Menggabungkan prefix dengan nomor urut untuk menghasilkan ID lengkap
$kodeauto = $prefix . $seriallabel;

// Menampilkan nomor auto-generated
// echo $kodeauto;
?>
