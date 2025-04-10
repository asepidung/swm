<?php
// $idusers = $_SESSION['idusers'];

// hitung draft tally
$querytallyCount = "SELECT COUNT(*) AS drafttally FROM salesorder WHERE progress = 'Waiting' AND is_deleted = 0";
$resulttallyCount = mysqli_query($conn, $querytallyCount);
$rowtallyCount = mysqli_fetch_assoc($resulttallyCount);
$drafttally = $rowtallyCount['drafttally'];

// hitung draft do
$querydoCount = "SELECT COUNT(*) AS draftdo FROM tally WHERE stat = 'Approved' AND is_deleted = 0";
$resultdoCount = mysqli_query($conn, $querydoCount);
$rowdoCount = mysqli_fetch_assoc($resultdoCount);
$draftdo = $rowdoCount['draftdo'];

// hitung draft invoice
$queryinvoiceCount = "SELECT COUNT(*) AS draftinvoice FROM doreceipt WHERE status = 'Approved' AND is_deleted = 0";
$resultinvoiceCount = mysqli_query($conn, $queryinvoiceCount);
$rowinvoiceCount = mysqli_fetch_assoc($resultinvoiceCount);
$draftinvoice = $rowinvoiceCount['draftinvoice'];


// requestbeef
$CountRequest = 0;
$CountWaiting = 0;
$CountOrdering = 0;

$query = "SELECT stat, COUNT(*) as count FROM requestbeef WHERE is_deleted = 0 GROUP BY stat";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['stat'] === 'Request') {
        $CountRequest = $row['count'];
    } elseif ($row['stat'] === 'Waiting') {
        $CountWaiting = $row['count'];
    } elseif ($row['stat'] === 'Ordering') {
        $CountOrdering = $row['count'];
    }
}

// request (Non Daging)
$CountRequestNonDaging = 0;
$CountWaitingNonDaging = 0;
$CountOrderingNonDaging = 0;

// Query untuk menghitung jumlah berdasarkan status
$query = "SELECT stat, COUNT(*) as count FROM request WHERE is_deleted = 0 GROUP BY stat";
$result = mysqli_query($conn, $query);

// Loop hasil query
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['stat'] === 'Request') {
        $CountRequestNonDaging = $row['count'];
    } elseif ($row['stat'] === 'Waiting') {
        $CountWaitingNonDaging = $row['count'];
    } elseif ($row['stat'] === 'Ordering') {
        $CountOrderingNonDaging = $row['count'];
    }
}

$TotalRequest = $CountRequest + $CountRequestNonDaging;
$TotalOrdering = $CountOrdering + $CountOrderingNonDaging;
$TotalWaiting = $CountWaiting + $CountWaitingNonDaging;

// Query untuk menghitung data di tabel invoice dengan status 'Belum TF' dan is_deleted = 0
$queryInvoiceBelumTF = "SELECT COUNT(*) AS belumTFCount FROM invoice WHERE status = 'Belum TF' AND is_deleted = 0";
$resultInvoiceBelumTF = mysqli_query($conn, $queryInvoiceBelumTF);
$rowInvoiceBelumTF = mysqli_fetch_assoc($resultInvoiceBelumTF);
$belumTFCount = $rowInvoiceBelumTF['belumTFCount'];


// plan delivery
$queryFutureDeliveries = "
    SELECT COUNT(*) AS futureDeliveryCount
    FROM salesorder so
    JOIN customers c ON so.idcustomer = c.idcustomer
    WHERE so.deliverydate >= CURDATE() + INTERVAL 1 DAY 
      AND so.is_deleted = 0
      AND c.idgroup != 21
";
$resultFutureDeliveries = mysqli_query($conn, $queryFutureDeliveries);
$rowFutureDeliveries = mysqli_fetch_assoc($resultFutureDeliveries);
$futureDeliveryCount = $rowFutureDeliveries['futureDeliveryCount'];


// plan kedatangan
$queryPobeefCount = "
    SELECT COUNT(*) AS pobeefCount
    FROM pobeef
    WHERE is_deleted = 0 AND stat = 0
";
$resultPobeefCount = mysqli_query($conn, $queryPobeefCount);
$rowPobeefCount = mysqli_fetch_assoc($resultPobeefCount);
$pobeefCount = $rowPobeefCount['pobeefCount'];

// repack
$queryrepackCount = "
    SELECT COUNT(*) AS repackCount
    FROM repack
    WHERE is_deleted = 0 AND kunci = 1
";
$resultrepackCount = mysqli_query($conn, $queryrepackCount);
$rowrepackCount = mysqli_fetch_assoc($resultrepackCount);
$repackCount = $rowrepackCount['repackCount'];


// po belum grraw

$queryPoBelumGR = "
    SELECT COUNT(*) AS poBelumGRCount
    FROM po p
    JOIN request r ON p.idrequest = r.idrequest
    WHERE p.is_deleted = 0
      AND p.stat = 0
      AND r.iduser = $idusers
      AND NOT EXISTS (
          SELECT 1
          FROM grraw g
          WHERE g.idpo = p.idpo
            AND g.is_deleted = 0
      )
";
$resultPoBelumGR = mysqli_query($conn, $queryPoBelumGR);
$rowPoBelumGR = mysqli_fetch_assoc($resultPoBelumGR);
$poBelumGRCount = $rowPoBelumGR['poBelumGRCount'];
