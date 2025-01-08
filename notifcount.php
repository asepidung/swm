<?php
$idusers = $_SESSION['idusers'];

// hitung draft tally
$querytallyCount = "SELECT COUNT(*) AS drafttally FROM salesorder WHERE progress = 'Waiting' AND is_deleted = 0";
$resulttallyCount = mysqli_query($conn, $querytallyCount);
$rowtallyCount = mysqli_fetch_assoc($resulttallyCount);
$drafttally = $rowtallyCount['drafttally'];
