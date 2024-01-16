<?php

// Hitung total qty dari tabel detailbahan berdasarkan idrepack
$queryTotalBahan = "SELECT SUM(qty) AS total_bahan
FROM detailbahan
WHERE idrepack = " . $tampil['idrepack'];
$resultTotalBahan = mysqli_query($conn, $queryTotalBahan);
$rowTotalBahan = mysqli_fetch_assoc($resultTotalBahan);

// Hitung total qty dari tabel detailhasil berdasarkan idrepack
$queryTotalHasil = "SELECT SUM(qty) AS total_hasil
FROM detailhasil
WHERE idrepack = " . $tampil['idrepack'];
$resultTotalHasil = mysqli_query($conn, $queryTotalHasil);
$rowTotalHasil = mysqli_fetch_assoc($resultTotalHasil);

$lost = $rowTotalHasil['total_hasil'] - $rowTotalBahan['total_bahan'];
