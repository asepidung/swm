<?php
// $idusers = $_SESSION['idusers'];

// hitung draft tally
$querytallyCount = "SELECT COUNT(*) AS drafttally FROM salesorder WHERE progress = 'Waiting' AND is_deleted = 0";
$resulttallyCount = mysqli_query($conn, $querytallyCount);
$rowtallyCount = mysqli_fetch_assoc($resulttallyCount);
$drafttally = $rowtallyCount['drafttally'];

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
