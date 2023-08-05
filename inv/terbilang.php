<?php

function terbilang($angka)
{
   $angka = floatval($angka);
   $bilangan = array(
      '',
      'Satu',
      'Dua',
      'Tiga',
      'Empat',
      'Lima',
      'Enam',
      'Tujuh',
      'Delapan',
      'Sembilan',
      'Sepuluh',
      'Sebelas'
   );

   if ($angka < 12) {
      return $bilangan[$angka];
   } elseif ($angka < 20) {
      return $bilangan[$angka - 10] . ' Belas';
   } elseif ($angka < 100) {
      $puluh = intval($angka / 10);
      $sisa = $angka % 10;
      return $bilangan[$puluh] . ' Puluh ' . $bilangan[$sisa];
   } elseif ($angka < 200) {
      return 'Seratus ' . terbilang($angka - 100);
   } elseif ($angka < 1000) {
      $ratusan = intval($angka / 100);
      $sisa = $angka % 100;
      return $bilangan[$ratusan] . ' Ratus ' . terbilang($sisa);
   } elseif ($angka < 2000) {
      return 'Seribu ' . terbilang($angka - 1000);
   } elseif ($angka < 1000000) {
      $ribuan = intval($angka / 1000);
      $sisa = $angka % 1000;
      return terbilang($ribuan) . ' Ribu ' . terbilang($sisa);
   } elseif ($angka < 1000000000) {
      $jutaan = intval($angka / 1000000);
      $sisa = $angka % 1000000;
      return terbilang($jutaan) . ' Juta ' . terbilang($sisa);
   } else {
      return 'Maaf, fungsi ini hanya berlaku untuk angka sampai dengan 999.999.999.999';
   }
}

function terbilang_desimal($angka)
{
   $angka_arr = explode(".", $angka);
   $desimal = "";
   if (count($angka_arr) > 1) {
      $desimal = " koma ";
      $desimal_arr = str_split($angka_arr[1]);
      foreach ($desimal_arr as $digit) {
         $desimal .= $digit == "0" ? "nol " : terbilang($digit) . " ";
      }
   }
   return terbilang($angka_arr[0]) . $desimal . "Rupiah";
}
