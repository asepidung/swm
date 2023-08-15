<?php
require "../konak/conn.php";
// $idbarang = $_GET['idbarang'];
$idbarang = $tampil['idbarang'];
$currentMonth = date('m');
$currentYear = date('Y');

// Query for GR transactions within the current month
$queryGR = "
   SELECT
   SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
   SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
   SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
   SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
   SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
   SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM grdetail gd
   INNER JOIN gr g ON gd.idgr = g.idgr
   WHERE gd.idbarang = $idbarang
   AND MONTH(g.receivedate) = $currentMonth
   AND YEAR(g.receivedate) = $currentYear
   ";

$resultGR = mysqli_query($conn, $queryGR);

if (!$resultGR) {
   die("Query execution failed: " . mysqli_error($conn));
}
$rowGR = mysqli_fetch_assoc($resultGR);

// Query for INB transactions within the current month
$queryINB = "
   SELECT
   SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
   SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
   SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
   SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
   SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
   SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM inbounddetail gd
   INNER JOIN inbound g ON gd.idinbound = g.idinbound
   WHERE gd.idbarang = $idbarang
   AND MONTH(g.tglinbound) = $currentMonth
   AND YEAR(g.tglinbound) = $currentYear
   ";

$resultINB = mysqli_query($conn, $queryINB);

if (!$resultINB) {
   die("Query execution failed: " . mysqli_error($conn));
}
$rowINB = mysqli_fetch_assoc($resultINB);


// Query for RJ transactions within the current month
$queryRJ = "
   SELECT
   SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
   SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
   SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
   SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
   SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
   SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM returjualdetail gd
   INNER JOIN returjual g ON gd.idreturjual = g.idreturjual
   WHERE gd.idbarang = $idbarang
   AND MONTH(g.returdate) = $currentMonth
   AND YEAR(g.returdate) = $currentYear
   ";

$resultRJ = mysqli_query($conn, $queryRJ);

if (!$resultINB) {
   die("Query execution failed: " . mysqli_error($conn));
}
$rowRJ = mysqli_fetch_assoc($resultRJ);

// Query for BN transactions within the current month
$queryBN = "
   SELECT
   SUM(CASE WHEN idgrade = 1 THEN qty ELSE 0 END) AS J01,
   SUM(CASE WHEN idgrade = 2 THEN qty ELSE 0 END) AS J02,
   SUM(CASE WHEN idgrade = 3 THEN qty ELSE 0 END) AS P01,
   SUM(CASE WHEN idgrade = 4 THEN qty ELSE 0 END) AS P02,
   SUM(CASE WHEN idgrade = 5 THEN qty ELSE 0 END) AS J03,
   SUM(CASE WHEN idgrade = 6 THEN qty ELSE 0 END) AS P03
   FROM labelboning gd
   INNER JOIN boning g ON gd.idboning = g.idboning
   WHERE gd.idbarang = $idbarang
   AND MONTH(g.tglboning) = $currentMonth
   AND YEAR(g.tglboning) = $currentYear
   ";

$resultBN = mysqli_query($conn, $queryBN);

if (!$resultBN) {
   die("Query execution failed: " . mysqli_error($conn));
}
$rowBN = mysqli_fetch_assoc($resultBN);

// Query for ADJ transactions within the current month
$queryADJ = "
   SELECT
   SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
   SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
   SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
   SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
   SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
   SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM adjustmentdetail gd
   INNER JOIN adjustment g ON gd.idadjustment = g.idadjustment
   WHERE gd.idbarang = $idbarang
   AND MONTH(g.tgladjustment) = $currentMonth
   AND YEAR(g.tgladjustment) = $currentYear
   ";

$resultADJ = mysqli_query($conn, $queryADJ);

if (!$resultADJ) {
   die("Query execution failed: " . mysqli_error($conn));
}
$rowADJ = mysqli_fetch_assoc($resultADJ);



// Query for DO transactions within the current month
$queryDO = "
   SELECT
   SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
   SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
   SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
   SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
   SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
   SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM dodetail gd
   INNER JOIN do g ON gd.iddo = g.iddo
   WHERE gd.idbarang = $idbarang
   AND MONTH(g.deliverydate) = $currentMonth
   AND YEAR(g.deliverydate) = $currentYear
   ";

$resultDO = mysqli_query($conn, $queryDO);

if (!$resultDO) {
   die("Query execution failed: " . mysqli_error($conn));
}
$rowDO = mysqli_fetch_assoc($resultDO);

