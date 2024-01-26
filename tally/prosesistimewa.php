<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
$idso = $_GET['idso'];
$idtally = $_GET['idtally'];
$idboning = $_GET['idboning'];
