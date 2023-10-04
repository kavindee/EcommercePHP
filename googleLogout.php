<?php 
ob_start();
session_start();
include 'admin/inc/config.php';
unset($_SESSION['access_token']);
unset($_SESSION['ucode']);
$_SESSION['authenticated']=false;
header("location: ".BASE_URL.'login.php'); 
?>