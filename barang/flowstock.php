<?php
require "../konak/conn.php";
$idbarang = $tampil['idbarang'];
// $idbarang = $_GET['idbarang'];
// Query untuk transaksi Delivery Order (DO)
$queryDO = "
   SELECT 
      SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
      SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
      SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
      SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
      SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
      SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM dodetail
   WHERE idbarang = $idbarang
";
$resultDO = mysqli_query($conn, $queryDO);
$rowDO = mysqli_fetch_assoc($resultDO);

$doJ01 = $rowDO['J01'];
$doJ02 = $rowDO['J02'];
$doP01 = $rowDO['P01'];
$doP02 = $rowDO['P02'];
$doJ03 = $rowDO['J03'];
$doP03 = $rowDO['P03'];

// Query untuk transaksi Label Boning (LB)
$queryLB = "
   SELECT 
      SUM(CASE WHEN idgrade = 1 THEN qty ELSE 0 END) AS J01,
      SUM(CASE WHEN idgrade = 2 THEN qty ELSE 0 END) AS J02,
      SUM(CASE WHEN idgrade = 3 THEN qty ELSE 0 END) AS P01,
      SUM(CASE WHEN idgrade = 4 THEN qty ELSE 0 END) AS P02,
      SUM(CASE WHEN idgrade = 5 THEN qty ELSE 0 END) AS J03,
      SUM(CASE WHEN idgrade = 6 THEN qty ELSE 0 END) AS P03
   FROM labelboning
   WHERE idbarang = $idbarang
";
$resultLB = mysqli_query($conn, $queryLB);
$rowLB = mysqli_fetch_assoc($resultLB);

$lbJ01 = $rowLB['J01'];
$lbJ02 = $rowLB['J02'];
$lbP01 = $rowLB['P01'];
$lbP02 = $rowLB['P02'];
$lbJ03 = $rowLB['J03'];
$lbP03 = $rowLB['P03'];
