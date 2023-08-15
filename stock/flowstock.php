<?php
require "../konak/conn.php";

$idbarang = $tampil['idbarang'];

// Query untuk transaksi GR (Goods Receipt)
$queryGR = "
   SELECT 
      SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
      SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
      SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
      SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
      SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
      SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM grdetail
   WHERE idbarang = $idbarang
";
$resultGR = mysqli_query($conn, $queryGR);
$rowGR = mysqli_fetch_assoc($resultGR);

$GRJ01 = $rowGR['J01'];
$GRJ02 = $rowGR['J02'];
$GRP01 = $rowGR['P01'];
$GRP02 = $rowGR['P02'];
$GRJ03 = $rowGR['J03'];
$GRP03 = $rowGR['P03'];

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

// Query untuk transaksi inbound (INB)
$queryINB = "
   SELECT 
      SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
      SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
      SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
      SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
      SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
      SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM inbounddetail
   WHERE idbarang = $idbarang
";
$resultINB = mysqli_query($conn, $queryINB);
$rowINB = mysqli_fetch_assoc($resultINB);

$INBJ01 = $rowINB['J01'];
$INBJ02 = $rowINB['J02'];
$INBP01 = $rowINB['P01'];
$INBP02 = $rowINB['P02'];
$INBJ03 = $rowINB['J03'];
$INBP03 = $rowINB['P03'];

// Query untuk transaksi retur jual (RJ)
$queryRJ = "
   SELECT 
      SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
      SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
      SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
      SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
      SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
      SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM returjualdetail
   WHERE idbarang = $idbarang
";
$resultRJ = mysqli_query($conn, $queryRJ);
$rowRJ = mysqli_fetch_assoc($resultRJ);

$RJJ01 = $rowRJ['J01'];
$RJJ02 = $rowRJ['J02'];
$RJP01 = $rowRJ['P01'];
$RJP02 = $rowRJ['P02'];
$RJJ03 = $rowRJ['J03'];
$RJP03 = $rowRJ['P03'];

// Query untuk transaksi adjustment (ADJ)
$queryADJ = "
   SELECT 
      SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
      SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
      SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
      SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
      SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
      SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM adjustmentdetail
   WHERE idbarang = $idbarang
";
$resultADJ = mysqli_query($conn, $queryADJ);
$rowADJ = mysqli_fetch_assoc($resultADJ);

$ADJJ01 = $rowADJ['J01'];
$ADJJ02 = $rowADJ['J02'];
$ADJP01 = $rowADJ['P01'];
$ADJP02 = $rowADJ['P02'];
$ADJJ03 = $rowADJ['J03'];
$ADJP03 = $rowADJ['P03'];

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

// Query untuk transaksi outbound (OTB)
$queryOTB = "
   SELECT 
      SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
      SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
      SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
      SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
      SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
      SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM outbounddetail
   WHERE idbarang = $idbarang
";
$resultOTB = mysqli_query($conn, $queryOTB);
$rowOTB = mysqli_fetch_assoc($resultOTB);

$OTBJ01 = $rowOTB['J01'];
$OTBJ02 = $rowOTB['J02'];
$OTBP01 = $rowOTB['P01'];
$OTBP02 = $rowOTB['P02'];
$OTBJ03 = $rowOTB['J03'];
$OTBP03 = $rowOTB['P03'];

// mulai hitung stock
$J01 = ($GRJ01 + $lbJ01 + $INBJ01 + $ADJJ01 + $RJJ01) - ($doJ01 + $OTBJ01);
$J02 = ($GRJ02 + $lbJ02 + $INBJ02 + $ADJJ02 + $RJJ02) - ($doJ02 + $OTBJ02);
$J03 = ($GRJ03 + $lbJ03 + $INBJ03 + $ADJJ03 + $RJJ03) - ($doJ03 + $OTBJ03);
$P01 = ($GRP01 + $lbP01 + $INBP01 + $ADJP01 + $RJP01) - ($doP01 + $OTBP01);
$P02 = ($GRP02 + $lbP02 + $INBP02 + $ADJP02 + $RJP02) - ($doP02 + $OTBP02);
$P03 = ($GRP03 + $lbP03 + $INBP03 + $ADJP03 + $RJP03) - ($doP03 + $OTBP03);
$totalstockperitem = $J01 + $J02 + $J03 + $P01 + $P02 + $P03;