// Query for OTB transactions within the current month
$queryOTB = "
   SELECT
   SUM(CASE WHEN idgrade = 1 THEN weight ELSE 0 END) AS J01,
   SUM(CASE WHEN idgrade = 2 THEN weight ELSE 0 END) AS J02,
   SUM(CASE WHEN idgrade = 3 THEN weight ELSE 0 END) AS P01,
   SUM(CASE WHEN idgrade = 4 THEN weight ELSE 0 END) AS P02,
   SUM(CASE WHEN idgrade = 5 THEN weight ELSE 0 END) AS J03,
   SUM(CASE WHEN idgrade = 6 THEN weight ELSE 0 END) AS P03
   FROM outbounddetail gd
   INNER JOIN outbound g ON gd.idoutbound = g.idoutbound
   WHERE gd.idbarang = $idbarang
   AND MONTH(g.tgloutbound) = $currentMonth
   AND YEAR(g.tgloutbound) = $currentYear
   ";

$resultOTB = mysqli_query($conn, $queryOTB);

if (!$resultOTB) {
   die("Query execution failed: " . mysqli_error($conn));
}
$rowOTB = mysqli_fetch_assoc($resultOTB);

$OTBJ01 = $rowOTB['J01'];
$OTBJ02 = $rowOTB['J02'];
$OTBP01 = $rowOTB['P01'];
$OTBP02 = $rowOTB['P02'];
$OTBJ03 = $rowOTB['J03'];
$OTBP03 = $rowOTB['P03'];

$DOJ01 = $rowDO['J01'];
$DOJ02 = $rowDO['J02'];
$DOP01 = $rowDO['P01'];
$DOP02 = $rowDO['P02'];
$DOJ03 = $rowDO['J03'];
$DOP03 = $rowDO['P03'];

$BNJ01 = $rowBN['J01'];
$BNJ02 = $rowBN['J02'];
$BNP01 = $rowBN['P01'];
$BNP02 = $rowBN['P02'];
$BNJ03 = $rowBN['J03'];
$BNP03 = $rowBN['P03'];

$RJJ01 = $rowRJ['J01'];
$RJJ02 = $rowRJ['J02'];
$RJP01 = $rowRJ['P01'];
$RJP02 = $rowRJ['P02'];
$RJJ03 = $rowRJ['J03'];
$RJP03 = $rowRJ['P03'];

$INBJ01 = $rowINB['J01'];
$INBJ02 = $rowINB['J02'];
$INBP01 = $rowINB['P01'];
$INBP02 = $rowINB['P02'];
$INBJ03 = $rowINB['J03'];
$INBP03 = $rowINB['P03'];

$GRJ01 = $rowGR['J01'];
$GRJ02 = $rowGR['J02'];
$GRP01 = $rowGR['P01'];
$GRP02 = $rowGR['P02'];
$GRJ03 = $rowGR['J03'];
$GRP03 = $rowGR['P03'];

$ADJJ01 = $rowADJ['J01'];
$ADJJ02 = $rowADJ['J02'];
$ADJP01 = $rowADJ['P01'];
$ADJP02 = $rowADJ['P02'];
$ADJJ03 = $rowADJ['J03'];
$ADJP03 = $rowADJ['P03'];

$J01 = ($GRJ01 + $BNJ01 + $INBJ01 + $ADJJ01 + $RJJ01) - ($DOJ01 + $OTBJ01);
$J02 = ($GRJ02 + $BNJ02 + $INBJ02 + $ADJJ02 + $RJJ02) - ($DOJ02 + $OTBJ02);
$J03 = ($GRJ03 + $BNJ03 + $INBJ03 + $ADJJ03 + $RJJ03) - ($DOJ03 + $OTBJ03);
$P01 = ($GRP01 + $BNP01 + $INBP01 + $ADJP01 + $RJP01) - ($DOP01 + $OTBP01);
$P02 = ($GRP02 + $BNP02 + $INBP02 + $ADJP02 + $RJP02) - ($DOP02 + $OTBP02);
$P03 = ($GRP03 + $BNP03 + $INBP03 + $ADJP03 + $RJP03) - ($DOP03 + $OTBP03);
$totalstockperitem = $J01 + $J02 + $J03 + $P01 + $P02 + $P03;

// echo "$J01";
// echo "<br>";
// echo "$J02";
// echo "<br>";
// echo "$J03";
// echo "<br>";
// echo "$P01";
// echo "<br>";
// echo "$P02";
// echo "<br>";
// echo "$P03";
